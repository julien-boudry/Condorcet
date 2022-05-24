<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Throwable\CondorcetInternalError;
use PHPUnit\Framework\TestCase;

class CondorcetInternalErrorTest extends TestCase
{
    public function testMessage (): void
    {
        $this->expectException(CondorcetInternalError::class);
        $this->expectExceptionMessage($message = 'Test message');

        throw new CondorcetInternalError($message);
    }
}