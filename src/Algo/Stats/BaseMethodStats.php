<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Stats;

use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError;
use CondorcetPHP\Condorcet\Throwable\StatsEntryDoNotExistException;
use Traversable;

class BaseMethodStats implements StatsInterface
{

    public readonly string $buildByMethod;
    protected array $data = [];

    public array $asArray {
        get => $this->data;
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setEntry((string) $offset, $value);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->unsetEntry($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getEntry($offset);
    }

    public function __construct(
        ?array $data = null,
        public protected(set) bool $closed = true
    )
    {
        if ($data !== null) {
            $this->data = $data;
        }

        $backtrace = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        if (isset($backtrace[1]) && isset($backtrace[1]['class'])) {
            $this->buildByMethod =  new \ReflectionMethod($backtrace[1]['class'], $backtrace[1]['function'])->class;
        } else {
            throw new CondorcetInternalError('Unable to get the caller method');
        }

    }

    public function close(): static
    {
        $this->closed = true;

        return $this;
    }

    public function getEntry(string $entryName): mixed
    {
        if (array_key_exists($entryName, $this->data)) {
            return $this->data[$entryName];
        }

        throw new StatsEntryDoNotExistException("entry {$entryName} do not exist");
    }

    public function setEntry(string $key, mixed $entry): static
    {
        if ($this->closed) {
            $this->throwAlreadyBuild();
        }

        $this->data[$key] = $entry;

        return $this;
    }

    public function unsetEntry(string $key): static
    {
        if ($this->closed) {
            $this->throwAlreadyBuild();
        }

        unset($this->data[$key]);

        return $this;
    }

    protected function throwAlreadyBuild(): never
    {
        throw new CondorcetInternalError('stats object is already build');
    }
}