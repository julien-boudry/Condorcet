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
    public const METHOD_NAME = ['Kemeny–Young','Kemeny Young','KemenyYoung','Kemeny rule','VoteFair popularity ranking','Maximum Likelihood Method','Median Relation'];

    // Method Name
    public const CONFLICT_WARNING_CODE = 42;

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
    public function getResult () : Result
    {
        // Cache
        if ( $this->_Result === null )
        {
            $this->calcPossibleRanking();
            $this->calcRankingScore();
            $this->makeRanking();
            $this->conflictInfos();
        }

        // Return
        return $this->_Result;
    }


    protected function getStats () : array
    {
        $explicit = [];

        foreach ($this->_PossibleRanking as $key => $value) :
            $explicit[$key] = $value;

            // Human readable
            foreach ($explicit[$key] as &$candidate_key) :
                $candidate_key = $this->_selfElection->getCandidateId($candidate_key);
            endforeach;

            $explicit[$key]['score'] = $this->_RankingScore[$key];
        endforeach;

        $stats['bestScore'] = max($this->_RankingScore);
        $stats['rankingScore'] = $explicit;

        return $stats;
    }

        protected function conflictInfos () : void
        {
            $max = max($this->_RankingScore);

            $conflict = -1;
            foreach ($this->_RankingScore as $value) :
                if ($value === $max) :
                    $conflict++;
                endif;
            endforeach;

            if ($conflict > 0)  :
                $this->_Result->addWarning(self::CONFLICT_WARNING_CODE, ($conflict + 1).';'.max($this->_RankingScore) );
            endif;
        }


/////////// COMPUTE ///////////


    //:: Kemeny-Young ALGORITHM. :://

    protected function calcPossibleRanking () : void
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

        foreach ($this->_selfElection->getCandidatesList() as $candidate_id => $candidate_name) :
            $search[] = 's:'.(($i < 10) ? "2" : "3").':"C'.$i++.'"';
            $replace[] = 'i:'.$candidate_id;
        endforeach;

        $this->_PossibleRanking = unserialize( str_replace($search, $replace, $compute) );
    }

    protected function doPossibleRanking (string $path = null)
    {
        $permutation = new Permutation ($this->_selfElection->countCandidates());

        if ($path === null) :
            return $permutation->getResults(true);
        else :
            $permutation->writeResults($path);
        endif;
    }

    protected function calcRankingScore () : void
    {
        $this->_RankingScore = [];
        $pairwise = $this->_selfElection->getPairwise(false);

        foreach ($this->_PossibleRanking as $keyScore => $ranking) :
            $this->_RankingScore[$keyScore] = 0;

            $do = [];

            foreach ($ranking as $candidateId) :
                $do[] = $candidateId;

                foreach ($ranking as $rank => $rankCandidate) :
                    if (!in_array($rankCandidate, $do, true)) :
                        $this->_RankingScore[$keyScore] += $pairwise[$candidateId]['win'][$rankCandidate];
                    endif;
                endforeach;
            endforeach;
        endforeach;
    }


    /*
    I do not know how in the very unlikely event that several possible classifications have the same highest score.
    In the current state, one of them is chosen arbitrarily.

    See issue on Github : https://github.com/julien-boudry/Condorcet/issues/6
    */
    protected function makeRanking () : void
    {
        $this->_Result = $this->createResult($this->_PossibleRanking[ array_search(max($this->_RankingScore), $this->_RankingScore, true) ]);
    }

}
