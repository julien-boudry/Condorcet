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

    public function testPermutationsAllResultsFor3 (): void
    {
        $p = new Permutations(3);

        $r = $p->getResults();

        self::assertInstanceOf(\SplFixedArray::class, $r);
        self::assertSame(6, $r->getSize());

        self::assertSame(   [   [1=>0,2=>1,3=>2],
                                [1=>1,2=>0,3=>2],
                                [1=>1,2=>2,3=>0],
                                [1=>0,2=>2,3=>1],
                                [1=>2,2=>0,3=>1],
                                [1=>2,2=>1,3=>0]
                            ],
                            $r->toArray());
    }

    public function testPermutationsAllResultsFor1 (): void
    {
        $p = new Permutations(1);

        $r = $p->getResults();

        self::assertInstanceOf(\SplFixedArray::class, $r);
        self::assertSame(1, $r->getSize());

        self::assertSame([[1=>0]], $r->toArray());
    }
}