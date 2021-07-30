<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Election;
use PhpBench\Attributes as Bench;
ini_set('memory_limit','51200M');

// Must use --executor=memory_centric_microtime

class KemenyYoungBench
{
    #[Bench\Warmup(1)]
    #[Bench\Iterations(10)]
    #[Bench\Revs(10)]
    public function benchKemenyYoung8 () : void
    {
       $election = new Election;
       $election->allowsVoteWeight(true);

       $election->parseCandidates('A;B;C;D;E;F;G;H');

       $election->parseVotes('A>B>C>D>E>F>G');

       $result = $election->getResult('Kemeny–Young');
    }

}