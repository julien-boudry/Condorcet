<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\{Condorcet, Election};
use PhpBench\Attributes as Bench;

class SimpleUsageBench
{
    #[Bench\Warmup(1)]
    #[Bench\Iterations(10)]
    #[Bench\Revs(10)]
    #[Bench\OutputTimeUnit('milliseconds')]
    public function benchSimpleManyVotes(): void
    {
        $election = new Election;
        $election->allowsVoteWeight(true);

        $election->parseCandidates('A;B;C;D;E;F');

        $election->parseVotes('
       		Ultimate Question of Life || A>B>C ^42 * 42
          C=A>B ^2 * 250
          B>C
          E > B > C > A ^80 *257
          F > B > G > H > A* 250
          D = B = E > F ^6 * 100
          B>F=A>C * 100
       ');

        $election->getCondorcetWinner();
        $election->getCondorcetLoser();

        $result = [];

        foreach (Condorcet::getAuthMethods() as $method) {
            $result[] = $election->getResult($method);
        }
    }
}
