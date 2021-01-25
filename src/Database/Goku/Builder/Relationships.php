<?php

namespace Devlob\Database\Goku\Builder;

use Devlob\Database\Goku\Model;
use Exception;
use PDO;

trait Relationships
{
    /**
     * Belongs to relationship.
     *
     * @param Model $model
     * @param       $search
     *
     * @return Model|null
     */
    public function belongsTo(Model $model, $search)
    {
        return $this->find($model, $search);
    }

    /**
     * Has many relationship.
     *
     * @param Model  $model
     * @param string $foreignKey
     * @param        $search
     *
     * @return array
     */
    public function hasMany(Model $model, string $foreignKey, $search): array
    {
        try {
            $sql = "SELECT * FROM {$model->getTable()} where $foreignKey = ?";

            $statement = $this->pdo->prepare($sql);

            $statement->execute([$search]);

            $objects = $statement->fetchAll(PDO::FETCH_CLASS, $model->getClass());

            if ($objects) {
                return $objects;
            }

            return [];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}