<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);


namespace Condorcet\DataManager;


use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Vote;


class VotesManager extends ArrayManager
{

/////////// Magic ///////////

    public function __construct (Election $election)
    {
        $this->setElection($election);

        parent::__construct();
    }

    public function setElection (Election $election)
    {
        $this->_link[0] = $election;
    }

/////////// Data CallBack for external drivers ///////////

    public function getDataContextObject () : DataContextInterface
    {
        $context = new Class implements DataContextInterface {
            public $election;

            public function dataCallBack ($data) : Vote
            {
                $vote = new Vote ($data);
                $this->election->checkVoteCandidate($vote);
                $vote->registerLink($this->election);

                return $vote;
            }

            public function dataPrepareStoringAndFormat ($data) : string
            {
                $data->destroyLink($this->election);

                return (string) $data;
            }
        };

        $context->election = $this->_link[0] ?? null;

        return $context;
    }

    protected function preDeletedTask ($object) : void
    {
        $object->destroyLink($this->_link[0]);
    }

/////////// Array Access - Specials improvements ///////////

    public function offsetSet($offset, $value) : void
    {
        if ($value instanceof Vote) :
            parent::offsetSet($offset,$value);
            $this->setStateToVote();
        else :
            throw new CondorcetException (0,'Value must be an instanceof Condorcet\\Vote');
        endif;
    }

    public function offsetUnset($offset) : void
    {
        parent::offsetUnset($offset);
        $this->setStateToVote();
    }

/////////// Internal Election related methods ///////////

    protected function setStateToVote () : void
    {
        foreach ($this->_link as $element) :
            $element->setStateToVote();
        endforeach;
    }

/////////// Get Votes Methods ///////////

    public function getVoteKey (Vote $vote) {
        ($r = array_search($vote, $this->_Container, true)) !== false || ($r = array_search($vote, $this->_Cache, true));

        return $r;
    }

    protected function getFulllVotesListGenerator () : \Generator
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
                if ( ( $oneTag === $voteKey ) || in_array($oneTag, $vote->getTags(),true) ) :
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
            return $this->getFulllVotesListGenerator();
        else :
            return $this->getPartialVotesListGenerator($tag,$with);
        endif;
    }

    public function getVotesListAsString () : string
    {
        $simpleList = '';

        $weight = [];
        $nb = [];

        foreach ($this as $oneVote) :
            $oneVoteString = $oneVote->getSimpleRanking($this->_link[0]);

            if(!array_key_exists($oneVoteString, $weight)) :
                $weight[$oneVoteString] = 0;
            endif;
            if(!array_key_exists($oneVoteString, $nb)) :
                $nb[$oneVoteString] = 0;
            endif;

            if ($this->_link[0]->isVoteWeightIsAllowed()) :
                $weight[$oneVoteString] += $oneVote->getWeight();
            else :
                $weight[$oneVoteString]++;
            endif;

            $nb[$oneVoteString]++;
        endforeach;

        ksort($weight);
        arsort($weight);

        $isFirst = true;
        foreach ($weight as $key => $value) :
            if (!$isFirst) : $simpleList .= "\n"; endif;
            $simpleList .= $key.' * '.$nb[$key];
            $isFirst = false;
        endforeach;

        return $simpleList;
    }

    public function countVotes (?array $tag, bool $with) : int
    {
        if ($tag === null) :
            return count($this);
        else :
            $count = 0;

            foreach ($this as $key => $value) :
                $noOne = true;
                foreach ($tag as $oneTag) :
                    if ( ( $oneTag === $key ) || in_array($oneTag, $value->getTags(),true) ) :
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

    public function sumVotesWeight () : int
    {
        $sum = 0;

        foreach ($this as $oneVote) :
            $sum += ($this->_link[0]->isVoteWeightIsAllowed()) ? $oneVote->getWeight() : 1;
        endforeach;

        return $sum;
    }
}
