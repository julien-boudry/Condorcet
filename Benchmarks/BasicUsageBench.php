<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;


class BasicUsageBench
{
    /**
     * @Iterations(2)
     * @Warmup(1)
     * @Revs(4)
     */
    public function benchSimpleManyVotes () : void
    {
       $election = new Election;
       $election->allowVoteWeight(true);

       $election->parseCandidates('A;B;C;D;E;F');

       $election->parseVotes('
       		Ultimate Question of Life || A>B>C ^42 * 42
          C=A>B ^2 * 200
          B>C
          E > B > C > A ^80 *50
          F > B > G > H > A* 250
          D = B = E > F ^6 * 48
       ');

       $election->getWinner();
       $election->getLoser();

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;

       $election->setImplicitRanking(false);

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;

       $election->allowVoteWeight(false);

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;

       $election->parseVotes('
          Ultimate Question of Life || C>B>A ^42 * 42
          C=A=B ^2 * 200
          B>C
          A > C >E ^80 *50
          G > B > G > H > F* 250
          C = B = E > A ^6 * 48
       ');

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;
    }

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

        $election->getWinner();

        $election->removeVote($vote);

        $election->getWinner();
    }

}