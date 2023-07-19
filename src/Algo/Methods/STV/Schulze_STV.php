<?php

namespace CondorcetPHP\Condorcet\Algo\Methods\STV;

use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException;
use CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException;
use CondorcetPHP\Condorcet\Vote;
use SplFixedArray;

class Schulze_STV extends CPO_STV
{
    public const METHOD_NAME = ['Schulze STV', 'Schulze-STV', 'Schulze_STV', ];

    protected readonly array $initialScoreTable;
    protected readonly array $candidatesRemainingFromFirstRound;

    protected array $StrongestPaths;

    protected function compute(): void
    {
        //Vote::initCache(); // Performances
        $this->outcomes = new SplFixedArray(0);
        $this->outcomeComparisonTable = new SplFixedArray(0);

        $this->votesNeededToWin = round(self::$optionQuota->getQuota($this->getElection()->sumValidVotesWeightWithConstraints(), $this->getElection()->getNumberOfSeats()), self::DECIMAL_PRECISION, \PHP_ROUND_HALF_DOWN);

        // Compute Initial Score
        $this->initialScoreTable = $this->makeScore();

        // Candidates elected from first round
        foreach ($this->initialScoreTable as $candidateKey => $oneScore) {
            if ($oneScore >= $this->votesNeededToWin) {
                $this->candidatesElectedFromFirstRound[] = $candidateKey;
            }
        }

        $numberOfCandidatesNeededToComplete = $this->getElection()->getNumberOfSeats() - \count($this->candidatesElectedFromFirstRound);
        $this->candidatesRemainingFromFirstRound = array_diff(array_keys($this->getElection()->getCandidatesList()), $this->candidatesElectedFromFirstRound);

        if ($numberOfCandidatesNeededToComplete > 0 && $numberOfCandidatesNeededToComplete <= \count($this->candidatesRemainingFromFirstRound)) {
            try {
                $numberOfComparisons = Combinations::getPossibleCountOfCombinations(
                        count: \count($this->candidatesRemainingFromFirstRound),
                        length: $numberOfCandidatesNeededToComplete
                    ) * $numberOfCandidatesNeededToComplete * (\count($this->candidatesRemainingFromFirstRound) - $numberOfCandidatesNeededToComplete);
            } catch (IntegerOverflowException) {
                $numberOfComparisons = false;
            }

            if ($numberOfComparisons === false || (self::$MaxOutcomeComparisons !== null && $numberOfComparisons > self::$MaxOutcomeComparisons)) {
                throw new MethodLimitReachedException(self::METHOD_NAME[0], self::METHOD_NAME[1] . ' is currently limited to ' . self::$MaxOutcomeComparisons . ' comparisons in order to avoid unreasonable deadlocks due to non-polyminial runtime aspects of the algorithm. Consult the documentation book to increase or remove this limit.');
            }

            // Determine all possible Outcomes
            $this->outcomes = Combinations::compute($this->candidatesRemainingFromFirstRound, $numberOfCandidatesNeededToComplete, $this->candidatesElectedFromFirstRound);

            // Compare it
            $this->outcomeComparisonTable->setSize($numberOfComparisons);
            $this->compareOutcomes();
            $this->findStrongestPaths();
            $this->findStrongestPaths();

            $result = $this->outcomes[$this->selectBestOutcome()];
        } else {
            //throw new CondorcetInternalException('There are more candidates than there are seats to fill');
            $result = array_keys($this->initialScoreTable);
            $result = \array_slice($result, 0, $this->getElection()->getNumberOfSeats());
        }

        // Sort winning candidates by how many voters prefer them to the other winning candidates.
        $finalScoreTable = $this->makeScore([], [], array_diff(array_keys($this->getElection()->getCandidatesList()), $this->candidatesRemainingFromFirstRound));
        arsort($finalScoreTable);
        $result = array_intersect(array_keys($finalScoreTable), $result);

        $this->Result = $this->createResult($result);

        //Vote::initCache();
    }

    protected function compareOutcomes(): void
    {
        $election = $this->getElection();
        $index = 0;
        $key_done = [];

        $voteCandidateRanks = [];
        foreach($election->getVotesValidUnderConstraintGenerator() as $oneVote) {
            $candidateRanks = $oneVote->getCandidateRanks(true, $election);
            if ($election->getImplicitRankingRule()) {
                $lastRank = $election->countCandidates();
                foreach ($election->getCandidatesList() as $key=>$candidate) {
                    if (!isset($candidateRanks[$key])) $candidateRanks[$key] = $lastRank;
                }
            }
            $voteCandidateRanks[] = $candidateRanks;
        }

        foreach ($this->outcomes as $iKey => $iSet) {
            foreach ($this->outcomes as $jKey => $jSet) {
                if ($iKey == $jKey || count($iUnique= array_diff($iSet, $jSet)) !== 1) {
                    continue;
                }

                $iUnique = reset($iUnique);
                $jUnique = current(array_diff($jSet, $iSet));
                $others = array_merge(array_diff($iSet, [$jUnique]), $this->candidatesElectedFromFirstRound);

                //$this->StrongestPaths[$iKey][$jKey] = $this->votesPreferring($iUnique, $jSet);
                $linkStrength = 0;
                foreach ($voteCandidateRanks as $rankNumbers) {
                    //$rankNumbers = $oneVote->getCandidateRanks();
                    if (!isset($rankNumbers[$iUnique]) || !isset($rankNumbers[$jUnique])) continue;

                    if ($rankNumbers[$iUnique] < $rankNumbers[$jUnique]) {
                        foreach ($others as $candidate) {
                            if (isset($rankNumbers[$candidate]) AND $rankNumbers[$iUnique] > $rankNumbers[$candidate]) {
                                continue 2;
                            }
                        }
                        $linkStrength += $oneVote->getWeight();
                    }
                }
                $this->StrongestPaths[$iKey][$jKey] = $linkStrength;
            }
        }
    }

    protected function findStrongestPaths(): void
    {
        $outcomeCount = $this->outcomes->count();

        for ($i = 0; $i < $outcomeCount; $i++) {
            for ($j = 0; $j < $outcomeCount; $j++) {
                if ($i !== $j && isset($this->StrongestPaths[$j][$i])) {
                    for ($k = 0; $k < $outcomeCount; $k++) {
                        if ($i !== $k && $j !== $k && isset($this->StrongestPaths[$i][$k])) {
                            $this->StrongestPaths[$j][$k] =
                                max(
                                    $this->StrongestPaths[$j][$k] ?? 0,
                                    min($this->StrongestPaths[$j][$i], $this->StrongestPaths[$i][$k])
                                );
                        }
                    }
                }
            }
        }
    }

    protected function selectBestOutcome(): int
    {

        $done = [];
        $rank = 1;

        foreach ($this->StrongestPaths as $set_key => $opposing_keys) {
            if (\in_array(needle: $set_key, haystack: $done, strict: true)) {
                continue;
            }

            foreach ($opposing_keys as $beaten_key => $beaten_value) {
                if (\in_array(needle: $beaten_key, haystack: $done, strict: true)) {
                    continue;
                }

                if ($beaten_value < $this->StrongestPaths[$beaten_key][$set_key]) {
                    continue 2;
                }
            }

            $result = $this->outcomes[$set_key];
        }

        $this->Result = $this->createResult($result);
        return $set_key;
    }
}
