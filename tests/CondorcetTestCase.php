<?php

declare(strict_types=1);

namespace Tests;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

abstract class CondorcetTestCase extends TestCase
{
    public Election $election;
}
