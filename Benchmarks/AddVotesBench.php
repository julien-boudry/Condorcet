<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Election;
use PhpBench\Attributes as Bench;
ini_set('memory_limit','51200M');

class AddVotesBench
{
    protected Election $election;

    #[Bench\OutputTimeUnit('seconds')]
    #[Bench\Warmup(1)]
    #[Bench\Iterations(3)]
    #[Bench\Revs(1)]
    public function benchVotesWithManyCandidates (): void
    {
        $this->election = $election = new Election;

        $candidates = [];

        for ($i=0 ; $i < 100 ; $i++) :
            $candidates[] = $election->addCandidate();
        endfor;

        for ($i = 0 ; $i < 1_000 ; $i++) :
            $oneVote = $candidates;
            shuffle($oneVote);

            $election->addVote($oneVote);
        endfor;
    }
}