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
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface, StatsVerbosity};
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Vote;

// Single transferable vote | https://en.wikipedia.org/wiki/Single_transferable_vote
class SingleTransferableVote extends Method implements MethodInterface
{
    final public const IS_PROPORTIONAL = true;

    // Method Name
    public const METHOD_NAME = ['STV','Single Transferable Vote','SingleTransferableVote'];

    public static StvQuotas $optionQuota = StvQuotas::DROOP;

    protected ?array $_Stats = null;

    protected float $votesNeededToWin;


/////////// COMPUTE ///////////

    protected function compute (): void
    {
        $election = $this->getElection();
        Vote::initCache(); // Performances

        $result = [];
        $rank = 0;

        $this->votesNeededToWin = round(self::$optionQuota->getQuota($election->sumValidVotesWeightWithConstraints(), $election->getNumberOfSeats()), self::DECIMAL_PRECISION, \PHP_ROUND_HALF_DOWN);

        $candidateElected = [];
        $candidateEliminated = [];

        $end = false;
        $round = 0;

        $surplusToTransfer = [];

        while (!$end) :
            $scoreTable = $this->makeScore($surplusToTransfer, $candidateElected, $candidateEliminated);
            \ksort($scoreTable, \SORT_NATURAL);
            \arsort($scoreTable, \SORT_NUMERIC);

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
            elseif (empty($scoreTable) || $rank >= $election->getNumberOfSeats()) :
                $end = true;
            endif;

            $this->_Stats[++$round] = $scoreTable;

        endwhile;

        while ($rank < $election->getNumberOfSeats() && !empty($candidateEliminated)) :
            $rescueCandidateKey = \array_key_last($candidateEliminated);
            $result[++$rank] = $candidateEliminated[$rescueCandidateKey];
            unset($candidateEliminated[$rescueCandidateKey]);
        endwhile;

        $this->_Result = $this->createResult($result);

        Vote::clearCache(); // Performances
    }

    protected function makeScore (array $surplus = [], array $candidateElected = [], array $candidateEliminated = []): array
    {
        $election = $this->getElection();
        $scoreTable = [];

        $candidateDone = array_merge($candidateElected, $candidateEliminated);

        foreach (\array_keys($election->getCandidatesList()) as $oneCandidateKey) :
            if (!\in_array($candidateKey = $oneCandidateKey, $candidateDone, true)) :
                $scoreTable[$candidateKey] = 0.0;
            endif;
        endforeach;

        foreach ($election->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) :

            $weight = $oneVote->getWeight($election);

            $winnerBonusWeight = 0;
            $winnerBonusKey = null;
            $LoserBonusWeight = 0;

            $firstRank = true;
            foreach ($oneVote->getContextualRankingWithoutSort($election) as $oneRank) :
                foreach ($oneRank as $oneCandidate) :
                    if (\count($oneRank) !== 1): break; endif;

                    $candidateKey = $election->getCandidateKey($oneCandidate);

                    if ($firstRank) :
                        if (\array_key_exists($candidateKey, $surplus)) :
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

                    if (\array_key_exists($candidateKey, $scoreTable)) :
                        if ($winnerBonusKey !== null) :
                            $scoreTable[$candidateKey] += $winnerBonusWeight / $surplus[$winnerBonusKey]['total'] * $surplus[$winnerBonusKey]['surplus'];
                        elseif ($LoserBonusWeight > 0) :
                            $scoreTable[$candidateKey] += $LoserBonusWeight;
                        else :
                            $scoreTable[$candidateKey] += $weight;
                        endif;

                        $scoreTable[$candidateKey] = \round($scoreTable[$candidateKey], self::DECIMAL_PRECISION, \PHP_ROUND_HALF_DOWN);

                        break 2;
                    endif;
                endforeach;
            endforeach;
        endforeach;

        return $scoreTable;
    }

    protected function getStats(): array
    {
        $election = $this->getElection();

        $stats = ['votes_needed_to_win' => $this->votesNeededToWin];

        if ($election->getStatsVerbosity()->value > StatsVerbosity::LOW->value) :
            $stats['rounds'] = [];

            foreach ($this->_Stats as $roundNumber => $roundData) :
                foreach ($roundData as $candidateKey => $candidateValue) :
                    $stats['rounds'][$roundNumber][(string) $election->getCandidateObjectFromKey($candidateKey)] = $candidateValue;
                endforeach;
            endforeach;
        endif;

        return $stats;
    }

}
