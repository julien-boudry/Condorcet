<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace Condorcet\Algo;

use Condorcet\CondorcetVersion;
use Condorcet\Election;
use Condorcet\Timer\Chrono as Timer_Chrono;

class Pairwise implements \ArrayAccess,\Iterator
{
    use CondorcetVersion;

    // Implement ArrayAccess
    public function offsetSet($offset, $value) {}

    public function offsetExists($offset) {
        return isset($this->_Pairwise[$offset]);
    }

    public function offsetUnset($offset) {}

    public function offsetGet($offset) {
        return isset($this->_Pairwise[$offset]) ? $this->_Pairwise[$offset] : null;
    }


    // Implement Iterator
    private $valid = true;

    public function rewind() {
        reset($this->_Pairwise);
        $this->valid = true;
    }

    public function current() {
        return $this->_Pairwise[$this->key()];
    }

    public function key() {
        return key($this->_Pairwise);
    }

    public function next() {
        if (next($this->_Pairwise) === false) :
            $this->valid = false;
        endif;
    }

    public function valid() {
        return $this->valid;
    }   


    // Pairwise

    protected $_Election;
    protected $_Pairwise = [];

    public function __construct (Election &$link)
    {
        $this->_Election = $link;
        $this->doPairwise();
    }

    public function getExplicitPairwise ()
    {
        $explicit_pairwise = [];

        foreach ($this->_Pairwise as $candidate_key => $candidate_value) :

            $candidate_name = $this->_Election->getCandidateId($candidate_key, true);
            
            foreach ($candidate_value as $mode => $mode_value) :

                foreach ($mode_value as $candidate_list_key => $candidate_list_value) :
                    $explicit_pairwise[$candidate_name][$mode][$this->_Election->getCandidateId($candidate_list_key, true)] = $candidate_list_value;
                endforeach;

            endforeach;

        endforeach;

        return $explicit_pairwise;
    }

    protected function doPairwise ()
    {
        // Chrono
        $chrono = new Timer_Chrono ( $this->_Election->getTimerManager(), 'Do Pairwise' );

        // Get election data
        $candidate_list = $this->_Election->getCandidatesList(false);
        $vote_list = $this->_Election->getVotesManager();

        foreach ( $candidate_list as $candidate_key => $candidate_id ) :

            $this->_Pairwise[$candidate_key] = array( 'win' => [], 'null' => [], 'lose' => [] );

            foreach ( $candidate_list as $candidate_key_r => $candidate_id_r ) :

                if ($candidate_key_r !== $candidate_key) :
                    $this->_Pairwise[$candidate_key]['win'][$candidate_key_r]   = 0;
                    $this->_Pairwise[$candidate_key]['null'][$candidate_key_r]  = 0;
                    $this->_Pairwise[$candidate_key]['lose'][$candidate_key_r]  = 0;
                endif;

            endforeach;

        endforeach;

        // Win && Null
        foreach ( $vote_list as $vote_id => $vote_ranking ) :

            $done_Candidates = [];

            foreach ($vote_ranking->getContextualVote($this->_Election) as $candidates_in_rank) :

                $candidates_in_rank_keys = [];

                foreach ($candidates_in_rank as $candidate) :
                    $candidates_in_rank_keys[] = $this->_Election->getCandidateKey($candidate);
                endforeach;

                foreach ($candidates_in_rank as $candidate) :

                    $candidate_key = $this->_Election->getCandidateKey($candidate);

                    // Process
                    foreach ( $candidate_list as $g_candidate_key => $g_CandidateId ) :

                        // Win
                        if (    $candidate_key !== $g_candidate_key && 
                                !in_array($g_candidate_key, $done_Candidates, true) && 
                                !in_array($g_candidate_key, $candidates_in_rank_keys, true)
                            ) :

                            $this->_Pairwise[$candidate_key]['win'][$g_candidate_key]++;

                            $done_Candidates[] = $candidate_key;

                        endif;

                        // Null
                        if (    $candidate_key !== $g_candidate_key &&
                                count($candidates_in_rank) > 1 &&
                                in_array($g_candidate_key, $candidates_in_rank_keys, true)
                            ) :

                            $this->_Pairwise[$candidate_key]['null'][$g_candidate_key]++;

                        endif;

                    endforeach;

                endforeach;

            endforeach;

        endforeach;

        // Lose
        foreach ( $this->_Pairwise as $option_key => $option_results ) :

            foreach ($option_results['win'] as $option_compare_key => $option_compare_value) :

                $this->_Pairwise[$option_key]['lose'][$option_compare_key] = $this->_Election->countVotes() -
                        (
                            $this->_Pairwise[$option_key]['win'][$option_compare_key] + 
                            $this->_Pairwise[$option_key]['null'][$option_compare_key]
                        );

            endforeach;

        endforeach;
    }

}