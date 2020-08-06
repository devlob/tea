<?php

namespace Devlob\Database\Goku\Builder;

use Devlob\Database\Goku\Model;
use Devlob\Database\Migrations\Columns\Drivers\MysqlColumn;
use Devlob\Database\Migrations\Columns\Drivers\PostgreSqlColumn;
use Devlob\Database\Migrations\Constraints\Drivers\MySqlConstraint;
use Devlob\Database\Migrations\Constraints\Drivers\PostgreSqlConstraint;
use Devlob\Database\Migrations\Generator;
use Exception;
use PDO;

/**
 * Class QueryBuilder
 *
 * Execute database queries.
 *
 * @package Devlob\Database\Goku\Builder
 */
class QueryBuilder
{
    use Events, Relationships;

    /**
     * PDO holder.
     */
    protected $pdo = null;

    /**
     * QueryBuilder constructor.
     *
     * @param $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all entities.
     *
     * @param Model $model
     *
     * @return array
     */
    public function all(Model $model): array
    {
        try {
            $sql = "SELECT * FROM {$model->getTable()}";

            $statement = $this->pdo->prepare($sql);

            $statement->execute();

            $objects = $statement->fetchAll(PDO::FETCH_CLASS, $model->getClass());

            if ($objects) {
                return $objects;
            }

            return [];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Find an entity by search.
     *
     * @param Model $model
     * @param       $search
     *
     * @return Model|null
     */
    public function find(Model $model, $search)
    {
        try {
            $sql = "SELECT * FROM {$model->getTable()} where {$model->getKey()} = ? limit 1";

            $statement = $this->pdo->prepare($sql);

            $statement->execute([$search]);

            $object = $statement->fetchObject($model->getClass());

            if ($object) {
                return $object;
            }

            return null;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Find entities by using a where statement.
     *
     * @param Model  $model
     * @param string $key
     * @param string $operation
     * @param string $value
     *
     *
     * @return array
     */
    public function where(Model $model, string $key, string $operation, string $value): array
    {
        try {
            $sql = "SELECT * FROM {$model->getTable()} where $key $operation ?";

            $statement = $this->pdo->prepare($sql);

            $statement->execute([$value]);

            $objects = $statement->fetchAll(PDO::FETCH_CLASS, $model->getClass());

            if ($objects) {
                return $objects;
            }

            return [];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Create a new entity.
     *
     * @param Model $model
     *
     * @return Model|null
     */
    public function insert(Model $model)
    {
        try {
            $this->beforeInsert($model);

            $keys = array_keys($model->toArray());

            $sql = sprintf(
                "insert into {$model->getTable()} (%s) values (%s)",
                implode(', ', $keys),
                ':' . implode(', :', $keys)
            );

            $statement = $this->pdo->prepare($sql);

            $statement->execute($model->toArray());

            $model->setKey('id');
            $object = $this->find($model, $this->pdo->lastInsertId());

            $this->afterInsert($object);

            return $object;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Update an existing entity.
     *
     * @param Model $model
     * @param       $search
     *
     * @return Model|null
     */
    public function update(Model $model, $search)
    {
        try {
            $this->beforeUpdate($model);

            $keys = array_keys($model->toArray());

            $set = '';
            foreach ($keys as $k) {
                $set .= "$k=:$k, ";
            }

            $sql = sprintf(
                "UPDATE {$model->getTable()} SET %s WHERE {$model->getKey()}=:{$model->getKey()}",
                rtrim($set, ', ')
            );

            $statement = $this->pdo->prepare($sql);

            $model->{$model->getKey()} = $search;

            $statement->execute($model->toArray());

            $object = $this->find($model, $search);

            $this->afterUpdate($object);

            return $object;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Delete an entity.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function delete(Model $model): bool
    {
        try {
            $this->beforeDelete($model);

            $sql = "DELETE FROM {$model->getTable()} where {$model->getKey()}=?";

            $statement = $this->pdo->prepare($sql);

            $statement->execute([$model->{$model->getKey()}]);

            $this->afterDelete($model);

            return true;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Create a new table.
     *
     * @param string $table
     * @param array  $columns
     * @param array  $constraints
     */
    public function createTable(string $table, array $columns, array $constraints): void
    {
        try {
            $generator = new Generator();

            $generator->driverSpecific($this->pdo, $columns);

            $sql = "CREATE TABLE $table ({$generator->generate($columns, $constraints)})";

            $this->pdo->exec($sql);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Drop an existing table.
     *
     * @param string $table
     */
    public function dropTable(string $table): void
    {
        try {
            $sql = "DROP TABLE IF EXISTS $table";

            $this->pdo->exec($sql);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getDriver()
    {
        return $this->pdo;
    }

    public function getColumnDriver()
    {
        $lookup = [
            'mysql' => MysqlColumn::class,
            'pgsql' => PostgreSqlColumn::class
        ];

        $className = $lookup[$this->getDriver()->getAttribute(PDO::ATTR_DRIVER_NAME)];

        return new $className;
    }

    public function getConstraintDriver()
    {
        $lookup = [
            'mysql' => MySqlConstraint::class,
            'pgsql' => PostgreSqlConstraint::class
        ];

        $className = $lookup[$this->getDriver()->getAttribute(PDO::ATTR_DRIVER_NAME)];

        return new $className;
    }
}