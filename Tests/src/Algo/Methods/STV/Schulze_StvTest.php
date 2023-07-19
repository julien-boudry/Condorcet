<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\STV;

use CondorcetPHP\Condorcet\Algo\Methods\STV\CPO_STV;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer;
use PHPUnit\Framework\TestCase;
use function CondorcetPHP\Condorcet\Tools\Converters\CEF\setDataToAnElection;

class Schulze_StvTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
    {
        $this->election->addCandidate('A1');
        $this->election->addCandidate('A2');
        $this->election->addCandidate('B1');
        $this->election->addCandidate('B2');
        $this->election->addCandidate('C1');

        $this->election->setImplicitRanking(true);

        $this->election->parseVotes('
            A1 > A2 *5
            A2 > A1 *4
            B1 > B2 *4
            B2 > B1 *5
            C1 *6
        ');

        $this->election->setNumberOfSeats(2);

        $resultArray = $this->election->getResult('Schulze-STV')->getResultAsArray(true);
        self::assertContains('A1', $resultArray);
        self::assertContains('B2', $resultArray);
    }

    public function testResult_Tideman(): void
    {
        // Correct results for Tideman dataset as determined using the C++ program written by Markus Schulze.
        $correctResults = [
            'TidemanA2'=> ['C', 'D'],
            'TidemanA6'=> ['B', 'C', 'E', 'H', 'I'],
            'TidemanA19'=> ['A', 'E', 'G'],
            'TidemanA22'=> ['C', 'K'],
            'TidemanA52'=> ['A', 'B', 'C', 'D', 'E', 'G'],
            'TidemanA53'=> ['A', 'D', 'G', 'J'],
            'TidemanA57'=> ['D', 'E'],
            'TidemanA69'=> ['A', 'C', 'E'],
            'TidemanA76'=> ['A', 'C'],
            'TidemanA86'=> ['A', 'C', 'D', 'E'],
            'TidemanA88'=> ['A', 'C', 'E', 'F', 'G', 'H'],
            'TidemanA95'=> ['A', 'B']
        ];

        foreach ($correctResults as $fileName=>$correctWinners) {
            $election = (new CondorcetElectionFormat(__DIR__.'/../../../../LargeElectionData/'.$fileName.'.cvotes'))->setDataToAnElection();

            $resultArray = $election->getResult('Schulze STV')->getResultAsArray(true);
            sort($resultArray);

            self::assertSame($correctWinners, $resultArray, "Results for ".$fileName.".cvotes differs from the correct result.");
        }
    }
}
