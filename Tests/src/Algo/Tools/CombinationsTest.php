<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Throwable\CondorcetInternalException;
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
}