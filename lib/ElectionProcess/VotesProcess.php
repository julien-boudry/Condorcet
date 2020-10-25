<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\{CondorcetUtil, Vote};
use CondorcetPHP\Condorcet\DataManager\VotesManager;
use CondorcetPHP\Condorcet\Throwable\{CondorcetException, CondorcetInternalException};

// Manage Results for Election class
trait VotesProcess
{

/////////// CONSTRUCTOR ///////////

    // Data and global options
    protected VotesManager $_Votes; // Votes list
    protected int $_voteFastMode = 0; // When parsing vote, avoid unnecessary checks


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

    public function getVotesValidUnderConstraintGenerator ($tags = null, bool $with = true) : \Generator
    {
        return $this->_Votes->getVotesValidUnderConstraintGenerator($tags, $with);
    }

    public function getVoteKey (Vote $vote) : ?int
    {
        return $this->_Votes->getVoteKey($vote);
    }


/////////// ADD & REMOVE VOTE ///////////

    // Add a single vote. Array key is the rank, each candidate in a rank are separate by ',' It is not necessary to register the last rank.
    public function addVote (array|string|Vote $vote, array|string|null $tags = null) : Vote
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
        $this->_Votes->UpdateAndResetComputing(key: $this->getVoteKey($existVote), type: 2);
    }

    public function finishUpdateVote (Vote $existVote) : void
    {
        $this->_Votes->UpdateAndResetComputing(key: $this->getVoteKey($existVote), type: 1);

        if ($this->_Votes->isUsingHandler()) :
            $this->_Votes[$this->getVoteKey($existVote)] = $existVote;
        endif;
    }

    public function checkVoteCandidate (Vote $vote) : bool
    {
        if ($this->_voteFastMode === 0) :
            $linkCount = $vote->countLinks();
            $links = $vote->getLinks();
            $linkCheck = ( $linkCount === 0 || ($linkCount === 1 && \reset($links) === $this) );

            foreach ($vote->getAllCandidates() as $candidate) :
                if (!$linkCheck && $candidate->getProvisionalState() && !$this->isRegisteredCandidate(candidate: $candidate, strictMode: true) && $this->isRegisteredCandidate(candidate: $candidate, strictMode: false)) :
                    return false;
                endif;
            endforeach;
        endif;

        if ($this->_voteFastMode < 2) :
            $ranking = $vote->getRanking();

            $change = $this->convertRankingCandidates($ranking);

            if ($change) :
                $vote->setRanking(  $ranking,
                                    ( \abs($vote->getTimestamp() - \microtime(true)) > 0.5 ) ? ($vote->getTimestamp() + 0.001) : null
                );
            endif;
        endif;

        return true;
    }

    public function convertRankingCandidates (array &$ranking) : bool
    {
        $change = false;

        foreach ($ranking as &$choice) :
            foreach ($choice as &$candidate) :
                if ( !$this->isRegisteredCandidate($candidate, true) ) :
                    if ($candidate->getProvisionalState() && $this->isRegisteredCandidate(candidate: $candidate, strictMode: false)) :
                            $candidate = $this->_Candidates[$this->getCandidateKey((string) $candidate)];
                            $change = true;
                    endif;
                endif;
            endforeach;
        endforeach;

        return $change;
    }

    // Write a new vote
    protected function registerVote (Vote $vote, array|string|null $tags) : Vote
    {
        // Vote identifiant
        $tags === null || $vote->addTags($tags);

        // Register
        try {
            $vote->registerLink($this);
            $this->_Votes[] = $vote;
        } catch (CondorcetInternalException) {
            // Security : Check if vote object not already register
            throw new CondorcetException(31);
        }

        return $vote;
    }

    public function removeVote (Vote $vote) : bool
    {
        $key = $this->getVoteKey($vote);
        if ($key !== null) :
            $deletedVote = $this->_Votes[$key];

            unset($this->_Votes[$key]);

            $deletedVote->destroyLink($this);

            return true;
        else :
            throw new CondorcetException(33);
        endif;

    }

    public function removeVotesByTags (array|string $tags, bool $with = true) : array
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
    protected function prepareVoteInput (array|string|Vote &$vote, array|string $tags = null) : void
    {
        if (!($vote instanceof Vote)) :
            $vote = new Vote (ranking: $vote, tags: $tags, ownTimestamp: null, electionContext: $this);
        endif;

        // Check array format && Make checkVoteCandidate
        if ( !$this->checkVoteCandidate($vote) ) :
            throw new CondorcetException(5);
        endif;
    }

    public function addVotesFromJson (string $input) : int
    {
        $input = CondorcetUtil::prepareJson($input);

        $adding = [];
        $count = 0;

        foreach ($input as $record) :
            if (empty($record['vote'])) :
                continue;
            endif;

            $vote = $record['vote'];
            $tags = !isset($record['tag']) ? null : $record['tag'];
            $multiple = !isset($record['multi']) ? 1 : (int) $record['multi'];
            $weight = !isset($record['weight']) ? 1 : (int) $record['weight'];

            $this->synthesisVoteFromParse ($count, $multiple, $adding, $vote, $tags, $weight);

        endforeach;

        $this->doAddVotesFromParse($adding);

        return $count;
    }

    public function parseVotes (string $input, bool $isFile = false) : int
    {
        $input = CondorcetUtil::prepareParse($input, $isFile);

        $adding = [];
        $count = 0;

        foreach ($input as $line) :
            // Empty Line
            if (empty($line)) :
                continue;
            endif;

            // Disallow < and "
            if ( \preg_match('/<|"/mi', $line) === 1 ) :
                throw new CondorcetException(14, $line);
            endif;

            // Multiples
            $multiple = VoteUtil::parseAnalysingOneLine(\strpos($line, '*'),$line);

            // Vote Weight
            $weight = VoteUtil::parseAnalysingOneLine(\strpos($line, '^'),$line);

            // Tags + vote
            if (\str_contains($line, '||') === true) :
                $data = \explode('||', $line);

                $vote = $data[1];
                $tags = $data[0];
            // Vote without tags
            else :
                $vote = $line;
                $tags = null;
            endif;

            $this->synthesisVoteFromParse(count: $count, multiple: $multiple, adding: $adding, vote: $vote, tags: $tags, weight: $weight);
        endforeach;

        $this->doAddVotesFromParse($adding);

        return $count;
    }

    public function parseVotesWithoutFail (string $input, bool $isFile = false, ?\Closure $callBack = null) : int
    {
        $inserted_votes_count = 0;
        $fail_count = 0;
        $doCallBack = $callBack !== null;

        if (!$isFile) :
            $file = \fopen("php://memory", 'r+');
            \fwrite($file, $input);
            \rewind($file);
            unset($input); // Memory Optimization
        else :
            $file = \fopen($input, 'r');
        endif;

        $char = '';
        $record = '';

        while ($char !== false) :
            $char = \fgetc($file);

            if ($char === ";" || $char === "\n" || $char === false) :
                try {
                    CondorcetUtil::prepareParse($record, false);

                    if ( ($is_comment = \strpos($record, '#')) !== false ) :
                        $record = \substr($record, 0, $is_comment);
                    endif;

                    $multiple = VoteUtil::parseAnalysingOneLine(\strpos(haystack: $record, needle: '*'),$record);

                    for ($i=0; $i < $multiple; $i++) :
                        $inserted_votes_count += $this->parseVotes($record);

                        if ($doCallBack) :
                            $doCallBack = $callBack($inserted_votes_count);
                        endif;
                    endfor;

                } catch (CondorcetException) {
                    ++$fail_count;
                } finally {
                    $record = '';
                }
            else :
                $record .= $char;
            endif;
        endwhile;

        \fclose($file);

        return $fail_count;
    }

    protected function synthesisVoteFromParse (int &$count, int $multiple, array &$adding, array|string|Vote $vote, null|array|string $tags, int $weight) : void
    {
        $adding_predicted_count = $count + $multiple;

        if (self::$_maxVoteNumber && self::$_maxVoteNumber < ($this->countVotes() + $adding_predicted_count)) :
            throw new CondorcetException(16, (string) self::$_maxParseIteration);
        endif;

        if (self::$_maxParseIteration !== null && $adding_predicted_count >= self::$_maxParseIteration) :
            throw new CondorcetException(12, (string) self::$_maxParseIteration);
        endif;

        $newVote = new Vote ($vote, $tags, null, $this);
        $newVote->setWeight($weight);

        $adding[] = ['multiple' => $multiple, 'vote' => $newVote];

        $count += $multiple;
    }

    protected function doAddVotesFromParse (array $adding) : void
    {
        $this->_voteFastMode = 1;

        foreach ($adding as $oneLine) :
            for ($i = 1 ; $i <= $oneLine['multiple'] ; $i++) :
                if ($i === 1) :
                    $finalVoteModel = $this->addVote($oneLine['vote']);
                    $this->_voteFastMode = 2;
                else :
                    $this->addVote(clone $finalVoteModel);
                endif;
            endfor;
        endforeach;

        $this->_voteFastMode = 0;
    }

}
