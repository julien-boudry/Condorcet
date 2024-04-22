<?php
/*
    WoollyM DataHandler Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\WoollyDriver;

use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError;
use MammothPHP\WoollyM\DataDrivers\DriversExceptions\KeyNotExistException;
use MammothPHP\WoollyM\DataFrame;

class WoollyDriver implements DataHandlerDriverInterface
{
    public string $voteColumnName = 'vote';

    public function __construct(public readonly DataFrame $df = new DataFrame) {}

    /* public $dataContextObject; */
    // The Data Manager will set an object into this property. You should call for each Entity $dataContextObject->dataCallBack($EntityData) before returning stored data by the two select method.


    // Entities to register.
    // Ex: [Condorcet/Vote,Condorcet/Vote,Condorcet/Vote]. The key should not be kept
    public function insertEntities(array $input): void
    {
        foreach ($input as $key => $vote) {
            $this->df->update()->record(key: $key, recordArray: [$this->voteColumnName => $vote]);
        }
    }

    // Delete Entity with this key. If justTry is true, don't throw Exception if row not exist. Else throw an \CondorcetPHP\Concordet\Throwable\CondorcetInternalError.
    public function deleteOneEntity(int $key, bool $justTry): ?int
    {
        try {
            $this->df->delete()->record(key: $key);
        } catch (KeyNotExistException $e) {
            if ($justTry) {
                return null;
            } else {
                throw new CondorcetInternalError('key not exist');
            }
        }

        return 1;
    }

    // Return (int) max register key.
    // SQL example : SELECT max(key) FROM...
    public function selectMaxKey(): ?int
    {
        $maxKey = null;

        foreach ($this->df as $key => $record) {
            if ($key >= $maxKey) {
                $maxKey = $key;
            }
        }

        return $maxKey;
    }

    // Return (int) max register key.
    // SQL example : SELECT min(key) FROM...
    public function selectMinKey(): ?int
    {
        $minKey = null;

        foreach ($this->df as $key => $record) {
            if ($key <= $minKey) {
                $minKey = $key;
            }
        }

        return $minKey;
    }

    // Return (int) :nomber of recording
    public function countEntities(): int
    {
        return $this->df->count();
    }

    // Return one Entity by key
    public function selectOneEntity(int $key): string|bool
    {
        try {
            $r = $this->df->select()->record($key);
        } catch (KeyNotExistException) {
            return false;
        }

        return $r[$this->voteColumnName];
    }

    // Return an array of entity where $key is the first Entity and $limit is the maximum number of entity. Must return an array, keys must be preseve into there.
    // Arg example : (42, 3)
    // Return example : [42 => Condorcet/Vote, 43 => Condorcet/Vote, 44 => Condorcet/Vote]
    public function selectRangeEntities(int $key, int $limit): array
    {
        $r = $this->df
            ->selectAll()
            ->whereKeyBetween($key)
            ->limit($limit)
            ->toArray();

        foreach ($r as &$e) {
            $e = $e[$this->voteColumnName];
        }

        return $r;
    }
}
