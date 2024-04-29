<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Election;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

beforeEach(function (): void {
    $this->election = new Election;
    $this->election->allowsVoteWeight();
    $this->election->parseCandidates('A;B;C');

    $this->election->setMethodOption('random candidates', 'Randomizer', new Randomizer(new Xoshiro256StarStar(hash('sha256', 'A random ballot Seed', true))));
});

test('simple', function (): void {
    $this->election->addVote('A>B>C');

    expect($this->election->getResult('Random candidates')->getOriginalResultAsString())->toBe('C > A > B');

    // Test again, test cache
    expect($this->election->getResult('Random candidates')->getOriginalResultAsString())->toBe('C > A > B');

    // Test tie probability
    $this->election->setMethodOption('Random Candidates', 'TiesProbability', $lastTiesProbability = 42.42);

    expect(($lastResult = $this->election->getResult('Random candidates'))->getOriginalResultAsString())->toBe('C > A = B');

    $this->election->setMethodOption('Random Candidates', 'TiesProbability', 0);

    expect($lastResult->getStats()['Ties Probability'])->toBe($lastTiesProbability);
});
