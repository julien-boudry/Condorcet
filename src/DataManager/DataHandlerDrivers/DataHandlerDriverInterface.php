<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers;

interface DataHandlerDriverInterface
{
    /* public $_dataContextObject; */
    // The Data Manager will set an object into this property. You should call for each Entity $_dataContextObject->dataCallBack($EntityData) before returning stored data by the two select method.


    // Entities to register.
    // Ex: [Condorcet/Vote,Condorcet/Vote,Condorcet/Vote]. The key should not be kept
    public function insertEntities(array $input): void;

    // Delete Entity with this key. If justTry is true, don't throw Exception if row not exist. Else throw an \CondorcetPHP\Concordet\Throwable\CondorcetInternalError.
    public function deleteOneEntity(int $key, bool $justTry): ?int;

    // Return (int) max register key.
    // SQL example : SELECT max(key) FROM...
    public function selectMaxKey(): ?int;

    // Return (int) max register key.
    // SQL example : SELECT min(key) FROM...
    public function selectMinKey(): ?int;

    // Return (int) :nomber of recording
    // SQL example : SELECT count(*) FROM...
    public function countEntities(): int;

    // Return one Entity by key
    public function selectOneEntity(int $key): string|bool;

    // Return an array of entity where $key is the first Entity and $limit is the maximum number of entity. Must return an array, keys must be preseve into there.
    // Arg example : (42, 3)
    // Return example : [42 => Condorcet/Vote, 43 => Condorcet/Vote, 44 => Condorcet/Vote]
    public function selectRangeEntities(int $key, int $limit): array;
}
