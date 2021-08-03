<?php
/*
    PDO DataHandler Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\CondorcetVersion;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use CondorcetPHP\Condorcet\Throwable\{CondorcetException, CondorcetInternalError};

class PdoHandlerDriver implements DataHandlerDriverInterface
{
    use CondorcetVersion;

    protected const SEGMENT = [499,50,4,1]; // Must be ordered desc.

    protected \PDO $_handler;
    protected bool $_transaction = false;
    protected bool $_queryError = false;

    // Database structure
    protected array $_struct;
    // Prepare Query
    protected array $_prepare = [];


    public function __construct (\PDO $bdd, bool $tryCreateTable = false, array $struct = ['tableName' => 'Entities', 'primaryColumnName' => 'id', 'dataColumnName' => 'data'])
    {
        if (!$this->checkStructureTemplate($struct)) :
            throw new CondorcetException;
        endif;

        $this->_struct = $struct;

        $this->_handler = $bdd;

        $this->_handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        if ($tryCreateTable) :
            $this->createTable();
        endif;

        $this->initPrepareQuery();
    }

    // INTERNAL

    protected function checkStructureTemplate (array &$struct): bool
    {
        if (    !empty($struct['tableName']) && !empty($struct['primaryColumnName']) && !empty($struct['dataColumnName']) &&
                \is_string($struct['tableName']) && \is_string($struct['primaryColumnName']) && \is_string($struct['dataColumnName'])
         ) :
            return true;
        else :
            return false;
        endif;
    }

    public function createTable (): void
    {
        $tableCreationQuery = match ($this->_handler->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
            default => 'CREATE TABLE IF NOT EXISTS '.$this->_struct['tableName'].' ('.$this->_struct['primaryColumnName'].' INT AUTO_INCREMENT PRIMARY KEY NOT NULL , '.$this->_struct['dataColumnName'].' BLOB NOT NULL );'
        };

        try {
            $this->_handler->exec($tableCreationQuery);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function initPrepareQuery (): void
    {
        $template = [];

        // Base - Small query ends
        $template['end_template'] = ';';
        $template['insert_template'] = 'INSERT INTO '.$this->_struct['tableName'].' ('.$this->_struct['primaryColumnName'].', '.$this->_struct['dataColumnName'].') VALUES ';
        $template['delete_template'] = 'DELETE FROM '.$this->_struct['tableName'].' WHERE '.$this->_struct['primaryColumnName'];
        $template['select_template'] = 'SELECT '.$this->_struct['primaryColumnName'].','.$this->_struct['dataColumnName'].' FROM '.$this->_struct['tableName'].' WHERE '.$this->_struct['primaryColumnName'];

        // Select the max / min key value. Usefull if array cursor is lost on DataManager.
        $this->_prepare['selectMaxKey'] = $this->_handler->prepare('SELECT max('.$this->_struct['primaryColumnName'].') FROM '.$this->_struct['tableName'] . $template['end_template']);
        $this->_prepare['selectMinKey'] = $this->_handler->prepare('SELECT min('.$this->_struct['primaryColumnName'].') FROM '.$this->_struct['tableName'] . $template['end_template']);

        // Insert many Entities
            $makeMany = static function (int $how) use (&$template): string {
                $query = $template['insert_template'];

                for ($i=1; $i < $how; $i++) :
                    $query .= '(:key'.$i.', :data'.$i.'),';
                endfor;

                $query .= '(:key'.$how.', :data'.$how.')' . $template['end_template'];

                return $query;
            };

            foreach (self::SEGMENT as $value) :
                $this->_prepare['insert'.$value.'Entities'] = $this->_handler->prepare($makeMany($value));
            endforeach;

        // Delete one Entity
        $this->_prepare['deleteOneEntity'] = $this->_handler->prepare($template['delete_template'] . ' = ?' . $template['end_template']);

        // Get a Entity
        $this->_prepare['selectOneEntity'] = $this->_handler->prepare($template['select_template'] . ' = ?' . $template['end_template']);

        // Get a range of Entity
        $this->_prepare['selectRangeEntities'] = $this->_handler->prepare($template['select_template'] . ' >= :startKey order by '.$this->_struct['primaryColumnName'].' asc LIMIT :limit' . $template['end_template']);

        // Count Entities
        $this->_prepare['countEntities'] = $this->_handler->prepare('SELECT count('.$this->_struct['primaryColumnName'].') FROM '. $this->_struct['tableName'] . $template['end_template']);
    }

    protected function initTransaction (): void
    {
        if (!$this->_transaction) :
            $this->_transaction = $this->_handler->beginTransaction();
        endif;
    }

    public function closeTransaction (): void
    {
        if ($this->_transaction === true) :
            if ($this->_queryError) :
                throw new CondorcetInternalError ('Query Error.');
            endif;

            $this->_transaction = !$this->_handler->commit();
        endif;
    }


    // DATA MANAGER
    public function insertEntities (array $input): void
    {
        $this->sliceInput($input);

        try {
            $this->initTransaction();

            foreach ($input as $group) :
                $param = [];
                $i = 1;
                $group_count = \count($group);

                foreach ($group as $key => &$Entity) :
                    $param['key'.$i] = $key;
                    $param['data'.$i++] = $Entity;
                endforeach;

                $this->_prepare['insert'.$group_count.'Entities']->execute(
                    $param
                );

                if ($this->_prepare['insert'.$group_count.'Entities']->rowCount() !== $group_count) :
                    throw new CondorcetInternalError ('Not all entities have been inserted');
                endif;

                $this->_prepare['insert'.$group_count.'Entities']->closeCursor();
            endforeach;

            $this->closeTransaction();
        } catch (\Throwable $e) {
            $this->_queryError = true;
            throw $e;
        }
    }

        protected function sliceInput (array &$input): void
        {
            $count = \count($input);

            foreach (self::SEGMENT as $value) :
                if ($count >= $value) :
                    $input = \array_chunk($input, $value, true);

                    $end = \end($input);
                    if (\count($input) > 1 && \count($end) < $value) :
                        $this->sliceInput($end);
                        unset($input[\key($input)]);
                        $input = \array_merge($input,$end);
                    endif;
                    break;
                endif;
            endforeach;
        }


    public function deleteOneEntity (int $key, bool $justTry): ?int
    {
        try {
            $this->_prepare['deleteOneEntity']->bindParam(1, $key, \PDO::PARAM_INT);
            $this->_prepare['deleteOneEntity']->execute();

            $deleteCount = $this->_prepare['deleteOneEntity']->rowCount();

            if (!$justTry && $deleteCount !== 1) :
                throw new CondorcetInternalError ('Entity deletion failure.');
            endif;

            $this->_prepare['deleteOneEntity']->closeCursor();

            return $deleteCount;
        } catch (\Throwable $e) {
            $this->_queryError = true;
            throw $e;
        }
    }

    public function selectMaxKey (): ?int
    {
        if ($this->countEntities() === 0) :
            return null;
        endif;

        $this->_prepare['selectMaxKey']->execute();
        $r = (int) $this->_prepare['selectMaxKey']->fetch(\PDO::FETCH_NUM)[0];
        $this->_prepare['selectMaxKey']->closeCursor();

        return $r;
    }

    public function selectMinKey (): int
    {
        $this->_prepare['selectMinKey']->execute();
        $r = (int) $this->_prepare['selectMinKey']->fetch(\PDO::FETCH_NUM)[0];
        $this->_prepare['selectMinKey']->closeCursor();

        return $r;
    }

    public function countEntities (): int
    {
        $this->_prepare['countEntities']->execute();
        $r = (int) $this->_prepare['countEntities']->fetch(\PDO::FETCH_NUM)[0];
        $this->_prepare['countEntities']->closeCursor();

        return $r;
    }

    // return false if Entity does not exist.
    public function selectOneEntity (int $key): string|bool
    {
        $this->_prepare['selectOneEntity']->bindParam(1, $key, \PDO::PARAM_INT);
        $this->_prepare['selectOneEntity']->execute();

        $r = $this->_prepare['selectOneEntity']->fetchAll(\PDO::FETCH_NUM);
        $this->_prepare['selectOneEntity']->closeCursor();
        if (!empty($r)) :
            return $r[0][1];
        else :
            return false;
        endif;
    }

    public function selectRangeEntities (int $key, int $limit): array
    {
        $this->_prepare['selectRangeEntities']->bindParam(':startKey', $key, \PDO::PARAM_INT);
        $this->_prepare['selectRangeEntities']->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $this->_prepare['selectRangeEntities']->execute();

        $r = $this->_prepare['selectRangeEntities']->fetchAll(\PDO::FETCH_NUM);
        $this->_prepare['selectRangeEntities']->closeCursor();
        if (!empty($r)) :
            $result = [];
            foreach ($r as $value) :
                $result[(int) $value[0]] = $value[1];
            endforeach ;

            return $result;
        else :
            return [];
        endif;
    }
}
