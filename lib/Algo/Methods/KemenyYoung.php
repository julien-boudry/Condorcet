<?php
/*
    Kemeny-Young part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;
use Condorcet\Algo\Tools\Permutation;
use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Result;

// Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung extends Method implements MethodInterface
{
    // Method Name
    const METHOD_NAME = ['Kemeny–Young','Kemeny Young','KemenyYoung','Kemeny rule','VoteFair popularity ranking','Maximum Likelihood Method','Median Relation'];

    // Limits
        /* If you need to put it on 9, You must use ini_set('memory_limit','1024M'); before. The first use will be slower because Kemeny-Young will work without pre-calculated data of Permutations.
        Do not try to go to 10, it is not viable! */
        public static $_maxCandidates = 8;


    // Kemeny Young
    protected $_PossibleRanking;
    protected $_RankingScore;


    public function __construct (Election $mother)
    {
        parent::__construct($mother);

        if (!is_null(self::$_maxCandidates) && $this->_selfElection->countCandidates() > self::$_maxCandidates)
        {
            throw new CondorcetException( 101,self::$_maxCandidates.'|'.self::METHOD_NAME[0] );
        }
    }


/////////// PUBLIC ///////////


    // Get the Kemeny ranking
    public function getResult ($options = null) : Result
    {
        // Cache
        if ( $this->_Result === null )
        {
            $this->calcPossibleRanking();
            $this->calcRankingScore();
            $this->makeRanking();
        }

        if (isset($options['noConflict']) && $options['noConflict'] === true)
        {
            $conflicts = $this->conflictInfos();
            if ( $conflicts !== false)
            {
                return $this->conflictInfos();
            }
        }

        // Return
        return $this->_Result;
    }


    public function getStats () : array
    {
        $this->getResult();

            //////

        $explicit = [];

        foreach ($this->_PossibleRanking as $key => $value)
        {
            $explicit[$key] = $value;

            // Human readable
            foreach ($explicit[$key] as &$candidate_key)
            {
                $candidate_key = $this->_selfElection->getCandidateId($candidate_key);
            }

            $explicit[$key]['score'] = $this->_RankingScore[$key];
        }

        return $explicit;
    }

        protected function conflictInfos ()
        {
            $max = max($this->_RankingScore);

            $conflict = -1;
            foreach ($this->_RankingScore as $value)
            {
                if ($value === $max)
                {
                    $conflict++;
                }
            }

            if ($conflict === 0) 
                {return false;}
            else
            {
                return ($conflict + 1).';'.max($this->_RankingScore);
            }
        }


/////////// COMPUTE ///////////


    //:: Kemeny-Young ALGORITHM. :://

    protected function calcPossibleRanking ()
    {
        $path = __DIR__ . '/KemenyYoung-Data/'.$this->_selfElection->countCandidates().'.data';

        // But ... where are the data ?! Okay, old way now...
        if (!file_exists($path)) :
            $compute = $this->doPossibleRanking( (Condorcet::ENV === 'DEV') ? $path : null );
        else :
            $compute = file_get_contents($path);
        endif;

        $i = 0;
        $search = [];
        $replace = [];

        foreach ($this->_selfElection->getCandidatesList() as $candidate_id => $candidate_name)
        {
            $search[] = 's:'.(($i < 10) ? "2" : "3").':"C'.$i++.'"';
            $replace[] = 'i:'.$candidate_id;
        }

        $this->_PossibleRanking = unserialize( str_replace($search, $replace, $compute) );
    }

    protected function doPossibleRanking ($path = null)
    {
        $permutation = new Permutation ($this->_selfElection->countCandidates());

        if ($path === null) :
            return $permutation->getResults(true);
        else :
            $permutation->writeResults($path);
        endif;
    }

    protected function calcRankingScore ()
    {
        $this->_RankingScore = [];
        $pairwise = $this->_selfElection->getPairwise(false);

        foreach ($this->_PossibleRanking as $keyScore => $ranking) 
        {
            $this->_RankingScore[$keyScore] = 0;

            $do = [];

            foreach ($ranking as $candidateId)
            {
                $do[] = $candidateId;

                foreach ($ranking as $rank => $rankCandidate)
                {
                    if (!in_array($rankCandidate, $do, true))
                    {
                        $this->_RankingScore[$keyScore] += $pairwise[$candidateId]['win'][$rankCandidate];
                    }
                }
            }
        }
    }


    /*
    I do not know how in the very unlikely event that several possible classifications have the same highest score.
    In the current state, one of them is chosen arbitrarily.

    See issue on Github : https://github.com/julien-boudry/Condorcet/issues/6
    */
    protected function makeRanking ()
    {
        $this->_Result = $this->createResult($this->_PossibleRanking[ array_search(max($this->_RankingScore), $this->_RankingScore, true) ]);
    }

}
