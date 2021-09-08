<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\VotingHasStartedException;
use PHPUnit\Framework\TestCase;

class VotingHasStartedExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown (): void
    {
        $this->expectException(VotingHasStartedException::class);

        $e = new VotingHasStartedException();

        $this->assertInstanceOf(VotingHasStartedException::class, $e);

        throw $e;
    }

}
