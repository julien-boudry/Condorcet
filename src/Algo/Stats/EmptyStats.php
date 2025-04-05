<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Stats;

use CondorcetPHP\Condorcet\Throwable\StatsEntryDoNotExistException;

final class EmptyStats implements StatsInterface
{
    public array $asArray {
        get => [];
    }

    public function getIterator(): \Traversable
    {
        return new \EmptyIterator;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new StatsEntryDoNotExistException($offset);
    }

    public function offsetExists(mixed $offset): bool
    {
        return false;
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new StatsEntryDoNotExistException($offset);
    }

    public function offsetGet(mixed $offset): never
    {
        $this->getEntry($offset);
    }

    public function getEntry(string $entryName): never
    {
        throw new StatsEntryDoNotExistException($entryName);
    }
}