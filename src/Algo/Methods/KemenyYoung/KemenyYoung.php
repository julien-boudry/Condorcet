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

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\Permutations;
use SplFixedArray;

// Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Kemeny–Young','Kemeny-Young','Kemeny Young','KemenyYoung','Kemeny rule','VoteFair popularity ranking','Maximum Likelihood Method','Median Relation'];

    // Method Name
    final public const CONFLICT_WARNING_CODE = 42;

    // Limits
    # If you need 9 candidates, you must use \ini_set('memory_limit','1024M'); before. Do not try to go to 10, it is not viable!
    public static ?int $MaxCandidates = 9;

    // Cache
    public static bool $devWriteCache = false;
    public static string $cachePath = __DIR__ . '/KemenyYoung-Data/';

    // Kemeny Young
    protected SplFixedArray $_PossibleRanking;
    protected array $_RankingScore = [];


/////////// PUBLIC ///////////


    // Get the Kemeny ranking
    public function getResult (): Result
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


    protected function getStats (): array
    {
        $election = $this->getElection();
        $explicit = [];

        foreach ($this->_PossibleRanking as $key => $value) :
            // Human readable
            $i = 1;
            foreach ($value as $candidate_key) :
                $explicit[$key][$i++] = $election->getCandidateObjectFromKey($candidate_key)->getName();
            endforeach;

            $explicit[$key]['score'] = $this->_RankingScore[$key];
        endforeach;

        $stats = [];
        $stats['bestScore'] = \max($this->_RankingScore);
        $stats['rankingScore'] = $explicit;

        return $stats;
    }

        protected function conflictInfos (): void
        {
            $max = \max($this->_RankingScore);

            $conflict = -1;
            foreach ($this->_RankingScore as $value) :
                if ($value === $max) :
                    $conflict++;
                endif;
            endforeach;

            if ($conflict > 0)  :
                $this->_Result->addWarning(
                    type: self::CONFLICT_WARNING_CODE,
                    msg: ($conflict + 1).';'.\max($this->_RankingScore)
                );
            endif;
        }


/////////// COMPUTE ///////////


    //:: Kemeny-Young ALGORITHM. :://

    protected function calcPossibleRanking (): void
    {
        $election = $this->getElection();
        $electionCandidatesCount = $election->countCandidates();
        $this->_PossibleRanking = new SplFixedArray(Permutations::countPossiblePermutations($electionCandidatesCount));

        $i = 0;
        $search = [];
        $replace = [];

        foreach (\array_keys($election->getCandidatesList()) as $candidate_key) :
            $search[] = $i++;
            $replace[] = $candidate_key;
        endforeach;

        /** @infection-ignore-all */
        $path = self::$cachePath.$electionCandidatesCount.'.data';
        $f = new \SplFileInfo($path);

        // Create cache file if not exist, or temp cache file if candidates count > 9
        if (self::$devWriteCache || !$f->isFile()) :
            if (!self::$devWriteCache && !$f->isFile() && $electionCandidatesCount > 9) :
                $f = new \SplTempFileObject();
            else :
                $f = new \SplFileObject($f->getPathname(), 'w+');
            endif;

            (new Permutations ($electionCandidatesCount))->writeResults($f);
        endif;

        // Read Cache & Compute
        if (!($f instanceof \SplFileObject)) :
            $f = $f->openFile('r');
        endif;

        $f->rewind();

        $arrKey = 0;
        while (!$f->eof()) :
            $l = trim($f->fgets());

            if (\strlen($l) < 1) : continue; endif;

            $oneResult = explode(',', $l);

            foreach ($oneResult as &$oneCandidateId) :
                $oneCandidateId = $replace[(int) $oneCandidateId];
            endforeach;

            $resultToRegister = new SplFixedArray($electionCandidatesCount);
            $rank = 0;
            foreach($oneResult as $oneCandidate) :
                $resultToRegister[$rank++] = (int) $oneCandidate;
            endforeach;

            $this->_PossibleRanking[$arrKey++] = $resultToRegister;
        endwhile;
    }

    protected function calcRankingScore (): void
    {
        $pairwise = $this->getElection()->getPairwise();

        foreach ($this->_PossibleRanking as $keyScore => $ranking) :
            $this->_RankingScore[$keyScore] = 0;

            $do = [];

            foreach ($ranking as $candidateId) :
                $do[] = $candidateId;

                foreach ($ranking as $rankCandidate) :
                    if (!\in_array(needle: $rankCandidate, haystack: $do, strict: true)) :
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
    protected function makeRanking (): void
    {
        $winnerRanking = $this->_PossibleRanking[ \array_search(needle: \max($this->_RankingScore), haystack: $this->_RankingScore, strict: true) ];

        $winnerRanking = \array_merge([0 => null], $winnerRanking->toArray());
        unset($winnerRanking[0]);

        $this->_Result = $this->createResult($winnerRanking);
    }
}
