<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Throwable;

use CondorcetPHP\Condorcet\Throwable\VotingHasStartedException;
use PHPUnit\Framework\TestCase;

class VotingHasStartedTest extends TestCase
{
    public function testBuild (): void
    {
        $this->expectException(VotingHasStartedException::class);

        $e = new VotingHasStartedException();

        self::assertStringStartsWith("CondorcetPHP\Condorcet\Throwable\VotingHasStartedException in",
            (string) $e
        );

        throw $e;
    }

}
