<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\KemenyYoung;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;
use PHPUnit\Framework\TestCase;

class KemenyYoungTest extends TestCase
{
    private readonly Election $election;

    public function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1 (): void
    {
        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Knoxville');
        $this->election->addCandidate('Chattanooga');

        $this->election->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');


        self::assertSame(
            [
                1 => 'Nashville',
                2 => 'Chattanooga',
                3 => 'Knoxville',
                4 => 'Memphis'
            ],
            $this->election->getResult('KemenyYoung')->getResultAsArray(true)
        );

        self::assertSame(393, $this->election->getResult('KemenyYoung')->getStats()['bestScore']);

        self::assertSame($this->election->getWinner(),$this->election->getWinner('KemenyYoung'));
    }

    /**
     * @preserveGlobalState disabled
     */
    public function testResult2 (): void
    {
        $this->election->parseCandidates('Elliot;Roland;Meredith;Selden');

        $this->election->parseVotes('
            Elliot > Roland ^30
            Elliot > Meredith ^60
            Elliot > Selden ^60
            Roland > Meredith ^70
            Roland > Selden ^60
            Meredith > Selden ^40
        ');

        $this->election->setImplicitRanking(false);

        self::assertSame(
            [
                1 => 'Elliot',
                2 => 'Roland',
                3 => 'Meredith',
                4 => 'Selden'
            ],
            $this->election->getResult('KemenyYoung')->getResultAsArray(true)
        );
    }

    public function testStats_1 (): void
    {
        $this->election->setStatsVerbosity(StatsVerbosity::FULL);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');

        $this->election->parseVotes($r = 'A > B');

        self::assertSame(
            $r,
            $this->election->getResult('KemenyYoung')->getResultAsString()
        );

        self::assertSame(
            [
                'bestScore' => 1,
                'rankingInConflicts' => 0,
                'rankingScores' => [
                    [1 => 'A', 2 => 'B', 'score' => 1],
                    [1 => 'B', 2 => 'A', 'score' => 0],
                ]
            ],
            $this->election->getResult('KemenyYoung')->getStats()
        );

        $this->election->setStatsVerbosity(StatsVerbosity::STD);

        self::assertArrayNotHasKey('rankingScores', $this->election->getResult('KemenyYoung')->getStats());
    }

    public function testMaxCandidates (): never
    {
        $this->expectException(CandidatesMaxNumberReachedException::class);
        $this->expectExceptionMessage("Maximum number of candidates reached: The method 'Kemeny–Young' is configured to accept only ".KemenyYoung::$MaxCandidates." candidates");

        for ($i=0; $i < (KemenyYoung::$MaxCandidates + 1); $i++) :
            $this->election->addCandidate();
        endfor;

        $this->election->parseVotes('A');

        $this->election->getWinner('KemenyYoung');
    }

    public function testConflicts (): void
    {
        $this->election->parseCandidates('A;B;C');

        $this->election->parseVotes('
            A>B>C;
            B>C>A;
            C>A>B');

        $result = $this->election->getResult( 'KemenyYoung' ) ;

        self::assertEquals(
            [ 0 => [
                'type' => 42,
                'msg' => '3;5'
              ]
            ],
            $result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE)
        );

        self::assertEquals(
            [ 0 => [
                'type' => 42,
                'msg' => '3;5'
              ]
            ],
            $result->getWarning()
        );

        self::assertSame(3, $result->getStats()['rankingInConflicts']);

        $this->election->addVote('A>B>C');

        $result = $this->election->getResult( 'KemenyYoung' ) ;

        self::assertEquals(
            [],
            $result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE)
        );

        self::assertEquals('A',$this->election->getWinner('KemenyYoung'));
    }

    public function testKemenyWithOnly1Candidate ()
    {
        $candidate[] = $this->election->addCandidate();

        $this->election->addVote($candidate);

        self::assertSame($candidate[0],$this->election->getWinner('KemenyYoung'));
    }

    public function ManyCandidatesProvider (): array
    {
        return [
            9  => [9],
            10  => [10],
        ];
    }

    /**
     * @group large
     * @dataProvider  ManyCandidatesProvider
     */
    public function testKemenyWith9Candidates (int $candidatesCount)
    {
        $original = KemenyYoung::$MaxCandidates;
        KemenyYoung::$MaxCandidates = null;

        for ($i=0;$i<$candidatesCount;$i++):
            $candidates[] = $this->election->addCandidate();
        endfor;

        $this->election->addVote($candidates);

        self::assertSame($candidates[0],$this->election->getWinner('KemenyYoung'));

        KemenyYoung::$MaxCandidates = $original;
    }
}