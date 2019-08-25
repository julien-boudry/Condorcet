<?php
/*
    Part of SCHULZE method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;

use CondorcetPHP\Condorcet\Result;

// Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
abstract class Schulze_Core extends Method implements MethodInterface
{
    // Schulze
    protected array $_StrongestPaths = [];


/////////// PUBLIC ///////////

    abstract protected function schulzeVariant (int &$i,int &$j);

    public function getResult () : Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

            //////

        // Format array
        $this->prepareStrongestPath();

        // Strongest Paths calculation
        $this->makeStrongestPaths();

        // Ranking calculation
        $this->makeRanking();


        // Return
        return $this->_Result;
    }


    // Get the Schulze ranking
    protected function getStats () : array
    {
        $explicit = [];

        foreach ($this->_StrongestPaths as $candidate_key => $candidate_value) :
            $candidate_key = $this->_selfElection->getCandidateObjectFromKey($candidate_key)->getName();

            foreach ($candidate_value as $challenger_key => $challenger_value) :
                $explicit[$candidate_key][$this->_selfElection->getCandidateObjectFromKey($challenger_key)->getName()] = $challenger_value;
            endforeach;
        endforeach;

        return $explicit;
    }



/////////// COMPUTE ///////////


    //:: SCHULZE ALGORITHM. :://


    // Calculate the strongest Paths for Schulze Method
    protected function prepareStrongestPath () : void
    {
        foreach ( $this->_selfElection->getCandidatesList() as $candidate_key => $candidate_id ) :
            $this->_StrongestPaths[$candidate_key] = [];

            // Format array for the strongest path
            foreach ( $this->_selfElection->getCandidatesList() as $candidate_key_r => $candidate_id_r ) :
                if ($candidate_key_r != $candidate_key) :
                    $this->_StrongestPaths[$candidate_key][$candidate_key_r] = 0;
                endif;
            endforeach;
        endforeach;
    }


    // Calculate the Strongest Paths
    protected function makeStrongestPaths () : void
    {
        foreach ($this->_selfElection->getCandidatesList() as $i => $i_value) :
            foreach ($this->_selfElection->getCandidatesList() as $j => $j_value) :
                if ($i !== $j) :
                    if ( $this->_selfElection->getPairwise()[$i]['win'][$j] > $this->_selfElection->getPairwise()[$j]['win'][$i] ) :
                        $this->_StrongestPaths[$i][$j] = $this->schulzeVariant($i,$j);                      
                    else :
                        $this->_StrongestPaths[$i][$j] = 0;
                    endif;
                endif;
            endforeach;
        endforeach;

        foreach ($this->_selfElection->getCandidatesList() as $i => $i_value) :
            foreach ($this->_selfElection->getCandidatesList() as $j => $j_value) :
                if ($i !== $j) :
                    foreach ($this->_selfElection->getCandidatesList() as $k => $k_value) :
                        if ($i !== $k && $j !== $k) :
                            $this->_StrongestPaths[$j][$k] = 
                                max( $this->_StrongestPaths[$j][$k], 
                                     min($this->_StrongestPaths[$j][$i], $this->_StrongestPaths[$i][$k]) );
                        endif;
                    endforeach;
                endif;
            endforeach;
        endforeach;
    }


    // Calculate && Format human readable ranking
    protected function makeRanking () : void
    {       
        $result = [];

        // Calculate ranking
        $done = [];
        $rank = 1;

        while (count($done) < $this->_selfElection->countCandidates()) :
            $to_done = [];

            foreach ( $this->_StrongestPaths as $candidate_key => $challengers_key ) :
                if ( in_array($candidate_key, $done, true) ) :
                    continue;
                endif;

                $winner = true;

                foreach ($challengers_key as $beaten_key => $beaten_value) :
                    if ( in_array($beaten_key, $done, true) ) :
                        continue;
                    endif;

                    if ( $beaten_value < $this->_StrongestPaths[$beaten_key][$candidate_key] ) :
                        $winner = false;
                    endif;
                endforeach;

                if ($winner) :
                    $result[$rank][] = $candidate_key;

                    $to_done[] = $candidate_key;
                endif;
            endforeach;

            array_push($done, ...$to_done);

            $rank++;
        endwhile;

        $this->_Result = $this->createResult($result);
    }
}
