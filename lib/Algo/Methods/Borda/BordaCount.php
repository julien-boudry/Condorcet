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

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};

class BordaCount extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['BordaCount','Borda Count','Borda','Méthode Borda'];

    public static int $starting = 1;

    protected ?array $_Stats = null;

    protected function getStats () : array
    {
        $stats = [];

        foreach ($this->_Stats as $candidateKey => $oneScore) :
             $stats[(string) $this->_selfElection->getCandidateObjectFromKey($candidateKey)] = $oneScore;
        endforeach;

        return $stats;
    }


/////////// COMPUTE ///////////

    //:: BORDA ALGORITHM. :://

    protected function compute () : void
    {
        $score = [];

        foreach ($this->_selfElection->getCandidatesList() as $oneCandidate) :
            $score[$this->_selfElection->getCandidateKey($oneCandidate)] = 0;
        endforeach;

        foreach ($this->_selfElection->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) :

            $weight = $this->_selfElection->isVoteWeightAllowed() ? $oneVote->getWeight() : 1;

            for ($i = 0 ; $i < $weight ; $i++) :
                $CandidatesRanked = 0;
                $oneRanking = $oneVote->getContextualRanking($this->_selfElection);

                foreach ($oneRanking as $oneRank) :
                    $rankScore = 0;
                    foreach ($oneRank as $oneCandidateInRank) :
                        $rankScore += $this->getScoreByCandidateRanking($CandidatesRanked++);
                    endforeach;

                    foreach ($oneRank as $oneCandidateInRank) :
                        $score[$this->_selfElection->getCandidateKey($oneCandidateInRank)] += $rankScore / \count($oneRank);
                    endforeach;
                endforeach;
            endfor;
        endforeach;

        \arsort($score,\SORT_NUMERIC);

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

    protected function getScoreByCandidateRanking (int $CandidatesRanked) : float
    {
        return $this->_selfElection->countCandidates() + static::$starting - 1 - $CandidatesRanked;
    }
}
