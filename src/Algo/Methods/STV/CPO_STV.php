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
use CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount;
use CondorcetPHP\Condorcet\Algo\Methods\Copeland\Copeland;
use CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonTidemanApproximation;
use CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff\InstantRunoff;
use CondorcetPHP\Condorcet\Algo\Methods\Majority\FirstPastThePost;
use CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxMargin;
use CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxWinning;
use CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeMargin;
use CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeRatio;
use CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException;
use CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException;
use CondorcetPHP\Condorcet\Vote;
use SplFixedArray;

// Single transferable vote | https://en.wikipedia.org/wiki/CPO-STV
class CPO_STV extends SingleTransferableVote
{
    // Method Name
    public const METHOD_NAME = ['CPO STV', 'CPO-STV', 'CPO_STV', 'CPO', 'Comparison of Pairs of Outcomes by the Single Transferable Vote', 'Tideman STV'];

    // Limits
    public static ?int $MaxOutcomeComparisons = 12_000;

    public const DEFAULT_METHODS_CHAINING = [
        SchulzeMargin::METHOD_NAME[0],
        SchulzeWinning::METHOD_NAME[0],
        SchulzeRatio::METHOD_NAME[0],
        BordaCount::METHOD_NAME[0],
        Copeland::METHOD_NAME[0],
        InstantRunoff::METHOD_NAME[0],
        MinimaxMargin::METHOD_NAME[0],
        MinimaxWinning::METHOD_NAME[0],
        DodgsonTidemanApproximation::METHOD_NAME[0],
        FirstPastThePost::METHOD_NAME[0],
    ];

    public static StvQuotas $optionQuota = StvQuotas::HAGENBACH_BISCHOFF;
    public static array $optionCondorcetCompletionMethod = self::DEFAULT_METHODS_CHAINING;
    public static array $optionTieBreakerMethods = self::DEFAULT_METHODS_CHAINING;

    protected ?array $_Stats = null;

    protected SplFixedArray $outcomes;
    protected readonly array $initialScoreTable;
    protected array $candidatesElectedFromFirstRound = [];
    protected readonly array $candidatesEliminatedFromFirstRound;
    protected SplFixedArray $outcomeComparisonTable;
    protected readonly int $condorcetWinnerOutcome;
    protected readonly array $completionMethodPairwise;
    protected readonly array $completionMethodStats;


/////////// COMPUTE ///////////

    protected function compute (): void
    {
        Vote::initCache(); // Performances
        $this->outcomes = new SplFixedArray(0);
        $this->outcomeComparisonTable = new SplFixedArray(0);

        $this->votesNeededToWin = \round(self::$optionQuota->getQuota($this->getElection()->sumValidVotesWeightWithConstraints(), $this->getElection()->getNumberOfSeats()), self::DECIMAL_PRECISION, \PHP_ROUND_HALF_DOWN);

        // Compute Initial Score
        $this->initialScoreTable = $this->makeScore();

        // Candidates elected from first round
        foreach ($this->initialScoreTable as $candidateKey => $oneScore) :
            if ($oneScore >= $this->votesNeededToWin) :
                $this->candidatesElectedFromFirstRound[] = $candidateKey;
            endif;
        endforeach;

        $numberOfCandidatesNeededToComplete = $this->getElection()->getNumberOfSeats() - \count($this->candidatesElectedFromFirstRound);
        $this->candidatesEliminatedFromFirstRound = \array_diff(\array_keys($this->getElection()->getCandidatesList()), $this->candidatesElectedFromFirstRound);

        if ($numberOfCandidatesNeededToComplete > 0 && $numberOfCandidatesNeededToComplete < \count($this->candidatesEliminatedFromFirstRound)) :
            try {
                $numberOfComparisons =  Combinations::getPossibleCountOfCombinations(  count: Combinations::getPossibleCountOfCombinations(
                                                                                    count: \count($this->candidatesEliminatedFromFirstRound),
                                                                                    length: $numberOfCandidatesNeededToComplete
                                                                                ),
                                                                                length: 2);
            } catch (IntegerOverflowException) {
                $numberOfComparisons = false;
            }

            if ($numberOfComparisons === false || (self::$MaxOutcomeComparisons !== null && $numberOfComparisons > self::$MaxOutcomeComparisons) ) :
                throw new MethodLimitReachedException(self::METHOD_NAME[0], self::METHOD_NAME[1].' is currently limited to '.self::$MaxOutcomeComparisons.' comparisons in order to avoid unreasonable deadlocks due to non-polyminial runtime aspects of the algorithm. Consult the manual to increase or remove this limit.');
            endif;


            // Compute all possible Ranking
            $this->outcomes = Combinations::compute($this->candidatesEliminatedFromFirstRound, $numberOfCandidatesNeededToComplete, $this->candidatesElectedFromFirstRound);

            // Compare it
            $this->outcomeComparisonTable->setSize($numberOfComparisons);
            $this->compareOutcomes();

            // Select the best with a Condorcet method
            $this->selectBestOutcome();
            $result = $this->outcomes[$this->condorcetWinnerOutcome];

            // Sort the best Outcome candidate list using originals scores
            \usort($result, function (int $a, int $b): int {
                return $this->initialScoreTable[$b] <=> $this->initialScoreTable[$a];
            });

        else :
            $result = \array_keys($this->initialScoreTable);

            // Sort the best Outcome candidate list using originals scores, or using others methods
            $this->sortResultBeforeCut($result);

            // Cut
            $result = \array_slice($result, 0, $this->getElection()->getNumberOfSeats());
        endif;

        // Results: Format Ranks from 1
        $rank = 0;
        $lastScore = null;
        $candidatesDoneCount = 0;
        $r = [];
        foreach ($result as $candidateKey) :
            $score = $this->initialScoreTable[$candidateKey];

            if ($score !== $lastScore) :
                $rank = $candidatesDoneCount + 1;
                $lastScore = $score;
            endif;

            $r[$rank][] = $candidateKey;
            $candidatesDoneCount++;
        endforeach;

        // Register result
        $this->_Result = $this->createResult($r);

        Vote::clearCache(); // Performances
    }

    protected function compareOutcomes (): void
    {
        $election = $this->getElection();
        $index = 0;
        $key_done = [];

        foreach ($this->outcomes as $MainOutcomeKey => $MainOutcomeR) :
            foreach ($this->outcomes as $ComparedOutcomeKey => $ComparedOutcomeR) :
                $outcomeComparisonKey = $this->getOutcomesComparisonKey($MainOutcomeKey, $ComparedOutcomeKey);

                if ( $MainOutcomeKey === $ComparedOutcomeKey || \in_array($outcomeComparisonKey, $key_done, true) ) :
                    continue;
                endif;

                $entry = [  'c_key' => $outcomeComparisonKey,
                            'candidates_excluded' => [],
                        ];

                // Eliminate Candidates from Outcome
                foreach (\array_keys($election->getCandidatesList()) as $candidateKey) :
                    if (!\in_array($candidateKey, $MainOutcomeR, true) && !\in_array($candidateKey, $ComparedOutcomeR, true)) :
                        $entry['candidates_excluded'][] = $candidateKey;
                    endif;
                endforeach;

                // Make score again
                $entry['scores_after_exclusion'] = $this->makeScore(candidateEliminated: $entry['candidates_excluded']);

                $surplusToTransfer = [];
                $winnerToJoin = [];
                foreach($entry['scores_after_exclusion'] as $candidateKey => $oneScore) :
                    $surplus = $oneScore - $this->votesNeededToWin;

                    if ($surplus >= 0 && \in_array($candidateKey, $MainOutcomeR, true) && \in_array($candidateKey, $ComparedOutcomeR, true)) :
                        $surplusToTransfer[$candidateKey] ?? $surplusToTransfer[$candidateKey] = ['surplus' => 0, 'total' => 0];
                        $surplusToTransfer[$candidateKey]['surplus'] += $surplus;
                        $surplusToTransfer[$candidateKey]['total'] += $oneScore;
                        $winnerToJoin[$candidateKey] = $this->votesNeededToWin;
                    endif;
                endforeach;

                $winnerFromFirstRound = \array_keys($winnerToJoin);

                $entry['scores_after_surplus'] = $winnerToJoin + $this->makeScore($surplusToTransfer, $winnerFromFirstRound, $entry['candidates_excluded']);

                // Outcome Score
                $MainOutcomeScore = 0;
                $ComparedOutcomeScore = 0;

                foreach ($entry['scores_after_surplus'] as $candidateKey => $candidateScore) :
                    if (in_array($candidateKey, $MainOutcomeR, true)) :
                        $MainOutcomeScore += $candidateScore;
                    endif;

                    if (in_array($candidateKey, $ComparedOutcomeR, true)) :
                        $ComparedOutcomeScore += $candidateScore;
                    endif;
                endforeach;

                $entry['outcomes_scores'] = [$MainOutcomeKey => $MainOutcomeScore, $ComparedOutcomeKey => $ComparedOutcomeScore];

                $key_done[] = $outcomeComparisonKey;
                $this->outcomeComparisonTable[$index++] = $entry;
            endforeach;
        endforeach;
    }

    protected function getOutcomesComparisonKey (int $MainOutcomeKey, int $ComparedOutcomeKey): string
    {
        $minOutcome = (string) \min($MainOutcomeKey, $ComparedOutcomeKey);
        $maxOutcome = (string) \max($MainOutcomeKey, $ComparedOutcomeKey);

        return 'Outcome N° '.$minOutcome.' compared to Outcome N° '.$maxOutcome;
    }

    protected function selectBestOutcome (): void
    {
            // With Condorcet
            $winnerOutcomeElection = new Election;
            $winnerOutcomeElection->setImplicitRanking(false);
            $winnerOutcomeElection->allowsVoteWeight(true);
            $winnerOutcomeElection->setStatsVerbosity($this->getElection()->getStatsVerbosity());

                // Candidates
                foreach ($this->outcomes as $oneOutcomeKey => $outcomeValue) :
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

            // Selection Winner
            $selectionSucces = false;

            foreach (self::$optionCondorcetCompletionMethod as $completionMethod) :
                $completionMethodResult = $winnerOutcomeElection->getResult($completionMethod);
                $condorcetWinnerOutcome = $completionMethodResult->getWinner();

                if (!\is_array($condorcetWinnerOutcome)) :
                    $selectionSucces = true;
                    $this->completionMethodStats = $completionMethodResult->getStats();
                    break;
                endif;
            endforeach;

            if (!$selectionSucces) :
                $completionMethodResult = $winnerOutcomeElection->getResult(self::$optionCondorcetCompletionMethod[0]);
                $condorcetWinnerOutcome = $completionMethodResult->getWinner();
                $condorcetWinnerOutcome = \reset($condorcetWinnerOutcome);
                $this->completionMethodStats = $completionMethodResult->getStats();
            endif;

            $this->condorcetWinnerOutcome = (int) $condorcetWinnerOutcome->getName();
            $this->completionMethodPairwise = $winnerOutcomeElection->getExplicitPairwise();
    }

    protected function sortResultBeforeCut (array &$result): void
    {
        \usort($result, function (int $a, int $b): int {
            $tieBreakerFromInitialScore = $this->initialScoreTable[$b] <=> $this->initialScoreTable[$a];

            if ($tieBreakerFromInitialScore !== 0) :
                return $tieBreakerFromInitialScore;
            else :
                $election = $this->getElection();

                if (\count($tiebreaker = TieBreakersCollection::tieBreakerWithAnotherMethods($election, self::$optionTieBreakerMethods, [$a,$b])) === 1) :
                    $w = \reset($tiebreaker);
                    return ($w === $a) ? -1 : 1;
                else:
                    return \mb_strtolower($election->getCandidateObjectFromKey($b)->getName(),'UTF-8') <=> \mb_strtolower($election->getCandidateObjectFromKey($b)->getName(),'UTF-8');
                endif;
            endif;
        });
    }

    // Stats

    protected function getStats(): array
    {
        $election = $this->getElection();

        $stats = ['votes_needed_to_win' => $this->votesNeededToWin];

        $changeKeyToCandidateAndSortByName = function (array $arr, Election $election): array {
            $r = [];
            foreach ($arr as $candidateKey => $value) :
                $r[(string) $election->getCandidateObjectFromKey($candidateKey)] = $value;
            endforeach;

            \ksort($r, \SORT_NATURAL);
            return $r;
        };

        $changeValueToCandidateAndSortByName = function (array $arr, Election $election): array {
            $r = [];
            foreach ($arr as $candidateKey) :
                $r[] = (string) $election->getCandidateObjectFromKey($candidateKey);
            endforeach;

            \sort($r, \SORT_NATURAL);
            return $r;
        };

        // Initial Scores Table
        $stats['Initial Score Table'] = $changeKeyToCandidateAndSortByName($this->initialScoreTable, $election);

        // Candidates Elected from first round
        $stats['Candidates elected from first round'] = $changeValueToCandidateAndSortByName($this->candidatesElectedFromFirstRound, $election);

        // Candidates Eliminated from first round
        $stats['Candidates eliminated from first round'] = $changeValueToCandidateAndSortByName($this->candidatesEliminatedFromFirstRound, $election);

        // Stats >= HIGH
        if ($election->getStatsVerbosity()->value >= StatsVerbosity::HIGH->value) :
            // Completion method Stats
            if (isset($this->completionMethodPairwise)) :
                $stats['Condorcet Completion Method Stats'] = [
                    'Pairwise' => $this->completionMethodPairwise,
                    'Stats' => $this->completionMethodStats,
                ];
            endif;
        endif;

        // Stats >= FULL
        if ($election->getStatsVerbosity()->value >= StatsVerbosity::FULL->value) :
            // Outcome
            foreach ($this->outcomes as $outcomeKey => $outcomeValue) :
                $stats['Outcomes'][$outcomeKey] = $changeValueToCandidateAndSortByName($outcomeValue, $election);
            endforeach;

            // Outcomes Comparison
            foreach ($this->outcomeComparisonTable as $octValue) :
                foreach ($octValue as $octDetailsKey => $octDetailsValue) :
                    if ($octDetailsKey === 'candidates_excluded') :
                        $stats['Outcomes Comparison'][$octValue['c_key']][$octDetailsKey] = $changeValueToCandidateAndSortByName($octDetailsValue, $election);
                    elseif ($octDetailsKey === 'outcomes_scores') :
                        $stats['Outcomes Comparison'][$octValue['c_key']][$octDetailsKey] = $octDetailsValue;
                    elseif (\is_array($octDetailsValue)) :
                        $stats['Outcomes Comparison'][$octValue['c_key']][$octDetailsKey] = $changeKeyToCandidateAndSortByName($octDetailsValue, $election);
                    endif;
                endforeach;
            endforeach;
        endif;

        // Return
        return $stats;
    }

}
