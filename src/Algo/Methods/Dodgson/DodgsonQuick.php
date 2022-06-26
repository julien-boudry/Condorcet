<?php
/*
    Part of DODGSON QUICK method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Dodgson;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};

// DODGSON Quick is an approximation for Dodgson method | https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
class DodgsonQuick extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Dodgson Quick', 'DodgsonQuick', 'Dodgson Quick Winner'];

    protected ?array $_Stats = null;

    protected function getStats(): array
    {
        $election = $this->getElection();
        $stats = [];

        foreach ($this->_Stats as $candidateKey => $dodgsonQuickValue) {
            $stats[(string) $election->getCandidateObjectFromKey($candidateKey)] = $dodgsonQuickValue;
        }

        return $stats;
    }


    /////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    protected function compute(): void
    {
        $election = $this->getElection();

        $pairwise = $election->getPairwise();
        $HeadToHead = [];

        foreach ($pairwise as $candidateId => $CandidateStats) {
            foreach ($CandidateStats['lose'] as $opponentId => $CandidateLose) {
                if (($diff = $CandidateLose - $CandidateStats['win'][$opponentId]) >= 0) {
                    $HeadToHead[$candidateId][$opponentId] = $diff;
                }
            }
        }

        $dodgsonQuick = [];

        foreach ($HeadToHead as $candidateId => $CandidateTidemanScores) {
            $dodgsonQuick[$candidateId] = 0;

            foreach ($CandidateTidemanScores as $opponentId => $oneTidemanScore) {
                $dodgsonQuick[$candidateId] += ceil($oneTidemanScore / 2);
            }
        }
        asort($dodgsonQuick);

        $rank = 0;
        $result = [];

        if ($basicCondorcetWinner = $election->getWinner(null)) {
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

        $this->_Stats = $dodgsonQuick;
        $this->_Result = $this->createResult($result);
    }
}
