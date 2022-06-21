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
use CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAveragesMethod;

# Copeland is a proportional algorithm | https://en.wikipedia.org/wiki/Webster/Sainte-Lagu%C3%AB_method
abstract class LargestRemainderMethod extends HighestAveragesMethod implements MethodInterface
{
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
}
