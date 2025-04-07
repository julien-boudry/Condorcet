<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Smith;

use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;
use CondorcetPHP\Condorcet\Result;

/**
 * Smith Set algorithm implementation.
 *
 * The Smith Set is the smallest non-empty set of candidates such that every candidate in the set
 * defeats every candidate outside the set in a pairwise comparison.
 */
class SmithSet extends Method implements MethodInterface
{
    public const array METHOD_NAME = ['Smith set', 'Smith'];


    protected readonly array $SmithSet;
    protected array $Stats = [];

    #[\Override]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        // Calculate Smith Set
        $this->SmithSet = $this->computeSmithSet();
        $this->Stats['smith_set'] = $this->SmithSet;

        // Create result
        $result = [];

        // All candidates in the Smith set are ranked first (equally)
        foreach ($this->SmithSet as $candidateKey) {
            $result[1][] = $candidateKey;
        }

        // All other candidates are ranked second (equally)
        $rank = 2;
        foreach (array_keys($this->getElection()->getCandidatesList()) as $candidateKey) {
            if (!in_array($candidateKey, $this->SmithSet, true)) {
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
                    // candidateId defeats opponentId if it has more wins than losses
                    if ($pairwise[$candidateKey]['win'][$opponentKey] > $pairwise[$candidateKey]['lose'][$opponentKey]) {
                        $graph[$candidateKey][] = $opponentKey;
                    }
                }
            }
        }

        // Initialize all candidates as potential Smith Set members
        $potentialSmithSet = $candidateList;

        // Iteratively identify and remove candidates that are not part of the Smith Set
        $changed = true;
        while ($changed && count($potentialSmithSet) > 0) {
            $changed = false;

            foreach ($potentialSmithSet as $idx => $candidateKey) {
                // Check if there is any candidate in the potential Smith Set that defeats this candidate
                // and is not defeated by any candidate in the set
                foreach ($potentialSmithSet as $opponentKey) {
                    if ($candidateKey === $opponentKey) {
                        continue;
                    }

                    // If opponent defeats candidate
                    if (in_array($candidateKey, $graph[$opponentKey], true)) {
                        $isDefeated = false;

                        // Check if opponent is defeated by any candidate in the set
                        foreach ($potentialSmithSet as $thirdCandidateId) {
                            if ($opponentKey !== $thirdCandidateId && in_array($opponentKey, $graph[$thirdCandidateId], true)) {
                                $isDefeated = true;
                                break;
                            }
                        }

                        // If opponent is not defeated by any candidate in the set,
                        // remove the candidate from the potential Smith Set
                        if (!$isDefeated) {
                            unset($potentialSmithSet[$idx]);
                            $changed = true;
                            break;
                        }
                    }
                }

                if ($changed) {
                    break;
                }
            }
        }

        return array_values($potentialSmithSet);
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
