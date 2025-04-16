<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Throwable\TimerException;
use CondorcetPHP\Condorcet\Timer\{Chrono, Manager};

test('invalid chrono', function (): void {
    $manager1 = new Manager;
    $manager2 = new Manager;

    $chrono1 = new Chrono($manager1);
    $chrono2 = new Chrono($manager2);

    $manager1->addTime($chrono1);

    $this->expectException(TimerException::class);
    $this->expectExceptionMessage('Only a chrono linked to this manager can be used');

    $manager1->addTime($chrono2);
});
