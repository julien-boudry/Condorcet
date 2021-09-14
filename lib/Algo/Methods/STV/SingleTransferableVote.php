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

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

// Single transferable vote | https://en.wikipedia.org/wiki/Single_transferable_vote
class SingleTransferableVote extends Method implements MethodInterface
{
    final public const IS_PROPORTIONAL = true;

    // Method Name
    public const METHOD_NAME = ['STV','Single Transferable Vote','SingleTransferableVote'];

    public static string $optionQuota = 'droop quota';

    protected ?array $_Stats = null;

    protected readonly float $votesNeededToWin;


/////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute (): void
    {
        $result = [];
        $rank = 0;

        $this->votesNeededToWin = StvQuotas::getQuota(self::$optionQuota, $this->_selfElection->sumValidVotesWeightWithConstraints(), $this->_selfElection->getNumberOfSeats());

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
                $surplus = $oneScore - $this->votesNeededToWin;

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
                $candidateEliminated[] = \array_key_last($scoreTable);
            elseif (empty($scoreTable) || $rank >= $this->_selfElection->getNumberOfSeats()) :
                $end = true;
            endif;

            $this->_Stats[++$round] = $scoreTable;

        endwhile;

        while ($rank < $this->_selfElection->getNumberOfSeats() && !empty($candidateEliminated)) :
            $rescueCandidateKey = \array_key_last($candidateEliminated);
            $result[++$rank] = $candidateEliminated[$rescueCandidateKey];
            unset($candidateEliminated[$rescueCandidateKey]);
        endwhile;

        $this->_Result = $this->createResult($result);
    }

    protected function makeScore (array $surplus, array $candidateElected, array $candidateEliminated): array
    {
        $scoreTable = [];

        $candidateDone = array_merge($candidateElected, $candidateEliminated);

        foreach ($this->_selfElection->getCandidatesList() as $oneCandidate) :
            if (!\in_array($candidateKey = $this->_selfElection->getCandidateKey($oneCandidate), $candidateDone, true)) :
                $scoreTable[$candidateKey] = 0;
            endif;
        endforeach;

        foreach ($this->_selfElection->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) :

            $weight = $oneVote->getWeight($this->_selfElection);

            $winnerBonusWeight = 0;
            $winnerBonusKey = null;
            $LoserBonusWeight = 0;

            $firstRank = true;
            foreach ($oneVote->getContextualRanking($this->_selfElection) as $oneRank) :
                foreach ($oneRank as $oneCandidate) :
                    if (\count($oneRank) !== 1): break; endif;

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

    protected function getStats(): array
    {
        $stats = [
            'votes_needed_to_win' => $this->votesNeededToWin,
            'rounds' => []
        ];

        foreach ($this->_Stats as $roundNumber => $roundData) :
            foreach ($roundData as $candidateKey => $candidateValue) :
                $stats['rounds'][$roundNumber][(string) $this->_selfElection->getCandidateObjectFromKey($candidateKey)] = \round($candidateValue, 12, \PHP_ROUND_HALF_DOWN);
            endforeach;
        endforeach;

        return $stats;
    }

}
