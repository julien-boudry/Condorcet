<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Timer;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\Throws;
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
    /** @var list<array{role: ?string, process_in: float, timer_start: float, timer_end: float}> */
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

            $this->history[] = [
                'role'        => $chrono->getRole(),
                'process_in'  => ($m - $chrono->getStart()),
                'timer_start' => $chrono->getStart(),
                'timer_end'   => $m,
            ];

            // Total wall-clock elapsed since the first chrono was declared.
            // Using a wall-clock model allows nested chronos to all appear in history
            // without double-counting: globalTimer measures the outer bracket, not a sum.
            $this->globalTimer = $m - $this->startDeclare;

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
     * Returns the chronological history of all timed operations for this election.
     * Entries are recorded in completion order (inner/nested operations appear before
     * their outer container). Nested chronos will naturally overlap in time-range.
     *
     * @api
     *
     * @return list<array{
     *     role: ?string,
     *     process_in: float,
     *     timer_start: float,
     *     timer_end: float
     * }> Each entry describes one timed operation:
     *   - `role`        — human-readable label of the operation, or null if unnamed
     *   - `process_in`  — duration in seconds (float) of this specific operation
     *   - `timer_start` — Unix timestamp (microtime) when the operation started
     *   - `timer_end`   — Unix timestamp (microtime) when the operation ended
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
