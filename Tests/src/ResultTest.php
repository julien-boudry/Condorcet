<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Throwable\{AlgorithmException, ResultException, VoteNotLinkedException};
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    private readonly Election $election1;

    public function setUp(): void
    {
        $this->election1 = new Election;
    }

    public function testGetResultAsString(): void
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

    public function testGetResultAsInternalKey(): void
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

    public function testgetCondorcetElectionGeneratorVersion(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame(Condorcet::getVersion(), $this->election1->getResult('Ranked Pairs')->getCondorcetElectionGeneratorVersion());
    }

    public function testResultClassgenerator(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame(\CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin::class, $this->election1->getResult('Ranked Pairs')->getClassGenerator());
    }

    public function testMethod(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame('Ranked Pairs Margin', $this->election1->getResult('Ranked Pairs')->getMethod());
    }

    public function testGetBuildTimeStamp(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertIsFloat($this->election1->getResult('Ranked Pairs')->getBuildTimeStamp());
    }

    public function testGetWinner(): void
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

    public function testGetLoser(): void
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

    public function testgetOriginalResultArrayWithString(): void
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

    public function testOffsetSet(): never
    {
        $this->expectException(ResultException::class);
        $this->expectExceptionMessage("Result cannot be changed");

        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        $result[] = 42;
    }

    public function testOffUnset(): never
    {
        $this->expectException(ResultException::class);
        $this->expectExceptionMessage("Result cannot be changed");

        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        self::assertSame(true, isset($result[1]));

        unset($result[1]);
    }

    public function testIterator(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $vote = $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        foreach ($result as $key => $value) {
            self::assertSame($vote->getRanking()[$key], $value);
        }
    }

    public function testBadMethodName(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage("The voting algorithm is not available: bad method");

        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->parseVotes('A>B>C');

        $this->election1->getResult('bad method');
    }

    public function testResultRankOrdering(): void
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

    public function testProportional(): void
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

    public function testMethodOption(): void
    {
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        $this->election1->addVote('A>B>C');

        $class = Condorcet::getMethodClass('Borda');

        self::assertSame(1, $class::$optionStarting);

        $b1 = $this->election1->getResult('Borda');
        $c1 = $this->election1->getResult('Copeland');

        self::assertSame(1, $b1->getMethodOptions()['Starting']);

        self::assertTrue($this->election1->setMethodOption('Borda Count', 'Starting', 0));
        self::assertSame(0, $class::$optionStarting);

        self::assertSame(1, $b1->getMethodOptions()['Starting']);

        $b2 = $this->election1->getResult('Borda');
        $c2 = $this->election1->getResult('Copeland');

        self::assertNotSame($b1, $b2);
        self::assertSame($c1, $c2);

        self::assertSame(0, $b2->getMethodOptions()['Starting']);

        self::assertFalse($this->election1->setMethodOption('Unregistered method', 'Starting', 0));
    }

    public function testVerbosityLevel(): void
    {
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        $this->election1->addVote('A>B>C');

        $r1 = $this->election1->getResult(KemenyYoung::class);
        self::assertSame(StatsVerbosity::STD, $r1->statsVerbosity);

        $this->election1->setStatsVerbosity(StatsVerbosity::STD);
        $r2 = $this->election1->getResult(KemenyYoung::class);
        self::assertSame($r1, $r2);

        $this->election1->setStatsVerbosity(StatsVerbosity::FULL);
        $r3 = $this->election1->getResult(KemenyYoung::class);

        self::assertSame(StatsVerbosity::STD, $r1->statsVerbosity);
        self::assertSame(StatsVerbosity::FULL, $r3->statsVerbosity);

        self::assertNotSame($r1, $r3);
        self::assertArrayNotHasKey('Ranking Scores', $r1->getStats());
        self::assertArrayHasKey('Ranking Scores', $r3->getStats());
    }
}
