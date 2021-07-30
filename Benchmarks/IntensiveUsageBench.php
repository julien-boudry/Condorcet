<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;


class IntensiveUsageBench
{
    /**
     * @Iterations(4)
     * @Warmup(1)
     * @Revs(10)
     */
    public function benchSimpleManyVotes () : void
    {
       $election = new Election;
       $election->allowsVoteWeight(true);

       $election->parseCandidates('A;B;C;D;E;F');

       $election->parseVotes('
       		Ultimate Question of Life || A>B>C ^42 * 42
          C=A>B ^2 * 200
          B>C
          E > B > C > A ^80 *50
          F > B > G > H > A* 250
          D = B = E > F ^6 * 48
       ');

       $election->getCondorcetWinner();
       $election->getCondorcetLoser();

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;

       $election->setImplicitRanking(false);

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;

       $election->allowsVoteWeight(false);

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;

       $election->parseVotes('
          Ultimate Question of Life || C>B>A ^42 * 42
          C=A=B ^2 * 200
          B>C
          A > C >E ^80 *50
          G > B > H > F* 250
          C = B = E > A ^6 * 48
       ');

       foreach (Condorcet::getAuthMethods() as $method) :
         $election->getResult($method);
       endforeach;

       $votes = $election->getVotesListAsString();
    }

}