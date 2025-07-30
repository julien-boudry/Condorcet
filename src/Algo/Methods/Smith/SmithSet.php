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
        foreach (array_keys($this->getElection()->getCandidatesList()) as $candidateKey) {
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
     * @return array Array of candidate IDs in the Smith Set
     */
    protected function computeSmithSet(): array
    {
        $election = $this->getElection();
        $pairwise = $election->getPairwise();
        $candidateList = array_keys($election->getCandidatesList());

        // Create a directed graph representing the defeat relation
        $graph = [];
        foreach ($candidateList as $candidateKey) {
            $graph[$candidateKey] = [];
            foreach ($candidateList as $opponentKey) {
                if ($candidateKey !== $opponentKey) {
                    // candidateKey defeats opponentKey if it has more wins than losses
                    if ($pairwise->candidateKeyWinVersus($candidateKey, $opponentKey)) {
                        $graph[$candidateKey][] = $opponentKey;
                    }
                }
            }
        }

        // Find the Smith Set using an iterative algorithm
        $candidates = $candidateList;

        do {
            $modified = false;

            foreach ($candidates as $i => $candidateId) {
                // Check if this candidate is beaten by someone in the set
                $beatenBy = [];
                foreach ($candidates as $j => $opponentId) {
                    if ($i !== $j && \in_array($candidateId, $graph[$opponentId], true)) {
                        $beatenBy[] = $opponentId;
                    }
                }

                if (empty($beatenBy)) {
                    continue;
                }

                // Check if this candidate beats anyone in the set
                $beats = [];
                foreach ($candidates as $j => $opponentId) {
                    if ($i !== $j && \in_array($opponentId, $graph[$candidateId], true)) {
                        $beats[] = $opponentId;
                    }
                }

                // If this candidate is beaten by someone in the set but doesn't beat anyone in the set,
                // remove it from consideration
                if (empty($beats)) {
                    unset($candidates[$i]);
                    $modified = true;
                }
            }

        } while ($modified && \count($candidates) > 0);

        return array_values($candidates);
    }

    #[\Override]
    protected function getStats(): BaseMethodStats
    {
        $stats = new BaseMethodStats(closed: false);

        return $stats->setEntry(
            'smith_set',
            array_map(fn(int $k): string => $this->getElection()->getCandidateObjectFromKey($k)->name, $this->SmithSet)
        )->close();
    }
}
