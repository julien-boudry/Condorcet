<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Permutations;

use PHPUnit\Framework\TestCase;


class PermutationsTest extends TestCase
{
    public function testCountPossiblePermutations (): void
    {
        self::assertSame(6, Permutations::countPossiblePermutations(3));
    }
}