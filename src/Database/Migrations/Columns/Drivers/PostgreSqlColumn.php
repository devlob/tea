<?php

namespace Devlob\Database\Migrations\Columns\Drivers;

use Devlob\Database\Migrations\Columns\Columns;

/**
 * Class PostgreSqlColumn
 *
 * PostgreSql columns.
 *
 * @package Devlob\Database\Migrations\Columns\Drivers
 */
class PostgreSqlColumn implements Columns
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

        return rtrim("$key SERIAL PRIMARY KEY UNIQUE CHECK ($key > 0) $constraints");
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
        $unsigned = isset($value['unsigned']) && $value['unsigned'] === true ? "CHECK ($key >= 0)" : '';

        $constraints = implode(" ", $constraints);

        return rtrim("$key INTEGER $unsigned $constraints");
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

        return rtrim("$key e_{$key}_method $constraints");
    }

    /**
     * Create type.
     *
     * @param string $key
     * @param array  $value
     */
    public final function createType(string $key, array $value)
    {
        if ($value['type'] === 'enum') {
            return <<<EOD
                    DO $$
                    BEGIN
                        IF NOT EXISTS (
                            SELECT
                                *
                            FROM
                                pg_type typ
                                INNER JOIN pg_namespace nsp ON nsp.oid = typ.typnamespace
                            WHERE
                                nsp.nspname = current_schema() AND typ.typname = 'e_{$key}_method') THEN
                            CREATE TYPE e_{$key}_method AS ENUM ({$value['values']});
                        END IF;
                    END;
                    $$
                    LANGUAGE plpgsql;
                EOD;
        }
    }
}