<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Permutations;
use CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException;
use PHPUnit\Framework\TestCase;


class PermutationsTest extends TestCase
{
    public function tearDown (): void
    {
        Permutations::$useBigIntegerIfAvailable = true;
    }

    public function testCountPossiblePermutations (): void
    {
        self::assertSame(6, Permutations::getPossibleCountOfPermutations(3));

        Permutations::$useBigIntegerIfAvailable = false;
        self::assertSame(6, Permutations::getPossibleCountOfPermutations(3));
    }

    public function testIntegerOverflowWithBigInt (): void
    {
        $this->expectException(IntegerOverflowException::class);
        Permutations::getPossibleCountOfPermutations(42);
    }

    public function testIntegerOverflowWithoutBigInt (): void
    {
        Permutations::$useBigIntegerIfAvailable = false;

        $this->expectException(IntegerOverflowException::class);
        Permutations::getPossibleCountOfPermutations(42);
    }
}