<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\STV;

use CondorcetPHP\Condorcet\Algo\Methods\STV\CPO_STV;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException;
use PHPUnit\Framework\TestCase;

class CPO_StvTest extends TestCase
{
    private readonly Election $election;

    public function setUp(): void
    {
        $this->election = new Election;
    }

    public function tearDown(): void
    {
        $this->election->setMethodOption('STV', 'Quota', StvQuotas::DROOP);
        $this->election->setMethodOption('CPO STV', 'Quota', StvQuotas::HAGENBACH_BISCHOFF);
        $this->election->setMethodOption('CPO STV', 'CondorcetCompletionMethod', CPO_STV::DEFAULT_METHODS_CHAINING);
        $this->election->setMethodOption('CPO STV', 'TieBreakerMethods', CPO_STV::DEFAULT_METHODS_CHAINING);
    }

    # From https://en.wikipedia.org/wiki/CPO-STV
    public function testCPO1 (): void
    {
        $this->election->setStatsVerbosity(StatsVerbosity::FULL);

        $this->election->addCandidate('Andrea'); // key 0
        $this->election->addCandidate('Brad'); // key 1
        $this->election->addCandidate('Carter'); // key 2
        $this->election->addCandidate('Delilah'); // key 3
        $this->election->addCandidate('Scott'); // key 4

        $this->election->setImplicitRanking(false);
        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('
            Andrea ^25
            Carter > Brad > Delilah ^34
            Brad > Delilah ^7
            Delilah > Brad ^8
            Delilah > Scott ^5
            Scott > Delilah ^21
        ');

        $this->election->setNumberOfSeats(3);

        self::assertSame( [
                1 => 'Carter',
                2 => 'Andrea',
                3 => 'Delilah'
             ],
            $this->election->getResult('CPO STV')->getResultAsArray(true)
        );

        $stats = $this->election->getResult('CPO STV')->getStats();

        self::assertSame(25.0, $stats['votes_needed_to_win']);
        self::assertSame([  "Andrea"=> 25.0,
                            "Brad"=> 7.0,
                            "Carter"=> 34.0,
                            "Delilah"=> 13.0,
                            "Scott"=> 21.0
        ], $stats['Initial Score Table']);

        self::assertSame(['Andrea', 'Carter'], $stats['Candidates elected from first round']);
        self::assertSame(['Brad', 'Delilah', 'Scott'], $stats['Candidates eliminated from first round']);

        self::assertSame([
            ['Andrea', 'Carter', 'Scott'],
            ['Andrea', 'Carter', 'Delilah'],
            ['Andrea', 'Brad', 'Carter']
        ],  $stats['Outcomes']);

        self::assertSame('Schulze Margin', $stats['Completion Method']);

        self::assertSame(
            [  'Outcome N° 0 compared to Outcome N° 1' =>
                [
                'candidates_excluded' =>
                [
                    0 => 'Brad',
                ],
                'scores_after_exclusion' =>
                [
                    'Andrea' => 25.0,
                    'Carter' => 34.0,
                    'Delilah' => 20.0,
                    'Scott' => 21.0,
                ],
                'scores_after_surplus' =>
                [
                    'Andrea' => 25.0,
                    'Carter' => 25.0,
                    'Delilah' => 29.0,
                    'Scott' => 21.0,
                ],
                'outcomes_scores' =>
                [
                    0 => 71.0,
                    1 => 79.0,
                ],
                ],
                'Outcome N° 0 compared to Outcome N° 2' =>
                [
                'candidates_excluded' =>
                [
                    0 => 'Delilah',
                ],
                'scores_after_exclusion' =>
                [
                    'Andrea' => 25.0,
                    'Brad' => 15.0,
                    'Carter' => 34.0,
                    'Scott' => 26.0,
                ],
                'scores_after_surplus' =>
                [
                    'Andrea' => 25.0,
                    'Brad' => 24.0,
                    'Carter' => 25.0,
                    'Scott' => 26.0,
                ],
                'outcomes_scores' =>
                [
                    0 => 76.0,
                    2 => 74.0,
                ],
                ],
                'Outcome N° 1 compared to Outcome N° 2' =>
                [
                'candidates_excluded' =>
                [
                    0 => 'Scott',
                ],
                'scores_after_exclusion' =>
                [
                    'Andrea' => 25.0,
                    'Brad' => 7.0,
                    'Carter' => 34.0,
                    'Delilah' => 34.0,
                ],
                'scores_after_surplus' =>
                [
                    'Andrea' => 25.0,
                    'Brad' => 16.0,
                    'Carter' => 25.0,
                    'Delilah' => 34.0,
                ],
                'outcomes_scores' =>
                [
                    1 => 84.0,
                    2 => 66.0,
                ],
                ]
            ],
            $stats['Outcomes Comparison']);

        self::assertArrayHasKey('Condorcet Completion Method Stats', $stats);
    }

    public function testLessOrEqualCandidatesThanSeats (): void
    {
        $expectedRanking = [
            1 => 'Memphis',
            2 => 'Nashville',
            3 => 'Chattanooga',
            4 => 'Knoxville',
        ];

        // Ref
        $this->election->setNumberOfSeats(4);

        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Knoxville');
        $this->election->addCandidate('Chattanooga');

        $this->election->parseVotes(' Memphis * 4
                                    Nashville * 3
                                    Chattanooga * 2
                                    Knoxville * 1');

        self::assertSame($expectedRanking, $this->election->getResult('CPO STV')->getResultAsArray(true));

        $this->election->setNumberOfSeats(5);

        self::assertSame($expectedRanking, $this->election->getResult('CPO STV')->getResultAsArray(true));
    }

    public function testEquality1 (): void
    {
        $this->election->setNumberOfSeats(2);

        $this->election->parseCandidates('A;B;C');

        $this->election->addVote('A>B>C');
        $this->election->addVote('B>A>C');
        $this->election->addVote('B>C>A');
        $this->election->addVote('A>B>C');

        self::assertSame([1=>['A','B']], $this->election->getResult('CPO STV')->getResultAsArray(true));

        $this->election->setNumberOfSeats(3);

        self::assertSame([1=>['A','B'], 3=> 'C'], $this->election->getResult('CPO STV')->getResultAsArray(true));
    }

    public function testEquality2 (): void
    {
        $this->election->setImplicitRanking(false);
        $this->election->setNumberOfSeats(3);

        $this->election->parseCandidates('A;B;C;D');

        $this->election->addVote('A>B>C>D');
        $this->election->addVote('A>B>D>C');

        self::assertSame([1=>'A',2=>['B','D']], $this->election->getResult('CPO STV')->getResultAsArray(true));
    }

    public function testLimit1 (): void
    {
        $this->expectException(MethodLimitReachedException::class);
        $this->expectExceptionMessage('CPO-STV is currently limited to 12000 comparisons in order to avoid unreasonable deadlocks due to non-polyminial runtime aspects of the algorithm. Consult the manual to increase or remove this limit.');


        $this->election->setNumberOfSeats(10);
        $this->election->parseCandidates('1;2;3;4;5;6;7;8;9;10;11;12;13;14;15');
        $this->election->addVote('1>2');

        $this->election->getResult('CPO STV');
    }

    public function testCPO40Candidates (): void
    {
        $this->expectException(MethodLimitReachedException::class);
        $this->expectExceptionMessage('CPO-STV is currently limited to 12000 comparisons in order to avoid unreasonable deadlocks due to non-polyminial runtime aspects of the algorithm. Consult the manual to increase or remove this limit.');

        $this->election->setImplicitRanking(false);
        $this->election->setNumberOfSeats((int) (40 / 3));

        $candidates = [];
        for ($i=0 ; $i < 40 ; $i++) :
            $candidates[] = $this->election->addCandidate();
        endfor;

        for ($i = 0 ; $i < 100 ; $i++):
            \shuffle($candidates);
            $this->election->addVote($candidates);
        endfor;

        $this->election->getResult('CPO STV')->getResultAsString();
    }


}