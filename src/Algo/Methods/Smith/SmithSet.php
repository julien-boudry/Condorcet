<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\Smith;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;
use CondorcetPHP\Condorcet\Result;

/**
 * Smith Set algorithm implementation.
 *
 * The Smith Set is the smallest non-empty set of candidates such that every candidate in the set
 * defeats every candidate outside the set in a pairwise comparison.
 *
 * @internal
 */
class SmithSet extends Method implements MethodInterface
{
    public const array METHOD_NAME = ['Smith set', 'Smith'];


    protected readonly array $SmithSet;

    #[\Override]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        // Calculate Smith Set
        $this->SmithSet = $this->computeSmithSet();

        // Create result
        $result = [];

        // All candidates in the Smith set are ranked first (equally)
        foreach ($this->SmithSet as $candidateKey) {
            $result[1][] = $candidateKey;
        }

        // All other candidates are ranked second (equally)
        $rank = 2;
        foreach (array_keys($this->getElectionOrFail()->getCandidatesList()) as $candidateKey) {
            if (!\in_array($candidateKey, $this->SmithSet, true)) {
                $result[$rank][] = $candidateKey;
            }
        }

        // Create and return the Result object
        return $this->Result = $this->createResult($result);
    }

    /**
     * Compute the Smith Set.
     *
     * @return array<int, int> Array of candidate IDs in the Smith Set
     */
    protected function computeSmithSet(): array
    {
        $election = $this->getElectionOrFail();
        $pairwise = $election->getPairwise();
        $candidateKeys = array_keys($election->getCandidatesList());

        // 1. Initialize Reachability Matrix
        // $reach[i][j] = true if i beats or ties j
        $reach = [];
        foreach ($candidateKeys as $i) {
            foreach ($candidateKeys as $j) {
                if ($i === $j) {
                    $reach[$i][$j] = true;
                } else {
                    // i beats or ties j if j does NOT beat i
                    $reach[$i][$j] = !$pairwise->candidateKeyWinVersus($j, $i);
                }
            }
        }

        // 2. Floyd-Warshall Algorithm
        foreach ($candidateKeys as $k) {
            foreach ($candidateKeys as $i) {
                foreach ($candidateKeys as $j) {
                    if ($reach[$i][$k] && $reach[$k][$j]) {
                        $reach[$i][$j] = true;
                    }
                }
            }
        }

        // 3. Collect candidates who can reach all other candidates
        $smithSet = [];
        foreach ($candidateKeys as $i) {
            $canReachAll = true;
            foreach ($candidateKeys as $j) {
                if (!$reach[$i][$j]) {
                    $canReachAll = false;

                    break;
                }
            }
            if ($canReachAll) {
                $smithSet[] = $i;
            }
        }

        return $smithSet;
    }

    #[\Override]
    protected function getStats(): BaseMethodStats
    {
        $stats = new BaseMethodStats(closed: false);

        return $stats->setEntry(
            'smith_set',
            array_map(fn(int $k): string => $this->getElectionOrFail()->getCandidateObjectFromKey($k)->name, $this->SmithSet)
        )->close();
    }
}
