<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use PHPUnit\Framework\TestCase;

class VoteInvalidFormatExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown (): void
    {
        $this->expectException(VoteInvalidFormatException::class);

        $e = new VoteInvalidFormatException();

        $this->assertInstanceOf(VoteInvalidFormatException::class, $e);

        throw $e;
    }

    public function testExceptionWithArgument (): void
    {
        $this->expectException(VoteInvalidFormatException::class);

        $e = new VoteInvalidFormatException("Bad vote format");

        $this->assertInstanceOf(VoteInvalidFormatException::class, $e);
        $this->assertEquals("Bad vote format", $e->getMessage());

        throw $e;
    }

}
