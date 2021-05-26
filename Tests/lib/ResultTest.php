<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;


class ResultTest extends TestCase
{
    private Election $election1;

    public function setUp() : void
    {
        $this->election1 = new Election;
    }

    public function testGetResultAsString () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->parseVotes('
            B > A > C * 7
            A > B > C * 7
            C > A > B * 2
            C > B > A * 2
        ');


        self::assertSame(
            'A = B > C',
            $this->election1->getResult('Ranked Pairs')->getResultAsString()
        );
    }

    public function testGetResultAsInternalKey () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->parseVotes('
            B > A > C * 7
            A > B > C * 7
            C > A > B * 2
            C > B > A * 2
        ');


        self::assertSame(
            [1 => [0,1], 2 => [2]],
            $this->election1->getResult('Ranked Pairs')->getResultAsInternalKey()
        );
    }

    public function testgetCondorcetElectionGeneratorVersion () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame(Condorcet::getVersion(),$this->election1->getResult('Ranked Pairs')->getCondorcetElectionGeneratorVersion());
    }

    public function testResultClassgenerator () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame(\CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin::class,$this->election1->getResult('Ranked Pairs')->getClassGenerator());
    }

    public function testMethod () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame('Ranked Pairs Margin',$this->election1->getResult('Ranked Pairs')->getMethod());
    }

    public function testGetBuildTimeStamp () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertIsFloat($this->election1->getResult('Ranked Pairs')->getBuildTimeStamp());
    }

    public function testGetWinner () : void
    {
        $this->election1->addCandidate('a');
        $this->election1->addCandidate('b');
        $this->election1->addCandidate('c');

        $this->election1->parseVotes('
            a > c > b * 23
            b > c > a * 19
            c > b > a * 16
            c > a > b * 2
        ');

        self::assertEquals('c', $this->election1->getResult()->getWinner());
        self::assertEquals('c', $this->election1->getResult()->getCondorcetWinner());

    }

    public function testGetLoser () : void
    {
        $this->election1->addCandidate('Memphis');
        $this->election1->addCandidate('Nashville');
        $this->election1->addCandidate('Knoxville');
        $this->election1->addCandidate('Chattanooga');

        $this->election1->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

        self::assertEquals('Memphis', $this->election1->getResult()->getLoser());
        self::assertEquals('Memphis', $this->election1->getResult()->getCondorcetLoser());
    }

    public function testgetOriginalResultArrayWithString () : void
    {
        $this->election1->addCandidate('a');
        $this->election1->addCandidate('b');
        $this->election1->addCandidate('c');

        $this->election1->addVote('a > b > c');

        self::assertEquals(
            [   1 => 'a',
                2 => 'b',
                3 => 'c',
            ],
            $this->election1->getResult()->getOriginalResultArrayWithString()
        );
    }

    public function testOffsetSet () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);

        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        $result[] = 42;
    }

    public function testOffUnset () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);

        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        self::assertSame(true,isset($result[1]));

        unset($result[1]);
    }

    public function testIterator () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $vote = $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        foreach ($result as $key => $value) :
            self::assertSame($vote->getRanking()[$key],$value);
        endforeach;
    }

    public function testBadMethodName () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(8);

        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->parseVotes('A>B>C');

        $this->election1->getResult('bad method');
    }

    public function testResultRankOrdering () : void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');
        $this->election1->addCandidate('A');

        $this->election1->addVote('C = A = B');

        self::assertSame(
            [   1 => ['A','B','C']
            ],
            $this->election1->getResult()->getOriginalResultArrayWithString()
        );
    }

    public function testProportional () : void
    {
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        $this->election1->addVote('A');

        $this->election1->setNumberOfSeats(2);

        $result = $this->election1->getResult('STV');

        self::assertSame(
            2,
            $result->getNumberOfSeats()
        );

        self::assertTrue(
            $result->getClassGenerator()::IS_PROPORTIONAL
        );

        self::assertTrue(
            $result->isProportional()
        );

        $result = $this->election1->getResult('Schulze');

        self::assertNull(
            $result->getNumberOfSeats()
        );

        self::assertFalse(
            $result->getClassGenerator()::IS_PROPORTIONAL
        );

        self::assertFalse(
            $result->isProportional()
        );
    }
}