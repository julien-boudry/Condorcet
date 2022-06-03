<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException;
use PHPUnit\Framework\TestCase;


class CombinationsTest extends TestCase
{
    public function testCountPossibleCombinationsResult (): void
    {
        self::assertSame(2_598_960, Combinations::getNumberOfCombinations(52,5)); // Card Game

        self::assertSame(5_278_511_093_147_205_632, Combinations::getNumberOfCombinations(78,24)); // Tarot Card Game - 4 players
        self::assertSame(212_566_476_905_162_400, Combinations::getNumberOfCombinations(78,18)); // Tarot Card Game - 4 players
        self::assertSame(4_367_914_309_753_280, Combinations::getNumberOfCombinations(78,15)); // Tarot Card Game - 5 players

        // Usual permutation for CPO STV 11 candidates and 3 seats left
        self::assertSame(13_530,    Combinations::getNumberOfCombinations(  count: Combinations::getNumberOfCombinations(
                                    count: 11,
                                    length: 3),
                                length: 2)
        );
    }

    public function testCountPossibleCombinationsBadParameters1 (): void
    {
        $this->expectException(CondorcetInternalException::class);

        Combinations::getNumberOfCombinations(2,3);
    }

    public function testIntegerOverflow (): void
    {
        $this->expectException(IntegerOverflowException::class);

        Combinations::getNumberOfCombinations(\PHP_INT_MAX - 1, 2);
    }
}