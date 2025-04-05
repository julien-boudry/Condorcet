<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result 1', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > B > C * 3
            D > A > B * 2
            D > B > C * 2
            C > B > D * 2
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe([$candidateB, $candidateD]);

    expect($this->election->getResult('Schulze Winning')->getResultAsArray(true))->toBe([1 => ['B', 'D'],
        2 => ['A', 'C'], ]);
});

test('result 2', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > C > D * 6
            B > A > D * 1
            C > B > D * 3
            D > B > A * 3
            D > C > B * 2
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe([$candidateA, $candidateD]);
});

test('result 3', function (): void {
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

    expect($this->election->getWinner('Schulze Winning'))->toEqual('Abby');

    expect($this->election->getResult('Schulze Winning')->getResultAsArray(true))->toBe([1 => 'Abby',
        2 => 'Brad',
        3 => 'Erin',
        4 => 'Dave',
        5 => 'Cora', ]);
});

test('schulze official example 1', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > C > D * 8
            B > A > D * 2
            C > D > B * 4
            D > B > A * 4
            D > C > B * 3
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateD);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 14, 'C' => 14, 'D' => 12],
        'B' => ['A' => 13, 'C' => 13, 'D' => 12],
        'C' => ['A' => 13, 'B' => 15, 'D' => 12],
        'D' => ['A' => 13, 'B' => 19, 'C' => 13],
    ]);
});

test('schulze official example 2', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > C > D * 3
            B > A > C * 9
            C > D > A * 8
            D > A > B * 5
            D > B > C * 5
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateC);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 17, 'C' => 17, 'D' => 17],
        'B' => ['A' => 18, 'C' => 19, 'D' => 19],
        'C' => ['A' => 18, 'B' => 20, 'D' => 20],
        'D' => ['A' => 18, 'B' => 21, 'C' => 19],
    ]);
});

test('schulze official example 3', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');
    $candidateE = $this->election->addCandidate('E');

    $this->election->parseVotes('
            A > C > B > E * 5
            A > D > E > C * 5
            B > E > D > A * 8
            C > A > B > E * 3
            C > A > E > B * 7
            C > B > A > D * 2
            D > C > E > B * 7
            E > B > A > D * 8
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateE);

    expect($this->election->getResult('Schulze Winning')->getResultAsArray(true))->toBe([
        1 => 'E',
        2 => 'A',
        3 => 'C',
        4 => 'B',
        5 => 'D',
    ]);
});

test('schulze official example 4', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > B > C * 3
            C > B > D * 2
            D > A > B * 2
            D > B > C * 2
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe([$candidateB, $candidateD]);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 5, 'C' => 5, 'D' => 5],
        'B' => ['A' => 5, 'C' => 7, 'D' => 5],
        'C' => ['A' => 5, 'B' => 5, 'D' => 5],
        'D' => ['A' => 6, 'B' => 5, 'C' => 5],
    ]);
});

test('schulze official example 5', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > B > C * 12
            A > D > B * 6
            B > C > D * 9
            C > D > A * 15
            D > B > A * 21
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateD);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 36, 'C' => 39, 'D' => 36],
        'B' => ['A' => 36, 'C' => 48, 'D' => 36],
        'C' => ['A' => 36, 'B' => 36, 'D' => 36],
        'D' => ['A' => 45, 'B' => 42, 'C' => 42],
    ]);
});

test('schulze official example 6', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > C > D * 8
            B > C > D * 2
            B > D > A * 3
            C > B > D * 5
            C > D > B
            D > A > B * 3
            D > B > A
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe([$candidateA, $candidateC]);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 14, 'C' => 15, 'D' => 15],
        'B' => ['A' => 12, 'C' => 12, 'D' => 12],
        'C' => ['A' => 15, 'B' => 14, 'D' => 16],
        'D' => ['A' => 15, 'B' => 14, 'C' => 15],
    ]);
});

test('schulze official example 7', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');
    $candidateE = $this->election->addCandidate('E');
    $candidateF = $this->election->addCandidate('F');

    $this->election->parseVotes('
            A > D > E > B > C * 3
            B > F > E > C > D * 3
            C > A > B > F > D * 4
            D > B > C > E > F * 1
            D > E > F > A > B * 4
            E > C > B > D > F * 2
            F > A > C > D > B * 2
        ');

    # Situation 1
    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateA);

    # Situation 2
    $this->election->parseVotes('A > E > F > C > B * 2');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateD);
});

test('schulze official example 8 situation 1', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > B > D * 3
            A > D > B * 5
            A > D > C * 1
            B > A > D * 2
            B > D > C * 2
            C > A > B * 4
            C > B > A * 6
            D > B > C * 2
            D > C > A * 5
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateA);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 18, 'C' => 20, 'D' => 21],
        'B' => ['A' => 17, 'C' => 17, 'D' => 17],
        'C' => ['A' => 19, 'B' => 18, 'D' => 19],
        'D' => ['A' => 19, 'B' => 18, 'C' => 20],
    ]);
});

test('schulze official example 8 situation 2', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');
    $candidateE = $this->election->addCandidate('E');

    $this->election->parseVotes('
            A > B > D > E * 3
            A > D > E > B * 5
            A > D > E > C * 1
            B > A > D > E * 2
            B > D > E > C * 2
            C > A > B > D * 4
            C > B > A > D * 6
            D > B > E > C * 2
            D > E > C > A * 5
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateB);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 18, 'C' => 20, 'D' => 21, 'E' => 21],
        'B' => ['A' => 19, 'C' => 19, 'D' => 19, 'E' => 19],
        'C' => ['A' => 19, 'B' => 18, 'D' => 19, 'E' => 19],
        'D' => ['A' => 19, 'B' => 18, 'C' => 20, 'E' => 30],
        'E' => ['A' => 19, 'B' => 18, 'C' => 20, 'D' => 19],
    ]);
});

test('schulze official example 9 situation 1', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > C > D * 5
            B > C > D * 2
            B > D > A * 4
            C > D > A * 2
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateA);
});

test('schulze official example 9 situation 2', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');
    $candidateE = $this->election->addCandidate('E');

    $this->election->parseVotes('
            A > E > C > D * 5
            B > C > D > A * 2
            B > D > A > E * 4
            C > D > A > B * 2
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateB);
});

test('schulze official example 10', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');

    $this->election->parseVotes('
            A > B > C > D * 6
            A = B * 8
            A = C * 8
            A = C > D * 18
            A = C = D * 8
            B * 40
            C > B > D * 4
            C > D > A * 9
            C = D * 8
            D > A > B * 14
            D > B > C * 11
            D > C > A * 4
        ');

    # Margin
    expect($this->election->getWinner('Schulze Margin'))->toBe($candidateA);

    expect($this->election->getResult('Schulze Margin')->getStatsAsArray())->toBe([
        'A' => ['B' => 67 - 55, 'C' => 67 - 55, 'D' => 67 - 55],
        'B' => ['A' => 50 - 40, 'C' => 79 - 59, 'D' => 45 - 29],
        'C' => ['A' => 50 - 40, 'B' => 72 - 58, 'D' => 45 - 29],
        'D' => ['A' => 50 - 40, 'B' => 72 - 58, 'C' => 72 - 58],
    ]);

    # Ratio
    expect($this->election->getWinner('Schulze Ratio'))->toBe($candidateB);

    expect($this->election->getResult('Schulze Ratio')->getStatsAsArray())->toBe([
        'A' => ['B' => 67 / 55, 'C' => 67 / 55, 'D' => 67 / 55],
        'B' => ['A' => 36 / 28, 'C' => 79 / 59, 'D' => 79 / 59],
        'C' => ['A' => 36 / 28, 'B' => 72 / 58, 'D' => 45 / 29],
        'D' => ['A' => 50 / 40, 'B' => 72 / 58, 'C' => 72 / 58],
    ]);

    # Winning
    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateD);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 67, 'C' => 67, 'D' => 45],
        'B' => ['A' => 45, 'C' => 79, 'D' => 45],
        'C' => ['A' => 45, 'B' => 45, 'D' => 45],
        'D' => ['A' => 50, 'B' => 72, 'C' => 72],
    ]);

    # Losing Votes
    // not implemented
});

test('schulze official example 11', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');
    $candidateE = $this->election->addCandidate('E');

    $this->election->parseVotes('
            A > D > B > E * 9
            B > C > A > D * 6
            B > C > D > E * 5
            C > D > B > E * 2
            D > E > C > B * 6
            E > A > C > B * 14
            E > C > A > B * 2
            E > D > A > C * 1
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateB);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 26, 'C' => 28, 'D' => 31, 'E' => 28],
        'B' => ['A' => 27, 'C' => 27, 'D' => 27, 'E' => 27],
        'C' => ['A' => 28, 'B' => 26, 'D' => 29, 'E' => 28],
        'D' => ['A' => 28, 'B' => 26, 'C' => 28, 'E' => 28],
        'E' => ['A' => 30, 'B' => 26, 'C' => 32, 'D' => 30],
    ]);
});

test('schulze official example 12', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');
    $candidateD = $this->election->addCandidate('D');
    $candidateE = $this->election->addCandidate('E');

    $this->election->parseVotes('
            A > D > B > E * 9
            B > A > C > E * 1
            C > B > A > D * 6
            C > D > B > E * 2
            C > D > E > A * 5
            D > E > C > A * 6
            E > B > A > C * 14
            E > B > C > A * 2
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe($candidateE);
});

test('schulze official example 13', function (): void {
    $candidateA = $this->election->addCandidate('A');
    $candidateB = $this->election->addCandidate('B');
    $candidateC = $this->election->addCandidate('C');

    $this->election->parseVotes('
            A > B > C * 2
            B > C > A * 2
            C > A > B
        ');

    expect($this->election->getWinner('Schulze Winning'))->toBe([$candidateA, $candidateB]);

    expect($this->election->getResult('Schulze Winning')->getStatsAsArray())->toBe([
        'A' => ['B' => 3, 'C' => 3],
        'B' => ['A' => 3, 'C' => 4],
        'C' => ['A' => 3, 'B' => 3],
    ]);
});

test('schulze ratio equality', function (): void {
    $this->election->parseCandidates('A;B;C;D');
    $this->election->parseVotes('A>B=C>D * 10');

    expect($this->election->getResult('Schulze Ratio')->getResultAsArray(true))->toBe([1 => 'A',
        2 => ['B', 'C'],
        3 => 'D',
    ]);
});
