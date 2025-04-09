<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result 1', function (): void {
    # From https://en.wikipedia.org/wiki/Copeland%27s_method
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

    expect($this->election->getResult('Copeland')->rankingAsArrayString)->toBe([
        1 => 'Nashville',
        2 => 'Chattanooga',
        3 => 'Knoxville',
        4 => 'Memphis', ]);

    expect($this->election->getWinner())->toBe($this->election->getWinner('Copeland'));

    expect($this->election->getResult('Copeland')->stats->asArray)->toBe([
        'Memphis' => [
            'balance' => -3,
        ],
        'Nashville' => [
            'balance' => 3,
        ],
        'Knoxville' => [
            'balance' => -1,
        ],
        'Chattanooga' => [
            'balance' => 1,
        ],
    ]);
});

test('result 2', function (): void {
    # From https://en.wikipedia.org/wiki/Copeland%27s_method
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');
    $candidateE = $this->election->addCandidate('E');

    $this->election->parseVotes('
            A > E > C > D * 31
            B > A > E * 30
            C > D > B * 29
            D > A > E * 10
        ');

    expect($this->election->getWinner())->toBeNull();
    expect($this->election->getWinner('Copeland'))->toBe($candidateA);

    expect($this->election->getResult('Copeland')->rankingAsArray)->toBe([1 => $candidateA,
        2 => [$candidateB, $candidateC, $candidateE],
        3 => $candidateD,
    ]);
});

test('result 3', function (): void {
    # From http://www.cs.wustl.edu/~legrand/rbvote/desc.html
    $this->election->addCandidate('Abby');
    $this->election->addCandidate('Brad');
    $this->election->addCandidate('Cora');
    $this->election->addCandidate('Dave');
    $this->election->addCandidate('Erin');

    $this->election->parseVotes('
            Abby>Cora>Erin>Dave>Brad * 98
            Brad>Abby>Erin>Cora>Dave * 64
            Brad>Abby>Erin>Dave>Cora * 12
            Brad>Erin>Abby>Cora>Dave * 98
            Brad>Erin>Abby>Dave>Cora * 13
            Brad>Erin>Dave>Abby>Cora * 125
            Cora>Abby>Erin>Dave>Brad * 124
            Cora>Erin>Abby>Dave>Brad * 76
            Dave>Abby>Brad>Erin>Cora * 21
            Dave>Brad>Abby>Erin>Cora * 30
            Dave>Brad>Erin>Cora>Abby * 98
            Dave>Cora>Abby>Brad>Erin * 139
            Dave>Cora>Brad>Abby>Erin * 23
        ');

    expect($this->election->getWinner('Copeland'))->toEqual(['Abby', 'Brad']);

    expect($this->election->getResult('Copeland')->rankingAsArrayString)->toBe([1 => ['Abby', 'Brad'],
        2 => ['Dave', 'Erin'],
        3 => 'Cora', ]);
});
