<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;

// Must use --executor=memory_centric_microtime

class KemenyYoungBench
{
    /**
     * @Iterations(10)
     * @Warmup(1)
     * @Revs(10)
     */
    public function benchKemenyYoung8 () : void
    {
       $election = new Election;
       $election->allowsVoteWeight(true);

       $election->parseCandidates('A;B;C;D;E;F;G;H');

       $election->parseVotes('A>B>C>D>E>F>G');

       $result = $election->getResult('Kemeny–Young');
    }

}