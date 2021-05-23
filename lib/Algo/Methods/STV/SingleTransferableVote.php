<?php
/*
    Part of STV method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\STV;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Examples, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};

// Single transferable vote | https://en.wikipedia.org/wiki/Single_transferable_vote
class SingleTransferableVote extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['STV','Single Transferable Vote','SingleTransferableVote'];

    public static int $seats = 2;

    protected ?array $_Stats = null;


/////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute () : void
    {
        $result = [];
        $rank = 0;

        $v = $this->_selfElection->sumValidVotesWeightWithConstraints();

        $votesNeededToWin = floor(($v / (self::$seats + 1)) + 1);

        $candidateElected = [];
        $candidateEliminated = [];

        $end = false;
        $round = 0;

        $surplusToTransfer = [];

        while (!$end) :

            $scoreTable = $this->makeScore($surplusToTransfer, $candidateElected, $candidateEliminated);
            arsort($scoreTable, \SORT_NUMERIC);

            $successOnRank = false;

            foreach($scoreTable as $candidateKey => $oneScore) :
                $surplus = $oneScore - $votesNeededToWin;

                if ($surplus >= 0) :
                    $result[++$rank] = [$candidateKey];
                    $candidateElected[] = $candidateKey;

                    $surplusToTransfer[$candidateKey] ?? $surplusToTransfer[$candidateKey] = ['surplus' => 0, 'total' => 0];
                    $surplusToTransfer[$candidateKey]['surplus'] += $surplus;
                    $surplusToTransfer[$candidateKey]['total'] += $oneScore;
                    $successOnRank = true;
                endif;
            endforeach;

            if (!$successOnRank && !empty($scoreTable)) :
                \array_key_last($scoreTable);
                $candidateEliminated[] = \array_key_last($scoreTable);
            elseif (empty($scoreTable) || $rank >= self::$seats) :
                $end = true;
            endif;

            $this->_Stats[++$round] = $scoreTable;

        endwhile;

        $this->_Result = $this->createResult($result);
    }

    protected function makeScore (array $surplus, array $candidateElected, array $candidateEliminated) : array
    {
        $scoreTable = [];

        $candidateDone = array_merge($candidateElected, $candidateEliminated);

        foreach ($this->_selfElection->getCandidatesList() as $oneCandidate) :
            if (!\in_array($candidateKey = $this->_selfElection->getCandidateKey($oneCandidate), $candidateDone, true)) :
                $scoreTable[$candidateKey] = 0;
            endif;
        endforeach;

        var_dump($scoreTable);

        foreach ($this->_selfElection->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) :

            $weight = $oneVote->getWeight($this->_selfElection);

            $winnerBonusWeight = 0;
            $winnerBonusKey = null;
            $LoserBonusWeight = 0;

            $firstRank = true;
            foreach ($oneVote->getContextualRanking($this->_selfElection) as $oneRank) :
                foreach ($oneRank as $oneCandidate) :
                    if (\count($oneRank) !== 1) : break; endif;

                    $candidateKey = $this->_selfElection->getCandidateKey($oneCandidate);

                    if ($firstRank) :
                        if (isset($surplus[$candidateKey])) :
                            $winnerBonusWeight = $weight;
                            $winnerBonusKey = $candidateKey;
                            $firstRank = false;
                            break;
                        elseif (\in_array($candidateKey, $candidateEliminated, true)) :
                            $LoserBonusWeight = $weight;
                            $firstRank = false;
                            break;
                        endif;
                    endif;

                    if (isset($scoreTable[$candidateKey])) :
                        if ($winnerBonusKey !== null) :
                            $scoreTable[$candidateKey] += $winnerBonusWeight / $surplus[$winnerBonusKey]['total']  * $surplus[$winnerBonusKey]['surplus'];
                        elseif ($LoserBonusWeight > 0) :
                            $scoreTable[$candidateKey] += $LoserBonusWeight;
                        else :
                            $scoreTable[$candidateKey] += $weight;
                        endif;

                        break 2;
                    endif;
                endforeach;
            endforeach;
        endforeach;

        return $scoreTable;
    }

    protected function tieBreaking (array $candidatesKeys) : array
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

    protected function getStats(): array
    {
        $stats = [];

        foreach ($this->_Stats as $roundNumber => $roundData) :
            foreach ($roundData as $candidateKey => $candidateValue) :
                $stats[$roundNumber][(string) $this->_selfElection->getCandidateObjectFromKey($candidateKey)] = $candidateValue;
            endforeach;
        endforeach;

        return $stats;
    }

}
