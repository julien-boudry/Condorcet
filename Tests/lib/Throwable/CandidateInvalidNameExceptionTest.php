<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException;
use PHPUnit\Framework\TestCase;

class CandidateInvalidNameExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown (): void
    {
        $this->expectException(CandidateInvalidNameException::class);

        $e = new CandidateInvalidNameException();

        $this->assertInstanceOf(CandidateInvalidNameException::class, $e);

        throw $e;
    }

    public function testExceptionWithArgument (): void
    {
        $this->expectException(CandidateInvalidNameException::class);

        $e = new CandidateInvalidNameException("Invalid candidate name");

        $this->assertInstanceOf(CandidateInvalidNameException::class, $e);
        $this->assertEquals("Invalid candidate name", $e->getMessage());

        throw $e;
    }

}
