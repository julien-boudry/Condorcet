<?php

/*
    Part of STV method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\STV;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface, StatsVerbosity};
use CondorcetPHP\Condorcet\Algo\Stats\{BaseMethodStats};
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Vote;

// Single transferable vote | https://en.wikipedia.org/wiki/Single_transferable_vote
class SingleTransferableVote extends Method implements MethodInterface
{
    final public const bool IS_PROPORTIONAL = true;

    // Method Name
    public const array METHOD_NAME = ['STV', 'Single Transferable Vote', 'SingleTransferableVote'];

    public static StvQuotas $optionQuota = StvQuotas::DROOP;

    protected readonly array $Stats;

    protected float $votesNeededToWin;


    /////////// COMPUTE ///////////

    protected function compute(): void
    {
        $election = $this->getElection();
        Vote::initCache(); // Performances

        $result = [];
        $stats = [];
        $rank = 0;

        $this->votesNeededToWin = round(self::$optionQuota->getQuota($election->sumValidVoteWeightsWithConstraints(), $election->getNumberOfSeats()), self::DECIMAL_PRECISION, \RoundingMode::HalfTowardsZero);

        $candidateElected = [];
        $candidateEliminated = [];

        $end = false;
        $round = 0;

        $surplusToTransfer = [];

        while (!$end) {
            $scoreTable = $this->makeScore($surplusToTransfer, $candidateElected, $candidateEliminated);
            ksort($scoreTable, \SORT_NATURAL);
            arsort($scoreTable, \SORT_NUMERIC);

            $successOnRank = false;

            foreach ($scoreTable as $candidateKey => $oneScore) {
                $surplus = $oneScore - $this->votesNeededToWin;

                if ($surplus >= 0) {
                    $result[++$rank] = [$candidateKey];
                    $candidateElected[] = $candidateKey;

                    $surplusToTransfer[$candidateKey] ?? $surplusToTransfer[$candidateKey] = ['surplus' => 0, 'total' => 0];
                    $surplusToTransfer[$candidateKey]['surplus'] += $surplus;
                    $surplusToTransfer[$candidateKey]['total'] += $oneScore;
                    $successOnRank = true;
                }
            }

            if (!$successOnRank && !empty($scoreTable)) {
                $candidateEliminated[] = array_key_last($scoreTable);
            } elseif (empty($scoreTable) || $rank >= $election->getNumberOfSeats()) {
                $end = true;
            }

            $stats[++$round] = $scoreTable;
        }

        while ($rank < $election->getNumberOfSeats() && !empty($candidateEliminated)) {
            $rescueCandidateKey = array_key_last($candidateEliminated);
            $result[++$rank] = $candidateEliminated[$rescueCandidateKey];
            unset($candidateEliminated[$rescueCandidateKey]);
        }

        $this->Stats = $stats;
        $this->Result = $this->createResult($result);

        Vote::clearCache(); // Performances
    }

    protected function makeScore(array $surplus = [], array $candidateElected = [], array $candidateEliminated = []): array
    {
        $election = $this->getElection();
        $scoreTable = [];

        $candidateDone = array_merge($candidateElected, $candidateEliminated);

        foreach (array_keys($election->getCandidatesList()) as $oneCandidateKey) {
            if (!\in_array($candidateKey = $oneCandidateKey, $candidateDone, true)) {
                $scoreTable[$candidateKey] = 0.0;
            }
        }

        foreach ($election->getVotesValidUnderConstraintGenerator() as $oneVote) {
            $weight = $oneVote->getWeight($election);

            $winnerBonusWeight = 0;
            $winnerBonusKey = null;
            $LoserBonusWeight = 0;

            $firstRank = true;
            foreach ($oneVote->getContextualRankingWithCandidateKeys($election) as $oneRank) {
                foreach ($oneRank as $candidateKey) {
                    if (\count($oneRank) !== 1) {
                        break;
                    }

                    if ($firstRank) {
                        if (\array_key_exists($candidateKey, $surplus)) {
                            $winnerBonusWeight = $weight;
                            $winnerBonusKey = $candidateKey;
                            $firstRank = false;
                            break;
                        } elseif (\in_array($candidateKey, $candidateEliminated, true)) {
                            $LoserBonusWeight = $weight;
                            $firstRank = false;
                            break;
                        }
                    }

                    if (\array_key_exists($candidateKey, $scoreTable)) {
                        if ($winnerBonusKey !== null) {
                            $scoreTable[$candidateKey] += $winnerBonusWeight / $surplus[$winnerBonusKey]['total'] * $surplus[$winnerBonusKey]['surplus'];
                        } elseif ($LoserBonusWeight > 0) {
                            $scoreTable[$candidateKey] += $LoserBonusWeight;
                        } else {
                            $scoreTable[$candidateKey] += $weight;
                        }

                        $scoreTable[$candidateKey] = round($scoreTable[$candidateKey], self::DECIMAL_PRECISION, \RoundingMode::HalfTowardsZero);

                        break 2;
                    }
                }
            }
        }

        return $scoreTable;
    }

    protected function getStats(): BaseMethodStats
    {
        $election = $this->getElection();

        $stats = ['Votes Needed to Win' => $this->votesNeededToWin];

        if ($election->statsVerbosity->value > StatsVerbosity::LOW->value) {
            $stats['rounds'] = [];

            foreach ($this->Stats as $roundNumber => $roundData) {
                foreach ($roundData as $candidateKey => $candidateValue) {
                    $stats['rounds'][$roundNumber][(string) $election->getCandidateObjectFromKey($candidateKey)] = $candidateValue;
                }
            }
        }

        return new BaseMethodStats($stats);
    }
}
