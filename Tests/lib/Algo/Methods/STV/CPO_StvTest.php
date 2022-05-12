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

}