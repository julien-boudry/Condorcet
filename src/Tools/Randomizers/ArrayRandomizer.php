<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Randomizers;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionParameter, FunctionReturn, PublicAPI};
use Random\Randomizer;

class ArrayRandomizer
{
    protected Randomizer $randomizer;

    public array $candidates;

    #[PublicAPI]
    public ?int $maxCandidatesRanked = null;
    #[PublicAPI]
    public int|false $minCandidatesRanked = false; // false => min = max or candidatesCount

    #[PublicAPI]
    public ?int $maxRanksCount = null;

    #[PublicAPI]
    public float|int $tiesProbability = 0; // Per vote. Max decimal precision is 3.

    #[PublicAPI]
    #[Description('Create a new VotesRandomGenerator instance')]
    public function __construct(
        #[FunctionParameter('List of candidates as string, candidates objects or sub-array')]
        array $candidates,
        #[FunctionParameter('If null, will use a cryptographivcally secure randomier')]
        Randomizer|null|string $seed = null
    ) {
        $this->setCandidates($candidates);

        if ($seed === null) {
            $this->randomizer = new Randomizer(new \Random\Engine\Secure);
        } elseif ($seed instanceof Randomizer) {
            $this->randomizer = $seed;
        } else {
            $seed = (\strlen($seed) === 32) ? $seed : hash('sha256', $seed, true);
            $this->randomizer = new Randomizer(new \Random\Engine\Xoshiro256StarStar($seed));
        }
    }

    #[PublicAPI]
    #[Description('Change the candidates running for this randomizer.')]
    public function setCandidates(
        #[FunctionParameter('The array of candidates to shuffle')]
        array $candidates
    ): void {
        $this->candidates = $candidates;
    }

    #[PublicAPI]
    #[Description('Count candidates currently running for this instance of the randomizer.')]
    #[FunctionReturn('Count of candidates')]
    public function countCandidates(): int
    {
        return \count($this->candidates);
    }

    #[PublicAPI]
    #[Description('Generate a new random vote.')]
    #[FunctionReturn('Return the new vote.')]
    public function shuffle(): array
    {
        $randomizedCandidates = $this->randomizer->shuffleArray($this->candidates);

        // Max Candidates
        if (\count($randomizedCandidates) > $this->maxCandidatesRanked) {
            $randomizedCandidates = \array_slice($randomizedCandidates, 0, $this->maxCandidatesRanked);
        }

        // Min Candidates
        if ($this->minCandidatesRanked !== false && $this->minCandidatesRanked >= 0 && $this->minCandidatesRanked < \count($randomizedCandidates)) {
            $randomizedCandidates = \array_slice(
                array: $randomizedCandidates,
                offset: 0,
                length: $this->randomizer->getInt($this->minCandidatesRanked, \count($randomizedCandidates)),
            );
        }

        // Build Ties
        if ($this->tiesProbability > 0) {
            $randomizedCandidates = $this->makeTies($randomizedCandidates);
        }

        // Max Ranks
        if (\count($randomizedCandidates) > $this->maxRanksCount) {
            $randomizedCandidates = \array_slice($randomizedCandidates, 0, $this->maxRanksCount, false);
        }

        //

        return $randomizedCandidates;
    }

    protected function makeTies(array $randomizedCandidates): array
    {
        $numberOfTiesToAdd = 0;
        $prob = (int) ($this->tiesProbability * 1_000);

        while ($prob > 0) {
            if ($prob >= 100_000 || $this->randomizer->getInt(1, 100_000) <= $prob) {
                $numberOfTiesToAdd++;
            }

            $prob -= 100_000;
        }

        for ($i = $numberOfAddedTies = 0; $i < $numberOfTiesToAdd && \count($randomizedCandidates) > 1; ++$i && ++$numberOfAddedTies) {
            $swFrom = $this->randomizer->pickArrayKeys($randomizedCandidates, 1)[0];

            $orphan = $randomizedCandidates[$swFrom];
            unset($randomizedCandidates[$swFrom]);

            $swTo = $this->randomizer->pickArrayKeys($randomizedCandidates, 1)[0];
            $destination = $randomizedCandidates[$swTo];

            \is_array($orphan) || $orphan = [$orphan];
            \is_array($destination) || $destination = [$destination];
            $newRankValue = array_merge($destination, $orphan);

            $randomizedCandidates[$swTo] = $newRankValue;
        }

        return $randomizedCandidates;
    }
}
