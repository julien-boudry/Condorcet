<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\TimerException;
use CondorcetPHP\Condorcet\Timer\{Chrono, Manager};

afterEach(function (): void {
    Condorcet::$UseTimer = new ReflectionClass(Condorcet::class)->getProperty('UseTimer')->getDefaultValue();
});

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

test('getLastTimer return value', function (): void {
    $election = new Election;
    $election->parseCandidates('A;B;C');
    $election->parseVotes('A>B>C');

    Condorcet::$UseTimer = true; // is declared after adding votes

    $election->getPairwise();
    expect($election->getLastTimer())->toBeNull(); // Pairwise was computed during voting
});
