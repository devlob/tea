<?php

namespace Devlob\Database\Migrations\Constraints\Drivers;

use Devlob\Database\Migrations\Constraints\ConstraintsI;

class MySqlConstraint implements ConstraintsI
{
    /**
     * Primary key constraint.
     *
     * @return string
     */
    public function primary(): string
    {
        return 'PRIMARY KEY';
    }

    /**
     * Default constraint.
     *
     * @param $value
     *
     * @return string
     */
    public function default($value): string
    {
        if (is_bool($value)) {
            return 'DEFAULT ' . ($value === true ? 'TRUE' : 'FALSE');
        } else {
            return "DEFAULT '$value'";
        }
    }

    /**
     * Unique constraint.
     *
     * @return string
     */
    public function unique(): string
    {
        return 'UNIQUE';
    }

    /**
     * Not null constraint.
     *
     * @return string
     */
    public function notNull(): string
    {
        return 'NOT NULL';
    }
}