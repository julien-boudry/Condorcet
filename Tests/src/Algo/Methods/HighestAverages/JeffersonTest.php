<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\HighestAverage;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use PHPUnit\Framework\TestCase;

class JeffersonTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    # https://fr.wikipedia.org/wiki/Scrutin_proportionnel_plurinominal#M%C3%A9thode_de_Jefferson_ou_m%C3%A9thode_D'Hondt
    public function testResult_1(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->setNumberOfSeats(6);
        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('A * 42; B ^31; C *15; D ^12'); // Mix weight and number

        self::assertSame(['A' => 3, 'B' => 2, 'C' => 1, 'D' => 0], $this->election->getResult('Jefferson')->getStats()['Seats per Candidates']);
    }

    public function testResult_Tideman_A03(): void
    {
        $cef = new CondorcetElectionFormat(__DIR__.'/'.'A03.cvotes');
        $cef->setDataToAnElection($this->election);

        $this->election->setImplicitRanking(false); // Empty ranking was throw an error.

        $this->election->getResult('Jefferson');

        self::assertTrue(true);
    }
}
