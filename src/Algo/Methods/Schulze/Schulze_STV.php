<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\{Election, Result};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\Schulze_proportional_prefilter;

//May be removed later with reorganisation.
use CondorcetPHP\Condorcet\Vote;

class Schulze_STV extends Schulze_Core implements MethodInterface
{
    final public const IS_PROPORTIONAL = true;
    public const METHOD_NAME = ['Schulze STV', 'Schulze-STV', 'Schulze_STV'];

    public int $M;
    protected array $StrongestPaths = [];
    protected array $StrongestSetPaths = [];
    protected array $outcomes = [];
    protected array $CandidatesKeys = [];
    // May be renamed to $votesNeededToWin to match SingleTransferableVote class.
    protected float $quota;

    public function getResult(): Result
    {
        if ($this->Result !== null) {
            return $this->Result;
        }

        $election = $this->getElection();
        $result = [];

        $this->M = $election->getNumberOfSeats();
        //$this->quota = self::$optionQuota->getQuota($election()->sumValidVotesWeightWithConstraints(), $this->M);

        $prefilter = new Schulze_proportional_prefilter($election, $this->M);
        $candidates = $prefilter->getResult('Schulze proportional prefilter')->getResultAsArray(true);
        foreach ($candidates as $candidate) {
            $candidate_key = $election->getCandidateKey($candidate);
            $this->CandidatesKeys[$candidate_key] = $candidate_key;
        }
        unset($candidates, $candidate_key);

        // Originally, it was going to do the regular Schulze method within this same method object. These commented lines can be removed unless we go back to this approach.
        /*$this->prepareStrongestPath();
        $this->makeStrongestPaths($this->M);
        $this->filterCandidates();*/

        $this->prepareOutcomes();
        $this->makeStrongestSetPaths($this->M);
        $this->selectBestOutcome();

        return $this->Result;
    }

    protected function selectBestOutcome()
    {

        $done = [];
        $rank = 1;

        $to_done = [];

        foreach ($this->StrongestSetPaths as $set_key => $opposing_key) {
            if (\in_array(needle: $set_key, haystack: $done, strict: true)) {
                continue;
            }

            $winner = true;

            foreach ($opposing_key as $beaten_key => $beaten_value) {
                if (\in_array(needle: $beaten_key, haystack: $done, strict: true)) {
                    continue;
                }

                if ($beaten_value < $this->StrongestSetPaths[$beaten_key][$set_key]) {
                    $winner = false;
                    continue;
                }
            }

            if ($winner) {
                $result = $this->outcomes[$set_key];
                break;
            }
        }

        $this->Result = $this->createResult($result);
    }

    protected function schulzeVariant(int $i, int $j, Election $election): int
    {
        return $election->getPairwise()[$i]['win'][$j];
    }

    protected function prepareOutcomes()
    {
        $election = $this->getElection();
        $totalVotesWeight = $election->sumVotesWeight();
        foreach ($this->CandidatesKeys as $candidate) {
            $this->addToSet([$candidate], $this->M/*, $election->sumVotesWeight()/($this->M + 1)*/);
        }
    }

    // Recursive function to add candidates to a set.
    protected function addToSet(array $set)
    {
        foreach ($this->CandidatesKeys as $candidate) if($candidate > end($set) ?? -1)
        {
            array_push($set, $candidate);
            if (count($set) < $this->M) {
                /*$set =*/ $this->addToSet($set);
            } elseif(count($set)===$this->M)/*($this->checkSupport($set, $quota))*/ {
                array_push($this->outcomes, $set);
            }
            array_pop($set);
        }
    }

    // This function may not be necessary. It can be determined experimentally whether this is required for this method.
    protected function checkSupport($set, $quota = NULL)
    {
        $quota = $quota ?? $this->quota;
        foreach ($set as $i) {
            if ($this->votesPreferring($i, $set) < $quota) {
                return false;
            }
        }
        return true;
    }

    protected function votesPreferring($i, $set, $from=NULL)
    {
        $election = $this->getElection();
        //unset($set[array_search($i, $set, true)]);
        $total = 0;

        foreach($election->getVotesValidUnderConstraintGenerator() as $oneVote)
        {
            $rankings = $oneVote->getContextualRankingWithCandidateKeys($election);
            if (isset($from) && $rankings[$from] > $rankings[$i]) {
                continue;
            }
            if ($this->prefers($i, $set, $rankings, $election)) {
                $total = $total + $oneVote->getWeight();
            }
        }
        return $total;
    }

    protected function prefers(int $i, $set, array $rankings, Election $election)
    {
        if ($rankings[$i] === NULL) {
            return false;
        }
        $prefers = true;
        foreach ($set as $j) if ($rankings[$j] < $rankings[$i]) {
            $prefers = false;
            break;
        }
        return $prefers;
    }

    /*  Originally this function was supposed to determine how many voters favour each candidate in both or neither set,
        transfer some votes from the overlapping candidates to the unique candidates, & return it as an array. The lines for this are in comments. */
    protected function compareOutcomes(array $iArray, array $jArray, Election $election, $quota = NULL): int
    {
        //$overlap = array_intersect_assoc($iArray, $jArray);
        //There may be a more efficient function than current() for this task.
        $iUnique = current(array_diff($iArray, $jArray));
        /*$jUnique = current(array_diff($jArray, $iArray));
        $all = $iArray;
        $all[$this->M] = $jUnique;
        $votesFor = [];*/

        /*foreach ($all as $candidate)
        {
            $votesFor[$candidate] = $this->votesPreferring($candidate, $all);
        }*/
        $iVotes = $this->votesPreferring($iUnique, $jArray);
        return $iVotes;

        /*foreach ($overlap as $candidate)
        {
            $weight = ($votesFor[$candidate] - $quota)/$votesFor[$candidate];
            $votesFor[$iUnique] += $weight * $this->votesPreferring($iUnique, $jArray, $candidate);
            $votesFor[$jUnique] += $weight * $this->votesPreferring($jUnique, $iArray, $candidate);
        }*/
        return $votesFor[$iUnique] - $votesFor[$jUnique];
    }

    protected function makeStrongestSetPaths(): void
    {
        $election = $this->getElection();
        $outcomes = $this->outcomes;

        foreach ($this->outcomes as $iKey=>$iSet) {
            //Note: The line below must be changed if this were modified for a variable number of winners.
            $time = microtime(true);
            foreach ($this->outcomes as $jKey=>$jSet) {
                //$count = count(array_diff($iSet, $jSet));
                if (count(array_diff($iSet, $jSet)) === 1) {
                    /*if ($iKey > $jKey) {
                        $this->StrongestSetPaths[$iKey][$jKey] = (-1) * $this->StrongestSetPaths[$jKey][$iKey];
                    } else*/if ($iKey !== $jKey) {
                        $this->StrongestSetPaths[$iKey][$jKey] = $this->compareOutcomes($iSet, $jSet, $election);
                        //echo("Went through outcome number ".$jKey."\n");
                    }
                }
            }
            echo('Time to compare outcome '.$iKey.' with others was '.microtime(true)-$time." microseconds.\n");
        }

        foreach ($outcomes as $iKey=>$iSet) {
            foreach ($outcomes as $jKey=>$jSet) {
                if ($iKey !== $jKey) {
                    foreach ($outcomes as $kKey=>$kSet) {
                        if ($iKey !== $kKey && $jKey !== $kKey) {
                            $this->StrongestSetPaths[$jKey][$kKey] =
                                max(
                                    $this->StrongestSetPaths[$jKey][$kKey],
                                    min($this->StrongestSetPaths[$jKey][$iKey], $this->StrongestSetPaths[$iKey][$kKey])
                                );
                        }
                    }
                }
            }
        }
    }
}