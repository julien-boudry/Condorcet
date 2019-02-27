<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;

class CondorcetExceptionTest extends TestCase
{
    public function testBuild ()
    {
        self::expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        self::expectExceptionCode(0);

        $ce = new CondorcetException;

        throw $ce;;
    }

}
