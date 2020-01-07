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

        self::assertStringStartsWith(  "CondorcetPHP\Condorcet\Throwable\CondorcetException: [0]:  (",
                              (string) $ce
                        );

        throw $ce;
    }

    public function testMaxCodeRange () : void
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(0);

        new Throwable\CondorcetException (\max(Throwable\CondorcetException::CODE_RANGE) + 1);
    }

    public function testMysteriousError () : void
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(\max(Throwable\CondorcetException::CODE_RANGE) - 1);
        self::expectExceptionMessage('Mysterious Error');

        throw new Throwable\CondorcetException (\max(Throwable\CondorcetException::CODE_RANGE) - 1);
    }

}
