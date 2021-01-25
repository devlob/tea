<?php

namespace Devlob\Database\Migrations;

use Devlob\Database\Migrations\Constraints\Constraints;

class Blueprint extends Constraints
{
    /**
     * Store columns.
     *
     * @var array
     */
    private $columns = [];

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * ID type.
     *
     * @return Blueprint
     */
    public function id(): Blueprint
    {
        $this->setColumn('id');

        $this->addColumn('id', 'id');

        return $this;
    }

    /**
     * Unsigned integer data type.
     *
     * @param string $column
     *
     * @return Blueprint
     */
    public function unsignedInteger(string $column): Blueprint
    {
        $this->setColumn($column);

        $this->integer($column, true);

        return $this;
    }

    /**
     * Integer data type.
     *
     * @param string $column
     * @param bool   $unsigned
     *
     * @return Blueprint
     */
    public function integer(string $column, $unsigned = false): Blueprint
    {
        $this->setColumn($column);

        $this->addColumn('integer', $column, compact('unsigned'));

        return $this;
    }

    /**
     * String data type.
     *
     * @param string   $column
     * @param int|null $length
     *
     * @return Blueprint
     */
    public function string(string $column, int $length = null): Blueprint
    {
        $this->setColumn($column);

        $length = $length ?: 255;

        $this->addColumn('string', $column, compact('length'));

        return $this;
    }

    /**
     * Enum data type.
     *
     * @param string $column
     * @param array  $values
     *
     * @return Blueprint
     */
    public function enum(string $column, array $values): Blueprint
    {
        $this->setColumn($column);

        $values = sprintf("'%s'", implode("','", $values));

        $this->addColumn('enum', $column, compact('values'));

        return $this;
    }

    /**
     * Text data type.
     *
     * @param string $column
     *
     * @return Blueprint
     */
    public function text(string $column): Blueprint
    {
        $this->setColumn($column);

        $this->addColumn('text', $column);

        return $this;
    }

    /**
     * Boolean data type.
     *
     * @param string $column
     *
     * @return Blueprint
     */
    public function boolean(string $column): Blueprint
    {
        $this->setColumn($column);

        $this->addColumn('boolean', $column);

        return $this;
    }

    /**
     * Add data type to the columns array.
     *
     * @param string $type
     * @param string $name
     * @param array  $parameters
     *
     * @return array
     */
    private function addColumn(string $type, string $name, array $parameters = []): array
    {
        $this->columns[$name] = $column = array_merge(compact('type'), $parameters);

        return $column;
    }
}