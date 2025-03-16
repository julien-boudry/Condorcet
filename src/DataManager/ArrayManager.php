<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\DataManager;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\Throws;
use CondorcetPHP\Condorcet\{CondorcetVersion, Election, Vote};
use CondorcetPHP\Condorcet\Throwable\DataHandlerException;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use CondorcetPHP\Condorcet\Relations\HasElection;

/**
 *  ArrayManager
 *
 *  It's a simple array storage manager with a cache system and a DataHandlerDriverInterface.
 *  It's used by VoteManager.
 *
 *  @internal
 */
abstract class ArrayManager implements \ArrayAccess, \Countable, \Iterator
{
    use HasElection;
    use CondorcetVersion;

    public static int $CacheSize = 2000;
    public static int $MaxContainerLength = 2000;

    protected array $Container = [];
    protected ?DataHandlerDriverInterface $DataHandler = null;

    protected array $Cache = [];
    protected int $CacheMaxKey = 0;
    protected int $CacheMinKey = 0;

    protected ?int $cursor = null;
    protected int $counter = 0;
    protected int $maxKey = -1;

    public function __construct(Election $election)
    {
        $this->setElection($election);
    }

    public function __destruct()
    {
        $this->regularize();
    }

    public function __serialize(): array
    {
        $this->regularize();
        $this->clearCache();
        $this->rewind();

        return ['Container' => $this->Container, 'DataHandler' => $this->DataHandler];
    }

    public function __unserialize(array $data): void
    {
        $this->Container = $data['Container'];
        $this->DataHandler = $data['DataHandler'];

        $this->resetMaxKey();
        $this->resetCounter();
    }


    /////////// Implement ArrayAccess ///////////

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->Container[++$this->maxKey] = $value;
            ++$this->counter;
        } else {
            $state = $this->keyExist($offset);
            $this->Container[$offset] = $value;

            if (!$state) {
                ++$this->counter;

                if ($offset > $this->maxKey) {
                    $this->maxKey = $offset;
                }

                ksort($this->Container, \SORT_NUMERIC);
            } elseif ($this->DataHandler !== null) {
                $this->DataHandler->deleteOneEntity(key: $offset, justTry: true);
                unset($this->Cache[$offset]);
            }

            $this->clearCache();
        }

        // Delegate this step to VoteManager. To give him time to calculate the pairwise iteratively. Without recharging the memory.
        // $this->checkRegularize();
    }

    // Use by isset() function, must return false if offset value is null.
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->Container[$offset]) || $this->dataHandlerKeyExist($offset);
    }

    public function offsetUnset(mixed $offset): void
    {
        if ($this->keyExist($offset)) {
            if (\array_key_exists(key: $offset, array: $this->Container)) {
                $this->preDeletedTask($this->Container[$offset]);
                unset($this->Container[$offset]);
            } else {
                if (\array_key_exists(key: $offset, array: $this->Cache)) {
                    $this->preDeletedTask($this->Cache[$offset]);
                    unset($this->Cache[$offset]);
                }

                $this->DataHandler->deleteOneEntity(key: $offset, justTry: false);
            }

            --$this->counter;
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (isset($this->Container[$offset])) {
            return $this->Container[$offset];
        } elseif ($this->DataHandler !== null) {
            if (\array_key_exists(key: $offset, array: $this->Cache)) {
                return $this->Cache[$offset];
            } else {
                $oneEntity = $this->DataHandler->selectOneEntity(key: $offset);
                if ($oneEntity === false) {
                    return null;
                } else {
                    return $this->Cache[$offset] = $this->decodeOneEntity($oneEntity);
                }
            }
        } else {
            return null;
        }
    }


    /////////// Implement Iterator ///////////

    protected bool $valid = true;

    public function rewind(): void
    {
        $this->cursor = null;
        $this->valid = true;

        reset($this->Cache);
        reset($this->Container);
    }

    public function current(): mixed
    {
        return $this->offsetGet($this->key());
    }

    public function key(): ?int
    {
        if ($this->counter === 0) {
            return null;
        } else {
            return $this->cursor ?? $this->getFirstKey();
        }
    }

    public function next(): void
    {
        $oldCursor = $this->cursor;

        if ($this->cursor >= $this->maxKey) {
            // Do nothing
        } elseif (!$this->isUsingHandler()) {
            $this->setCursorOnNextKeyInArray($this->Container);
        } else {
            $this->populateCache();
            $this->setCursorOnNextKeyInArray($this->Cache);
        }

        if ($this->cursor === $oldCursor) {
            $this->valid = false;
        }
    }

    protected function setCursorOnNextKeyInArray(array &$array): void
    {
        next($array);
        $arrayKey = key($array);

        if ($arrayKey > $this->key()) {
            $this->cursor = $arrayKey;
        }
    }

    public function valid(): bool
    {
        return ($this->counter !== 0) ? $this->valid : false;
    }


    /////////// Implement Countable ///////////

    public function count(): int
    {
        return $this->counter;
    }

    /////////// Array Methods ///////////

    public function getFullDataSet(): array
    {
        if ($this->isUsingHandler()) {
            $this->regularize();
            $this->clearCache();

            return $this->Cache = $this->decodeManyEntities($this->DataHandler->selectRangeEntities(key: 0, limit: $this->maxKey + 1));
        } else {
            return $this->Container;
        }
    }

    public function keyExist(int $offset): bool
    {
        return $this->containerKeyExist($offset) || $this->dataHandlerKeyExist($offset);
    }

    protected function containerKeyExist(int $offset): bool
    {
        return \array_key_exists(key: $offset, array: $this->Container);
    }

    protected function dataHandlerKeyExist(int $offset): bool
    {
        return $this->DataHandler !== null && $this->DataHandler->selectOneEntity(key: $offset) !== false;
    }

    public function getFirstKey(): int
    {
        $r = array_keys($this->Container);

        if ($this->DataHandler !== null) {
            $r[] = $this->DataHandler->selectMinKey();
        }

        return (int) min($r);
    }

    public function getContainerSize(): int
    {
        return \count($this->Container);
    }

    public function getCacheSize(): int
    {
        return \count($this->Cache);
    }

    public function debugGetCache(): array
    {
        return $this->Cache;
    }


    /////////// HANDLER API ///////////

    abstract protected function preDeletedTask(Vote $object): void;

    abstract protected function decodeOneEntity(string $data): Vote;

    abstract protected function encodeOneEntity(Vote $data): string;

    protected function decodeManyEntities(array $entities): array
    {
        $r = [];

        foreach ($entities as $key => $oneEntity) {
            $r[(int) $key] = $this->decodeOneEntity($oneEntity);
        }

        return $r;
    }

    protected function encodeManyEntities(array $entities): array
    {
        $r = [];

        foreach ($entities as $key => $oneEntity) {
            $r[(int) $key] = $this->encodeOneEntity($oneEntity);
        }

        return $r;
    }

    public function regularize(): bool
    {
        if (!$this->isUsingHandler() || empty($this->Container)) {
            return false;
        } else {
            $this->DataHandler->insertEntities($this->encodeManyEntities($this->Container));
            $this->Container = [];
            return true;
        }
    }

    public function checkRegularize(): bool
    {
        if ($this->DataHandler !== null && self::$MaxContainerLength <= $this->getContainerSize()) {
            $this->regularize();
            return true;
        } else {
            return false;
        }
    }

    protected function populateCache(): void
    {
        $this->regularize();

        $currentKey = $this->key();

        if (empty($this->Cache) || $currentKey >= $this->CacheMaxKey || $currentKey < $this->CacheMinKey) {
            $this->clearCache();
            $this->Cache = $this->decodeManyEntities($this->DataHandler->selectRangeEntities(key: $currentKey, limit: self::$CacheSize));

            $keys = array_keys($this->Cache);
            $this->CacheMaxKey = max($keys);
            $this->CacheMinKey = min($keys);
        }
    }

    public function clearCache(): void
    {
        foreach ($this->Cache as $e) {
            $this->preDeletedTask($e);
        }

        $this->Cache = [];
        $this->CacheMaxKey = 0;
        $this->CacheMinKey = 0;
    }

    public function isUsingHandler(): bool
    {
        return $this->DataHandler !== null;
    }

    /////////// HANDLER INTERRACTION ///////////

    public function resetCounter(): int
    {
        return $this->counter = $this->getContainerSize() + ($this->isUsingHandler() ? $this->DataHandler->countEntities() : 0);
    }

    public function resetMaxKey(): ?int
    {
        $this->resetCounter();

        if ($this->count() < 1) {
            $this->maxKey = -1;
            return null;
        } else {
            $maxContainerKey = empty($this->Container) ? null : max(array_keys($this->Container));
            $maxHandlerKey = $this->DataHandler !== null ? $this->DataHandler->selectMaxKey() : null;

            return $this->maxKey = max($maxContainerKey, $maxHandlerKey);
        }
    }

    #[Throws(DataHandlerException::class)]
    public function importHandler(DataHandlerDriverInterface $handler): true
    {
        if ($handler->countEntities() === 0) {
            $this->DataHandler = $handler;

            try {
                $this->regularize();
            } catch (\Exception $e) {
                $this->DataHandler = null;
                $this->resetCounter();
                $this->resetMaxKey();
                throw $e;
            }

            $this->resetCounter();
            $this->resetMaxKey();

            return true;
        } else {
            throw new DataHandlerException;
        }
    }

    public function closeHandler(): void
    {
        if ($this->DataHandler !== null) {
            $this->regularize();
            $this->clearCache();

            $this->Container = $this->decodeManyEntities($this->DataHandler->selectRangeEntities(key: 0, limit: $this->maxKey + 1));

            $this->DataHandler = null;

            $this->resetCounter();
            $this->resetMaxKey();
        }
    }
}
