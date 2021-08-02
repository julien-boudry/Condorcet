<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Permutation;

use PHPUnit\Framework\TestCase;


class PermutationTest extends TestCase
{
    public function testCountPossiblePermutations (): void
    {
        self::assertSame(6, Permutation::countPossiblePermutations(3));
    }
}