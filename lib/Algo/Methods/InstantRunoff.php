<?php
/*
    Part of INSTANT-RUNOFF method Module - From the original Condorcet PHP

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

class InstantRunoff extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Instant-runoff','InstantRunoff','preferential voting','ranked-choice voting','alternative vote','AlternativeVote','transferable vote','Vote alternatif'];

    public static $starting = 1;

    protected $_Stats;

    protected function getStats () : array
    {
        $stats = [];

        return $stats;
    }


/////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute () : void
    {
        $candidateCount = $this->_selfElection->countCandidates();
        $candidateDone = [];
        $result = [];
        $CandidatesWinnerCount = 0;
        $CandidatesLoserCount = 0;

        while (count($candidateDone) < $candidateCount) :
            $score = $this->makeScore($candidateDone);
            $maxScore = max($score);
            $minScore = min($score);

            if ($maxScore > $this->_selfElection->sumVotesWeight()) :
                $WinnersToRegister = [];
                $rank = $CandidatesWinnerCount + 1;

                foreach ($score as $candidateKey => $candidateScore) :
                    if ($candidateScore !== $maxScore) :
                        continue;
                    else :
                        $WinnersToRegister[] = $candidateKey;
                    endif;

                    if(count($WinnersToRegister) > 1) :
                        $WinnersToRegister = $this->tieBreaking($WinnersToRegister, true);
                    endif;

                    $CandidatesWinnerCount += count($WinnersToRegister);
                    $candidateDone = array_merge($candidateDone,$WinnersToRegister);
                    $result[$rank][] = $WinnersToRegister;
                endforeach;
            else :
                $LosersToRegister = [];

                foreach ($score as $candidateKey => $candidateScore) :
                    if ($candidateScore !== $minScore) :
                        continue;
                    else :
                        $LosersToRegister[] = $candidateKey;
                    endif;
                endforeach;

                if(count($LosersToRegister) > 1) :
                    $LosersToRegister = $this->tieBreaking($LosersToRegister, false);
                endif;

                $CandidatesLoserCount += count($LosersToRegister);
                $candidateDone = array_merge($candidateDone,$LosersToRegister);
                $result[$candidateCount - $CandidatesLoserCount + 1] = $LosersToRegister;
            endif;

        endwhile;

        $this->_Result = $this->createResult($result);
    }

    protected function makeScore (array $candidateDone) : array
    {
        $score = [];
        foreach ($this->_selfElection->getCandidatesList() as $oneCandidate) :
            if (!in_array($this->_selfElection->getCandidateKey($oneCandidate),$candidateDone,true)) :
                $score[$this->_selfElection->getCandidateKey($oneCandidate)] = 0;
            endif;
        endforeach;

        foreach ($this->_selfElection->getVotesManager() as $oneVote) :
            $weight = ($this->_selfElection->isVoteWeightIsAllowed()) ? $oneVote->getWeight() : 1;

            for ($i = 0 ; $i < $weight ; $i++) :
                $oneRanking = $oneVote->getContextualRanking($this->_selfElection);

                foreach ($oneRanking as $oneRank) :
                    foreach ($oneRank as $oneCandidate) :
                        if(count($oneRank) !== 1) :
                            break;
                        elseif (!in_array($this->_selfElection->getCandidateKey($oneCandidate),$candidateDone,true)) :
                            $score[$this->_selfElection->getCandidateKey(reset($oneRank))] += 1;
                            break 2;
                        endif;
                    endforeach;
                endforeach;
            endfor;
        endforeach;

        return $score;
    }

    protected function tieBreaking (array $candidatesKeys, bool $isWinnerTieBreaking) : array
    {
        $pairwise = $this->_selfElection->getPairwise(false);
        $tooKeep = [];

        foreach ($candidatesKeys as $oneCandidateKeyTotest) :
            $select = true;
            foreach ($candidatesKeys as $oneChallengerKey) :
                if ($oneCandidateKeyTotest === $oneChallengerKey) :
                    continue;
                endif;


                $win = $pairwise[$oneCandidateKeyTotest]['win'][$oneChallengerKey];
                $lose = $pairwise[$oneCandidateKeyTotest]['lose'][$oneChallengerKey];

                if ( ($isWinnerTieBreaking) ? $this->winnerTieBreaking($win,$lose) : $this->loserTieBreaking($win,$lose) ) :
                    $select = false;
                endif;
            endforeach;

            if ($select) :
                $tooKeep[] = $oneCandidateKeyTotest;
            endif;
        endforeach;

        return $tooKeep;
    }

    protected function winnerTieBreaking (int $win, int $lose) : bool
    {
        return $win < $lose;
    }

    protected function loserTieBreaking (int $win, int $lose) : bool
    {
        return $win > $lose;
    }
}
