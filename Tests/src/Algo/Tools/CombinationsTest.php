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