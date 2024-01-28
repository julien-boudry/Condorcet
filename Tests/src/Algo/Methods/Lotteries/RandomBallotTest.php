<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Lotteries;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

class RandomBallotTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
        $this->election->allowsVoteWeight();
        $this->election->parseCandidates('A;B;C');

        $this->election->setMethodOption('random ballot', 'Randomizer', new Randomizer(new Xoshiro256StarStar(hash('sha256', 'A random ballot Seed', true))));
    }

    public function testSimple(): void
    {
        $this->election->parseVotes('
            A > B > C
            B > A > C
            C > A > B ^2
            A = B = C ^3
        ');

        expect($this->election->countVotes())->toBe(4);
        expect($this->election->sumValidVotesWeightWithConstraints())->toBe(7);

        expect($this->election->getResult('Random ballot')->getResultAsString())->toBe('A = B = C');

        // Cache must continue to stabilize result
        for ($i = 0; $i < 4; $i++) {
            expect($this->election->getResult('Random ballot')->getResultAsString())->toBe('A = B = C');
        }

        expect($this->election->getResult('Random ballot')->getStats())->toBe([
            'Elected Weight Level' => 6,
            'Elected Ballot Key' => 3,
        ]);

        $this->election->cleanupCalculator();

        expect($this->election->getResult('Random ballot')->getResultAsString())->toBe('C > A > B');

        expect($this->election->getResult('Random ballot')->getStats())->toBe([
            'Elected Weight Level' => 4,
            'Elected Ballot Key' => 2,
        ]);

        $this->election->cleanupCalculator();
    }
}
