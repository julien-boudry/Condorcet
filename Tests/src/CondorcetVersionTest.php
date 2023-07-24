<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class CondorcetVersionTest extends TestCase
{
    public function testObjectVersion(): void
    {
        $election = new Election;

        self::assertSame(CONDORCET::getVersion(), $election->getObjectVersion());
        self::assertSame(CONDORCET::getVersion(true), $election->getObjectVersion(true));
    }
}
