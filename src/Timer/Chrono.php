<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Timer;

use CondorcetPHP\Condorcet\CondorcetVersion;

class Chrono
{
    use CondorcetVersion;

    protected readonly Manager $manager;
    public readonly float $start;
    protected ?string $role = null;

    public function __construct(Manager $timer, ?string $role = null)
    {
        $this->manager = $timer;
        $this->setRole($role);
        $this->start = microtime(true);
        $this->managerStartDeclare();
    }

    public function __destruct()
    {
        $this->manager->addTime($this);
    }

    public function getStart(): float
    {
        return $this->start;
    }

    public function getTimerManager(): Manager
    {
        return $this->manager;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    protected function managerStartDeclare(): void
    {
        $this->manager->startDeclare($this);
    }
}
