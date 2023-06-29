<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Lotteries;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

class RandomCandidatesTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
        $this->election->allowsVoteWeight();
        $this->election->parseCandidates('A;B;C');

        $this->election->setMethodOption('random candidates', 'Randomizer', new Randomizer(new Xoshiro256StarStar(hash('sha256', 'A random ballot Seed', true))));
    }

    public function testSimple(): void
    {
        $this->election->addVote('A>B>C');

        self::assertSame(
            expected: 'C > A > B',
            actual: $this->election->getResult('Random candidates')->getOriginalResultAsString()
        );

        // Test again, test cache
        self::assertSame(
            expected: 'C > A > B',
            actual: $this->election->getResult('Random candidates')->getOriginalResultAsString()
        );

        // Test tie probability
        $this->election->setMethodOption('Random Candidates', 'TiesProbability', $lastTiesProbability = 42.42);

        self::assertSame(
            expected: 'C > A = B',
            actual: ($lastResult = $this->election->getResult('Random candidates'))->getOriginalResultAsString()
        );

        $this->election->setMethodOption('Random Candidates', 'TiesProbability', 0);

        self::assertSame($lastTiesProbability, $lastResult->getStats()['Ties Probability']);
    }
}
