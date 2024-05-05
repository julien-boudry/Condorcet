<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\{Condorcet, Election};
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat;
use Tests\AlgoTestCase;

beforeEach(function (): void {
    AlgoTestCase::$tidemanA77 ?? (AlgoTestCase::$tidemanA77 = new DavidHillFormat(__DIR__ . '/TidemanData/A77.HIL'));
});

test('a77 with implicit', function (): void {
    $election = AlgoTestCase::$tidemanA77->setDataToAnElection();

    expect($election->countVotes())->toBe(213);
    expect($election->getNumberOfSeats())->toBe(1);

    expect($election->getVotesListAsString())->toBe(<<<'EOD'
        3 > 1 = 2 * 39
        1 > 3 > 2 * 38
        3 > 1 > 2 * 36
        3 > 2 > 1 * 29
        1 > 2 > 3 * 28
        2 > 1 > 3 * 15
        1 > 2 = 3 * 14
        2 > 3 > 1 * 9
        2 > 1 = 3 * 5
        EOD);
});

test('a77 with explicit', function (): void {
    $election = new Election;
    $election->setImplicitRanking(false);

    AlgoTestCase::$tidemanA77->setDataToAnElection($election);

    expect($election->countVotes())->toBe(213);

    expect($election->getVotesListAsString())->toBe(<<<'EOD'
        3 * 39
        1 > 3 * 38
        3 > 1 * 36
        3 > 2 * 29
        1 > 2 * 28
        2 > 1 * 15
        1 * 14
        2 > 3 * 9
        2 * 5
        EOD);
});

test('a1 for candidates names', function (): void {
    $election = (new DavidHillFormat(__DIR__ . '/TidemanData/A1.HIL'))->setDataToAnElection();

    expect($election->countVotes())->toBe(380);
    expect($election->getNumberOfSeats())->toBe(3);

    expect($election->getVotesListAsString())->toBe(<<<'EOD'
        Candidate  3 > Candidate  1 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 13
        Candidate  1 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 9
        Candidate  1 > Candidate  3 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 9
        Candidate  2 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 6
        Candidate  1 > Candidate  5 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 5
        Candidate  1 > Candidate  3 > Candidate  9 > Candidate  7 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 4
        Candidate  1 > Candidate  8 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 4
        Candidate  1 > Candidate  9 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 4
        Candidate  3 > Candidate  6 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  9 = Candidate 10 * 4
        Candidate  4 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 4
        Candidate  7 > Candidate  9 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 4
        Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 4
        Candidate  9 > Candidate  8 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 4
        Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 3
        Candidate  1 > Candidate  3 > Candidate  2 > Candidate  7 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 3
        Candidate  1 > Candidate  3 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 3
        Candidate  1 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 3
        Candidate  1 > Candidate  4 > Candidate  9 > Candidate  3 > Candidate  8 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 3
        Candidate  1 > Candidate  5 > Candidate  9 > Candidate  2 > Candidate  7 > Candidate 10 > Candidate  3 = Candidate  4 = Candidate  6 = Candidate  8 * 3
        Candidate  1 > Candidate  7 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 3
        Candidate  1 > Candidate  8 > Candidate  4 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 3
        Candidate  1 > Candidate  9 > Candidate  5 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 3
        Candidate  2 > Candidate  4 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 3
        Candidate  2 > Candidate  9 > Candidate  7 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 3
        Candidate  3 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 3
        Candidate  7 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 3
        Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 3
        Candidate  1 > Candidate  3 > Candidate  2 > Candidate  7 > Candidate  9 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 2
        Candidate  1 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 2
        Candidate  1 > Candidate  7 > Candidate  9 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 2
        Candidate  1 > Candidate  9 > Candidate  8 > Candidate  2 > Candidate  3 > Candidate  7 > Candidate  4 > Candidate 10 > Candidate  5 = Candidate  6 * 2
        Candidate  1 > Candidate 10 > Candidate  9 > Candidate  2 > Candidate  3 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  7 > Candidate  8 * 2
        Candidate  2 > Candidate  3 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 2
        Candidate  2 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
        Candidate  2 > Candidate  7 > Candidate  9 > Candidate  8 > Candidate 10 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 * 2
        Candidate  2 > Candidate  8 > Candidate  7 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 2
        Candidate  2 > Candidate  8 > Candidate  7 > Candidate  9 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 2
        Candidate  2 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 2
        Candidate  4 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
        Candidate  4 > Candidate  3 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 2
        Candidate  4 > Candidate  9 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate 10 * 2
        Candidate  6 > Candidate  7 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  9 = Candidate 10 * 2
        Candidate  6 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate  1 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate 10 * 2
        Candidate  7 > Candidate  1 > Candidate  3 > Candidate  9 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
        Candidate  8 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  2 > Candidate  5 > Candidate 10 > Candidate  9 > Candidate  7 > Candidate  6 * 2
        Candidate  8 > Candidate  1 > Candidate  7 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
        Candidate  8 > Candidate  1 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 2
        Candidate  8 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 2
        Candidate  8 > Candidate  2 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 2
        Candidate  8 > Candidate  2 > Candidate  6 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  9 = Candidate 10 * 2
        Candidate  8 > Candidate  2 > Candidate  7 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
        Candidate  8 > Candidate  3 > Candidate  1 > Candidate  6 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  9 = Candidate 10 * 2
        Candidate  8 > Candidate  3 > Candidate  1 > Candidate  6 > Candidate  9 > Candidate  7 > Candidate 10 > Candidate  2 = Candidate  4 = Candidate  5 * 2
        Candidate  9 > Candidate  1 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
        Candidate  9 > Candidate  2 > Candidate  7 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
        Candidate  9 > Candidate  4 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
        Candidate  9 > Candidate  4 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
        Candidate  9 > Candidate  4 > Candidate  3 > Candidate  1 > Candidate  7 > Candidate  8 > Candidate  6 > Candidate  2 > Candidate 10 > Candidate  5 * 2
        Candidate  9 > Candidate  7 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
        Candidate  9 > Candidate  8 > Candidate  3 > Candidate  6 > Candidate  7 > Candidate  2 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate 10 * 2
        Candidate  9 > Candidate  8 > Candidate  4 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 2
        Candidate  9 > Candidate 10 > Candidate  2 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 2
        Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 2
        Candidate 10 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 2
        Candidate 10 > Candidate  6 > Candidate  1 > Candidate  8 > Candidate  3 > Candidate  5 > Candidate  2 = Candidate  4 = Candidate  7 = Candidate  9 * 2
        Candidate 10 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 2
        Candidate  1 > Candidate  2 > Candidate  3 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate  5 > Candidate  6 > Candidate 10 > Candidate  4 * 1
        Candidate  1 > Candidate  2 > Candidate  4 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  2 > Candidate  4 > Candidate  3 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  2 > Candidate  4 > Candidate  5 > Candidate  8 > Candidate 10 > Candidate  3 = Candidate  6 = Candidate  7 = Candidate  9 * 1
        Candidate  1 > Candidate  2 > Candidate  5 > Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  4 > Candidate  8 > Candidate  6 > Candidate  5 > Candidate  7 > Candidate  9 > Candidate 10 > Candidate  2 * 1
        Candidate  1 > Candidate  3 > Candidate  4 > Candidate  9 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  4 > Candidate  9 > Candidate  5 > Candidate  6 > Candidate 10 > Candidate  2 > Candidate  8 > Candidate  7 * 1
        Candidate  1 > Candidate  3 > Candidate  5 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  5 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  6 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  7 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  7 > Candidate  8 > Candidate 10 > Candidate  9 > Candidate  2 > Candidate  6 > Candidate  4 > Candidate  5 * 1
        Candidate  1 > Candidate  3 > Candidate  7 > Candidate  9 > Candidate  2 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  8 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  1 > Candidate  3 > Candidate  9 > Candidate  8 > Candidate  4 > Candidate  2 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  1 > Candidate  3 > Candidate  9 > Candidate 10 > Candidate  7 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 * 1
        Candidate  1 > Candidate  4 > Candidate  3 > Candidate  6 > Candidate  8 > Candidate  5 > Candidate  2 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  4 > Candidate  3 > Candidate  6 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  1 > Candidate  4 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  4 > Candidate  5 > Candidate  8 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  9 * 1
        Candidate  1 > Candidate  4 > Candidate  5 > Candidate  9 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  1 > Candidate  4 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  4 > Candidate  8 > Candidate  7 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  4 > Candidate  9 > Candidate 10 > Candidate  6 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  7 = Candidate  8 * 1
        Candidate  1 > Candidate  4 > Candidate 10 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  1 > Candidate  5 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  5 > Candidate  4 > Candidate  9 > Candidate  2 > Candidate  3 > Candidate  7 > Candidate  6 > Candidate  8 > Candidate 10 * 1
        Candidate  1 > Candidate  6 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  6 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  7 > Candidate  9 > Candidate  6 > Candidate  2 > Candidate  3 > Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
        Candidate  1 > Candidate  7 > Candidate  9 > Candidate  8 > Candidate  3 > Candidate  4 > Candidate  6 > Candidate 10 > Candidate  5 > Candidate  2 * 1
        Candidate  1 > Candidate  7 > Candidate  9 > Candidate  8 > Candidate  6 > Candidate  3 > Candidate  2 > Candidate 10 > Candidate  4 > Candidate  5 * 1
        Candidate  1 > Candidate  8 > Candidate  3 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  1 > Candidate  8 > Candidate  3 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  1 > Candidate  8 > Candidate  6 > Candidate  4 > Candidate  2 > Candidate  3 > Candidate  5 > Candidate 10 > Candidate  9 > Candidate  7 * 1
        Candidate  1 > Candidate  8 > Candidate  9 > Candidate  3 > Candidate  4 > Candidate  2 > Candidate  6 > Candidate  5 > Candidate  7 > Candidate 10 * 1
        Candidate  1 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  1 > Candidate  9 > Candidate  2 > Candidate  4 > Candidate  8 > Candidate 10 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  1 > Candidate  9 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  1 > Candidate  9 > Candidate  4 > Candidate  7 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  1 > Candidate  9 > Candidate  7 > Candidate  4 > Candidate  5 > Candidate  2 > Candidate 10 > Candidate  8 > Candidate  6 > Candidate  3 * 1
        Candidate  1 > Candidate  9 > Candidate  7 > Candidate  6 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
        Candidate  1 > Candidate  9 > Candidate  8 > Candidate  3 > Candidate 10 > Candidate  2 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  7 * 1
        Candidate  1 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate  1 > Candidate  9 > Candidate 10 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate  1 > Candidate  9 > Candidate 10 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate  1 > Candidate  9 > Candidate 10 > Candidate  4 > Candidate  7 > Candidate  3 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 * 1
        Candidate  1 > Candidate  9 > Candidate 10 > Candidate  4 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  1 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  4 > Candidate  3 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  1 > Candidate 10 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  1 > Candidate 10 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  1 > Candidate 10 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate  1 > Candidate 10 > Candidate  9 > Candidate  2 > Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate  1 > Candidate 10 > Candidate  9 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate  2 > Candidate  3 > Candidate  1 > Candidate  4 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  5 > Candidate  6 > Candidate  7 * 1
        Candidate  2 > Candidate  3 > Candidate  9 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  2 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  2 > Candidate  4 > Candidate  9 > Candidate  7 > Candidate  3 > Candidate  8 > Candidate 10 > Candidate  6 > Candidate  1 > Candidate  5 * 1
        Candidate  2 > Candidate  5 > Candidate  3 > Candidate  1 > Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  2 > Candidate  7 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  2 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  2 > Candidate  8 > Candidate  9 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  2 > Candidate  8 > Candidate 10 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 1
        Candidate  2 > Candidate  9 > Candidate  1 > Candidate 10 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  2 > Candidate  9 > Candidate  7 > Candidate  4 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  2 > Candidate  9 > Candidate  7 > Candidate  8 > Candidate  6 > Candidate 10 > Candidate  5 > Candidate  4 > Candidate  3 > Candidate  1 * 1
        Candidate  3 > Candidate  1 > Candidate  2 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate 10 * 1
        Candidate  3 > Candidate  1 > Candidate  5 > Candidate  2 > Candidate  4 > Candidate  7 > Candidate  6 > Candidate  9 > Candidate  8 > Candidate 10 * 1
        Candidate  3 > Candidate  1 > Candidate  6 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  3 > Candidate  1 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  3 > Candidate  4 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  3 > Candidate  4 > Candidate  5 > Candidate  8 > Candidate 10 > Candidate  1 > Candidate  2 > Candidate  9 > Candidate  6 > Candidate  7 * 1
        Candidate  3 > Candidate  4 > Candidate  9 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  3 > Candidate  4 > Candidate 10 > Candidate  1 > Candidate  6 > Candidate  2 > Candidate  5 > Candidate  8 > Candidate  9 > Candidate  7 * 1
        Candidate  3 > Candidate  5 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  3 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  3 > Candidate  7 > Candidate  1 > Candidate  5 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  3 > Candidate  8 > Candidate  2 > Candidate  7 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
        Candidate  3 > Candidate  9 > Candidate  1 > Candidate  8 > Candidate  7 > Candidate  5 > Candidate  4 > Candidate  2 = Candidate  6 = Candidate 10 * 1
        Candidate  3 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  4 > Candidate  1 > Candidate  3 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  4 > Candidate  1 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  4 > Candidate  2 > Candidate  7 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  4 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  4 > Candidate  3 > Candidate  1 > Candidate  2 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  6 > Candidate  7 > Candidate  5 * 1
        Candidate  4 > Candidate  3 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  4 > Candidate  3 > Candidate  8 > Candidate  1 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 1
        Candidate  4 > Candidate  3 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  4 > Candidate  5 > Candidate  1 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 * 1
        Candidate  4 > Candidate  5 > Candidate  7 > Candidate 10 > Candidate  1 > Candidate  2 > Candidate  8 > Candidate  3 = Candidate  6 = Candidate  9 * 1
        Candidate  4 > Candidate  5 > Candidate  8 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  4 > Candidate  5 > Candidate  9 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  4 > Candidate  7 > Candidate 10 > Candidate  3 > Candidate  8 > Candidate  1 > Candidate  2 > Candidate  6 > Candidate  5 > Candidate  9 * 1
        Candidate  4 > Candidate  8 > Candidate  1 > Candidate  3 > Candidate  5 > Candidate  6 > Candidate 10 > Candidate  2 > Candidate  9 > Candidate  7 * 1
        Candidate  4 > Candidate  9 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  4 > Candidate  9 > Candidate  2 > Candidate  7 > Candidate  3 > Candidate  5 > Candidate 10 > Candidate  6 > Candidate  1 > Candidate  8 * 1
        Candidate  4 > Candidate  9 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  4 > Candidate 10 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  4 > Candidate 10 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  4 > Candidate 10 > Candidate  5 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  4 > Candidate 10 > Candidate  9 > Candidate  3 > Candidate  1 > Candidate  2 > Candidate  5 > Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate  5 > Candidate  8 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  5 > Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate  6 > Candidate  3 > Candidate  4 > Candidate  1 > Candidate  2 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  6 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  6 > Candidate  7 > Candidate  4 > Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  8 = Candidate  9 * 1
        Candidate  6 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate 10 * 1
        Candidate  6 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  6 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 * 1
        Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  7 > Candidate  1 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 * 1
        Candidate  7 > Candidate  1 > Candidate  9 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 * 1
        Candidate  7 > Candidate  2 > Candidate  6 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  7 > Candidate  2 > Candidate  9 > Candidate  6 > Candidate  8 > Candidate  3 > Candidate 10 > Candidate  5 > Candidate  1 > Candidate  4 * 1
        Candidate  7 > Candidate  2 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  7 > Candidate  2 > Candidate  9 > Candidate  8 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  7 > Candidate  3 > Candidate  4 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
        Candidate  7 > Candidate  4 > Candidate  1 > Candidate  2 > Candidate  3 > Candidate  5 > Candidate  6 > Candidate  8 > Candidate  9 > Candidate 10 * 1
        Candidate  7 > Candidate  4 > Candidate  2 > Candidate  9 > Candidate  8 > Candidate  1 > Candidate  3 > Candidate  6 > Candidate 10 > Candidate  5 * 1
        Candidate  7 > Candidate  6 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
        Candidate  7 > Candidate  8 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
        Candidate  7 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  7 > Candidate  9 > Candidate  6 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate 10 * 1
        Candidate  7 > Candidate 10 > Candidate  1 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate  9 > Candidate  2 = Candidate  5 = Candidate  6 * 1
        Candidate  8 > Candidate  1 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  8 > Candidate  1 > Candidate  9 > Candidate  4 > Candidate  3 > Candidate  2 > Candidate 10 > Candidate  5 > Candidate  6 > Candidate  7 * 1
        Candidate  8 > Candidate  2 > Candidate  7 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
        Candidate  8 > Candidate  4 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
        Candidate  8 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
        Candidate  8 > Candidate  7 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
        Candidate  8 > Candidate  7 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  8 > Candidate  7 > Candidate  9 > Candidate 10 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 * 1
        Candidate  8 > Candidate  9 > Candidate  1 > Candidate  2 > Candidate  7 > Candidate  4 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  8 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  8 > Candidate 10 > Candidate  3 > Candidate  1 > Candidate  2 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 1
        Candidate  8 > Candidate 10 > Candidate  7 > Candidate  6 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 * 1
        Candidate  8 > Candidate 10 > Candidate  9 > Candidate  7 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 * 1
        Candidate  9 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  7 > Candidate 10 > Candidate  8 > Candidate  6 > Candidate  2 > Candidate  5 * 1
        Candidate  9 > Candidate  1 > Candidate  4 > Candidate  3 > Candidate  7 > Candidate  8 > Candidate  2 > Candidate 10 > Candidate  6 > Candidate  5 * 1
        Candidate  9 > Candidate  1 > Candidate  7 > Candidate  3 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  9 > Candidate  1 > Candidate  8 > Candidate  3 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  9 > Candidate  2 > Candidate  7 > Candidate  3 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  8 > Candidate  1 > Candidate 10 * 1
        Candidate  9 > Candidate  2 > Candidate  7 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  2 > Candidate  7 > Candidate  8 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  9 > Candidate  2 > Candidate  7 > Candidate  8 > Candidate  6 > Candidate 10 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 * 1
        Candidate  9 > Candidate  2 > Candidate 10 > Candidate  6 > Candidate  5 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  7 = Candidate  8 * 1
        Candidate  9 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  3 > Candidate  4 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  4 > Candidate  1 > Candidate  2 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  4 > Candidate  8 > Candidate  1 > Candidate  2 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  9 > Candidate  4 > Candidate  8 > Candidate  1 > Candidate  3 > Candidate  7 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  9 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  6 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  6 > Candidate  7 > Candidate  2 > Candidate  3 > Candidate  4 > Candidate  5 > Candidate  1 > Candidate  8 > Candidate 10 * 1
        Candidate  9 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  7 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  7 > Candidate  2 > Candidate  1 > Candidate  5 > Candidate  3 = Candidate  4 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  7 > Candidate  2 > Candidate  6 > Candidate  8 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate 10 * 1
        Candidate  9 > Candidate  7 > Candidate  2 > Candidate 10 > Candidate  3 > Candidate  1 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
        Candidate  9 > Candidate  7 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
        Candidate  9 > Candidate  7 > Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
        Candidate  9 > Candidate  8 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
        Candidate  9 > Candidate  8 > Candidate  3 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  9 > Candidate  8 > Candidate  4 > Candidate  1 > Candidate  3 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 * 1
        Candidate  9 > Candidate  8 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate 10 * 1
        Candidate  9 > Candidate  8 > Candidate  6 > Candidate  3 > Candidate  2 > Candidate  7 > Candidate 10 > Candidate  1 > Candidate  4 > Candidate  5 * 1
        Candidate  9 > Candidate  8 > Candidate  7 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  9 > Candidate  8 > Candidate  7 > Candidate  2 > Candidate  1 > Candidate 10 > Candidate  3 > Candidate  4 > Candidate  6 > Candidate  5 * 1
        Candidate  9 > Candidate  8 > Candidate  7 > Candidate  2 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
        Candidate  9 > Candidate 10 > Candidate  2 > Candidate  6 > Candidate  7 > Candidate  8 > Candidate  4 > Candidate  5 > Candidate  1 > Candidate  3 * 1
        Candidate  9 > Candidate 10 > Candidate  7 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
        Candidate  9 > Candidate 10 > Candidate  7 > Candidate  4 > Candidate  1 > Candidate  2 > Candidate  6 > Candidate  3 > Candidate  5 > Candidate  8 * 1
        Candidate 10 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  2 > Candidate  5 > Candidate  6 > Candidate  9 > Candidate  8 > Candidate  7 * 1
        Candidate 10 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate 10 > Candidate  3 > Candidate  5 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate 10 > Candidate  4 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate 10 > Candidate  4 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
        Candidate 10 > Candidate  4 > Candidate  1 > Candidate  9 > Candidate  5 > Candidate  3 > Candidate  2 > Candidate  6 > Candidate  7 > Candidate  8 * 1
        Candidate 10 > Candidate  4 > Candidate  9 > Candidate  1 > Candidate  3 > Candidate  2 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate 10 > Candidate  7 > Candidate  8 > Candidate  4 > Candidate  5 > Candidate  1 > Candidate  2 > Candidate  9 > Candidate  6 > Candidate  3 * 1
        Candidate 10 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
        Candidate 10 > Candidate  9 > Candidate  4 > Candidate  8 > Candidate  6 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  7 * 1
        Candidate 10 > Candidate  9 > Candidate  7 > Candidate  3 > Candidate  2 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
        EOD);

    expect($election->getResult('STV')->getResultAsString())->toBe('Candidate  1 > Candidate  9 > Candidate  8');
});

test('bug david hill random order and stats round', function (): void {
    $hil = new DavidHillFormat(__DIR__ . '/TidemanData/A60.HIL');

    expect($hil->candidates)->toEqual([0 => '1', 1 => '2', 2 => '3', 3 => '4', 4 => '5', 5 => '6']);

    # Candidates are object, AssertEquals compare __toString
    $implicitElectionFromHill = $hil->setDataToAnElection();

    // Without aggregate vote
    $file = new SplTempFileObject;
    $file->fwrite(CondorcetElectionFormat::createFromElection(election: $implicitElectionFromHill, aggregateVotes: false));
    $implicitElectionFromCondorcetElection = (new CondorcetElectionFormat($file))->setDataToAnElection();

    expect($implicitElectionFromCondorcetElection->getCandidatesListAsString())->toEqual($implicitElectionFromHill->getCandidatesListAsString());

    foreach (Condorcet::getAuthMethods() as $method) {
        if (!Condorcet::getMethodClass($method)::IS_DETERMINISTIC) {
            continue;
        }

        // Stats
        expect($implicitElectionFromCondorcetElection->getResult($method)->getStats(), 'Method: ' . $method)->toBe($implicitElectionFromHill->getResult($method)->getStats());

        // Result
        expect($implicitElectionFromCondorcetElection->getResult($method)->getResultAsString(), 'Method: ' . $method)->toBe($implicitElectionFromHill->getResult($method)->getResultAsString());
    }

    // With aggregate vote
    $file = new SplTempFileObject;
    $file->fwrite(CondorcetElectionFormat::createFromElection(election: $implicitElectionFromHill, aggregateVotes: true));
    $implicitElectionFromCondorcetElection = (new CondorcetElectionFormat($file))->setDataToAnElection();

    expect($implicitElectionFromCondorcetElection->getCandidatesListAsString())->toEqual($implicitElectionFromHill->getCandidatesListAsString());

    foreach (Condorcet::getAuthMethods() as $method) {
        if (!Condorcet::getMethodClass($method)::IS_DETERMINISTIC) {
            continue;
        }

        // Stats
        expect($implicitElectionFromCondorcetElection->getResult($method)->getStats())
            ->toEqualWithDelta(
                expected: $implicitElectionFromHill->getResult($method)->getStats(),
                delta: 1 / (0.1 ** Condorcet::getMethodClass($method)::DECIMAL_PRECISION),
                message: 'Method: ' . $method
            );

        // Result
        expect($implicitElectionFromCondorcetElection->getResult($method)->getResultAsString(), 'Method: ' . $method)->toBe($implicitElectionFromHill->getResult($method)->getResultAsString());
    }
});
