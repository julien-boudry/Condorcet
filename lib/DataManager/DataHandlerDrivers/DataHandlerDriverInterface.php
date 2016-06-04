<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
//declare(strict_types=1);

namespace Condorcet\DataManager\DataHandlerDrivers;

interface DataHandlerDriverInterface
{
    public function insertEntitys(array $input);
    public function updateOneEntity ($key,$data);
    public function deleteOneEntity ($key, $justTry);
    public function selectMaxKey ();
    public function selectMinKey ();
    public function countEntitys ();
    public function selectOneEntity ($key);
    public function selectRangeEntitys ($key, $limit);
    public function flushAll ();
}