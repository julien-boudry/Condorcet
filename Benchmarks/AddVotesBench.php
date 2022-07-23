<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Election;
use PhpBench\Attributes as Bench;

ini_set('memory_limit', '51200M');

class AddVotesBench
{
    protected Election $election;

    #[Bench\OutputTimeUnit('seconds')]
    #[Bench\Warmup(1)]
    #[Bench\Iterations(3)]
    #[Bench\Revs(1)]
    public function benchVotesWithManyCandidates(): void
    {
        $randomizer = new \Random\Randomizer(new \Random\Engine\Xoshiro256StarStar('CondorcetReproductibleRandomSeed'));

        $this->election = $election = new Election;

        $candidates = [];

        for ($i=0; $i < 100; $i++) {
            $candidates[] = $election->addCandidate();
        }

        for ($i = 0; $i < 1_000; $i++) {
            $oneVote = $randomizer->shuffleArray($candidates);
            $election->addVote($oneVote);
        }
    }
}
