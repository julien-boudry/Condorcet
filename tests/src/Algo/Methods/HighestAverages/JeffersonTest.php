<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result 1', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->setSeatsToElect(6);
    $this->election->authorizeVoteWeight = true;

    $this->election->parseVotes('A * 42; B ^31; C *15; D ^12');

    // Mix weight and number
    expect($this->election->getResult('Jefferson')->stats['Seats per Candidates'])->toBe(['A' => 3, 'B' => 2, 'C' => 1, 'D' => 0]);
});

test('result tideman a03', function (): void {
    $cef = new CondorcetElectionFormat(__DIR__ . '/' . 'A03.cvotes');
    $cef->setDataToAnElection($this->election);

    $this->election->setImplicitRankingRule(false);

    // Empty ranking was throw an error.
    $this->election->getResult('Jefferson');

    expect(true)->toBeTrue();
});
