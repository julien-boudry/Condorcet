<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException;
use PHPUnit\Framework\TestCase;

class CombinationsTest extends TestCase
{
    protected function tearDown(): void
    {
        Combinations::$useBigIntegerIfAvailable = true;
    }

    public function testCountPossibleCombinationsResultWithBigInt(): void
    {
        // Usual permutation for CPO STV 11 candidates and 3 seats left
        $this->assertSame(
            13_530,
            Combinations::getPossibleCountOfCombinations(
                count: Combinations::getPossibleCountOfCombinations(
                    count: 11,
                    length: 3
                ),
                length: 2
            )
        );

        $this->assertSame(2_598_960, Combinations::getPossibleCountOfCombinations(52, 5)); // Card Game

        $this->assertSame(4_367_914_309_753_280, Combinations::getPossibleCountOfCombinations(78, 15)); // Tarot Card Game - 5 players
        $this->assertSame(212_566_476_905_162_380, Combinations::getPossibleCountOfCombinations(78, 18)); // Tarot Card Game - 4 players

        $this->expectException(IntegerOverflowException::class);
        Combinations::getPossibleCountOfCombinations(78, 24); // Tarot Card Game - 3 players - Result is 79_065_487_387_985_398_300, it's above PHP_MAX_INT
    }

    public function testCountPossibleCombinationsResultWithoutBigInt(): void
    {
        Combinations::$useBigIntegerIfAvailable = false;

        // Usual permutation for CPO STV 11 candidates and 3 seats left
        $this->assertSame(
            13_530,
            Combinations::getPossibleCountOfCombinations(
                count: Combinations::getPossibleCountOfCombinations(
                    count: 11,
                    length: 3
                ),
                length: 2
            )
        );

        $this->assertSame(2_598_960, Combinations::getPossibleCountOfCombinations(52, 5)); // Card Game - Result is - 4_367_914_309_753_280

        $this->expectException(IntegerOverflowException::class);
        Combinations::getPossibleCountOfCombinations(78, 15); // Tarot Card Game - 5 players -  - Result is 4_367_914_309_753_280, it's NOT above PHP_MAX_INT but the intermediate calculations are.
    }

    public function testCountPossibleCombinationsBadParameters1(): void
    {
        $this->expectException(CondorcetInternalException::class);

        Combinations::getPossibleCountOfCombinations(2, 3);
    }

    public function testIntegerOverflow(): void
    {
        $this->expectException(IntegerOverflowException::class);

        Combinations::getPossibleCountOfCombinations(\PHP_INT_MAX - 1, 2);
    }
}
