<?php declare(strict_types=1);
/*
    Part of Largest Remainder Methods module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\LargestRemainder;

use CondorcetPHP\Condorcet\Algo\{MethodInterface};
use CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

/**
 * Largest Remainder is a proportional algorithm | https://en.wikipedia.org/wiki/Largest_remainder_method.
 *
 * @internal
 */
class LargestRemainder extends HighestAverages_Core implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Largest Remainder', 'LargestRemainder', 'LR', 'Hare–Niemeyer method', 'Hamilton method', 'Vinton\'s method'];

    public static StvQuotas $optionQuota = StvQuotas::HARE;

    #[\Override]
    protected function makeRounds(): array
    {
        $election = $this->getElection();
        $results = [];
        $rescueCandidatesKeys = array_keys($election->getCandidatesList());
        reset($rescueCandidatesKeys);

        $quotient = $this->computeQuotient($election->sumValidVoteWeightsWithConstraints(), $election->seatsToElect);

        while (array_sum($this->candidatesSeats) < $election->seatsToElect) {
            $roundNumber = \count($this->rounds) + 1;
            $maxVotes = null;
            $maxVotesCandidateKey = null;

            foreach ($this->candidatesVotes as $candidateKey => $oneCandidateVotes) {
                $this->rounds[$roundNumber][$candidateKey]['NumberOfVotesAllocatedBeforeRound'] = $oneCandidateVotes;
                $this->rounds[$roundNumber][$candidateKey]['SeatsToElectAllocatedBeforeRound'] = $this->candidatesSeats[$candidateKey];

                if ($oneCandidateVotes > $maxVotes) {
                    $maxVotes = $oneCandidateVotes;
                    $maxVotesCandidateKey = $candidateKey;
                }
            }

            if ($maxVotesCandidateKey === null) {
                $n = current($rescueCandidatesKeys);
                $maxVotesCandidateKey = $n;
                if (next($rescueCandidatesKeys) === false) {
                    reset($rescueCandidatesKeys);
                }
            }

            $this->candidatesVotes[$maxVotesCandidateKey] -= $quotient;
            $this->candidatesSeats[$maxVotesCandidateKey]++;
            $results[$roundNumber] = $maxVotesCandidateKey;
        }

        return $results;
    }

    #[\Override]
    protected function computeQuotient(int $votesWeight, int $seats): float
    {
        return self::$optionQuota->getQuota($votesWeight, $seats);
    }
}
