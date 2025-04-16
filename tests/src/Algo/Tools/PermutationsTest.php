<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Algo\Tools\Permutations;
use CondorcetPHP\Condorcet\Throwable\Internal\{CondorcetInternalException, IntegerOverflowException};

afterEach(function (): void {
    Permutations::$useBigIntegerIfAvailable = true;
});

test('count possible permutations', function (): void {
    expect(Permutations::getPossibleCountOfPermutations(3))->toBe(6);

    Permutations::$useBigIntegerIfAvailable = false;
    expect(Permutations::getPossibleCountOfPermutations(3))->toBe(6);

    $this->expectException(CondorcetInternalException::class);
    Permutations::getPossibleCountOfPermutations(0);
});

test('integer overflow with big int', function (): void {
    $this->expectException(IntegerOverflowException::class);
    Permutations::getPossibleCountOfPermutations(42);
});

test('integer overflow without big int', function (): void {
    Permutations::$useBigIntegerIfAvailable = false;

    $this->expectException(IntegerOverflowException::class);
    Permutations::getPossibleCountOfPermutations(42);
});

test('permutations all results for3', function (): void {
    $p = new Permutations([0, 1, 2]);

    $r = $p->getResults();

    expect($r)->toBeInstanceOf(SplFixedArray::class);

    expect($r->getSize())->toBe(6);

    expect($r->toArray())->toBe([[1 => 0, 2 => 1, 3 => 2],
        [1 => 1, 2 => 0, 3 => 2],
        [1 => 1, 2 => 2, 3 => 0],
        [1 => 0, 2 => 2, 3 => 1],
        [1 => 2, 2 => 0, 3 => 1],
        [1 => 2, 2 => 1, 3 => 0],
    ]);
});

test('permutations all results for1', function (): void {
    $p = new Permutations([42]);

    $r = $p->getResults();

    expect($r)->toBeInstanceOf(SplFixedArray::class);

    expect($r->getSize())->toBe(1);

    expect($r->toArray())->toBe([[1 => 42]]);
});
