<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class GeckoTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
    {        
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->setImplicitRanking(false);

        $this->election->parseVotes('
            A > B > C * 42
            C > B > A * 42
            D > B > A > C
        ');

        self::assertSame(
            [
                1 => 'D',
                2 => 'B',
                3 => 'A',
                4 => 'C' 
            ],
            $this->election->getResult('Gecko')->getResultAsArray(true)
        );
    }
}
