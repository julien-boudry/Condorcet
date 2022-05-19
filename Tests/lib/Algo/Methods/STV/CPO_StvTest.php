<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\STV;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
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
    }

    public function testCPO1 (): void
    {
        # From https://en.wikipedia.org/wiki/CPO-STV

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

        // var_dump($this->election->getResult('CPO STV')->getStats());
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

    // In this example, only Borda method will break A and B
    public function testEquality1 (): void
    {
        // Ref
        $this->election->setNumberOfSeats(2);

        $this->election->parseCandidates('A;B;C');

        $this->election->addVote('A>B>C');
        $this->election->addVote('B>A>C');
        $this->election->addVote('B>C>A');
        $this->election->addVote('A>B>C');

        self::assertSame('B > A', $this->election->getResult('CPO STV')->getResultAsString());
    }

}