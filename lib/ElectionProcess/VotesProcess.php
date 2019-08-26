<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Throwable\CondorcetException;
use \CondorcetPHP\Condorcet\Throwable\CondorcetInternalException;
use CondorcetPHP\Condorcet\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\DataManager\VotesManager;

// Manage Results for Election class
trait VotesProcess
{

/////////// CONSTRUCTOR ///////////

    // Data and global options
    protected $_Votes; // Votes list


/////////// VOTES LIST ///////////

    // How many votes are registered ?
    public function countVotes ($tags = null, bool $with = true) : int
    {
        return $this->_Votes->countVotes(VoteUtil::tagsConvert($tags),$with);
    }

    public function countInvalidVoteWithConstraints () : int
    {
        return $this->_Votes->countInvalidVoteWithConstraints();
    }

    public function countValidVoteWithConstraints () : int
    {
        return $this->countVotes() - $this->countInvalidVoteWithConstraints();
    }

    // Sum votes weight
    public function sumVotesWeight () : int
    {
        return $this->_Votes->sumVotesWeight(false);
    }

    public function sumValidVotesWeightWithConstraints () : int
    {
        return $this->_Votes->sumVotesWeight(true);
    }

    // Get the votes registered list
    public function getVotesList ($tags = null, bool $with = true) : array
    {
        return $this->_Votes->getVotesList(VoteUtil::tagsConvert($tags), $with);
    }

    public function getVotesListAsString () : string
    {
        return $this->_Votes->getVotesListAsString();
    }

    public function getVotesManager () : VotesManager
    {
        return $this->_Votes;
    }

    public function getVotesListGenerator ($tags = null, bool $with = true) : \Generator
    {
        return $this->_Votes->getVotesListGenerator(VoteUtil::tagsConvert($tags), $with);
    }

    public function getVoteKey (Vote $vote) : ?int
    {
        return $this->_Votes->getVoteKey($vote);
    }


/////////// ADD & REMOVE VOTE ///////////

    // Add a single vote. Array key is the rank, each candidate in a rank are separate by ',' It is not necessary to register the last rank.
    public function addVote ($vote, $tags = null) : Vote
    {
        $this->prepareVoteInput($vote, $tags);

        // Check Max Vote Count
        if ( self::$_maxVoteNumber !== null && $this->countVotes() >= self::$_maxVoteNumber ) :
            throw new CondorcetException(16, (string) self::$_maxVoteNumber);
        endif;


        // Register vote
        return $this->registerVote($vote, $tags); // Return the vote object
    }

    public function prepareUpdateVote (Vote $existVote) : void
    {
        $this->_Votes->UpdateAndResetComputing($this->getVoteKey($existVote),2);
    }

    public function finishUpdateVote (Vote $existVote) : void
    {
        $this->_Votes->UpdateAndResetComputing($this->getVoteKey($existVote),1);

        if ($this->_Votes->isUsingHandler()) :
            $this->_Votes[$this->getVoteKey($existVote)] = $existVote;
        endif;
    }

    public function checkVoteCandidate (Vote $vote) : bool
    {
        $linkCount = $vote->countLinks();
        $links = $vote->getLinks();

        $mirror = $vote->getRanking();
        $change = false;
        foreach ($vote as $rank => $choice) :
            foreach ($choice as $choiceKey => $candidate) :
                if ( !$this->isRegisteredCandidate($candidate, true) ) :
                    if ($candidate->getProvisionalState() && $this->isRegisteredCandidate($candidate, false)) :
                        if ( $linkCount === 0 || ($linkCount === 1 && reset($links) === $this) ) :
                            $mirror[$rank][$choiceKey] = $this->_Candidates[$this->getCandidateKey((string) $candidate)];
                            $change = true;
                        else :
                            return false;
                        endif;
                    endif;
                endif;
            endforeach;
        endforeach;

        if ($change) :
            $vote->setRanking(  $mirror,
                                ( abs($vote->getTimestamp() - microtime(true)) > 0.5 ) ? ($vote->getTimestamp() + 0.001) : null
            );
        endif;

        return true;
    }

    // Write a new vote
    protected function registerVote (Vote $vote, $tag = null) : Vote
    {
        // Vote identifiant
        $vote->addTags($tag);
        
        // Register
        try {
            $vote->registerLink($this);
            $this->_Votes[] = $vote;
        } catch (CondorcetInternalException $e) {
            // Security : Check if vote object not already register
            throw new CondorcetException(31);
        }

        return $vote;
    }

    public function removeVotes (Vote $votes_input) : bool
    {    
        $key = $this->getVoteKey($votes_input);
        if ($key !== null) :
            $deletedVote = $this->_Votes[$key];
            $rem[] = $deletedVote;

            unset($this->_Votes[$key]);

            $deletedVote->destroyLink($this);

            return true;
        else :
            throw new CondorcetException(33);
        endif;

    }

    public function removeVotesByTags ($tags, bool $with = true) : array
    {    
        $rem = [];

        // Prepare Tags
        $tags = VoteUtil::tagsConvert($tags);

        // Deleting
        foreach ($this->getVotesList($tags, $with) as $key => $value) :
            $deletedVote = $this->_Votes[$key];
            $rem[] = $deletedVote;

            unset($this->_Votes[$key]);

            $deletedVote->destroyLink($this);
        endforeach;

        return $rem;
    }


/////////// PARSE VOTE ///////////

    // Return the well formated vote to use.
    protected function prepareVoteInput (&$vote, $tag = null) : void
    {
        if (!($vote instanceof Vote)) :
            $vote = new Vote ($vote, $tag);
        endif;

        // Check array format && Make checkVoteCandidate
        if ( !$this->checkVoteCandidate($vote) ) :
            throw new CondorcetException(5);
        endif;
    }

    public function addVotesFromJson (string $input) : array
    {
        $input = CondorcetUtil::prepareJson($input);

            //////

        $adding = [];

        foreach ($input as $record) :
            if (empty($record['vote'])) :
                continue;
            endif;

            $tags = !isset($record['tag']) ? null : $record['tag'];
            $multi = !isset($record['multi']) ? 1 : $record['multi'];

            $adding_predicted_count = count($adding) + $multi;

            if (self::$_maxVoteNumber && self::$_maxVoteNumber < ($this->countVotes() + $adding_predicted_count)) :
                throw new CondorcetException(16, self::$_maxParseIteration);
            endif;

            if (self::$_maxParseIteration !== null && $adding_predicted_count >= self::$_maxParseIteration) :
                throw new CondorcetException(12, self::$_maxParseIteration);
            endif;

            for ($i = 0; $i < $multi; $i++) :
                $adding[] = new Vote ($record['vote'], $tags);
            endfor;
        endforeach;

        foreach ($adding as $oneNewVote) :
            $this->addVote($oneNewVote);
        endforeach;

        return $adding;
    }

    public function parseVotes (string $input, bool $isFile = false) : array
    {
        $input = CondorcetUtil::prepareParse($input, $isFile);

        // Check each lines
        $adding = [];
        foreach ($input as $line) :
            // Empty Line
            if (empty($line)) :
                continue;
            endif;

            // Multiples
            $multiple = VoteUtil::parseAnalysingOneLine(mb_strpos($line, '*'),$line);

            // Vote Weight
            $weight = VoteUtil::parseAnalysingOneLine(mb_strpos($line, '^'),$line);

            // Tags + vote
            if (mb_strpos($line, '||') !== false) :
                $data = explode('||', $line);

                $vote = $data[1];
                $tags = $data[0];
            // Vote without tags
            else :
                $vote = $line;
                $tags = null;
            endif;

            $adding_predicted_count = count($adding) + $multiple;

            if (self::$_maxVoteNumber && self::$_maxVoteNumber < ($this->countVotes() + $adding_predicted_count)) :
                throw new CondorcetException(16, (string) self::$_maxParseIteration);
            endif;

            if (self::$_maxParseIteration !== null && $adding_predicted_count >= self::$_maxParseIteration) :
                throw new CondorcetException(12, (string) self::$_maxParseIteration);
            endif;

            // addVote
            for ($i = 0; $i < $multiple; $i++) :
                $newVote = new Vote ($vote, $tags);
                $newVote->setWeight($weight);

                $adding[] = $newVote;
            endfor;
        endforeach;

        foreach ($adding as $oneNewVote) :
            $this->addVote($oneNewVote);
        endforeach;

        return $adding;
    }

}
