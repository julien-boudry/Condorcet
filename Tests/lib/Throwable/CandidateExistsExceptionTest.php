<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\CandidateExistsException;
use PHPUnit\Framework\TestCase;

class CandidateExistsExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown (): void
    {
        $this->expectException(CandidateExistsException::class);

        $e = new CandidateExistsException();

        $this->assertInstanceOf(CandidateExistsException::class, $e);

        throw $e;
    }

    public function testExceptionWithArgument (): void
    {
        $this->expectException(CandidateExistsException::class);

        $e = new CandidateExistsException("Existing candidate name");

        $this->assertInstanceOf(CandidateExistsException::class, $e);
        $this->assertEquals("Existing candidate name", $e->getMessage());

        throw $e;
    }

}
