<?php
/*
    Part of Highest Averages Methods module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\LargestRemainder;

use CondorcetPHP\Condorcet\Algo\{MethodInterface};
use CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

# Largest Remainder is a proportional algorithm | https://en.wikipedia.org/wiki/Largest_remainder_method
class LargestRemainder extends HighestAverages_Core implements MethodInterface
{
    final public const IS_PROPORTIONAL = true;

    // Method Name
    public const METHOD_NAME = ['Hare-LR'];

    public static StvQuotas $optionQuota = StvQuotas::HARE;

    protected function makeRounds (): array
    {
        $election = $this->getElection();
        $results = [];

        $quotient = $this->computeQuotient($election->sumValidVotesWeightWithConstraints(), $election->getNumberOfSeats());

        while (\array_sum($this->candidatesSeats) < $election->getNumberOfSeats()) :
            $roundNumber = \count($this->rounds) + 1;
            $maxVotes = 0;
            $maxVotesCandidateKey = null;

            foreach ($this->candidatesVotes as $candidateKey => $oneCandidateVotes) :

                $this->rounds[$roundNumber][$candidateKey]['NumberOfVotesAllocatedBeforeRound'] = $oneCandidateVotes;
                $this->rounds[$roundNumber][$candidateKey]['NumberOfSeatsAllocatedBeforeRound'] = $this->candidatesSeats[$candidateKey];

                if ($oneCandidateVotes > $maxVotes) :
                    $maxVotes = $oneCandidateVotes;
                    $maxVotesCandidateKey = $candidateKey;
                endif;
            endforeach;

            $this->candidatesVotes[$maxVotesCandidateKey] -= $quotient;
            $this->candidatesSeats[$maxVotesCandidateKey]++;
            $results[$roundNumber] = $maxVotesCandidateKey;
        endwhile;

        return $results;
    }

    protected function computeQuotient (int $votesWeight, int $seats): float
    {
        return self::$optionQuota->getQuota($votesWeight, $seats);
    }
}
