<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});

test('smith set with clear condorcet winner', function (): void {
    $this->election->parseCandidates('A;B;C');
    $this->election->parseVotes('A>B>C');

    expect($this->election->getResult('Smith Set')->stats->getEntry('smith_set'))->toBe(['A']);
});

test('smith set with cycle', function (): void {
    $this->election->parseCandidates('A;B;C');
    $this->election->parseVotes('
        A>B
        B>C
        C>A
    ');

    expect($this->election->getResult('Smith Set')->stats->getEntry('smith_set'))->toEqualCanonicalizing(['A', 'B', 'C']);
});

test('smith set with tie in cycle', function (): void {
    // A beats B, B beats C, A ties C
    // Graph: A->B, B->C, A-C (tie)
    // Smith Set should be {A, B, C} because:
    // {A} -> fails (A doesn't beat C)
    // {A, B} -> fails (A doesn't beat C)
    // {B} -> fails (B beaten by A)
    // {C} -> fails (C beaten by B)

    $this->election->parseCandidates('A;B;C');
    $this->election->parseVotes('
        A > B > C
        C > B > A
        A > B
        B > C
        A > B
        C > A
    ');

    // Pairwise from reproduction:
    // A vs B: 4 - 2 (A wins)
    // A vs C: 3 - 3 (Tie)
    // B vs C: 4 - 2 (B wins)

    expect($this->election->getResult('Smith Set')->stats->getEntry('smith_set'))->toEqualCanonicalizing(['A', 'B', 'C']);
});

test('smith set with simple tie', function (): void {
    $this->election->parseCandidates('A;B');
    $this->election->parseVotes('A=B');

    expect($this->election->getResult('Smith Set')->stats->getEntry('smith_set'))->toEqualCanonicalizing(['A', 'B']);
});

test('smith set with one winner and a tie below', function (): void {
    $this->election->parseCandidates('A;B;C');
    // A beats B and C. B ties C.
    $this->election->parseVotes('
        A > B = C
    ');

    expect($this->election->getResult('Smith Set')->stats->getEntry('smith_set'))->toBe(['A']);
});
