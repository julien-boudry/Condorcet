<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException;
use PHPUnit\Framework\TestCase;

class CandidateDoesNotExistExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown (): void
    {
        $this->expectException(CandidateDoesNotExistException::class);

        $e = new CandidateDoesNotExistException();

        $this->assertInstanceOf(CandidateDoesNotExistException::class, $e);

        throw $e;
    }

    public function testExceptionWithArgument (): void
    {
        $this->expectException(CandidateDoesNotExistException::class);

        $e = new CandidateDoesNotExistException("Not existing candidate name");

        $this->assertInstanceOf(CandidateDoesNotExistException::class, $e);
        $this->assertEquals("Not existing candidate name", $e->getMessage());

        throw $e;
    }

}
