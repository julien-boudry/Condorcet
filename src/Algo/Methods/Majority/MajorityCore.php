<?php

/*
    Part of FTPT method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Majority;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};

abstract class MajorityCore extends Method implements MethodInterface
{
    protected int $maxRound;
    protected int $targetNumberOfCandidatesForTheNextRound;
    protected int $numberOfTargetedCandidatesAfterEachRound;

    protected array $admittedCandidates = [];
    protected readonly array $Stats;

    protected function getStats(): array
    {
        $election = $this->getElection();
        $stats = [];

        foreach ($this->Stats as $roundNumber => $roundScore) {
            foreach ($roundScore as $candidateKey => $oneScore) {
                $stats[$roundNumber][(string) $election->getCandidateObjectFromKey($candidateKey)] = $oneScore;
            }
        }

        return $stats;
    }


    /////////// COMPUTE ///////////

    protected function compute(): void
    {
        $election = $this->getElection();

        $round = 1;
        $resolved = false;
        $score = [];

        // Start a round
        while ($resolved === false) {
            $roundScore = $this->doOneRound();
            ksort($roundScore, \SORT_NATURAL);
            arsort($roundScore, \SORT_NUMERIC);

            $score[$round] = $roundScore;

            if ($round === 1) {
                foreach (array_keys($election->getCandidatesList()) as $oneCandidateKey) {
                    $score[$round][$oneCandidateKey] ??= 0.0;
                }
            }

            if ($round === $this->maxRound || reset($roundScore) > (array_sum($roundScore) / 2)) {
                $resolved = true;

                if (isset($score[$round - 1]) && $score[$round] === $score[$round - 1]) {
                    unset($score[$round]);
                }
            } else {
                $lastScore = null;
                $nextRoundAddedCandidates = 0;

                $this->admittedCandidates = [];

                foreach ($roundScore as $oneCandidateKey => $oneScore) {
                    if ($lastScore === null ||
                        $nextRoundAddedCandidates < ($this->targetNumberOfCandidatesForTheNextRound + ($this->numberOfTargetedCandidatesAfterEachRound * ($round - 1))) ||
                        $oneScore === $lastScore
                    ) {
                        $this->admittedCandidates[] = $oneCandidateKey;
                        $lastScore = $oneScore;
                        $nextRoundAddedCandidates++;
                    }
                }
            }

            $round++;
        }

        // Compute Ranking
        $rank = 0;
        $result = [];
        krsort($score, \SORT_NUMERIC);
        $doneCandidates = [];

        foreach ($score as $oneRound) {
            $lastScore = null;
            foreach ($oneRound as $candidateKey => $candidateScore) {
                if (!\in_array(needle: $candidateKey, haystack: $doneCandidates, strict: true)) {
                    if ($candidateScore === $lastScore) {
                        $doneCandidates[] = $result[$rank][] = $candidateKey;
                    } else {
                        $result[++$rank] = [$doneCandidates[] = $candidateKey];
                        $lastScore = $candidateScore;
                    }
                }
            }
        }

        // Finalizing
        ksort($score, \SORT_NUMERIC);
        $this->Stats = $score;
        $this->Result = $this->createResult($result);
    }

    protected function doOneRound(): array
    {
        $election = $this->getElection();
        $roundScore = [];

        foreach ($election->getVotesValidUnderConstraintGenerator() as $oneVote) {
            $weight = $oneVote->getWeight($election);

            $oneRanking = $oneVote->getContextualRankingWithCandidateKeys($election);

            if (!empty($this->admittedCandidates)) {
                foreach ($oneRanking as $rankKey => $oneRank) {
                    foreach ($oneRank as $InRankKey => $oneCandidate) {
                        if (!\in_array(needle: $oneCandidate, haystack: $this->admittedCandidates, strict: true)) {
                            unset($oneRanking[$rankKey][$InRankKey]);
                        }
                    }

                    if (empty($oneRanking[$rankKey])) {
                        unset($oneRanking[$rankKey]);
                    }
                }

                if (($newFirstRank = reset($oneRanking)) !== false) {
                    $oneRanking = [1 => $newFirstRank];
                } else {
                    continue;
                }
            }

            if (isset($oneRanking[1])) {
                foreach ($oneRanking[1] as $oneCandidateInRank) {
                    $roundScore[$oneCandidateInRank] ??= (float) 0;
                    $roundScore[$oneCandidateInRank] += (1 / \count($oneRanking[1])) * $weight;
                }
            }
        }

        array_walk($roundScore, static fn(float & $sc): float => $sc = round($sc, self::DECIMAL_PRECISION));

        return $roundScore;
    }
}
