<?php
/*
    Part of CPO STV method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\STV;

use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Vote;

// Single transferable vote | https://en.wikipedia.org/wiki/CPO-STV
class CPO_STV extends SingleTransferableVote
{
    // Method Name
    public const METHOD_NAME = ['CPO STV', 'CPO_STV', 'CPO-STV', 'CPO', 'Comparison of Pairs of Outcomes by the Single Transferable Vote', 'Tideman STV'];

    public static StvQuotas $optionQuota = StvQuotas::HAGENBACH_BISCHOFF;
    public static string $optionCondorcetCompletionMethod = RankedPairsMargin::class;

    protected ?array $_Stats = null;

    protected readonly array $outcomes;
    protected readonly array $initialScoreTable;
    protected array $candidatesElectedFromFirstRound = [];
    protected readonly array $candidatesEliminatedFromFirstRound;
    protected array $outcomeComparisonTable = [];
    protected readonly int $condorcetWinnerOutcome;
    protected readonly array $completionMethodPairwise;
    protected readonly array $completionMethodStats;


/////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute (): void
    {
        $rank = 0;

        $candidatesList = \array_keys($this->getElection()->getCandidatesList());
        $this->votesNeededToWin = \round(self::$optionQuota->getQuota($this->getElection()->sumValidVotesWeightWithConstraints(), $this->getElection()->getNumberOfSeats()), self::DECIMAL_PRECISION, \PHP_ROUND_HALF_DOWN);

        // Compute Initial Score
        $this->initialScoreTable = $this->makeScore([],[],[]);

        // Candidates elected from first round
        foreach ($this->initialScoreTable as $candidateKey => $oneScore) :
            if ($oneScore >= $this->votesNeededToWin) :
                $this->candidatesElectedFromFirstRound[] = $candidateKey;
            endif;
        endforeach;

        $numberOfCandidatesNeeded = $this->getElection()->getNumberOfSeats() - count($this->candidatesElectedFromFirstRound);
        $this->candidatesEliminatedFromFirstRound = \array_diff($candidatesList, $this->candidatesElectedFromFirstRound);

        // Compute all possible Ranking
        $this->outcomes = Combinations::compute($this->candidatesEliminatedFromFirstRound, $numberOfCandidatesNeeded, $this->candidatesElectedFromFirstRound);

        foreach ($this->outcomes as $MainOutcomeKey => $MainOutcomeR) :
            foreach ($this->outcomes as $ComparedOutcomeKey => $ComparedOutcomeR) :
                $outcomeComparisonKey = $this->getOutcomesComparisonKey($MainOutcomeKey, $ComparedOutcomeKey);

                if ( $MainOutcomeKey === $ComparedOutcomeKey || \array_key_exists($outcomeComparisonKey, $this->outcomeComparisonTable) ) :
                    continue;
                endif;

                $this->outcomeComparisonTable[$outcomeComparisonKey] = [];
                $this->outcomeComparisonTable[$outcomeComparisonKey]['candidates_excluded'] = [];

                // Eliminate Candidates from Outcome
                foreach ($candidatesList as $candidateKey) :
                    if (!\in_array($candidateKey, $MainOutcomeR, true) && !\in_array($candidateKey, $ComparedOutcomeR, true)) :
                        $this->outcomeComparisonTable[$outcomeComparisonKey]['candidates_excluded'][] = $candidateKey;
                    endif;
                endforeach;

                // Make score again
                $this->outcomeComparisonTable[$outcomeComparisonKey]['scores_after_exclusion'] = $this->makeScore([], [], $this->outcomeComparisonTable[$outcomeComparisonKey]['candidates_excluded']);

                $surplusToTransfer = [];
                $winnerToJoin = [];
                foreach($this->outcomeComparisonTable[$outcomeComparisonKey]['scores_after_exclusion'] as $candidateKey => $oneScore) :
                    $surplus = $oneScore - $this->votesNeededToWin;

                    if ($surplus >= 0 && \in_array($candidateKey, $MainOutcomeR, true) && \in_array($candidateKey,$ComparedOutcomeR, true)) :
                        $surplusToTransfer[$candidateKey] ?? $surplusToTransfer[$candidateKey] = ['surplus' => 0, 'total' => 0];
                        $surplusToTransfer[$candidateKey]['surplus'] += $surplus;
                        $surplusToTransfer[$candidateKey]['total'] += $oneScore;
                        $winnerToJoin[$candidateKey] = $this->votesNeededToWin;
                    endif;
                endforeach;

                $winnerFromFirstRound = \array_keys($winnerToJoin);

                $this->outcomeComparisonTable[$outcomeComparisonKey]['scores_after_surplus'] = $winnerToJoin + $this->makeScore($surplusToTransfer, $winnerFromFirstRound, $this->outcomeComparisonTable[$outcomeComparisonKey]['candidates_excluded'], 2);

                // Outcome Score
                $MainOutcomeScore = 0;
                $ComparedOutcomeScore = 0;

                foreach ($this->outcomeComparisonTable[$outcomeComparisonKey]['scores_after_surplus'] as $candidateKey => $candidateScore) :
                    if (in_array($candidateKey, $MainOutcomeR, true)) :
                        $MainOutcomeScore += $candidateScore;
                    endif;

                    if (in_array($candidateKey, $ComparedOutcomeR, true)) :
                        $ComparedOutcomeScore += $candidateScore;
                    endif;
                endforeach;

                $this->outcomeComparisonTable[$outcomeComparisonKey]['outcomes_scores'] = [$MainOutcomeKey => $MainOutcomeScore, $ComparedOutcomeKey => $ComparedOutcomeScore];

            endforeach;
        endforeach;

        // Chose Outcome with Condorcet
        $winnerOutcomeElection = new Election;
        $winnerOutcomeElection->setImplicitRanking(false);
        $winnerOutcomeElection->allowsVoteWeight(true);

            // Candidates
            foreach (\array_keys($this->outcomes) as $oneOutcomeKey) :
                $winnerOutcomeElection->addCandidate((string) $oneOutcomeKey);
            endforeach;

            // Votes
            $coef = Method::DECIMAL_PRECISION ** 10 ; # Actually, vote weight does not support float
            foreach ($this->outcomeComparisonTable as $comparison) :
                ($vote1 = new Vote([
                    (string) $key = \array_key_first($comparison['outcomes_scores']),
                    (string) \array_key_last($comparison['outcomes_scores'])
                ]))->setWeight((int) ($comparison['outcomes_scores'][$key] * $coef));

                ($vote2 = new Vote([
                    (string) $key = \array_key_last($comparison['outcomes_scores']),
                    (string) \array_key_first($comparison['outcomes_scores'])
                ]))->setWeight((int) ($comparison['outcomes_scores'][$key] * $coef));

                $winnerOutcomeElection->addVote($vote1);
                $winnerOutcomeElection->addVote($vote2);
            endforeach;

        // Chose Outcome
        $completionMethodResult = $winnerOutcomeElection->getResult(self::$optionCondorcetCompletionMethod);
        $this->completionMethodPairwise = $winnerOutcomeElection->getExplicitPairwise();
        $this->completionMethodStats = $completionMethodResult->getStats();

        $this->condorcetWinnerOutcome = (int) $completionMethodResult->getWinner(self::$optionCondorcetCompletionMethod)->getName();
        $winnerOutcome = $this->outcomes[$this->condorcetWinnerOutcome];

        // Sort Outcome using originals scores
        \usort($winnerOutcome, fn (float $a, float $b): int => $this->initialScoreTable[$b] <=> $this->initialScoreTable[$a]);

        // Results: Format Ranks
        $rank = 1;
        $r = [];
        foreach ($winnerOutcome as $candidateKey) :
            $r[$rank++] = $candidateKey;
        endforeach;

        // Register result
        $this->_Result = $this->createResult($r);
    }

    protected function getOutcomesComparisonKey (int $MainOutcomeKey, int $ComparedOutcomeKey): string
    {
        $minOutcome = (string) \min($MainOutcomeKey, $ComparedOutcomeKey);
        $maxOutcome = (string) \max($MainOutcomeKey, $ComparedOutcomeKey);

        return 'Outcome N° '.$minOutcome.' compared to Outcome N° '.$maxOutcome;
    }

    protected function getStats(): array
    {
        $stats = ['votes_needed_to_win' => $this->votesNeededToWin];

        $changeKeyToCandidateAndSortByName = function (array $arr): array {
            $r = [];
            foreach ($arr as $candidateKey => $value) :
                $r[(string) $this->getElection()->getCandidateObjectFromKey($candidateKey)] = $value;
            endforeach;

            \ksort($r, \SORT_NATURAL);
            return $r;
        };

        $changeValueToCandidateAndSortByName = function (array $arr): array {
            $r = [];
            foreach ($arr as $candidateKey) :
                $r[] = (string) $this->getElection()->getCandidateObjectFromKey($candidateKey);
            endforeach;

            \sort($r, \SORT_NATURAL);
            return $r;
        };

        // Initial Scores Table
        $stats['Initial Score Table'] = $changeKeyToCandidateAndSortByName($this->initialScoreTable);

        // Candidates Elected from first round
        $stats['Candidates elected from first round'] = $changeValueToCandidateAndSortByName($this->candidatesElectedFromFirstRound);

        // Candidates Eliminated from first round
        $stats['Candidates eliminated from first round'] = $changeValueToCandidateAndSortByName($this->candidatesEliminatedFromFirstRound);

        // Outcome
        foreach ($this->outcomes as $outcomeKey => $outcomeValue) :
            $stats['Outcomes'][$outcomeKey] = $changeValueToCandidateAndSortByName($outcomeValue);
        endforeach;

        // Outcomes Comparison
        foreach ($this->outcomeComparisonTable as $octKey => $octValue) :
            foreach ($octValue as $octDetailsKey => $octDetailsValue) :
                if ($octDetailsKey === 'candidates_excluded') :
                    $stats['Outcomes Comparison'][$octKey][$octDetailsKey] = $changeValueToCandidateAndSortByName($octDetailsValue);
                else :
                    $stats['Outcomes Comparison'][$octKey][$octDetailsKey] = $changeKeyToCandidateAndSortByName($octDetailsValue);
                endif;
            endforeach;
        endforeach;

        // Completion method Stats
        $stats['Condorcet Completion Method Stats'] = [
            'Pairwise' => $this->completionMethodPairwise,
            'Stats' => $this->completionMethodStats,
        ];

        // Return
        return $stats;
    }

}
