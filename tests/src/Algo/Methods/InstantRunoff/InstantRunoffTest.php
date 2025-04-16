<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result 1', function (): void {
    # From https://fr.wikipedia.org/wiki/Vote_alternatif
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

    expect($this->election->getResult('InstantRunoff')->rankingAsArrayString)->toBe([
        1 => 'D',
        2 => 'A',
        3 => 'B',
        4 => 'C', ]);

    expect($this->election->getResult('InstantRunoff')->stats->asArray)->toBe([
        'majority' => 50.0,
        'rounds' => [
            1 => [
                'A' => 42,
                'B' => 26,
                'C' => 15,
                'D' => 17,
            ],
            2 => [
                'A' => 42,
                'B' => 26,
                'D' => 32,
            ],
            3 => [
                'A' => 42,
                'D' => 58,
            ],
        ],
    ]);
});

test('result 2', function (): void {
    # From https://en.wikipedia.org/wiki/Instant-runoff_voting#Examples
    $this->election->addCandidate('bob');
    $this->election->addCandidate('sue');
    $this->election->addCandidate('bill');

    $this->election->parseVotes('
            bob > bill > sue
            sue > bob > bill
            bill > sue > bob
            bob > bill > sue
            sue > bob > bill
        ');

    expect($this->election->getResult('InstantRunoff')->rankingAsArrayString)->toBe([
        1 => 'sue',
        2 => 'bob',
        3 => 'bill', ]);
});

test('result 3', function (): void {
    $this->election->addCandidate('bob');
    $this->election->addCandidate('sue');
    $this->election->addCandidate('bill');

    $this->election->parseVotes('
            bob > bill > sue
            sue > bob > bill
            bill > sue > bob
            bob > bill > sue
            sue > bob > bill
            bill > bob > sue
        ');

    expect($this->election->getResult('InstantRunoff')->rankingAsArrayString)->toBe([
        1 => 'bob',
        2 => 'bill',
        3 => 'sue', ]);
});

test('result 4', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');

    $this->election->parseVotes('
            A=B=C
        ');

    expect($this->election->getResult('InstantRunoff')->rankingAsArrayString)->toBe([
        1 => ['A', 'B', 'C'], ]);
});

test('result equality', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');

    $this->election->parseVotes('
            A
            B
        ');

    expect($this->election->getResult('InstantRunoff')->rankingAsArrayString)->toBe([
        1 => ['A', 'B'], ]);
});

test('result tie breaking', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A * 4
            B * 4
            C>A * 2
            D>C>B * 2
        ');

    expect($this->election->getResult('InstantRunoff')->rankingAsArrayString)->toBe([
        1 => ['A', 'B'],
        3 => 'C',
        4 => 'D',
    ]);
});

test('infinite loop on tideman dataset3 if explicit ranking', function (): void {
    $election = new DavidHillFormat(__DIR__ . '/../../../Tools/Converters/TidemanData/A3.HIL')->setDataToAnElection();

    $election->setImplicitRanking(false);

    expect($election->getResult('InstantRunoff')->rankingAsString)->toBe('6 > 8 > 4 > 11 > 2 > 5 > 14 > 1 = 7 > 12 > 3 > 9 > 10 > 15 > 13');
});
