<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});
afterEach(function (): void {
    $this->election->setMethodOption('Borda Count', 'Starting', 1);
});

test('result 1', function (): void {
    # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A>B>C>D * 42
            B>C>D>A * 26
            C>D>B>A * 15
            D>C>B>A * 17
        ');

    expect($this->election->getResult('Borda Count')->rankingAsArrayString)->toBe([
        1 => 'B',
        2 => 'C',
        3 => 'A',
        4 => 'D', ]);

    expect($this->election->getResult('Borda Count')->stats->asArray)->toEqual([
        'B' => 294,
        'C' => 273,
        'A' => 226,
        'D' => 207, ]);
});

test('result 2', function (): void {
    # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->authorizeVoteWeight = true;

    $this->election->parseVotes('
            B>A>C>D * 30
            B>A>D>C * 30
            A>C>D>B * 25
            A>D>C>B ^ 15
        ');

    expect($this->election->getResult('Borda Count')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D', ]);

    expect($this->election->getResult('Borda Count')->stats->asArray)->toEqual([
        'A' => 340,
        'B' => 280,
        'C' => 195,
        'D' => 185, ]);
});

test('result 3', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');

    $this->election->parseVotes('
            A
        ');

    expect($this->election->getResult('Borda Count')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => ['B', 'C'], ]);

    expect($this->election->getResult('Borda Count')->stats->asArray)->toEqual([
        'A' => 3,
        'B' => 1.5,
        'C' => 1.5, ]);

    $this->election->implicitRankingRule(false);

    expect($this->election->getResult('Borda Count')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => ['B', 'C'], ]);

    expect($this->election->getResult('Borda Count')->stats->asArray)->toEqual([
        'A' => 3,
        'B' => 0,
        'C' => 0, ]);
});

test('result 4', function (): void {
    # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A>B>C>D * 42
            B>C>D>A * 26
            C>D>B>A * 15
            D>C>B>A * 17
        ');

    expect($this->election->getResult('Borda Count')->rankingAsArrayString)->toBe([
        1 => 'B',
        2 => 'C',
        3 => 'A',
        4 => 'D', ]);

    expect($this->election->getResult('Borda Count')->stats->asArray)->toEqual([
        'B' => 294,
        'C' => 273,
        'A' => 226,
        'D' => 207, ]);
});

test('result variant', function (): void {
    # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A>B>C>D * 42
            B>C>D>A * 26
            C>D>B>A * 15
            D>C>B>A * 17
        ');

    $this->election->setMethodOption('Borda Count', 'Starting', 0);

    expect($this->election->getResult('Borda Count')->rankingAsArrayString)->toBe([
        1 => 'B',
        2 => 'C',
        3 => 'A',
        4 => 'D', ]);

    expect($this->election->getResult('Borda Count')->stats->asArray)->toEqual([
        'B' => 294 - 100,
        'C' => 273 - 100,
        'A' => 226 - 100,
        'D' => 207 - 100, ]);
});

test('very high vote weight and performances', function (): void {
    $this->election->authorizeVoteWeight = true;
    $this->election->parseCandidates('0;1');

    $this->election->parseVotes('1 > 0 ^6973568802');

    expect($this->election->getResult('Borda Count')->rankingAsString)->toBe('1');
});
