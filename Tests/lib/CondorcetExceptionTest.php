<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;

class CondorcetExceptionTest extends TestCase
{
    public function testBuild () : void
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(0);

        $ce = new Throwable\CondorcetException;

        throw $ce;;
    }

}
