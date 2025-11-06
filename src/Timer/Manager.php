<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Timer;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Throws};
use CondorcetPHP\Condorcet\CondorcetVersion;
use CondorcetPHP\Condorcet\Throwable\TimerException;

/**
 * Manages chronometers and tracks execution time.
 */
class Manager
{
    use CondorcetVersion;

    protected float $globalTimer = 0.0;
    protected ?float $lastTimer = null;
    protected ?float $lastChronoTimestamp = null;
    protected ?float $startDeclare = null;
    protected array $history = [];

    /**
     * @throws TimerException
     */
    public function addTime(Chrono $chrono): void
    {
        if ($chrono->getTimerManager() === $this) {
            if ($this->lastChronoTimestamp === null && $chrono->getStart() !== $this->startDeclare) {
                return;
            }

            $m = microtime(true);

            if ($this->lastChronoTimestamp > $chrono->getStart()) {
                $c = $this->lastChronoTimestamp;
            } else {
                $c = $chrono->getStart();
                $this->history[] = ['role' => $chrono->getRole(),
                    'process_in' => ($m - $c),
                    'timer_start' => $c,
                    'timer_end' => $m,
                ];
            }

            $this->globalTimer += ($m - $c);

            $this->lastTimer = ($m - $chrono->getStart());
            $this->lastChronoTimestamp = $m;
        } else {
            throw new TimerException;
        }
    }

    public function getGlobalTimer(): float
    {
        return $this->globalTimer;
    }

    public function getLastTimer(): ?float
    {
        return $this->lastTimer;
    }

    /**
     * Returns benchmarked actions history.
     *
     * @api
     *
     * @return array An array containing detailed benchmark history.
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    public function startDeclare(Chrono $chrono): static
    {
        if ($this->startDeclare === null) {
            $this->startDeclare = $chrono->getStart();
        }

        return $this;
    }
}
