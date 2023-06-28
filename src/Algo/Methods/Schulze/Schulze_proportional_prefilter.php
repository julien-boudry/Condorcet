<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Election;

class Schulze_proportional_prefilter extends SchulzeWinning
{
    public const METHOD_NAME = ['Schulze proportional prefilter'];
    protected function makeRanking($M=1): void
    {
        $election = $this->getElection();
        $result = [];

        $M = $election->getNumberOfSeats();

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
                                unset($this->StrongestPaths[$candidate_key]);
                                unset($challengers_keys[$candidate_key]);
                                foreach($this->StrongestPaths as &$pathArray) {
                                    unset($pathArray[$candidate_key]);
                                }
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