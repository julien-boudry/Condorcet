<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;


class PairwiseUpdateOptimizationBench
{
    /**
     * @Iterations(2)
     * @Warmup(1)
     * @Revs(4)
     */
    public function benchPairwiseOptimization () : void
    {
       $election = new Election;

       $election->parseCandidates('A;B;C;D;E;F;G');

       $election->parseVotes('
          E > B > C > A > G * 2500
          F > B > G > H > A * 2500
          H > B > G > E > A * 2500
          A = B = C > D > E = F > G * 2500
          G = E = C > F > A * 2500
          C > D = G > A > B * 2500
       ');

        $election->getWinner();

        $vote = $election->addVote('A>B>C');

        $election->removeVote($vote);

        $vote->setRanking('C>B>A');

        $election->getWinner();
    }

}