<?php
/*
    Part of Highest Averages Methods module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\HighestAveragesMethod;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface, StatsVerbosity};

# Copeland is a proportional algorithm | https://en.wikipedia.org/wiki/Webster/Sainte-Lagu%C3%AB_method
abstract class HighestAveragesMethod extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['SainteLague'];

    protected array $_Stats = [];


/////////// COMPUTE ///////////

    protected function compute (): void
    {
        $election = $this->getElection();
        $candidatesVotes = [];

        foreach (\array_keys($election->getCandidatesList()) as $candidateKey) :
            $candidatesVotes[$candidateKey] = 0;
        endforeach;

        foreach ($election->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) :
            $voteWinnerRank = $oneVote->getContextualRankingWithoutSort($election)[1];

            if (\count($voteWinnerRank) !== 1): continue; endif; // This method support only one winner per vote. Ignore bad votes.

            $candidatesVotes[$election->getCandidateKey(\reset($voteWinnerRank))] += $oneVote->getWeight($election);
        endforeach;

        unset($voteWinnerRank);

        # Rounds
        $rounds = [];
        $candidatesSeats = [];
        $results = [];

        foreach (\array_keys($election->getCandidatesList()) as $candidateKey) :
            $candidatesSeats[$candidateKey] = 0;
        endforeach;

        while (\array_sum($candidatesSeats) < $election->getNumberOfSeats()) :
            $roundNumber = \count($rounds) + 1;
            $maxQuotient = 0;
            $maxQuotientCandididateKey = null;

            foreach ($candidatesVotes as $candidateKey => $oneCandidateVotes) :
                $quotient = $this->computeQuotient($oneCandidateVotes, $candidatesSeats[$candidateKey]);

                $rounds[$roundNumber][$candidateKey]['Quotient'] = $quotient;
                $rounds[$roundNumber][$candidateKey]['NumberOfSeatsAllocatedBeforeRound'] = $candidatesSeats[$candidateKey];

                if ($quotient > $maxQuotient) :
                    $maxQuotient = $quotient;
                    $maxQuotientCandididateKey = $candidateKey;
                endif;
            endforeach;

            $candidatesSeats[$maxQuotientCandididateKey]++;
            $results[$roundNumber] = $maxQuotientCandididateKey;
        endwhile;

        $this->_Stats['Rounds'] = $rounds;
        $this->_Stats['Seats per Candidates'] = $candidatesSeats;

        $this->_Result = $this->createResult($results);
    }

    abstract protected function computeQuotient (int $candidateVotes, int $candidateSeats): float;

    protected function getStats(): array
    {
        $election = $this->getElection();

        $stats = [];

        if ($election->getStatsVerbosity()->value > StatsVerbosity::NONE->value) :
            foreach ($this->_Stats['Rounds'] as $roundNumber => $oneRound) :
                foreach ($oneRound as $candidateKey => $roundCandidateStats) :
                    $stats['Rounds'][$roundNumber][$election->getCandidateObjectFromKey($candidateKey)->getName()] = $roundCandidateStats;
                endforeach;
            endforeach;

            foreach ($this->_Stats['Seats per Candidates'] as $candidateKey => $candidateSeats) :
                $stats['Seats per Candidates'][$election->getCandidateObjectFromKey($candidateKey)->getName()] = $candidateSeats;
            endforeach;
        endif;

        return $stats;
    }

}
