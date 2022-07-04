<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Timer;

use CondorcetPHP\Condorcet\Throwable\TimerException;
use CondorcetPHP\Condorcet\Timer\{Chrono, Manager};
use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase
{
    public function testInvalidChrono(): never
    {
        $this->expectException(TimerException::class);
        $this->expectExceptionMessage('Only a chrono linked to this manager can be used');

        $manager1 = new Manager;
        $manager2 = new Manager;

        $chrono1 = new Chrono($manager1);
        $chrono2 = new Chrono($manager2);

        $manager1->addTime($chrono1);
        $manager1->addTime($chrono2);
    }
}
