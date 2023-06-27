<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\{Election, Result};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};

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

    public function getResult(): Result
    {
        if ($this->Result !== null) {
            return $this->Result;
        }

        $this->M = $this->getElection()->getNumberOfSeats();

        /*$this->prepareStrongestPath();
        $this->makeStrongestPaths($this->M);
        $this->filterCandidates();*/

        $this->prepareOutcomes();
        $this->makeStrongestSetPaths();

        $election = $this->getElection();
        $result = [];

        $done = [];
        $rank = 1;

        while (\count($done) < $election->countCandidates()) {
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

                    if ($beaten_value < $this->StrongestPaths[$beaten_key][$candidate_key]) {
                        $winner = false;
                    }
                }

                if ($winner) {
                    $result[$rank][] = $candidate_key;

                    $to_done[] = $candidate_key;
                }
            }

            array_push($done, ...$to_done);

            $rank++;
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
        $this->CandidatesKeys = array_keys($election->getCandidatesList());
        $totalVotesWeight = $election->sumVotesWeight();
        foreach ($this->CandidatesKeys as $candidate) {
            $this->addToSet([$candidate], $this->M/*, $election->sumVotesWeight()/($this->M + 1)*/);
        }
    }

    protected function addToSet(array $set)
    {
        $election = $this->getElection();
        $CandidatesKeys = array_keys($election->getCandidatesList());
        foreach ($CandidatesKeys as $candidate) if($candidate > end($set))
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

    protected function checkSupport($set, $quota)
    {
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
        $implicit = $election->getImplicitRankingRule();

        foreach($election->getVotesList() as $oneVote)
        {
            $rankings = $oneVote->getRankingsAsAssociativeArray($election, $implicit);
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
            $time = microtime();
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
            echo('Time for to compare outcome '.$iKey.' with others was '.$time-microtime()." seconds.\n");
        }

        foreach ($outcomes as $i) {
            foreach ($outcomes as $j) {
                if ($i !== $j) {
                    foreach ($outcomes as $k) {
                        if ($i !== $k && $j !== $k) {
                            $this->StrongestSetPaths[$j][$k] =
                                max(
                                    $this->StrongestSetPaths[$j][$k],
                                    min($this->StrongestSetPaths[$j][$i], $this->StrongestSetPaths[$i][$k])
                                );
                        }
                    }
                }
            }
        }
    }

    protected function filterCandidates(): void
    {
        $election = $this->getElection();

        foreach ($this->StrongestPaths as $candidate_key => $challengers_key) {
            /*if (\in_array(needle: $candidate_key, haystack: $done, strict: true)) {
                continue;
            }*/

            $qualifies = true;
            $done;

            foreach ($challengers_key as $beaten_key => $beaten_value) {
                if (\in_array(needle: $beaten_key, haystack: $done, strict: true)) {
                    continue;
                }

                if ($beaten_value < $this->M * $this->StrongestPaths[$beaten_key][$candidate_key]) {
                    $qualifies = false;
                }
            }

            if (!$qualifies) {
                unset($this->CandidatesKeys[array_search($candidate_key, $this->CandidatesKeys)]);
            }
        }
    }

}