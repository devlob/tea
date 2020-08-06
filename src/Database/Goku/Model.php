<?php

namespace Devlob\Database\Goku;

use App\Bootstrap\App;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * Class Model
 *
 * Goku model.
 *
 * @package Devlob\Database\Goku
 */
class Model
{
    use Relationships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table = '';

    /**
     * The key used for operations.
     *
     * @var string
     */
    protected static $key = 'id';

    /**
     * Get all entities.
     *
     * @return array
     * @throws Exception
     */
    public static function all()
    {
        return App::get('Database')->all(new static());
    }

    /**
     * Find an entity by search.
     *
     * @param $search
     *
     * @return Model|null
     * @throws Exception
     */
    public static function find($search)
    {
        return App::get('Database')->find(new static(), $search);
    }

    /**
     * * Find entities by using a where statement.
     *
     * @param string $key
     * @param string $operation
     * @param string $value
     *
     * @return mixed
     * @throws Exception
     */
    public static function where(string $key, string $operation, string $value)
    {
        return App::get('Database')->where(new static(), $key, $operation, $value);
    }

    /**
     * Create a new entity.
     *
     * @param array $data
     *
     * @return Model|null
     * @throws Exception
     */
    public static function create(array $data)
    {
        $model = new static();

        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        return App::get('Database')->insert($model);
    }

    /**
     * Update an existing entity.
     *
     * @param array $data
     *
     * @return Model|null
     * @throws Exception
     */
    public function update(array $data)
    {
        $model = new $this;

        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        return App::get('Database')->update($model, $this->{$this->getKey()});
    }

    /**
     * Delete an entity.
     *
     * @return bool
     * @throws Exception
     */
    public function delete(): bool
    {
        return App::get('Database')->delete($this);
    }

    /**
     * Get table associated with the model.
     *
     * @return string
     * @throws ReflectionException
     */
    public function getTable(): string
    {
        if (empty(static::$table)) {
            return strtolower(pluralize($this->getReflectionClass()->getShortName()));
        }

        return static::$table;
    }

    /**
     * Set key used for operations.
     *
     * @param string $key
     */
    public function setKey(string $key): void
    {
        static::$key = $key;
    }

    /**
     * Get key used for operations.
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::$key;
    }

    /**
     * Get class extending Model.
     *
     * @return string
     */
    public function getClass(): string
    {
        return get_called_class();
    }

    /**
     * Get reflection class extending model.
     *
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public function getReflectionClass(): ReflectionClass
    {
        return new ReflectionClass($this->getClass());
    }

    /**
     * Convert model to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this;
    }

    /**
     * Get model property or model method.
     *
     * @param $name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        $methodName = 'get' . str_replace('_', '', ucwords($name, '_')) . 'Attribute';

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new Exception("Undefined property. Check if '$name' exists in '{$this->getClass()}' model.");
    }
}