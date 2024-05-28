<?php
/*
    Part of BORDA COUNT method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Borda;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Election;

class BordaCount extends Method implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['BordaCount', 'Borda Count', 'Borda', 'Méthode Borda'];

    public static int $optionStarting = 1;

    protected ?array $Stats = null;

    protected function getStats(): array
    {
        $election = $this->getElection();
        $stats = [];

        foreach ($this->Stats as $candidateKey => $oneScore) {
            $stats[(string) $election->getCandidateObjectFromKey($candidateKey)] = $oneScore;
        }

        return $stats;
    }


    /////////// COMPUTE ///////////

    //:: BORDA ALGORITHM. :://

    protected function compute(): void
    {
        $election = $this->getElection();
        $score = [];

        foreach (array_keys($election->getCandidatesList()) as $oneCandidateKey) {
            $score[$oneCandidateKey] = 0;
        }

        foreach ($election->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) {
            $CandidatesRanked = 0;
            $oneRanking = $oneVote->getContextualRankingWithCandidateKeys($election);

            foreach ($oneRanking as $oneRank) {
                $rankScore = 0.0;
                foreach ($oneRank as $oneCandidateInRank) {
                    $rankScore += $this->getScoreByCandidateRanking($CandidatesRanked++, $election);
                }

                foreach ($oneRank as $oneCandidateInRank) {
                    $score[$oneCandidateInRank] += ($rankScore / \count($oneRank)) * $oneVote->getWeight($election);
                }
            }
        }

        array_walk($score, static fn(float & $sc): float => $sc = round($sc, self::DECIMAL_PRECISION));
        ksort($score, \SORT_NATURAL);
        arsort($score, \SORT_NUMERIC);

        $rank = 0;
        $lastScore = null;
        $result = [];
        foreach ($score as $candidateKey => $candidateScore) {
            if ($candidateScore === $lastScore) {
                $result[$rank][] = $candidateKey;
            } else {
                $result[++$rank] = [$candidateKey];
                $lastScore = $candidateScore;
            }
        }

        $this->Stats = $score;
        $this->Result = $this->createResult($result);
    }

    protected function getScoreByCandidateRanking(int $CandidatesRanked, Election $election): float
    {
        return (float) ($election->countCandidates() + static::$optionStarting - 1 - $CandidatesRanked);
    }
}
