<?php declare(strict_types=1);
/*
    Part of DODGSON QUICK method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\Dodgson;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;

/**
 * Implements the Dodgson Quick approximation method.
 *
 * @internal
 */
class DodgsonQuick extends Method implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Dodgson Quick', 'DodgsonQuick', 'Dodgson Quick Winner'];

    protected readonly array $Stats;

    protected function getStats(): BaseMethodStats
    {
        $election = $this->getElection();
        $stats = new BaseMethodStats(closed: false);

        foreach ($this->Stats as $candidateKey => $dodgsonQuickValue) {
            $stats[(string) $election->getCandidateObjectFromKey($candidateKey)] = $dodgsonQuickValue;
        }

        return $stats->close();
    }


    /////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    protected function compute(): void
    {
        $election = $this->getElection();
        $candidates = array_keys($election->candidates);

        $pairwise = $election->getPairwise();
        $HeadToHead = [];

        foreach ($candidates as $candidateKey) {
            foreach ($candidates as $opponentId) {
                if ($candidateKey === $opponentId) {
                    continue;
                }

                $diff = $pairwise->compareCandidatesKeys($opponentId, $candidateKey);

                if ($diff >= 0) {
                    $HeadToHead[$candidateKey][$opponentId] = $diff;
                }
            }
        }

        $dodgsonQuick = [];

        foreach ($HeadToHead as $candidateKey => $CandidateTidemanScores) {
            $dodgsonQuick[$candidateKey] = 0;

            foreach ($CandidateTidemanScores as $oneTidemanScore) {
                $dodgsonQuick[$candidateKey] += ceil($oneTidemanScore / 2);
            }
        }
        asort($dodgsonQuick);

        $rank = 0;
        $result = [];

        if ($basicCondorcetWinner = $election->getCondorcetWinner()) {
            $result[++$rank][] = $election->getCandidateKey($basicCondorcetWinner);
        }

        $lastDodgsonQuickValue = null;

        foreach ($dodgsonQuick as $CandidateId => $dodgsonQuickValue) {
            if ($lastDodgsonQuickValue === $dodgsonQuickValue) {
                $result[$rank][] = $CandidateId;
            } else {
                $result[++$rank][] = $CandidateId;
                $lastDodgsonQuickValue = $dodgsonQuickValue;
            }
        }

        $this->Stats = $dodgsonQuick;
        $this->Result = $this->createResult($result);
    }
}
