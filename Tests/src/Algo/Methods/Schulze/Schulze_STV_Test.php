<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat;
use PHPUnit\Framework\TestCase;

class Schulze_STV_Test extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
    {
        // Set Tideman A5C to the election object
        (new CondorcetElectionFormat(__DIR__.'/../../../../LargeElectionData/TidemanA53.cvotes'))->setDataToAnElection($this->election);

        // Compare results

        $resultsArray = $this->election->getResult('Schulze STV')->getOriginalResultArrayWithString();

        self::assertSame(
            expected: $this->election->getNumberOfSeats(),
            actual: count($resultsArray)
        );

        self::assertContains('J', $resultsArray);
        self::assertContains('A', $resultsArray);
        self::assertContains('G', $resultsArray);
        self::assertContains('D', $resultsArray);

        var_dump($resultsArray);
    }

}
