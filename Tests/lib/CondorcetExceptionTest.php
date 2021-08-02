<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Throwable\CondorcetException;
use PHPUnit\Framework\TestCase;

class CondorcetExceptionTest extends TestCase
{
    public function testBuild (): void
    {
        $this->expectException(CondorcetException::class);
        $this->expectExceptionCode(0);

        $ce = new CondorcetException;

        self::assertStringStartsWith(  "CondorcetPHP\Condorcet\Throwable\CondorcetException: [0]:  (",
                              (string) $ce
                        );

        throw $ce;
    }

    public function testMaxCodeRange (): void
    {
        $this->expectException(CondorcetException::class);
        $this->expectExceptionCode(0);

        new CondorcetException (\max(CondorcetException::CODE_RANGE) + 1);
    }

    public function testMysteriousError (): void
    {
        $this->expectException(CondorcetException::class);
        $this->expectExceptionCode(\max(CondorcetException::CODE_RANGE) - 1);
        $this->expectExceptionMessage('Mysterious Error');

        throw new CondorcetException (\max(CondorcetException::CODE_RANGE) - 1);
    }

}
