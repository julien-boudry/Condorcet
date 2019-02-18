<?php
declare(strict_types=1);
namespace CondorcetPHP;

use CondorcetPHP\Algo\Tools\Permutation;

use PHPUnit\Framework\TestCase;


class PermutationTest extends TestCase
{
    public function testCountPossiblePermutations ()
    {
        self::assertSame(6, Permutation::countPossiblePermutations(3));
    }
}