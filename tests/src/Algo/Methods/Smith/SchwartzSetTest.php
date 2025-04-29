<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});

/**
 * Basic property of Schwartz set: when there's a Condorcet winner,
 * the Schwartz set contains only that candidate.
 */
test('schwartz set with clear condorcet winner', function (): void {
    $this->election->parseCandidates('A;B;C');
    $this->election->authorizeVoteWeight = true;

    $this->election->parseVotes('
        A>B>C ^ 6
        B>C>A ^ 3
        C>B>A ^ 2
    ');

    // A is Condorcet winner, so Schwartz set should contain only A
    $result = $this->election->getResult('Schwartz set');
    expect($result->stats->getEntry('schwartz_set'))->toBe(['A']);
    expect($result->rankingAsArrayString)->toBe([1 => 'A', 2 => ['B', 'C']]);
    expect($result->Winner->name)->toBe('A');
});

/**
 * Classic rock-paper-scissors pattern example, common in voting theory literature
 * Creates a cycle where A beats B, B beats C, and C beats A
 */
test('schwartz set with condorcet cycle', function (): void {
    $this->election->parseCandidates('A;B;C');
    $this->election->authorizeVoteWeight = true;
    $this->election->implicitRankingRule(false);

    // Créer un cycle parfait où chaque candidat en bat un autre
    $this->election->parseVotes('
        A>B * 6
        B>C * 6
        C>A * 6
    ');

    // All candidates are in a cycle, so all should be in the Schwartz set
    $result = $this->election->getResult('Schwartz set');
    expect($result->stats->getEntry('schwartz_set'))->toEqualCanonicalizing(['A', 'B', 'C']);
    expect($result->rankingAsArrayString)->toBe([1 => ['A', 'B', 'C']]);
});

/**
 * Adapted from the Smith set example on Wikipedia:
 * https://en.wikipedia.org/wiki/Smith_set
 */
test('schwartz set example from wikipedia', function (): void {
    $this->election->parseCandidates('A;B;C;D');
    $this->election->authorizeVoteWeight = true;

    $this->election->parseVotes('
        D>A>B>C ^40
        B>C>A>D ^35
        C>A>B>D ^25
    ');

    // Smith set should be {A,B,C}, and Schwartz set should match here
    expect($this->election->getResult('Schwartz set')->stats->getEntry('schwartz_set'))->toEqualCanonicalizing(['A', 'B', 'C']);
});

/**
 * Source: Schulze, Markus (2011).
 * "A new monotonic, clone-independent, reversal symmetric,
 * and Condorcet-consistent single-winner election method"
 */
test('schwartz set from schulze paper example', function (): void {
    $this->election->parseCandidates('A;B;C;D;E');

    $this->election->parseVotes('
        A>C>B>E>D * 5
        A>D>E>C>B * 5
        B>E>D>A>C * 8
        C>A>B>E>D * 3
        C>A>E>B>D * 7
        C>B>A>D>E * 2
        D>C>E>B>A * 7
        E>B>A>D>C * 8
    ');

    // Use consistent method to access Schwartz set results
    expect($this->election->getResult('Schwartz set')->stats->getEntry('schwartz_set'))->toContain('E');
});

/**
 * Constructed example to test multiple cycles
 * - One cycle: A beats B, B beats C, C beats A
 * - Second cycle: D beats E, E beats F, F beats D
 * - First cycle beats all members of second cycle
 * The Schwartz set should contain only members of the undominated first cycle
 */
test('schwartz set with multiple disjoint cycles', function (): void {
    $this->election->parseCandidates('A;B;C;D;E;F');
    $this->election->authorizeVoteWeight = true;
    $this->election->implicitRankingRule(false);

    // Define cycle for A, B, C
    $this->election->parseVotes('
        A>B ^10
        B>C ^10
        C>A ^10
    ');

    // Define cycle for D, E, F
    $this->election->parseVotes('
        D>E ^5
        E>F ^5
        F>D ^5
    ');

    // Ensure that every candidate in the first cycle beats every candidate in the second cycle
    $this->election->parseVotes('
        A>D ^8
        A>E ^8
        A>F ^8
        B>D ^8
        B>E ^8
        B>F ^8
        C>D ^8
        C>E ^8
        C>F ^8
    ');

    expect($this->election->getResult('Schwartz set')->stats->getEntry('schwartz_set'))
        ->toEqualCanonicalizing(['A', 'B', 'C']);
});

/**
 * Similar to examples on Electowiki:
 * https://electowiki.org/wiki/Schwartz_set
 */
test('schwartz set from electowiki', function (): void {
    $this->election->parseCandidates('A;B;C');
    $this->election->authorizeVoteWeight = true;

    $this->election->parseVotes('
        A>B ^ 49
        B>A ^ 3
        C>B ^ 48
    ');

    // The Schwartz set should be {B} (same as the Smith set in this case)
    expect($this->election->getResult('Schwartz set')->stats->getEntry('schwartz_set'))->toBe(['B']);
});

/**
 * Edge case: single candidate election
 */
test('schwartz set with single candidate', function (): void {
    $this->election->addCandidate('A');
    $this->election->addVote('A');

    expect($this->election->getResult('Schwartz set')->stats->getEntry('schwartz_set'))->toBe(['A']);
});

/**
 * Edge case: complete tie between all candidates
 */
test('schwartz set with complete tie', function (): void {
    $this->election->parseCandidates('A;B;C');
    $this->election->parseVotes('A=B=C * 10');

    // All candidates should be in the Schwartz set since none defeats another
    expect($this->election->getResult('Schwartz set')->stats->getEntry('schwartz_set'))->toEqualCanonicalizing(['A', 'B', 'C']);
});

/**
 * Check relationship between Smith and Schwartz sets
 * In some cases they are equal - when there are no defeats between candidates
 */
test('schwartz set equals smith set when no defeats between candidates', function (): void {
    $this->election->parseCandidates('A;B;C;D');

    $this->election->parseVotes('
        A>B>C>D * 10
        D>C>B>A * 10
    ');

    $smithSet = $this->election->getResult('Smith set')->stats->getEntry('smith_set');
    $schwartzSet = $this->election->getResult('Schwartz set')->stats->getEntry('schwartz_set');
    expect($schwartzSet)->toEqualCanonicalizing($smithSet);
});
