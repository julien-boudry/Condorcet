<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

/////////// TOOLS FOR MODULAR ALGORITHMS ///////////

namespace CondorcetPHP\Condorcet\Algo\Tools;

use CondorcetPHP\Condorcet\{Candidate, Election};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, InternalModulesAPI};
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use CondorcetPHP\Condorcet\Relations\HasElection;

// Generic for Algorithms
class VotesDeductedApprovals implements \Countable
{
    use HasElection;

    protected static function voteHasCandidates(array $voteCandidatesKey, array $combination): bool
    {
        foreach ($combination as $oneCombinationCandidateKey) {
            if (!\in_array($oneCombinationCandidateKey, $voteCandidatesKey, true)) {
                return false;
            }
        }

        return true;
    }

    protected static function getCombinationsScoreKey(array $oneCombination): string
    {
        sort($oneCombination, \SORT_NUMERIC);
        return implode('_', $oneCombination);
    }

    ///

    protected array $combinationsScore = [];
/**
 * Build the object.
 * @internal 
 */
    public function __construct(public readonly int $subsetSize, Election $election)
    {
        $this->setElection($election);

        $candidatesList = $this->getElection()->getCandidatesList();
        $candidatesKey = array_keys($candidatesList);
        rsort($candidatesKey, \SORT_NUMERIC);

        foreach (Combinations::computeGenerator($candidatesKey, $subsetSize) as $oneCombination) {
            $this->combinationsScore[self::getCombinationsScoreKey($oneCombination)] = 0;
        }

        foreach ($this->getElection()->getVotesValidUnderConstraintGenerator() as $oneVote) {
            $voteCandidates = $oneVote->getAllCandidates($this->getElection());
            $voteCandidates = array_map(static fn(Candidate $c): ?int => $election->getCandidateKey($c), $voteCandidates);
            $voteCandidates = array_filter($voteCandidates, static fn(?int $c) => $c !== null);

            foreach (Combinations::computeGenerator($candidatesKey, $subsetSize) as $oneCombination) {
                if (self::voteHasCandidates($voteCandidates, $oneCombination)) {
                    $this->combinationsScore[self::getCombinationsScoreKey($oneCombination)] += $oneVote->getWeight($this->getElection());
                }
            }
        }
    }

    // Implements Countable
    public function count(): int
    {
        return \count($this->combinationsScore);
    }
/**
 * @internal 
 */
    public function sumWeightIfVotesIncludeCandidates(array $candidatesKeys): int
    {
        return $this->combinationsScore[self::getCombinationsScoreKey($candidatesKeys)] ?? throw new CondorcetInternalException('This combination does not exist in this stats object');
    }
}
