<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\AlgorithmException;
use PHPUnit\Framework\TestCase;

class AlgorithmExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown (): void
    {
        $this->expectException(AlgorithmException::class);

        $e = new AlgorithmException();

        $this->assertInstanceOf(AlgorithmException::class, $e);

        throw $e;
    }

    public function testExceptionWithArgument (): void
    {
        $this->expectException(AlgorithmException::class);

        $e = new AlgorithmException("Method does not exist");

        $this->assertInstanceOf(AlgorithmException::class, $e);
        $this->assertEquals("Method does not exist", $e->getMessage());

        throw $e;
    }

}
