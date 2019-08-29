<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Timer\Manager;
use CondorcetPHP\Condorcet\Timer\Chrono;


use PHPUnit\Framework\TestCase;


class TimerTest extends TestCase
{
    public function testInvalidChrono () : void
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(0);
        self::expectExceptionMessage('Only chrono linked to this Manager can be used');

        $manager1 = new Manager;
        $manager2 = new Manager;

        $chrono1 = new Chrono ($manager1);
        $chrono2 = new Chrono ($manager2);

        $manager1->addTime($chrono1);
        $manager1->addTime($chrono2);
    }
}