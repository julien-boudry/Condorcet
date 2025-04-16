<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Throwable\Internal\{CondorcetInternalException, IntegerOverflowException};

afterEach(function (): void {
    Combinations::$useBigIntegerIfAvailable = true;
});

test('count possible combinations result with big int', function (): void {
    // Usual permutation for CPO STV 11 candidates and 3 seats left
    expect(
        Combinations::getPossibleCountOfCombinations(
            count: Combinations::getPossibleCountOfCombinations(
                count: 11,
                length: 3
            ),
            length: 2
        )
    )->toBe(13_530);

    expect(Combinations::getPossibleCountOfCombinations(52, 5))->toBe(2_598_960);

    // Card Game
    expect(Combinations::getPossibleCountOfCombinations(78, 15))->toBe(4_367_914_309_753_280);
    // Tarot Card Game - 5 players
    expect(Combinations::getPossibleCountOfCombinations(78, 18))->toBe(212_566_476_905_162_380);

    // Tarot Card Game - 4 players
    $this->expectException(IntegerOverflowException::class);
    Combinations::getPossibleCountOfCombinations(78, 24);
    // Tarot Card Game - 3 players - Result is 79_065_487_387_985_398_300, it's above PHP_MAX_INT
});

test('count possible combinations result without big int', function (): void {
    Combinations::$useBigIntegerIfAvailable = false;

    // Usual permutation for CPO STV 11 candidates and 3 seats left
    expect(
        Combinations::getPossibleCountOfCombinations(
            count: Combinations::getPossibleCountOfCombinations(
                count: 11,
                length: 3
            ),
            length: 2
        )
    )->tobe(13_530);

    expect(Combinations::getPossibleCountOfCombinations(52, 5))->toBe(2_598_960);

    // Card Game - Result is - 4_367_914_309_753_280
    $this->expectException(IntegerOverflowException::class);
    Combinations::getPossibleCountOfCombinations(78, 15);
    // Tarot Card Game - 5 players -  - Result is 4_367_914_309_753_280, it's NOT above PHP_MAX_INT but the intermediate calculations are.
});

test('count possible combinations bad parameters1', function (): void {
    $this->expectException(CondorcetInternalException::class);

    Combinations::getPossibleCountOfCombinations(2, 3);
});

test('integer overflow', function (): void {
    $this->expectException(IntegerOverflowException::class);

    Combinations::getPossibleCountOfCombinations(\PHP_INT_MAX - 1, 2);
});
