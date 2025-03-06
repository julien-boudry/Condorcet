<?php

/*
    Part of Highest Averages Methods module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\HighestAverages;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface, StatsVerbosity};

abstract class HighestAverages_Core extends Method implements MethodInterface
{
    final public const bool IS_PROPORTIONAL = true;

    protected array $candidatesVotes = [];
    protected array $candidatesSeats = [];
    protected array $rounds = [];


    /////////// COMPUTE ///////////

    protected function compute(): void
    {
        $this->countVotesPerCandidates();

        if (array_sum($this->candidatesVotes) > 0) {
            foreach (array_keys($this->getElection()->getCandidatesList()) as $candidateKey) {
                $this->candidatesSeats[$candidateKey] = 0;
            }

            # Rounds
            $this->Result = $this->createResult($this->makeRounds());
        } else {
            $this->Result = $this->createResult([]);
        }
    }

    protected function countVotesPerCandidates(): void
    {
        $election = $this->getElection();

        foreach (array_keys($election->getCandidatesList()) as $candidateKey) {
            $this->candidatesVotes[$candidateKey] = 0;
        }

        foreach ($election->getVotesValidUnderConstraintGenerator() as $oneVote) {
            if ($oneVote->countRankingCandidates() === 0) {
                continue;
            }

            $voteWinnerRank = $oneVote->getContextualRankingWithCandidateKeys($election)[1];

            if (\count($voteWinnerRank) !== 1) {
                continue;
            } // This method support only one winner per vote. Ignore bad votes.

            $this->candidatesVotes[reset($voteWinnerRank)] += $oneVote->getWeight($election);
        }
    }

    protected function makeRounds(): array
    {
        $election = $this->getElection();
        $results = [];

        while (array_sum($this->candidatesSeats) < $election->getNumberOfSeats()) {
            $roundNumber = \count($this->rounds) + 1;
            $maxQuotient = null;
            $maxQuotientCandidateKey = null;

            foreach ($this->candidatesVotes as $candidateKey => $oneCandidateVotes) {
                $quotient = $this->computeQuotient($oneCandidateVotes, $this->candidatesSeats[$candidateKey]);

                $this->rounds[$roundNumber][$candidateKey]['Quotient'] = $quotient;
                $this->rounds[$roundNumber][$candidateKey]['NumberOfSeatsAllocatedBeforeRound'] = $this->candidatesSeats[$candidateKey];

                if ($quotient > $maxQuotient) {
                    $maxQuotient = $quotient;
                    $maxQuotientCandidateKey = $candidateKey;
                }
            }

            $this->candidatesSeats[$maxQuotientCandidateKey]++;
            $results[$roundNumber] = $maxQuotientCandidateKey;
        }

        return $results;
    }

    abstract protected function computeQuotient(int $votesWeight, int $seats): float;

    protected function getStats(): array
    {
        $election = $this->getElection();

        $stats = [];

        if ($election->StatsVerbosity->value > StatsVerbosity::LOW->value) {
            foreach ($this->rounds as $roundNumber => $oneRound) {
                foreach ($oneRound as $candidateKey => $roundCandidateStats) {
                    $stats['Rounds'][$roundNumber][$election->getCandidateObjectFromKey($candidateKey)->name] = $roundCandidateStats;
                }
            }
        }

        if ($election->StatsVerbosity->value > StatsVerbosity::NONE->value) {
            foreach ($this->candidatesSeats as $candidateKey => $candidateSeats) {
                $stats['Seats per Candidates'][$election->getCandidateObjectFromKey($candidateKey)->name] = $candidateSeats;
            }
        }

        return $stats;
    }
}
