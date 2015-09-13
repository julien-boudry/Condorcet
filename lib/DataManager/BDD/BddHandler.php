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

    public function __construct ($bdd, $tryCreateTable = true, $struct = ['tableName' => 'votes', 'primaryColumnName' => 'key', 'dataColumnName' => 'data'])
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
        $template['delete_template'] = 'DELETE FROM '.$this->_struct['tableName'].' WHERE '.$this->_struct['primaryColumnName'].' = ';
        $template['select_template'] = 'SELECT '.$this->_struct['dataColumnName'].' FROM '.$this->_struct['tableName'].' WHERE '.$this->_struct['primaryColumnName'].' = ';

        // Select the max key value. Usefull if array cursor is lost on DataManager.
        $this->_prepare['selectMaxKey'] = $this->_handler->prepare('SELECT max('.$this->_struct['primaryColumnName'].') FROM '.$this->_struct['tableName'] . $template['end_template']);

        // Insert many Votes
            $makeMany = function ($how) use (&$template) {
                $query = $template['insert_template'];
                
                for ($i=1; $i < $how; $i++) {
                    $query .= '(:key'.$i.', :data'.$i.'),';
                }

                $query .= '(:key'.$how.', :data'.$how.')' . $template['end_template'];

                return $query;
            };

            foreach (explode(',', self::SEGMENT) as $value) {
                $this->_prepare['insert'.$value.'Votes'] = $this->_handler->prepare($makeMany($value));
            }

        // Delete one Vote
        $this->_prepare['deleteOneVote'] = $this->_handler->prepare($template['delete_template'] . '?' . $template['end_template']);

        // Get a Vote
        $this->_prepare['selectOneVote'] = $this->_handler->prepare($template['select_template'] . '?' . $template['end_template']);
    }


    // DATA MANAGER
    public function insertVotes (array $input) {
        
        $this->sliceInput($input);

        try {

            $this->_handler->beginTransaction();

            foreach ($input as $group) :
                $param = [];
                $i = 1;
                $group_count = count($group);

                foreach ($group as $key => &$vote) :
                    $param['key'.$i] = $key;
                    $param['data'.$i++] = $vote;
                endforeach; unset($vote);

                $this->_prepare['insert'.$group_count.'Votes']->execute(
                    $param
                );

                if ($this->_prepare['insert'.$group_count.'Votes']->rowCount() !== $group_count) :
                    throw new CondorcetException (0,'Tous les votes n\'ont pas été insérés');
                endif;

                $this->_prepare['insert'.$group_count.'Votes']->closeCursor();
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

    public function deleteOneVote ($key)
    {
        try {
            $this->_handler->beginTransaction();

            $this->_prepare['deleteOneVote']->bindParam(1, $key, \PDO::PARAM_INT);
            $this->_prepare['deleteOneVote']->execute();

            if ($this->_prepare['deleteOneVote']->rowCount() !== 1) :
                throw new CondorcetException (0,'Ce vote n\'existe pas !');
            endif;

            $this->_handler->commit();
            $this->_prepare['deleteOneVote']->closeCursor();
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

    // return false if vote does not exist.
    public function selectOneVote ($key)
    {
        try {
            $this->_prepare['selectOneVote']->bindParam(1, $key, \PDO::PARAM_INT);
            $this->_prepare['selectOneVote']->execute([$key]);
            
            $r = $this->_prepare['selectOneVote']->fetchAll(\PDO::FETCH_NUM);
            $this->_prepare['selectOneVote']->closeCursor();
            if (!empty($r)) :
                return  $r[0][0];
            else :
                return false;
            endif;
        } catch (Exception $e) {
            throw $e;
        }
    }

}