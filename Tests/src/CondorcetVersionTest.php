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

        expect($election->getObjectVersion())->toBe(CONDORCET::getVersion());
        expect($election->getObjectVersion(true))->toBe(CONDORCET::getVersion(true));
    }
}
