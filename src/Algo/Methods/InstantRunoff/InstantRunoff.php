<?php
/*
    Part of INSTANT-RUNOFF method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection;

class InstantRunoff extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Instant-runoff', 'InstantRunoff', 'IRV', 'preferential voting', 'ranked-choice voting', 'alternative vote', 'AlternativeVote', 'transferable vote', 'Vote alternatif'];

    protected ?array $_Stats = null;

    public readonly float $majority;

    protected function getStats(): array
    {
        $election = $this->getElection();
        $stats = ['majority' => $this->majority];

        foreach ($this->_Stats as $oneIterationKey => $oneIterationData) {
            if (\count($oneIterationData) === 1) {
                break;
            }

            foreach ($oneIterationData as $candidateKey => $candidateValue) {
                $stats['rounds'][$oneIterationKey][(string) $election->getCandidateObjectFromKey($candidateKey)] = $candidateValue;
            }
        }

        return $stats;
    }


    /////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute(): void
    {
        $election = $this->getElection();

        $candidateCount = $election->countCandidates();
        $this->majority = $election->sumValidVotesWeightWithConstraints() / 2;

        $candidateDone = [];
        $result = [];
        $CandidatesWinnerCount = 0;
        $CandidatesLoserCount = 0;

        $iteration = 0;

        while (\count($candidateDone) < $candidateCount) {
            $score = $this->makeScore($candidateDone);
            $maxScore = max($score);
            $minScore = min($score);

            $this->_Stats[++$iteration] = $score;

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

        $this->_Result = $this->createResult($result);
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

            foreach ($oneVote->getContextualRankingWithoutSort($election) as $oneRank) {
                foreach ($oneRank as $oneCandidate) {
                    if (\count($oneRank) !== 1) {
                        break;
                    } elseif (!\in_array(needle: ($candidateKey = $election->getCandidateKey($oneCandidate)), haystack: $candidateDone, strict: true)) {
                        $score[$candidateKey] += $weight;
                        break 2;
                    }
                }
            }
        }

        return $score;
    }
}
