<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Tools;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;
use CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

class Schulze_proportional_prefilter extends SchulzeWinning
{
    public const METHOD_NAME = ['Schulze proportional prefilter'];

    public function __construct(Election $election, $M=NULL)
    {
        $this->setElection($election);
        $this->M = $M ?? min($election->getNumberOfSeats(), $election->countCandidates());
    }

    // Get's a list of candidates who can possibly win in Schulze STV, as far as can be determined through pairwise results.
    // Would ideally be renamed to 'getPotentialWinners()', if it could still override the inherited function.
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        // Format array
        $this->prepareStrongestPath();

        // Strongest Paths calculation
        $this->makeStrongestPaths($this->M);

        $this->filterCandidates();

        // Return
        return $this->Result;
    }

    // Would ideally be renamed to 'filterCandidates()'.
    protected function filterCandidates(): void
    {
        $election = $this->getElection();
        $result = [];

        $M = &$this->M;

        // Calculate ranking
        $done = [];
        $rank = 1;
        $eliminated = 0;

        while (\count($done) + $eliminated < $election->countCandidates()) {
            $to_done = [];

            foreach ($this->StrongestPaths as $candidate_key => &$challengers_keys) {
                if (\in_array(needle: $candidate_key, haystack: $done, strict: true)) {
                    continue;
                }

                $winner = true;
                $superlosses = 0;

                foreach ($challengers_keys as $beaten_key => $beaten_value) {
                    if (\in_array(needle: $beaten_key, haystack: $done, strict: true)) {
                        continue;
                    }

                    if ($beaten_value < $this->StrongestPaths[$beaten_key][$candidate_key]) {
                        $winner = false;
                        if ($beaten_value * $M < $this->StrongestPaths[$beaten_key][$candidate_key]) {
                            $superlosses++;
                            if ($superlosses > $M) {
                                $eliminated++;
                                echo("Removed candidate ".$election->getCandidateObjectFromKey($candidate_key)->getName()."\n");
                                //unset($this->StrongestPaths[$candidate_key]);
                                //unset($challengers_keys[$candidate_key]);
                                /*foreach($this->StrongestPaths as &$pathArray) {
                                    unset($pathArray[$candidate_key]);
                                }*/
                                break;
                            }
                        }
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
}