<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\{Election, Result};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};

class Schulze_STV extends Schulze_Core implements MethodInterface
{
    public const METHOD_NAME = ['Schulze STV', 'Schulze-STV', 'Schulze_STV'];
    
    protected array $StrongestPaths = [];
    protected array $StrongestSetPaths = [];
    protected array $outcomes;
    protected array $CandidatesKeys;
    
    public function getResult(): Result
    {
        if ($this->Result !== null) {
            return $this->Result;
        }
        
        $this->M = $this->getElection()->getNumberOfSeats();
        
        $this->prepareStrongestPath();
        $this->makeStrongestPaths($this->M);
        $this->filterCandidates();
        
        $this->prepareOutcomes();
        var_dump($this->outcomes);
        $this->makeStrongestSetPaths($this->M);
        
        $election = $this->getElection();
        $result = [];

        $done = [];
        $rank = 1;

        while (\count($done) < $election->countCandidates()) {
            $to_done = [];

            foreach ($this->StrongestSetPaths as $set_key => $opposing_key) {
                if (\in_array(needle: $candidate_key, haystack: $done, strict: true)) {
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
        
        $this->addToSet([], $election->sumVotesWeight()/($this->M + 1));
    }
    
    protected function addToSet(array $set, int $quota)
    {
        foreach ($this->CandidatesKeys as $candidate) if ($candidate > end($prev))
        {
            array_push($set, $candidate);
            if (count($set) < $this->M) {
                $set = $this->addToSet($set, $M);
            } else {
                if ($this->checkSupport($set, $quota)) {
                    array_push($this->Outcomes, $set);
                }
            }
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
        unset($set[array_search($i, $set, true)]);
        $total = 0;
        
        foreach($election->getVotesList() as $oneVote)
        {
            $rankings = $oneVote->getRankingsAsAssociativeArray;
            if (isset($from) && $rankings[$from] < $rankings[$i]) {
                continue;
            }
            if ($this->prefers($i, $set, $rankings, $election)) {
                $total = $total + $oneVote->getWeight();
            }
        }
    }
    
    protected function prefers(int $i, $set, array $rankings, Election $election)
    {
        $prefers = true;
        foreach ($set as $j=>$ranking) if ($ranking > $rankings[$i]) {
            $prefers = false;
            break;
        }
        return $prefers;
    }
    
    protected function compareOutcomes(array $iArray, array $jArray, $quota)
    {
        $overlap = array_intersect_assoc($iArray, $jArray);
        $iUnique = array_diff($iArray, $jArray)[0];
        $jUnique = array_diff($jArray, $iArray)[0];
        $all = array_push($iArray, $jUnique);
        $votesFor = [];
        
        foreach ($all as $candidate)
        {
            $votesFor[$candidate] = $this->votesPreferring($candidate, $all);
        }
        
        foreach ($overlap as $candidate)
        {
            $weight = ($votesFor[$candidate] - $quota)/$votesFor[$candidate];
            $votesFor[$iUnique] += $weight * $this->votesPreferring($iUnique, $jArray, $candidate);
            $votesFor[$jUnique] += $weight * $this->votesPreferring($jUnique, $iArray, $candidate);
        }
        return $votesFor;
    }
    
    protected function makeStrongestSetPaths($M): void
    {
        $election = $this->getElection();

        foreach ($outcomes as $i) {
            //Note: The line below must be changed if this were modified for a variable number of winners.
            foreach ($outcomes as $j) {
                $count = count(array_diff($outcomes[$i], $outcomes[$j]));
                if ($count = 1) {
                    if ($i > $j) {
                        $this->StrongestSetPaths[$i][$j] = (-1) * $this->StrongestSetPaths[$j][$i];
                    } elseif ($i != $j) {
                        $this->StrongestPaths[$i][$j] = $this->schulzeVariant($i, $j, $contest);
                    }
                }
            }
        }

        foreach ($CandidatesKeys as $i) {
            foreach ($CandidatesKeys as $j) {
                if ($i !== $j) {
                    foreach ($CandidatesKeys as $k) {
                        if ($i !== $k && $j !== $k) {
                            $this->StrongestPaths[$j][$k] =
                                max(
                                    $this->StrongestPaths[$j][$k],
                                    min($this->StrongestPaths[$j][$i], $this->StrongestPaths[$i][$k])
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
                echo("Removed ".$election->getCandidateObjectFromKey($candidate_key)->getName()." due to low support.\n");
            }
        }
    }
    
}