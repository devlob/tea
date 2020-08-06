<?php

namespace Devlob\Database\Migrations\Columns\Drivers;

use Devlob\Database\Migrations\Columns\Columns;

/**
 * Class MySqlColumn
 *
 * MySql columns.
 *
 * @package Devlob\Database\Migrations\Columns\Drivers
 */
class MySqlColumn implements Columns
{
    /**
     * Construct id column.
     *
     * @param string $key
     * @param array  $constraints
     *
     * @return string
     */
    public function id(string $key, array $constraints = []): string
    {
        $constraints = implode(" ", $constraints);

        return rtrim("$key INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY $constraints");
    }

    /**
     * Construct integer column.
     *
     * @param string $key
     * @param array  $value
     * @param array  $constraints
     *
     * @return string
     */
    public function integer(string $key, array $value, array $constraints): string
    {
        $unsigned = isset($value['unsigned']) && $value['unsigned'] === true ? 'UNSIGNED' : '';

        $constraints = implode(" ", $constraints);

        return rtrim("$key INT(6) $unsigned $constraints");
    }

    /**
     * Construct string column.
     *
     * @param string $key
     * @param array  $value
     * @param array  $constraints
     *
     * @return string
     */
    public function string(string $key, array $value, array $constraints): string
    {
        $constraints = implode(" ", $constraints);

        return rtrim("$key VARCHAR({$value['length']}) $constraints");
    }

    /**
     * Construct text column.
     *
     * @param string $key
     * @param array  $constraints
     *
     * @return string
     */
    public function text(string $key, array $constraints): string
    {
        $constraints = implode(" ", $constraints);

        return rtrim("$key TEXT $constraints");
    }

    /**
     * Construct boolean column.
     *
     * @param string $key
     * @param array  $constraints
     *
     * @return string
     */
    public function boolean(string $key, array $constraints): string
    {
        $constraints = implode(" ", $constraints);

        return rtrim("$key BOOLEAN $constraints");
    }

    /**
     * Construct enum column.
     *
     * @param string $key
     * @param array  $value
     * @param array  $constraints
     *
     * @return string
     */
    public function enum(string $key, array $value, array $constraints): string
    {
        $constraints = implode(" ", $constraints);

        return rtrim("$key ENUM({$value['values']}) $constraints");
    }
}