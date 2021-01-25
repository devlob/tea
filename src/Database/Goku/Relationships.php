<?php

namespace Devlob\Database\Goku;

use App\Bootstrap\App;
use Exception;

trait Relationships
{
    /**
     * Belongs to relationship.
     *
     * @param             $class
     * @param string|null $key
     *
     * @return Model|null
     * @throws Exception
     */
    public function belongsTo($class, string $key = null)
    {
        $search = $this->{$this->getRelationshipKey($key)};

        return App::get('Database')->belongsTo(new $class, $search);
    }

    /**
     * Has many relationship.
     *
     * @param             $class
     * @param string|null $key
     *
     * @return array
     * @throws Exception
     */
    public function hasMany($class, string $key = null): array
    {
        $key    = $this->getRelationshipKey($key);
        $search = $this->{$this->getKey()};

        return App::get('Database')->hasMany(new $class, $key, $search);
    }

    /**
     * Get relationship key.
     *
     * @param string|null $key
     *
     * @return string
     * @throws Exception
     */
    private function getRelationshipKey(string $key = null): string
    {
        if ($key !== null) {
            return $key;
        }

        $caller = debug_backtrace()[1]['function'];

        switch ($caller) {
            case 'belongsTo':
                $callingFunction = debug_backtrace()[2]['function'];

                return $callingFunction . '_id';
            case 'hasMany':
                return strtolower(singularize(substr($this->getClass(), strrpos($this->getClass(), '\\') + 1))) . '_id';
            default:
                throw new Exception('Incorrect relationship');
        }
    }
}