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

    const SEGMENT = '300,100,50,10,5,1';

    protected $_handler;

    // Database structure
    protected $_struct;
    // Prepare Query
    protected $_prepare = [];

    public function __construct ($bdd, $tryCreateTable = true, $struct = ['tableName' => 'Entitys', 'primaryColumnName' => 'key', 'dataColumnName' => 'data'])
    {
        $this->_struct = $this->checkStructureTemplate($struct);

        if (is_string($bdd)) :

            $this->_handler = new \PDO ('sqlite:'.$bdd);

        elseif ($bdd instanceof \PDO) :

            $this->_handler = $bdd;

        else :
            throw new CondorcetException;
        endif;

        $this->_handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        if ($tryCreateTable) {
            try {
                $this->_handler->exec('CREATE  TABLE  IF NOT EXISTS '.$this->_struct['tableName'].' ('.$this->_struct['primaryColumnName'].' INTEGER PRIMARY KEY NOT NULL , '.$this->_struct['dataColumnName'].' BLOB NOT NULL )');
            } catch (Exception $e) {
                throw $e;
            }
        }

        $this->initPrepareQuery();
    }

    // INTERNAL

    protected function checkStructureTemplate ($struct)
    {
        // Need to be completed
        return $struct;
    }

    protected function initPrepareQuery ()
    {
        // Base - Small query ends
        $template['end_template'] = ';';
        $template['insert_template'] = 'INSERT INTO '.$this->_struct['tableName'].' ('.$this->_struct['primaryColumnName'].', '.$this->_struct['dataColumnName'].') VALUES ';
        $template['delete_template'] = 'DELETE FROM '.$this->_struct['tableName'].' WHERE '.$this->_struct['primaryColumnName'];
        $template['select_template'] = 'SELECT '.$this->_struct['primaryColumnName'].','.$this->_struct['dataColumnName'].' FROM '.$this->_struct['tableName'].' WHERE '.$this->_struct['primaryColumnName'];
        $template['update_template'] = 'UPDATE '.$this->_struct['tableName'].' SET '.$this->_struct['dataColumnName'].' = :data WHERE '.$this->_struct['primaryColumnName'];

        // Select the max / min key value. Usefull if array cursor is lost on DataManager.
        $this->_prepare['selectMaxKey'] = $this->_handler->prepare('SELECT max('.$this->_struct['primaryColumnName'].') FROM '.$this->_struct['tableName'] . $template['end_template']);
        $this->_prepare['selectMinKey'] = $this->_handler->prepare('SELECT min('.$this->_struct['primaryColumnName'].') FROM '.$this->_struct['tableName'] . $template['end_template']);

        // Insert many Entitys
            $makeMany = function ($how) use (&$template) {
                $query = $template['insert_template'];
                
                for ($i=1; $i < $how; $i++) {
                    $query .= '(:key'.$i.', :data'.$i.'),';
                }

                $query .= '(:key'.$how.', :data'.$how.')' . $template['end_template'];

                return $query;
            };

            foreach (explode(',', self::SEGMENT) as $value) {
                $this->_prepare['insert'.$value.'Entitys'] = $this->_handler->prepare($makeMany($value));
            }

        // Delete one Entity
        $this->_prepare['deleteOneEntity'] = $this->_handler->prepare($template['delete_template'] . ' = ?' . $template['end_template']);

        // Get a Entity
        $this->_prepare['selectOneEntity'] = $this->_handler->prepare($template['select_template'] . ' = ?' . $template['end_template']);

        // Get a range of Entity
        $this->_prepare['selectRangeEntitys'] = $this->_handler->prepare($template['select_template'] . ' >= :startKey order by '.$this->_struct['primaryColumnName'].' asc LIMIT :limit' . $template['end_template']);

        // Count Entitys
        $this->_prepare['countEntitys'] = $this->_handler->prepare('SELECT count('.$this->_struct['primaryColumnName'].') FROM '. $this->_struct['tableName'] . $template['end_template']);

        // Update Entity
        $this->_prepare['updateOneEntity'] = $this->_handler->prepare($template['update_template'] . ' = :key' . $template['end_template']);

        // Flush All
        $this->_prepare['flushAll'] = $this->_handler->prepare($template['delete_template'] . ' is not null' . $template['end_template']);
    }


    // DATA MANAGER
    public function insertEntitys (array $input)
    {        
        $this->sliceInput($input);

        try {

            $this->_handler->beginTransaction();

            foreach ($input as $group) :
                $param = [];
                $i = 1;
                $group_count = count($group);

                foreach ($group as $key => &$Entity) :
                    $param['key'.$i] = $key;
                    $param['data'.$i++] = $Entity;
                endforeach; unset($Entity);

                $this->_prepare['insert'.$group_count.'Entitys']->execute(
                    $param
                );

                if ($this->_prepare['insert'.$group_count.'Entitys']->rowCount() !== $group_count) :
                    throw new CondorcetException (0,'Tous les Entitys n\'ont pas été insérés');
                endif;

                $this->_prepare['insert'.$group_count.'Entitys']->closeCursor();
            endforeach;

            $this->_handler->commit();

        } catch (\Exception $e) {
            $this->_handler->rollBack();
            throw $e;
        }
    }

        protected function sliceInput (array &$input)
        {
            $count = count($input);

            foreach (explode(',', self::SEGMENT) as $value) :
                if ($count >= $value) :
                    $input = array_chunk($input, $value, true);

                    $end = end($input);
                    if (count($input) > 1 && count($end) < $value) :
                        $this->sliceInput($end);
                        unset($input[key($input)]);
                        $input = array_merge($input,$end);
                    endif;
                    break;
                endif;
            endforeach;
        }

    public function updateOneEntity ($key,$data)
    {
        try {
            $this->_handler->beginTransaction();

            $this->_prepare['updateOneEntity']->bindParam(':key', $key, \PDO::PARAM_INT);
            $this->_prepare['updateOneEntity']->bindParam(':data', $data, \PDO::PARAM_STR);

            $this->_prepare['updateOneEntity']->execute();

            if ($this->_prepare['updateOneEntity']->rowCount() !== 1) :
                throw new CondorcetException (0,'Ce Entity n\'existe pas !');
            endif;

            $this->_handler->commit();
            $this->_prepare['updateOneEntity']->closeCursor();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteOneEntity ($key, $justTry = false)
    {
        try {
            $this->_handler->beginTransaction();

            $this->_prepare['deleteOneEntity']->bindParam(1, $key, \PDO::PARAM_INT);
            $this->_prepare['deleteOneEntity']->execute();

            $deleteCount = $this->_prepare['deleteOneEntity']->rowCount();

            if (!$justTry && $deleteCount !== 1) :
                throw new CondorcetException (30);
            endif;

            $this->_handler->commit();
            $this->_prepare['deleteOneEntity']->closeCursor();

            return $deleteCount;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function selectMaxKey ()
    {
        try {
            $this->_prepare['selectMaxKey']->execute();
            $r = (int) $this->_prepare['selectMaxKey']->fetch(\PDO::FETCH_NUM)[0];
            $this->_prepare['selectMaxKey']->closeCursor();

            return $r;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function selectMinKey ()
    {
        try {
            $this->_prepare['selectMinKey']->execute();
            $r = (int) $this->_prepare['selectMinKey']->fetch(\PDO::FETCH_NUM)[0];
            $this->_prepare['selectMinKey']->closeCursor();

            return $r;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function countEntitys ()
    {
        try {
            $this->_prepare['countEntitys']->execute();
            $r = (int) $this->_prepare['countEntitys']->fetch(\PDO::FETCH_NUM)[0];
            $this->_prepare['countEntitys']->closeCursor();

            return $r;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // return false if Entity does not exist.
    public function selectOneEntity ($key)
    {
        try {
            $this->_prepare['selectOneEntity']->bindParam(1, $key, \PDO::PARAM_INT);
            $this->_prepare['selectOneEntity']->execute();
            
            $r = $this->_prepare['selectOneEntity']->fetchAll(\PDO::FETCH_NUM);
            $this->_prepare['selectOneEntity']->closeCursor();
            if (!empty($r)) :
                return  $r[0][1];
            else :
                return false;
            endif;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function selectRangeEntitys ($key, $limit)
    {
        try {
            $this->_prepare['selectRangeEntitys']->bindParam(':startKey', $key, \PDO::PARAM_INT);
            $this->_prepare['selectRangeEntitys']->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $this->_prepare['selectRangeEntitys']->execute();
            
            $r = $this->_prepare['selectRangeEntitys']->fetchAll(\PDO::FETCH_NUM);
            $this->_prepare['selectRangeEntitys']->closeCursor();
            if (!empty($r)) :
                $result = [];
                foreach ($r as $value) :
                    $result[(int) $value[0]] = $value[1];
                endforeach ;

                return $result;
            else :
                return [];
            endif;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function flushAll ()
    {
        try {
            $this->_prepare['flushAll']->execute();
            $r = $this->_prepare['flushAll']->rowCount();

            $this->_prepare['flushAll']->closeCursor();

            return $r;
        } catch (Exception $e) {
            throw $e;
        }      
    }

}