<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\ResultRequestedWithoutVotesException;
use PHPUnit\Framework\TestCase;

class ResultRequestedWithoutVotesExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown (): void
    {
        $this->expectException(ResultRequestedWithoutVotesException::class);

        $e = new ResultRequestedWithoutVotesException();

        $this->assertInstanceOf(ResultRequestedWithoutVotesException::class, $e);

        throw $e;
    }

}
