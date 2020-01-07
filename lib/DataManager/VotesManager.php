<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\DataManager;

use CondorcetPHP\Condorcet\{Election, Vote};
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

class VotesManager extends ArrayManager
{

/////////// Magic ///////////

    public function __construct (Election $election)
    {
        $this->setElection($election);

        parent::__construct();
    }

    public function setElection (Election $election) : void
    {
        $this->_Election = $election;
    }

    public function destroyElection () : void
    {
        unset($this->_Election);
    }

    public function getElection () : Election
    {
        return $this->_Election;
    }

/////////// Data CallBack for external drivers ///////////

    protected function decodeOneEntity (string $data) : Vote
    {
        $vote = new Vote ($data);
        $vote->registerLink($this->_Election);
        $vote->notUpdate = true;
        $this->_Election->checkVoteCandidate($vote);
        $vote->notUpdate = false;

        return $vote;
    }

    protected function encodeOneEntity (Vote $data) : string
    {
        $data->destroyLink($this->_Election);

        return \str_replace([' > ',' = '],['>','='],(string) $data);
    }

    protected function preDeletedTask ($object) : void
    {
        $object->destroyLink($this->_Election);
    }

/////////// Array Access - Specials improvements ///////////

    public function offsetSet($offset, $value) : void
    {
        if ($value instanceof Vote) :
            parent::offsetSet($offset,$value);
            $this->UpdateAndResetComputing($this->_maxKey,1);
        else :
            throw new CondorcetException (0,'Value must be an instanceof CondorcetPHP\Vote');
        endif;

        $this->checkRegularize();
    }

    public function offsetUnset($offset) : void
    {
        $this->UpdateAndResetComputing($offset,2);
        parent::offsetUnset($offset);
    }

/////////// Internal Election related methods ///////////

    public function UpdateAndResetComputing (int $key, int $type) : void
    {
        if ($this->_Election->getState() === 2) :

            if ($type === 1) :
                $this->_Election->getPairwise()->addNewVote($key);
            elseif ($type === 2) :
                $this->_Election->getPairwise()->removeVote($key);
            endif;

            $this->_Election->cleanupCalculator();
        else :
            $this->_Election->setStateToVote();
        endif;
    }


/////////// Get Votes Methods ///////////

    public function getVoteKey (Vote $vote) : ?int
    {
        ($r = \array_search($vote, $this->_Container, true)) !== false || ($r = \array_search($vote, $this->_Cache, true));

        return ($r !== false) ? $r : null;
    }

    protected function getFullVotesListGenerator () : \Generator
    {
        foreach ($this as $voteKey => $vote) :
            yield $voteKey => $vote;
        endforeach;
    }

    protected function getPartialVotesListGenerator (array $tag, bool $with) : \Generator
    {
        foreach ($this as $voteKey => $vote) :
            $noOne = true;
            foreach ($tag as $oneTag) :
                if ( ( $oneTag === $voteKey ) || \in_array($oneTag, $vote->getTags(),true) ) :
                    if ($with) :
                        yield $voteKey => $vote;
                        break;
                    else :
                        $noOne = false;
                    endif;
                endif;
            endforeach;

            if (!$with && $noOne) :
                yield $voteKey => $vote;
            endif;
        endforeach;
    }

    // Get the votes list
    public function getVotesList ($tag = null, bool $with = true) : array
    {
        if ($tag === null) :
            return $this->getFullDataSet();
        else :
            $search = [];

            foreach ($this->getPartialVotesListGenerator($tag,$with) as $voteKey => $vote) :
                $search[$voteKey] = $vote;
            endforeach;

            return $search;
        endif;
    }

    // Get the votes list as a generator object
    public function getVotesListGenerator ($tag = null, bool $with = true) : \Generator
    {
        if ($tag === null) :
            return $this->getFullVotesListGenerator();
        else :
            return $this->getPartialVotesListGenerator($tag,$with);
        endif;
    }

    public function getVotesValidUnderConstraintGenerator ($tag = null, bool $with = true) : \Generator
    {
        $generator = ($tag === null) ? $this->getFullVotesListGenerator() : $this->getPartialVotesListGenerator($tag,$with);

        foreach ($generator as $voteKey => $oneVote) :
            if (!$this->getElection()->testIfVoteIsValidUnderElectionConstraints($oneVote)) :
                continue;
            endif;

            yield $voteKey => $oneVote;
        endforeach;
    }

    public function getVotesListAsString () : string
    {
        $simpleList = '';

        $weight = [];
        $nb = [];

        foreach ($this as $oneVote) :
            $oneVoteString = $oneVote->getSimpleRanking($this->_Election);

            if(!array_key_exists($oneVoteString, $weight)) :
                $weight[$oneVoteString] = 0;
            endif;
            if(!array_key_exists($oneVoteString, $nb)) :
                $nb[$oneVoteString] = 0;
            endif;

            if ($this->getElection()->isVoteWeightAllowed()) :
                $weight[$oneVoteString] += $oneVote->getWeight();
            else :
                $weight[$oneVoteString]++;
            endif;

            $nb[$oneVoteString]++;
        endforeach;

        \ksort($weight);
        \arsort($weight);

        $isFirst = true;
        foreach ($weight as $key => $value) :
            if (!$isFirst) :
                $simpleList .= "\n";
            endif;
            $voteString = ($key === '') ? '{{EMPTY_VOTE_IN_CONTEXT}}' : $key;
            $simpleList .= $voteString.' * '.$nb[$key];
            $isFirst = false;
        endforeach;

        return $simpleList;
    }

    public function countVotes (?array $tag, bool $with) : int
    {
        if ($tag === null) :
            return \count($this);
        else :
            $count = 0;

            foreach ($this as $key => $value) :
                $noOne = true;
                foreach ($tag as $oneTag) :
                    if ( ( $oneTag === $key ) || \in_array($oneTag, $value->getTags(),true) ) :
                        if ($with) :
                            $count++;
                            break;
                        else :
                            $noOne = false;
                        endif;
                    endif;
                endforeach;

                if (!$with && $noOne) :
                    $count++;
                endif;
            endforeach;

            return $count;
        endif;
    }

    public function countInvalidVoteWithConstraints () : int
    {
        $count = 0;

        foreach ($this as $oneVote) :
            if(!$this->getElection()->testIfVoteIsValidUnderElectionConstraints($oneVote)) :
                $count++;
            endif;
        endforeach;

        return $count;
    }

    public function sumVotesWeight (bool $constraint = false) : int
    {
        $sum = 0;

        foreach ($this as $oneVote) :
            if ( !$constraint || $this->getElection()->testIfVoteIsValidUnderElectionConstraints($oneVote) ) :
                $sum += $this->getElection()->isVoteWeightAllowed() ? $oneVote->getWeight() : 1;
            endif;
        endforeach;

        return $sum;
    }
}
