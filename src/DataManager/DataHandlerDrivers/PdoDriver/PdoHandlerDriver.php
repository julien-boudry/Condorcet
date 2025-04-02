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

use CondorcetPHP\Condorcet\CondorcetVersion;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use CondorcetPHP\Condorcet\Throwable\DataHandlerException;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError;

class PdoHandlerDriver implements DataHandlerDriverInterface
{
    use CondorcetVersion;

    /**
     * @api
     */
    public const array SEGMENT = [499, 50, 4, 1]; // Must be ordered desc.

    protected readonly \PDO $handler;
    protected bool $transaction = false;
    protected bool $queryError = false;

    // Database structure

    /**
     * @api
     */
    public static bool $preferBlobInsteadVarchar = true;

    protected array $struct;
    // Prepare Query
    protected array $prepare = [];
    /**
     * @throws DataHandlerException
     */
    public function __construct(\PDO $bdd, bool $tryCreateTable = false, array $struct = ['tableName' => 'Entities', 'primaryColumnName' => 'id', 'dataColumnName' => 'data'])
    {
        if (!$this->checkStructureTemplate($struct)) {
            throw new DataHandlerException('invalid structure template for PdoHandler');
        }

        $this->struct = $struct;

        $this->handler = $bdd;

        $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        if ($tryCreateTable) {
            $this->createTable();
        }

        $this->initPrepareQuery();
    }

    // INTERNAL

    protected function checkStructureTemplate(array &$struct): bool
    {
        if (!empty($struct['tableName']) && !empty($struct['primaryColumnName']) && !empty($struct['dataColumnName']) &&
                \is_string($struct['tableName']) && \is_string($struct['primaryColumnName']) && \is_string($struct['dataColumnName'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function createTable(): void
    {
        $dataType = (self::$preferBlobInsteadVarchar) ? 'BLOB' : 'VARCHAR';

        $tableCreationQuery = match ($this->handler->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
            default => 'CREATE TABLE IF NOT EXISTS ' . $this->struct['tableName'] . ' (' . $this->struct['primaryColumnName'] . ' INT AUTO_INCREMENT PRIMARY KEY NOT NULL , ' . $this->struct['dataColumnName'] . ' ' . $dataType . ' NOT NULL );'
        };

        $this->handler->exec($tableCreationQuery);
    }

    protected function initPrepareQuery(): void
    {
        $template = [];

        // Base - Small query ends
        $template['end_template'] = ';';
        $template['insert_template'] = 'INSERT INTO ' . $this->struct['tableName'] . ' (' . $this->struct['primaryColumnName'] . ', ' . $this->struct['dataColumnName'] . ') VALUES ';
        $template['delete_template'] = 'DELETE FROM ' . $this->struct['tableName'] . ' WHERE ' . $this->struct['primaryColumnName'];
        $template['select_template'] = 'SELECT ' . $this->struct['primaryColumnName'] . ',' . $this->struct['dataColumnName'] . ' FROM ' . $this->struct['tableName'] . ' WHERE ' . $this->struct['primaryColumnName'];

        // Select the max / min key value. Usefull if array cursor is lost on DataManager.
        $this->prepare['selectMaxKey'] = $this->handler->prepare('SELECT max(' . $this->struct['primaryColumnName'] . ') FROM ' . $this->struct['tableName'] . $template['end_template']);
        $this->prepare['selectMinKey'] = $this->handler->prepare('SELECT min(' . $this->struct['primaryColumnName'] . ') FROM ' . $this->struct['tableName'] . $template['end_template']);

        // Insert many Entities
        $makeMany = static function (int $how) use (&$template): string {
            $query = $template['insert_template'];

            for ($i = 1; $i <= $how; $i++) {
                $query .= '(:key' . $i . ', :data' . $i . ')';

                if ($i !== $how) {
                    $query .= ',';
                }
            }

            return $query . $template['end_template'];
        };

        foreach (self::SEGMENT as $value) {
            $this->prepare['insert' . $value . 'Entities'] = $this->handler->prepare($makeMany($value));
        }

        // Delete one Entity
        $this->prepare['deleteOneEntity'] = $this->handler->prepare($template['delete_template'] . ' = ?' . $template['end_template']);

        // Get a Entity
        $this->prepare['selectOneEntity'] = $this->handler->prepare($template['select_template'] . ' = ?' . $template['end_template']);

        // Get a range of Entity
        $this->prepare['selectRangeEntities'] = $this->handler->prepare($template['select_template'] . ' >= :startKey order by ' . $this->struct['primaryColumnName'] . ' asc LIMIT :limit' . $template['end_template']);

        // Count Entities
        $this->prepare['countEntities'] = $this->handler->prepare('SELECT count(' . $this->struct['primaryColumnName'] . ') FROM ' . $this->struct['tableName'] . $template['end_template']);
    }

    protected function initTransaction(): void
    {
        if (!$this->transaction) {
            $this->transaction = $this->handler->beginTransaction();
        }
    }

    public function closeTransaction(): void
    {
        if ($this->transaction) {
            /**
             * @infection-ignore-all
             */
            if ($this->queryError) {
                throw new CondorcetInternalError('Query Error.');
            }

            $this->transaction = !$this->handler->commit();
        }
    }


    // DATA MANAGER
    public function insertEntities(array $input): void
    {
        $this->sliceInput($input);

        try {
            $this->initTransaction();

            foreach ($input as $group) {
                $param = [];
                $i = 1;
                $group_count = \count($group);

                foreach ($group as $key => &$Entity) {
                    $param['key' . $i] = $key;
                    $param['data' . $i++] = $Entity;
                }

                $this->prepare['insert' . $group_count . 'Entities']->execute(
                    $param
                );

                /**
                 * @infection-ignore-all
                 */
                if ($this->prepare['insert' . $group_count . 'Entities']->rowCount() !== $group_count) {
                    throw new CondorcetInternalError('Not all entities have been inserted');
                }

                $this->prepare['insert' . $group_count . 'Entities']->closeCursor();
            }

            $this->closeTransaction();
        } catch (\Throwable $e) {
            $this->queryError = true;
            throw $e;
        }
    }

    protected function sliceInput(array &$input): void
    {
        $count = \count($input);

        foreach (self::SEGMENT as $value) {
            if ($count >= $value) {
                $input = array_chunk($input, $value, true);

                $end = end($input);
                if (\count($input) > 1 && \count($end) < $value) {
                    $this->sliceInput($end);
                    unset($input[key($input)]);
                    $input = array_merge($input, $end);
                }
                break;
            }
        }
    }


    public function deleteOneEntity(int $key, bool $justTry): ?int
    {
        try {
            $this->prepare['deleteOneEntity']->bindParam(1, $key, \PDO::PARAM_INT);
            $this->prepare['deleteOneEntity']->execute();

            $deleteCount = $this->prepare['deleteOneEntity']->rowCount();

            /**
             * @infection-ignore-all
             */
            if (!$justTry && $deleteCount !== 1) {
                throw new CondorcetInternalError('Entity deletion failure.');
            }

            $this->prepare['deleteOneEntity']->closeCursor();

            return $deleteCount;
        } catch (\Throwable $e) {
            $this->queryError = true;
            throw $e;
        }
    }

    public function selectMaxKey(): ?int
    {
        if ($this->countEntities() === 0) {
            return null;
        }

        $this->prepare['selectMaxKey']->execute();
        $r = (int) $this->prepare['selectMaxKey']->fetch(\PDO::FETCH_NUM)[0];
        $this->prepare['selectMaxKey']->closeCursor();

        return $r;
    }

    public function selectMinKey(): int
    {
        $this->prepare['selectMinKey']->execute();
        $r = (int) $this->prepare['selectMinKey']->fetch(\PDO::FETCH_NUM)[0];
        $this->prepare['selectMinKey']->closeCursor();

        return $r;
    }

    public function countEntities(): int
    {
        $this->prepare['countEntities']->execute();
        $r = (int) $this->prepare['countEntities']->fetch(\PDO::FETCH_NUM)[0];
        $this->prepare['countEntities']->closeCursor();

        return $r;
    }

    // return false if Entity does not exist.
    public function selectOneEntity(int $key): string|bool
    {
        $this->prepare['selectOneEntity']->bindParam(1, $key, \PDO::PARAM_INT);
        $this->prepare['selectOneEntity']->execute();

        $r = $this->prepare['selectOneEntity']->fetchAll(\PDO::FETCH_NUM);
        $this->prepare['selectOneEntity']->closeCursor();
        if (!empty($r)) {
            return $r[0][1];
        } else {
            return false;
        }
    }

    public function selectRangeEntities(int $key, int $limit): array
    {
        $this->prepare['selectRangeEntities']->bindParam(':startKey', $key, \PDO::PARAM_INT);
        $this->prepare['selectRangeEntities']->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $this->prepare['selectRangeEntities']->execute();

        $r = $this->prepare['selectRangeEntities']->fetchAll(\PDO::FETCH_NUM);
        $this->prepare['selectRangeEntities']->closeCursor();
        if (!empty($r)) {
            $result = [];
            foreach ($r as $value) {
                $result[(int) $value[0]] = $value[1];
            }


            return $result;
        } else {
            return [];
        }
    }
}
