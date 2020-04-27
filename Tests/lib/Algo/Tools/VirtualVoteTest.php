<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\{Election, Vote};
use CondorcetPHP\Condorcet\Algo\Tools\VirtualVote;
use PHPUnit\Framework\TestCase;


class VirtualVoteTest extends TestCase
{
    public function testVirtualVote () : void
    {
        $election = new Election;
        $election->parseCandidates("A;B;C");

        $vote1 = new Vote ('A>B>C');
        $election->addVote($vote1);

        $vote2 = VirtualVote::removeCandidates($vote1,['B']);

        self::assertNotSame($vote1->getSimpleRanking(),$vote2->getSimpleRanking());
        self::assertSame('A > C',$vote2->getSimpleRanking());

        self::assertSame(1,$vote1->countLinks());
        self::assertSame(0,$vote2->countLinks());
        self::assertSame(1,$election->countVotes());
    }
}