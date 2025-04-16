<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\{Election, Vote};
use CondorcetPHP\Condorcet\Algo\Methods\CondorcetBasic;
use CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result basic', function (): void {
    $this->election->addCandidate('a');
    $this->election->addCandidate('b');
    $this->election->addCandidate('c');

    $vote = new Vote('a');

    $this->election->addVote($vote);

    expect($this->election->getCondorcetWinner())->toEqual('a');
});

test('result 1', function (): void {
    $this->election->addCandidate('a');
    $this->election->addCandidate('b');
    $this->election->addCandidate('c');

    $this->election->parseVotes('
            a > c > b * 23
            b > c > a * 19
            c > b > a * 16
            c > a > b * 2
        ');

    expect($this->election->getCondorcetWinner())->toEqual('c');
});

test('result 2', function (): void {
    $this->election->addCandidate('X');
    $this->election->addCandidate('Y');
    $this->election->addCandidate('Z');

    $this->election->parseVotes('
            X > Y > Z * 41
            Y > Z > X * 33
            Z > X > Y * 22
        ');

    expect($this->election->getWinner())->toBeNull();

    // Schulze Substitution
    expect($this->election->getWinner('Schulze'))->toEqual('X');
});

test('result 3', function (): void {
    $this->election->addCandidate('Memphis');
    $this->election->addCandidate('Nashville');
    $this->election->addCandidate('Knoxville');
    $this->election->addCandidate('Chattanooga');

    $this->election->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

    expect($this->election->getCondorcetWinner())->toEqual('Nashville');
    expect($this->election->getCondorcetLoser())->toEqual('Memphis');
});

test('result 4', function (): void {
    $this->election->addCandidate('Memphis');
    $this->election->addCandidate('Nashville');
    $this->election->addCandidate('Knoxville');
    $this->election->addCandidate('Chattanooga');

    $this->election->parseVotes('
            Memphis > Chattanooga > Nashville * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

    expect($this->election->getCondorcetWinner())->toEqual('Chattanooga');
});

test('result 5', function (): void {
    # From https://en.wikipedia.org/wiki/Condorcet_loser_criterion
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('L');

    $this->election->parseVotes('
            A > B > C * 1
            A > B > L * 1
            B > C > A * 3
            C > L > A * 1
            L > A > B * 1
            L > C > A * 2
        ');

    expect($this->election->getCondorcetLoser())->toEqual('L');
    expect($this->election->getCondorcetWinner())->toBeNull();
});

test('result 6', function (): void {
    # From https://en.wikipedia.org/wiki/Condorcet_loser_criterion
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('L');

    $this->election->parseVotes('
            A > B > L
            B > C > L
            A > C > L
        ');

    expect($this->election->getCondorcetLoser())->toEqual('L');
});

test('no result object', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('L');

    $this->election->parseVotes('
            A > B > L
            B > C > L
            A > C > L
        ');

    $this->expectException(AlgorithmWithoutRankingFeatureException::class);
    $this->expectExceptionMessage("This algortihm can't provide a full ranking (but only Winner and Loser): " . CondorcetBasic::METHOD_NAME[0]);

    $this->election->getResult(CondorcetBasic::class);
});
