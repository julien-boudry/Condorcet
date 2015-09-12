<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\DataManager\BDD;

use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;

class BddHandler
{
    use CondorcetVersion;

    protected $_handler;

    // Database structure
    public $tableName = 'votes';
    public $primaryColumnName = 'key';
    public $dataColumnName = 'data';

    public function __construct ($bdd, $tryCreateTable = true) {

        if (is_string($bdd)) :

            $this->_handler = new \PDO ('sqlite:'.$bdd);

        elseif ($bdd instanceof \PDO) :

            $this->_handler = $bdd;

        else :
            throw new CondorcetException;
        endif;

        if ($tryCreateTable) {
            try {
                $this->_handler->exec('CREATE  TABLE  IF NOT EXISTS '.$this->tableName.' ('.$this->primaryColumnName.' INTEGER PRIMARY KEY NOT NULL , '.$this->dataColumnName.' BLOB NOT NULL )');
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
}