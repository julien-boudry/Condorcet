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

    # From https://en.wikipedia.org/wiki/CPO-STV
    public function testSchulzeStvTideman(): void
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
