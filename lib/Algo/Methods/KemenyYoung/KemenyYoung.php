<?php
/*
    Part of KEMENY–YOUNG method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung;

use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\Permutation;

// Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Kemeny–Young','Kemeny-Young','Kemeny Young','KemenyYoung','Kemeny rule','VoteFair popularity ranking','Maximum Likelihood Method','Median Relation'];

    // Method Name
    public const CONFLICT_WARNING_CODE = 42;

    // Limits
        /* If you need to put it on 9, You must use ini_set('memory_limit','1024M'); before. The first use will be slower because Kemeny-Young will work without pre-calculated data of Permutations.
        Do not try to go to 10, it is not viable! */
        public static ?int $MaxCandidates = 8;

    // Cache
    public static bool $useCache = true;
    public static bool $devWriteCache = false;

    // Kemeny Young
    protected array $_PossibleRanking;
    protected array $_RankingScore;


/////////// PUBLIC ///////////


    // Get the Kemeny ranking
    public function getResult () : Result
    {
        // Cache
        if ( $this->_Result === null ) :
            $this->calcPossibleRanking();
            $this->calcRankingScore();
            $this->makeRanking();
            $this->conflictInfos();
        endif;

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
                $candidate_key = $this->_selfElection->getCandidateObjectFromKey($candidate_key);
            endforeach;

            $explicit[$key]['score'] = $this->_RankingScore[$key];
        endforeach;

        $stats = [];
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
        if (!self::$useCache || !file_exists($path)) :
            $compute = $this->doPossibleRanking( self::$devWriteCache ? $path : null );
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

    protected function doPossibleRanking (?string $path = null)
    {
        $permutation = new Permutation ($this->_selfElection->countCandidates());

        if ($path === null) :
            return $permutation->getResults(true);
        else :
            $permutation->writeResults($path);
            return $permutation->getResults(true);
        endif;
    }

    protected function calcRankingScore () : void
    {
        $this->_RankingScore = [];
        $pairwise = $this->_selfElection->getPairwise();

        foreach ($this->_PossibleRanking as $keyScore => $ranking) :
            $this->_RankingScore[$keyScore] = 0;

            $do = [];

            foreach ($ranking as $candidateId) :
                $do[] = $candidateId;

                foreach ($ranking as $rankCandidate) :
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
