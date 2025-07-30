<?php declare(strict_types=1);
/*
    Part of INSTANT-RUNOFF method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\{BaseMethodStats};
use CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection;

/** @internal */
class InstantRunoff extends Method implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Instant-runoff', 'InstantRunoff', 'IRV', 'preferential voting', 'ranked-choice voting', 'alternative vote', 'AlternativeVote', 'transferable vote', 'Vote alternatif'];

    protected readonly array $Stats;

    public readonly float $majority;

    protected function getStats(): BaseMethodStats
    {
        $election = $this->getElection();
        $stats = ['majority' => $this->majority];

        foreach ($this->Stats as $oneIterationKey => $oneIterationData) {
            if (\count($oneIterationData) === 1) {
                break;
            }

            foreach ($oneIterationData as $candidateKey => $candidateValue) {
                $stats['rounds'][$oneIterationKey][(string) $election->getCandidateObjectFromKey($candidateKey)] = $candidateValue;
            }
        }

        return new BaseMethodStats($stats);
    }


    /////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute(): void
    {
        $election = $this->getElection();

        $candidateCount = $election->countCandidates();
        $this->majority = $election->sumValidVoteWeightsWithConstraints() / 2;

        $candidateDone = [];
        $result = [];
        $stats = [];
        $CandidatesWinnerCount = 0;
        $CandidatesLoserCount = 0;

        $iteration = 0;

        while (\count($candidateDone) < $candidateCount) {
            $score = $this->makeScore($candidateDone);
            $maxScore = max($score);
            $minScore = min($score);

            $stats[++$iteration] = $score;

            if ($maxScore > $this->majority) {
                foreach ($score as $candidateKey => $candidateScore) {
                    if ($candidateScore !== $maxScore) {
                        continue;
                    } else {
                        $candidateDone[] = $candidateKey;
                        $result[++$CandidatesWinnerCount][] = $candidateKey;
                    }
                }
            } else {
                $LosersToRegister = [];

                foreach ($score as $candidateKey => $candidateScore) {
                    if ($candidateScore !== $minScore) {
                        continue;
                    } else {
                        $LosersToRegister[] = $candidateKey;
                    }
                }

                // Tie Breaking
                $round = \count($LosersToRegister);
                for ($i = 1; $i < $round; $i++) { // A little silly. But ultimately shorter and simpler.
                    $LosersToRegister = TieBreakersCollection::electSomeLosersbasedOnPairwiseComparaison($election, $LosersToRegister);
                }

                $CandidatesLoserCount += \count($LosersToRegister);
                array_push($candidateDone, ...$LosersToRegister);
                $result[$candidateCount - $CandidatesLoserCount + 1] = $LosersToRegister;
            }
        }

        $this->Stats = $stats;
        $this->Result = $this->createResult($result);
    }

    protected function makeScore(array $candidateDone): array
    {
        $election = $this->getElection();
        $score = [];

        foreach ($election->getCandidatesList() as $candidateKey => $oneCandidate) {
            if (!\in_array(needle: $candidateKey, haystack: $candidateDone, strict: true)) {
                $score[$candidateKey] = 0;
            }
        }

        foreach ($election->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) {
            $weight = $oneVote->getWeight($election);

            foreach ($oneVote->getContextualRankingWithCandidateKeys($election) as $oneRank) {
                foreach ($oneRank as $oneCandidate) {
                    if (\count($oneRank) !== 1) {
                        break;
                    } elseif (!\in_array(needle: ($candidateKey = $oneCandidate), haystack: $candidateDone, strict: true)) {
                        $score[$candidateKey] += $weight;

                        break 2;
                    }
                }
            }
        }

        return $score;
    }
}
