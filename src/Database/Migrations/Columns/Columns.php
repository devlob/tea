<?php

namespace Devlob\Database\Migrations\Columns;

interface Columns
{
    /**
     * Construct id column.
     *
     * @param string $key
     * @param array  $constraints
     *
     * @return string
     */
    public function id(string $key, array $constraints): string;

    /**
     * Construct integer column.
     *
     * @param string $key
     * @param array  $value
     * @param array  $constraints
     *
     * @return string
     */
    public function integer(string $key, array $value, array $constraints): string;

    /**
     * Construct string column.
     *
     * @param string $key
     * @param array  $value
     * @param array  $constraints
     *
     * @return string
     */
    public function string(string $key, array $value, array $constraints): string;

    /**
     * Construct text column.
     *
     * @param string $key
     * @param array  $constraints
     *
     * @return string
     */
    public function text(string $key, array $constraints): string;

    /**
     * Construct boolean column.
     *
     * @param string $key
     * @param array  $constraints
     *
     * @return string
     */
    public function boolean(string $key, array $constraints): string;

    /**
     * Construct enum column.
     *
     * @param string $key
     * @param array  $value
     * @param array  $constraints
     *
     * @return string
     */
    public function enum(string $key, array $value, array $constraints): string;
}