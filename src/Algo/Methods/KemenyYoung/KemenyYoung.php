<?php declare(strict_types=1);
/*
    Part of KEMENY–YOUNG method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung;

use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface, StatsVerbosity};
use CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise;
use CondorcetPHP\Condorcet\Algo\Stats\{BaseMethodStats};
use CondorcetPHP\Condorcet\Algo\Tools\Permutations;

/** Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
 * @internal
 */
class KemenyYoung extends Method implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Kemeny–Young', 'Kemeny-Young', 'Kemeny Young', 'KemenyYoung', 'Kemeny rule', 'VoteFair popularity ranking', 'Maximum Likelihood Method', 'Median Relation'];

    // Method Name
    final public const int CONFLICT_WARNING_CODE = 42;

    // Limits
    # If you need 9 candidates, you must use \ini_set('memory_limit','1024M'); before. Do not try to go to 10, it is not viable!
    public static ?int $MaxCandidates = 10;

    // Cache process
    protected readonly int $countElectionCandidates;
    protected readonly array $candidatesKey;
    protected readonly int $countPossibleRanking;

    // processing
    protected int $MaxScore = -1;
    protected int $Conflits = 0;
    protected int $bestRankingKey;
    protected array $bestRankingTab;


    /////////// PUBLIC ///////////


    // Get the Kemeny ranking
    #[\Override]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result === null) {
            $this->countElectionCandidates = $this->getElection()->countCandidates();
            $this->candidatesKey = array_keys($this->getElection()->getCandidatesList());
            $this->countPossibleRanking = Permutations::getPossibleCountOfPermutations($this->countElectionCandidates);

            $this->computeMaxAndConflicts();
            $this->makeRanking();
            $this->conflictInfos();
        }

        // Return
        return $this->Result;
    }


    protected function getStats(): BaseMethodStats
    {
        $election = $this->getElection();
        $stats = new BaseMethodStats(closed: false);

        $stats['Best Score'] = $this->MaxScore;
        $stats['Ranking In Conflicts'] = $this->Conflits > 0 ? $this->Conflits + 1 : $this->Conflits;

        if ($election->statsVerbosity->value >= StatsVerbosity::FULL->value) {
            $explicit = [];

            foreach ($this->getPossibleRankingIterator() as $key => $value) {
                // Human readable
                $i = 1;
                foreach ($value as $candidate_key) {
                    $explicit[$key][$i++] = $election->getCandidateObjectFromKey($candidate_key)->name;
                }

                $explicit[$key]['score'] = $this->computeOneScore($value, $election->getPairwise());
            }

            $stats['Ranking Scores'] = $explicit;
        }

        return $stats->close();
    }

    protected function conflictInfos(): void
    {
        if ($this->Conflits > 0) {
            $this->Result->addWarning(
                type: self::CONFLICT_WARNING_CODE,
                msg: ($this->Conflits + 1) . ';' . $this->MaxScore
            );
        }
    }


    /////////// COMPUTE ///////////


    //:: Kemeny-Young ALGORITHM. :://

    protected function getPossibleRankingIterator(): \Generator
    {
        $perm = new Permutations($this->candidatesKey);

        $key = 0;
        foreach ($perm->getPermutationGenerator() as $onePermutation) {
            yield $key++ => $onePermutation;
        }
    }


    protected function computeMaxAndConflicts(): void
    {
        $pairwise = $this->getElection()->getPairwise();

        foreach ($this->getPossibleRankingIterator() as $keyScore => $onePossibleRanking) {
            $rankingScore = $this->computeOneScore($onePossibleRanking, $pairwise);

            // Max Ranking Score
            if ($rankingScore > $this->MaxScore) {
                $this->MaxScore = $rankingScore;
                $this->Conflits = 0;
                $this->bestRankingKey = $keyScore;
                $this->bestRankingTab = $onePossibleRanking;
            } elseif ($rankingScore === $this->MaxScore) {
                $this->Conflits++;
            }
        }
    }

    protected function computeOneScore(array $ranking, Pairwise $pairwise): int
    {
        $rankingScore = 0;
        $do = [];

        foreach ($ranking as $candidateId) {
            $do[] = $candidateId;

            foreach ($ranking as $rankCandidate) {
                if (!\in_array(needle: $rankCandidate, haystack: $do, strict: true)) {
                    $rankingScore += $pairwise[$candidateId]['win'][$rankCandidate];
                }
            }
        }

        return $rankingScore;
    }


    /*
    I do not know how in the very unlikely event that several possible classifications have the same highest score.
    In the current state, one of them is chosen arbitrarily.

    See issue on Github : https://github.com/julien-boudry/Condorcet/issues/6
    */
    protected function makeRanking(): void
    {
        $winnerRanking = [null, ...$this->bestRankingTab];
        unset($winnerRanking[0]);

        $this->Result = $this->createResult($winnerRanking);
    }
}
