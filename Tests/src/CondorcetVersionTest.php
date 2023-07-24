<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Condorcet, Election};
use PHPUnit\Framework\TestCase;

class CondorcetVersionTest extends TestCase
{
    public function testObjectVersion(): void
    {
        $election = new Election;

        $this->assertSame(CONDORCET::getVersion(), $election->getObjectVersion());
        $this->assertSame(CONDORCET::getVersion(true), $election->getObjectVersion(true));
    }
}
