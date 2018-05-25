<?php
/*
    Part of FTPT method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;

use Condorcet\Result;

class Ftpt extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['First-past-the-post voting', 'First-past-the-post', 'First Choice', 'FirstChoice', 'FTPT'];

    protected $_Stats;

    protected function getStats(): array
    {
        $stats = [];

        foreach ($this->_Stats as $candidateKey => $oneScore) :
            $stats[(string)$this->_selfElection->getCandidateId($candidateKey)] = $oneScore;
        endforeach;

        return $stats;
    }


/////////// COMPUTE ///////////

    //:: FTPT Count :://

    protected function compute(): void
    {
        $score = [];

        foreach ($this->_selfElection->getCandidatesList() as $oneCandidate) :
            $score[$this->_selfElection->getCandidateKey($oneCandidate)] = 0;
        endforeach;

        foreach ($this->_selfElection->getVotesManager() as $oneVote) :
            $weight = ($this->_selfElection->isVoteWeightIsAllowed()) ? $oneVote->getWeight() : 1;

            for ($i = 0; $i < $weight; $i++) :
                $oneRanking = $oneVote->getContextualRanking($this->_selfElection);

                foreach ($oneRanking[1] as $oneCandidateInRank) :
                    $score[$this->_selfElection->getCandidateKey($oneCandidateInRank)] += 1 / count($oneRanking[1]);
                endforeach;
            endfor;
        endforeach;

        arsort($score, SORT_NUMERIC);

        $rank = 0;
        $lastScore = null;
        $result = [];
        foreach ($score as $candidateKey => $candidateScore) :
            if ($candidateScore === $lastScore) :
                $result[$rank][] = $candidateKey;
            else :
                $result[++$rank] = [$candidateKey];
                $lastScore = $candidateScore;
            endif;
        endforeach;

        $this->_Stats = $score;
        $this->_Result = $this->createResult($result);
    }
}
