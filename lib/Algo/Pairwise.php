<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\{CondorcetVersion, Election, Vote};
use CondorcetPHP\Condorcet\Timer\Chrono as Timer_Chrono;

class Pairwise implements \ArrayAccess, \Iterator
{
    use CondorcetVersion;

    // Implement ArrayAccess
    public function offsetSet($offset, $value) : void {}

    public function offsetExists($offset) : bool {
        return isset($this->_Pairwise[$offset]);
    }

    public function offsetUnset($offset) : void {}

    public function offsetGet($offset) : ?array {
        return $this->_Pairwise[$offset] ?? null;
    }


    // Implement Iterator
    private bool $valid = true;

    public function rewind() : void {
        reset($this->_Pairwise);
        $this->valid = true;
    }

    public function current() : array {
        return $this->_Pairwise[$this->key()];
    }

    public function key() : ?int {
        return key($this->_Pairwise);
    }

    public function next() : void {
        if (next($this->_Pairwise) === false) :
            $this->valid = false;
        endif;
    }

    public function valid() : bool {
        return $this->valid;
    }   


    // Pairwise

    protected ?Election $_Election;
    protected array $_Pairwise_Model;
    protected array $_Pairwise;

    public function __construct (Election $link)
    {
        $this->setElection($link);
        $this->formatNewpairwise();
        $this->doPairwise();
    }

    public function __clone ()
    {
        $this->_Election = null;
    }

    public function setElection (Election $election) : void
    {
        $this->_Election = $election;
    }

    public function addNewVote (int $key) : void
    {
        new Timer_Chrono ( $this->_Election->getTimerManager(), 'Add Vote To Pairwise' );

        $this->computeOneVote($this->_Pairwise,$this->_Election->getVotesManager()[$key]);
    }

    public function removeVote (int $key) : void
    {
        new Timer_Chrono ( $this->_Election->getTimerManager(), 'Remove Vote To Pairwise' );

        $diff = $this->_Pairwise_Model;

        $this->computeOneVote($diff,$this->_Election->getVotesManager()[$key]);

        foreach ($diff as $candidate_key => $candidate_details) :
            foreach ($candidate_details as $type => $opponent) :
                foreach ($opponent as $opponent_key => $score) :
                    $this->_Pairwise[$candidate_key][$type][$opponent_key] -= $score;
                endforeach;
            endforeach;
        endforeach;
    }

    public function getExplicitPairwise () : array
    {
        $explicit_pairwise = [];

        foreach ($this->_Pairwise as $candidate_key => $candidate_value) :

            $candidate_name = $this->_Election->getCandidateObjectFromKey($candidate_key)->getName();

            foreach ($candidate_value as $mode => $mode_value) :

                foreach ($mode_value as $candidate_list_key => $candidate_list_value) :
                    $explicit_pairwise[$candidate_name][$mode][$this->_Election->getCandidateObjectFromKey($candidate_list_key)->getName()] = $candidate_list_value;
                endforeach;

            endforeach;

        endforeach;

        return $explicit_pairwise;
    }

    protected function formatNewpairwise () : void
    {
        $this->_Pairwise_Model = [];

        foreach ( $this->_Election->getCandidatesList() as $candidate_key => $candidate_id ) :

            $this->_Pairwise_Model[$candidate_key] = [ 'win' => [], 'null' => [], 'lose' => [] ];

            foreach ( $this->_Election->getCandidatesList() as $candidate_key_r => $candidate_id_r ) :

                if ($candidate_key_r !== $candidate_key) :
                    $this->_Pairwise_Model[$candidate_key]['win'][$candidate_key_r]   = 0;
                    $this->_Pairwise_Model[$candidate_key]['null'][$candidate_key_r]  = 0;
                    $this->_Pairwise_Model[$candidate_key]['lose'][$candidate_key_r]  = 0;
                endif;

            endforeach;

        endforeach;
    }

    protected function doPairwise () : void
    {
        // Chrono
        new Timer_Chrono ( $this->_Election->getTimerManager(), 'Do Pairwise' );

        $this->_Pairwise = $this->_Pairwise_Model;

        foreach ( $this->_Election->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote ) :
            $this->computeOneVote($this->_Pairwise, $oneVote);
        endforeach;
    }

    protected function computeOneVote (array &$pairwise, Vote $oneVote) : void
    {
        $vote_ranking = $oneVote->getContextualRanking($this->_Election);

        $voteWeight = $this->_Election->isVoteWeightAllowed() ? $oneVote->getWeight() : 1;

        $vote_candidate_list = [];

        foreach ($vote_ranking as $rank) :
            foreach ($rank as $oneCandidate) :
                $vote_candidate_list[] = $oneCandidate;
            endforeach;
        endforeach;

        $done_Candidates = [];

        foreach ($vote_ranking as $candidates_in_rank) :

            $candidates_in_rank_keys = [];

            foreach ($candidates_in_rank as $candidate) :
                $candidates_in_rank_keys[] = $this->_Election->getCandidateKey($candidate);
            endforeach;

            foreach ($candidates_in_rank as $candidate) :

                $candidate_key = $this->_Election->getCandidateKey($candidate);

                // Process
                foreach ( $vote_candidate_list as $g_Candidate ) :

                    $g_candidate_key = $this->_Election->getCandidateKey($g_Candidate);

                    if ($candidate_key === $g_candidate_key) :
                        continue;
                    endif;

                    // Win & Lose
                    if (    !in_array($g_candidate_key, $done_Candidates, true) && 
                            !in_array($g_candidate_key, $candidates_in_rank_keys, true) ) :

                        $pairwise[$candidate_key]['win'][$g_candidate_key] += $voteWeight;
                        $pairwise[$g_candidate_key]['lose'][$candidate_key] += $voteWeight;

                        $done_Candidates[] = $candidate_key;

                    // Null
                    elseif (in_array($g_candidate_key, $candidates_in_rank_keys, true)) :
                        $pairwise[$candidate_key]['null'][$g_candidate_key] += $voteWeight;
                    endif;

                endforeach;

            endforeach;

        endforeach;
    }

}
