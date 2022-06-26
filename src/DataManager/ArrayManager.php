<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\DataManager;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\CondorcetVersion;
use CondorcetPHP\Condorcet\{Election, Vote};
use CondorcetPHP\Condorcet\Throwable\DataHandlerException;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;

abstract class ArrayManager implements \ArrayAccess, \Countable, \Iterator
{
    use CondorcetVersion;

    //////

    public static int $CacheSize = 2000;
    public static int $MaxContainerLength = 2000;

    protected array $_Container = [];
    protected ?DataHandlerDriverInterface $_DataHandler = null;
    protected \WeakReference $_Election;

    protected array $_Cache = [];
    protected int $_CacheMaxKey = 0;
    protected int $_CacheMinKey = 0;

    protected ?int $_cursor = null;
    protected int $_counter = 0;
    protected int $_maxKey = -1;

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

        return ['_Container' => $this->_Container,
            '_DataHandler' => $this->_DataHandler,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->_Container = $data['_Container'];
        $this->_DataHandler = $data['_DataHandler'];

        $this->resetMaxKey();
        $this->resetCounter();
    }

    public function getElection(): Election
    {
        return $this->_Election->get();
    }

    public function setElection(Election $election): void
    {
        $this->_Election = \WeakReference::create($election);
    }


    /////////// Implement ArrayAccess ///////////

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->_Container[++$this->_maxKey] = $value;
            ++$this->_counter;
        } else {
            $state = $this->keyExist($offset);
            $this->_Container[$offset] = $value;

            if (!$state) {
                ++$this->_counter;

                if ($offset > $this->_maxKey) {
                    $this->_maxKey = $offset;
                }

                ksort($this->_Container, \SORT_NUMERIC);
            } elseif ($this->_DataHandler !== null) {
                $this->_DataHandler->deleteOneEntity(key: $offset, justTry: true);
                unset($this->_Cache[$offset]);
            }

            $this->clearCache();
        }

        // Delegate this step to VoteManager. To give him time to calculate the pairwise iteratively. Without recharging the memory.
        // $this->checkRegularize();
    }

    // Use by isset() function, must return false if offset value is null.
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->_Container[$offset]) || ($this->_DataHandler !== null && $this->_DataHandler->selectOneEntity(key: $offset) !== false);
    }

    public function offsetUnset(mixed $offset): void
    {
        if ($this->keyExist($offset)) {
            if (\array_key_exists(key: $offset, array: $this->_Container)) {
                $this->preDeletedTask($this->_Container[$offset]);
                unset($this->_Container[$offset]);
            } else {
                if (\array_key_exists(key: $offset, array: $this->_Cache)) {
                    $this->preDeletedTask($this->_Cache[$offset]);
                    unset($this->_Cache[$offset]);
                }

                $this->_DataHandler->deleteOneEntity(key: $offset, justTry: false);
            }

            --$this->_counter;
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (isset($this->_Container[$offset])) {
            return $this->_Container[$offset];
        } elseif ($this->_DataHandler !== null) {
            if (\array_key_exists(key: $offset, array: $this->_Cache)) {
                return $this->_Cache[$offset];
            } else {
                $oneEntity = $this->_DataHandler->selectOneEntity(key: $offset);
                if ($oneEntity === false) {
                    return null;
                } else {
                    return $this->_Cache[$offset] = $this->decodeOneEntity($oneEntity);
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
        $this->_cursor = null;
        $this->valid = true;

        reset($this->_Cache);
        reset($this->_Container);
    }

    public function current(): mixed
    {
        return $this->offsetGet($this->key());
    }

    public function key(): ?int
    {
        if ($this->_counter === 0) {
            return null;
        } else {
            return $this->_cursor ?? $this->getFirstKey();
        }
    }

    public function next(): void
    {
        $oldCursor = $this->_cursor;

        if ($this->_cursor >= $this->_maxKey) {
            // Do nothing
        } elseif (!$this->isUsingHandler()) {
            $this->setCursorOnNextKeyInArray($this->_Container);
        } else {
            $this->populateCache();
            $this->setCursorOnNextKeyInArray($this->_Cache);
        }

        if ($this->_cursor === $oldCursor) {
            $this->valid = false;
        }
    }

    protected function setCursorOnNextKeyInArray(array &$array): void
    {
        next($array);
        $arrayKey = key($array);

        if ($arrayKey > $this->key()) {
            $this->_cursor = $arrayKey;
        }
    }

    public function valid(): bool
    {
        return ($this->_counter !== 0) ? $this->valid : false;
    }


    /////////// Implement Countable ///////////

    public function count(): int
    {
        return $this->_counter;
    }

    /////////// Array Methods ///////////

    public function getFullDataSet(): array
    {
        if ($this->isUsingHandler()) {
            $this->regularize();
            $this->clearCache();

            return $this->_Cache = $this->decodeManyEntities($this->_DataHandler->selectRangeEntities(key: 0, limit: $this->_maxKey + 1));
        } else {
            return $this->_Container;
        }
    }

    public function keyExist($offset): bool
    {
        if (\array_key_exists(key: $offset, array: $this->_Container) || ($this->_DataHandler !== null && $this->_DataHandler->selectOneEntity(key: $offset) !== false)) {
            return true;
        } else {
            return false;
        }
    }

    public function getFirstKey(): int
    {
        $r = array_keys($this->_Container);

        if ($this->_DataHandler !== null) {
            $r[] = $this->_DataHandler->selectMinKey();
        }

        return (int) min($r);
    }

    public function getContainerSize(): int
    {
        return \count($this->_Container);
    }

    public function getCacheSize(): int
    {
        return \count($this->_Cache);
    }

    public function debugGetCache(): array
    {
        return $this->_Cache;
    }


    /////////// HANDLER API ///////////

    abstract protected function preDeletedTask($object): void;

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
        if (!$this->isUsingHandler() || empty($this->_Container)) {
            return false;
        } else {
            $this->_DataHandler->insertEntities($this->encodeManyEntities($this->_Container));
            $this->_Container = [];
            return true;
        }
    }

    public function checkRegularize(): bool
    {
        if ($this->_DataHandler !== null && self::$MaxContainerLength <= $this->getContainerSize()) {
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

        if (empty($this->_Cache) || $currentKey >= $this->_CacheMaxKey || $currentKey < $this->_CacheMinKey) {
            $this->clearCache();
            $this->_Cache = $this->decodeManyEntities($this->_DataHandler->selectRangeEntities(key: $currentKey, limit: self::$CacheSize));

            $keys = array_keys($this->_Cache);
            $this->_CacheMaxKey = max($keys);
            $this->_CacheMinKey = min($keys);
        }
    }

    public function clearCache(): void
    {
        foreach ($this->_Cache as $e) {
            $this->preDeletedTask($e);
        }

        $this->_Cache = [];
        $this->_CacheMaxKey = 0;
        $this->_CacheMinKey = 0;
    }

    public function isUsingHandler(): bool
    {
        return $this->_DataHandler !== null;
    }

    /////////// HANDLER INTERRACTION ///////////

    public function resetCounter(): int
    {
        return $this->_counter = $this->getContainerSize() + ($this->isUsingHandler() ? $this->_DataHandler->countEntities() : 0);
    }

    public function resetMaxKey(): ?int
    {
        $this->resetCounter();

        if ($this->count() < 1) {
            $this->_maxKey = -1;
            return null;
        } else {
            $maxContainerKey = empty($this->_Container) ? null : max(array_keys($this->_Container));
            $maxHandlerKey = $this->_DataHandler !== null ? $this->_DataHandler->selectMaxKey() : null;

            return $this->_maxKey = max($maxContainerKey, $maxHandlerKey);
        }
    }

    #[Throws(DataHandlerException::class)]
    public function importHandler(DataHandlerDriverInterface $handler): bool
    {
        if ($handler->countEntities() === 0) {
            $this->_DataHandler = $handler;

            try {
                $this->regularize();
            } catch (\Exception $e) {
                $this->_DataHandler = null;
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
        if ($this->_DataHandler !== null) {
            $this->regularize();
            $this->clearCache();

            $this->_Container = $this->decodeManyEntities($this->_DataHandler->selectRangeEntities(key: 0, limit: $this->_maxKey + 1));

            $this->_DataHandler = null;

            $this->resetCounter();
            $this->resetMaxKey();
        }
    }
}
