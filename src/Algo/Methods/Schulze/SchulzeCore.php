<?php declare(strict_types=1);
/*
    Part of SCHULZE method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise;
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;

/**
 * Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method.
 *
 * @internal
 */
abstract class SchulzeCore extends Method implements MethodInterface
{
    // Schulze
    protected array $StrongestPaths = [];


    /////////// PUBLIC ///////////

    abstract protected function schulzeVariant(int $i, int $j, Pairwise $pairwise): int|float;

    #[\Override]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        // -------

        // Format array
        $this->prepareStrongestPath();

        // Strongest Paths calculation
        $this->makeStrongestPaths();

        // Ranking calculation
        $this->makeRanking();


        // Return
        return $this->Result;
    }

    // Get the Schulze ranking
    protected function getStats(): BaseMethodStats
    {
        $election = $this->getElection();
        $explicit = [];

        foreach ($this->StrongestPaths as $candidate_key => $candidate_value) {
            $candidate_key = $election->getCandidateObjectFromKey($candidate_key)->name;

            foreach ($candidate_value as $challenger_key => $challenger_value) {
                $explicit[$candidate_key][$election->getCandidateObjectFromKey($challenger_key)->name] = $challenger_value;
            }
        }

        return new BaseMethodStats($explicit);
    }



    /////////// COMPUTE ///////////


    //:: SCHULZE ALGORITHM. :://

    // Calculate the strongest Paths for Schulze Method
    protected function prepareStrongestPath(): void
    {
        $election = $this->getElection();
        $CandidatesKeys = array_keys($election->getCandidatesList());

        foreach ($CandidatesKeys as $candidate_key) {
            $this->StrongestPaths[$candidate_key] = [];

            // Format array for the strongest path
            foreach ($CandidatesKeys as $candidate_key_r) {
                if ($candidate_key_r !== $candidate_key) {
                    $this->StrongestPaths[$candidate_key][$candidate_key_r] = 0;
                }
            }
        }
    }

    // Calculate the Strongest Paths
    protected function makeStrongestPaths(): void
    {
        $candidates = array_keys($this->getElection()->getCandidatesList());
        $pairwise = $this->getElection()->getPairwise();

        foreach ($candidates as $i) {
            foreach ($candidates as $j) {
                if ($i !== $j) {
                    $this->StrongestPaths[$i][$j] = $pairwise->candidateKeyWinVersus($i, $j) ? $this->schulzeVariant($i, $j, $pairwise) : 0;
                }
            }
        }

        foreach ($candidates as $i) {
            foreach ($candidates as $j) {
                if ($i !== $j) {
                    foreach ($candidates as $k) {
                        if ($i !== $k && $j !== $k) {
                            $this->StrongestPaths[$j][$k]
                                = max(
                                    $this->StrongestPaths[$j][$k],
                                    min($this->StrongestPaths[$j][$i], $this->StrongestPaths[$i][$k])
                                );
                        }
                    }
                }
            }
        }
    }

    // Calculate && Format human readable ranking
    protected function makeRanking(): void
    {
        $election = $this->getElection();
        $result = [];

        // Calculate ranking
        $done = [];
        $rank = 1;

        while (\count($done) < $election->countCandidates()) {
            $to_done = [];

            foreach ($this->StrongestPaths as $candidate_key => $challengers_key) {
                if (\in_array(needle: $candidate_key, haystack: $done, strict: true)) {
                    continue;
                }

                $winner = true;

                foreach ($challengers_key as $beaten_key => $beaten_value) {
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
}
