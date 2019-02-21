<?php
/*
    Part of INSTANT-RUNOFF method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods;

use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;
use CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats;
use CondorcetPHP\Condorcet\Result;

class InstantRunoff extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Instant-runoff', 'InstantRunoff', 'preferential voting', 'ranked-choice voting', 'alternative vote', 'AlternativeVote', 'transferable vote', 'Vote alternatif'];

    public static int $starting = 1;

    protected ?array $_Stats = null;

    protected function getStats(): array
    {
        $stats = [];

        foreach ($this->_Stats as $oneIterationKey => $oneIterationData) :
            if (count($oneIterationData) === 1) :
                break;
            endif;

            foreach ($oneIterationData as $candidateKey => $candidateValue) :
                $stats[$oneIterationKey][(string) $this->_selfElection->getCandidateObjectFromKey($candidateKey)] = $candidateValue;
            endforeach;
        endforeach;

        return $stats;
    }


/////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute (): void
    {
        $candidateCount = $this->_selfElection->countCandidates();
        $majority = $this->_selfElection->sumValidVotesWeightWithConstraints() / 2;

        $candidateDone = [];
        $result = [];
        $CandidatesWinnerCount = 0;
        $CandidatesLoserCount = 0;

        $iteration = 0;

        while (count($candidateDone) < $candidateCount) :
            $score = $this->makeScore($candidateDone);
            $maxScore = max($score);
            $minScore = min($score);

            $this->_Stats[++$iteration] = $score;

            if ( $maxScore > $majority ) :
                foreach ($score as $candidateKey => $candidateScore) :
                    if ($candidateScore !== $maxScore) :
                        continue;
                    else :
                        $candidateDone[] = $candidateKey;
                        $result[++$CandidatesWinnerCount][] = $candidateKey;
                    endif;
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

                // Tie Breaking
                $round = count($LosersToRegister);
                for ($i = 1 ; $i < $round ; $i++) : // A little silly. But ultimately shorter and simpler.
                    $LosersToRegister = $this->tieBreaking($LosersToRegister);
                endfor;

                $CandidatesLoserCount += count($LosersToRegister);
                $candidateDone = array_merge($candidateDone, $LosersToRegister);
                $result[$candidateCount - $CandidatesLoserCount + 1] = $LosersToRegister;
            endif;

        endwhile;

        $this->_Result = $this->createResult($result);
    }

    protected function makeScore (array $candidateDone): array
    {
        $score = [];
        foreach ($this->_selfElection->getCandidatesList() as $oneCandidate) :
            if (!in_array($this->_selfElection->getCandidateKey($oneCandidate), $candidateDone, true)) :
                $score[$this->_selfElection->getCandidateKey($oneCandidate)] = 0;
            endif;
        endforeach;

        foreach ($this->_selfElection->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) :

            $weight = ($this->_selfElection->isVoteWeightIsAllowed()) ? $oneVote->getWeight() : 1;

            for ($i = 0; $i < $weight; $i++) :
                $oneRanking = $oneVote->getContextualRanking($this->_selfElection);

                foreach ($oneRanking as $oneRank) :
                    foreach ($oneRank as $oneCandidate) :
                        if (count($oneRank) !== 1) :
                            break;
                        elseif (!in_array($this->_selfElection->getCandidateKey($oneCandidate), $candidateDone, true)) :
                            $score[$this->_selfElection->getCandidateKey(reset($oneRank))] += 1;
                            break 2;
                        endif;
                    endforeach;
                endforeach;
            endfor;
        endforeach;

        return $score;
    }

    protected function tieBreaking (array $candidatesKeys): array
    {
        $pairwise = $this->_selfElection->getPairwise();
        $pairwiseStats = PairwiseStats::PairwiseComparison($pairwise);
        $tooKeep = [];

        foreach ($candidatesKeys as $oneCandidateKeyTotest) :
            $select = true;
            foreach ($candidatesKeys as $oneChallengerKey) :
                if ($oneCandidateKeyTotest === $oneChallengerKey) :
                    continue;
                endif;

                if (    $pairwise[$oneCandidateKeyTotest]['win'][$oneChallengerKey] > $pairwise[$oneCandidateKeyTotest]['lose'][$oneChallengerKey] ||
                        $pairwiseStats[$oneCandidateKeyTotest]['balance'] > $pairwiseStats[$oneChallengerKey]['balance'] ||
                        $pairwiseStats[$oneCandidateKeyTotest]['win'] > $pairwiseStats[$oneChallengerKey]['win']
                ) :
                    $select = false;
                endif;
            endforeach;

            if ($select) :
                $tooKeep[] = $oneCandidateKeyTotest;
            endif;
        endforeach;

        return $tooKeep;
    }
}
