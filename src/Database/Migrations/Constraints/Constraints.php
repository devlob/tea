<?php

namespace Devlob\Database\Migrations\Constraints;

use App\Bootstrap\App;
use Exception;

class Constraints
{
    /**
     * Keep track of migration constraints.
     *
     * @var array
     */
    protected $constraints = [];

    /**
     * Current column.
     *
     * @var string
     */
    private $column;

    /**
     * Constraint driver.
     *
     * @var
     */
    private $driver;

    /**
     * Constraints constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->driver = App::get('Database')->getConstraintDriver();
    }

    /**
     * Set current column.
     *
     * @param string $column
     */
    protected function setColumn(string $column): void
    {
        $this->column = $column;
    }

    /**
     * Primary key constraint.
     *
     * @return $this
     */
    public function primary()
    {
        $this->constraints[$this->column][] = $this->driver->primary();

        return $this;
    }

    /**
     * Default constraint.
     *
     * @param $value
     *
     * @return $this
     */
    public function default($value)
    {
        $this->constraints[$this->column][] = $this->driver->default($value);

        return $this;
    }

    /**
     * Unique constraint.
     *
     * @return $this
     */
    public function unique()
    {
        $this->constraints[$this->column][] = $this->driver->unique();

        return $this;
    }

    /**
     * Not null constraint.
     *
     * @return $this
     */
    public function notNull()
    {
        $this->constraints[$this->column][] = $this->driver->notNull();

        return $this;
    }

    /**
     * Get constraints.
     *
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}