<?php declare(strict_types=1);


use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});

test('simple smith set example', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');

    $this->election->parseVotes('
        A > B > C * 8
        B > C > A * 7
        C > A > B * 6
    ');

    $result = $this->election->getResult('Smith');

    // In this cycling case, all three candidates should be in the Smith set
    expect($result->stats['smith_set'])->toHaveCount(3);
    expect($result->stats['smith_set'])->toContain((string) $candidateA);
    expect($result->stats['smith_set'])->toContain((string) $candidateB);
    expect($result->stats['smith_set'])->toContain((string) $candidateC);

    // All candidates should be ranked equally at rank 1
    expect($result->ranking)->toHaveCount(1)->toHaveKey(1);
    expect($result->ranking[1])->toBe([$candidateA, $candidateB, $candidateC])->toBe($result->Winner);
});

test('smith set with condorcet winner', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');

    $this->election->parseVotes('
        A > B > C * 10
        A > C > B * 8
        B > A > C * 5
        C > B > A * 4
    ');

    $result = $this->election->getResult('Smith');

    // With a Condorcet winner (A), the Smith set should contain just that winner
    expect($result->stats['smith_set'])->toHaveCount(1);
    expect($result->stats['smith_set'][0])->toBe((string) $candidateA);

    expect($result->ranking)->toHaveCount(2)->toHaveKeys([1, 2]);
    expect($result->ranking[1])->toBe([$candidateA])->toContain($result->Winner);
    expect($result->ranking[2])->toBe([$candidateB, $candidateC])->toBe($result->Loser);
});

test('smith set with two candidates', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');

    $this->election->parseVotes('
        A > B * 10
        B > A * 8
    ');

    $result = $this->election->getResult('Smith');

    // A beats B, so the Smith set should contain just A
    expect($result->stats['smith_set'])->toHaveCount(1);
    expect($result->stats['smith_set'][0])->toBe((string) $candidateA);

    // A beats B, so the Smith set should contain just A
    expect($result->ranking)->toHaveCount(2)->toHaveKeys([1, 2]);
    expect($result->ranking[1])->toBe([$candidateA])->toContain($result->Winner);
    expect($result->ranking[2])->toBe([$candidateB])->toContain($result->Loser);
});

test('smith set with a clear top set', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
        A > B > C > D * 10
        B > A > D > C * 10
        C > D > A > B * 8
        D > C > B > A * 8
    ');

    $result = $this->election->getResult('Smith');

    // A and B beat C and D, but A and B are in a cycle
    // So the Smith set should contain A and B
    expect($result->stats['smith_set'])->toHaveCount(2);
    expect($result->stats['smith_set'])->toContain((string) $candidateA);
    expect($result->stats['smith_set'])->toContain((string) $candidateB);

    // A and B should be ranked equally at rank 1, C and D at rank 2
    expect($result->ranking)->toHaveCount(2);
    expect($result->rankingAsArray[1])->toBe([$candidateA, $candidateB])->toBe($result->Winner);
    expect($result->rankingAsArray[2])->toBe([$candidateC, $candidateD])->toBe($result->Loser);
});

// Example from https://electowiki.org/wiki/Smith_set
test('from electowiki 1', function (): void {
    $this->election->parseCandidates('A;B;C');
    $this->election->allowsVoteWeight();

    $this->election->parseVotes('
        A>B ^ 49
        B>A ^ 3
        C>B ^ 48
    ');

    expect($this->election->getResult('Smith')->rankingAsString)->toBe('B > A = C');
});

// Example from https://en.wikipedia.org/wiki/Smith_set
test('from wikipedia 1', function (): void {
    $this->election->parseCandidates('A;B;C;D');
    $this->election->allowsVoteWeight();

    $this->election->parseVotes('
        D>A>B>C ^40
        B>C>A>D ^35
        C>A>B>D ^25
    ');

    expect($this->election->getResult('Smith')->rankingAsString)->toBe('A = B = C > D');
});
