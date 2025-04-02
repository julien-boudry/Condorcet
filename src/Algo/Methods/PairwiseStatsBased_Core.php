<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods;

use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats;

abstract class PairwiseStatsBased_Core extends Method implements MethodInterface
{
    protected const string COUNT_TYPE = 'abstractCountType';

    protected readonly array $Comparison;

    /////////// PUBLIC ///////////


    // Get the ranking
    #[\Override]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        // -------

        // Comparison calculation
        $this->Comparison = PairwiseStats::PairwiseComparison($this->getElection()->getPairwise());

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->Result;
    }


    // Get the stats
    protected function getStats(): array
    {
        $election = $this->getElection();
        $explicit = [];

        foreach ($this->Comparison as $candidate_key => $value) {
            $explicit[$election->getCandidateObjectFromKey($candidate_key)->name] = [static::COUNT_TYPE => $value[static::COUNT_TYPE]];
        }

        return $explicit;
    }


    /////////// COMPUTE ///////////


    //:: ALGORITHM. :://

    protected function makeRanking(): void
    {
        $result = [];

        // Calculate ranking
        $challenge = [];
        $rank = 1;
        $done = 0;

        foreach ($this->Comparison as $candidate_key => $candidate_data) {
            $challenge[$candidate_key] = $candidate_data[static::COUNT_TYPE];
        }

        while ($done < $this->getElection()->countCandidates()) {
            $looking = $this->looking($challenge);

            foreach ($challenge as $candidate => $value) {
                if ($value === $looking) {
                    $result[$rank][] = $candidate;

                    $done++;
                    unset($challenge[$candidate]);
                }
            }

            $rank++;
        }

        $this->Result = $this->createResult($result);
    }

    abstract protected function looking(array $challenge): int;
}
