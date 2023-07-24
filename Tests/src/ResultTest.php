<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\{Condorcet, Election};
use CondorcetPHP\Condorcet\Throwable\{AlgorithmException, ResultException};
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    private readonly Election $election1;

    protected function setUp(): void
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


        $this->assertSame(
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


        $this->assertSame(
            [1 => [0, 1], 2 => [2]],
            $this->election1->getResult('Ranked Pairs')->getResultAsInternalKey()
        );
    }

    public function testgetCondorcetElectionGeneratorVersion(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $this->assertSame(Condorcet::getVersion(), $this->election1->getResult('Ranked Pairs')->getCondorcetElectionGeneratorVersion());
    }

    public function testResultClassgenerator(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $this->assertSame(\CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin::class, $this->election1->getResult('Ranked Pairs')->getClassGenerator());
    }

    public function testMethod(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $this->assertSame('Ranked Pairs Margin', $this->election1->getResult('Ranked Pairs')->getMethod());
    }

    public function testGetBuildTimeStamp(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $this->assertIsFloat($this->election1->getResult('Ranked Pairs')->getBuildTimeStamp());
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

        $this->assertEquals('c', $this->election1->getResult()->getWinner());
        $this->assertEquals('c', $this->election1->getResult()->getCondorcetWinner());
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

        $this->assertEquals('Memphis', $this->election1->getResult()->getLoser());
        $this->assertEquals('Memphis', $this->election1->getResult()->getCondorcetLoser());
    }

    public function testgetOriginalResult(): void
    {
        $this->election1->addCandidate('a');
        $this->election1->addCandidate('b');
        $this->election1->addCandidate('c');

        $this->election1->addVote('a > b > c');

        $this->assertEquals(
            [1 => 'a',
                2 => 'b',
                3 => 'c',
            ],
            $this->election1->getResult()->getOriginalResultArrayWithString()
        );

        $this->assertSame('a > b > c', $this->election1->getResult()->getOriginalResultAsString());
    }

    public function testOffsetSet(): never
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        $this->expectException(ResultException::class);
        $this->expectExceptionMessage('Result cannot be changed');

        $result[] = 42;
    }

    public function testOffUnset(): never
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        $this->assertTrue(isset($result[1]));

        $this->expectException(ResultException::class);
        $this->expectExceptionMessage('Result cannot be changed');

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
            $this->assertSame($vote->getRanking()[$key], $value);
        }
    }

    public function testBadMethodName(): never
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->parseVotes('A>B>C');

        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: bad method');

        $this->election1->getResult('bad method');
    }

    public function testResultRankOrdering(): void
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');
        $this->election1->addCandidate('A');

        $this->election1->addVote('C = A = B');

        $this->assertSame(
            [1 => ['A', 'B', 'C'],
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

        $this->assertSame(
            2,
            $result->getNumberOfSeats()
        );

        $this->assertTrue(
            $result->getClassGenerator()::IS_PROPORTIONAL
        );

        $this->assertTrue(
            $result->isProportional()
        );

        $result = $this->election1->getResult('Schulze');

        $this->assertNull(
            $result->getNumberOfSeats()
        );

        $this->assertFalse(
            $result->getClassGenerator()::IS_PROPORTIONAL
        );

        $this->assertFalse(
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

        $this->assertSame(1, $class::$optionStarting);

        $b1 = $this->election1->getResult('Borda');
        $c1 = $this->election1->getResult('Copeland');

        $this->assertSame(1, $b1->getMethodOptions()['Starting']);

        $this->assertTrue($this->election1->setMethodOption('Borda Count', 'Starting', 0));
        $this->assertSame(0, $class::$optionStarting);

        $this->assertSame(1, $b1->getMethodOptions()['Starting']);

        $b2 = $this->election1->getResult('Borda');
        $c2 = $this->election1->getResult('Copeland');

        $this->assertNotSame($b1, $b2);
        $this->assertSame($c1, $c2);

        $this->assertSame(0, $b2->getMethodOptions()['Starting']);

        $this->assertFalse($this->election1->setMethodOption('Unregistered method', 'Starting', 0));
    }

    public function testVerbosityLevel(): void
    {
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        $this->election1->addVote('A>B>C');

        $r1 = $this->election1->getResult(KemenyYoung::class);
        $this->assertSame(StatsVerbosity::STD, $r1->statsVerbosity);

        $this->election1->setStatsVerbosity(StatsVerbosity::STD);
        $r2 = $this->election1->getResult(KemenyYoung::class);
        $this->assertSame($r1, $r2);

        $this->election1->setStatsVerbosity(StatsVerbosity::FULL);
        $r3 = $this->election1->getResult(KemenyYoung::class);

        $this->assertSame(StatsVerbosity::STD, $r1->statsVerbosity);
        $this->assertSame(StatsVerbosity::FULL, $r3->statsVerbosity);

        $this->assertNotSame($r1, $r3);
        $this->assertArrayNotHasKey('Ranking Scores', $r1->getStats());
        $this->assertArrayHasKey('Ranking Scores', $r3->getStats());
    }

    #[DataProviderExternal(ElectionTest::class, 'MethodsListProvider')]
    public function testImmutablePairwise(string $method): void
    {
        $election = new Election;
        $election->parseCandidates('A;B');
        $election->parseVotes('
            tag1 || A > B
            tag2 || B > A
        ');

        $resultGlobal = $election->getResult($method);

        $resultTag1 = $election->getResult(method: $method, methodOptions: ['%tagFilter' => true, 'tags' => 'tag1']);
        $resultTag2 = $election->getResult(method: $method, methodOptions: ['%tagFilter' => true, 'tags' => 'tag2']);

        $testSuite = static function () use ($resultGlobal, $resultTag1, $resultTag2): void {
            self::assertSame(
                [
                    'A' => [
                        'win' => [
                            'B' => 1,
                        ],
                        'null' => [
                            'B' => 0,
                        ],
                        'lose' => [
                            'B' => 1,
                        ],
                    ],
                    'B' => [
                        'win' => [
                            'A' => 1,
                        ],
                        'null' => [
                            'A' => 0,
                        ],
                        'lose' => [
                            'A' => 1,
                        ],
                    ],
                ],
                $resultGlobal->pairwise
            );

            self::assertSame(
                [
                    'A' => [
                        'win' => [
                            'B' => 1,
                        ],
                        'null' => [
                            'B' => 0,
                        ],
                        'lose' => [
                            'B' => 0,
                        ],
                    ],
                    'B' => [
                        'win' => [
                            'A' => 0,
                        ],
                        'null' => [
                            'A' => 0,
                        ],
                        'lose' => [
                            'A' => 1,
                        ],
                    ],
                ],
                $resultTag1->pairwise
            );

            self::assertSame(
                [
                    'A' => [
                        'win' => [
                            'B' => 0,
                        ],
                        'null' => [
                            'B' => 0,
                        ],
                        'lose' => [
                            'B' => 1,
                        ],
                    ],
                    'B' => [
                        'win' => [
                            'A' => 1,
                        ],
                        'null' => [
                            'A' => 0,
                        ],
                        'lose' => [
                            'A' => 0,
                        ],
                    ],
                ],
                $resultTag2->pairwise
            );
        };

        // Run first
        $testSuite();

        // Add a vote, nothing move
        $election->parseVotes('tag1 || A > B');

        $testSuite();
    }
}
