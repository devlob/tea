<?php

namespace Devlob\Database\Migrations;

use App\Bootstrap\App;
use Closure;
use Exception;

class Schema
{
    /**
     * Create table.
     *
     * @param string  $table
     * @param Closure $callback
     *
     * @return mixed
     * @throws Exception
     */
    public static function create(string $table, Closure $callback)
    {
        $response = call_user_func($callback, new Blueprint());

        return App::get('Database')->createTable($table, $response->getColumns(), $response->getConstraints());
    }

    /**
     * Drop existing table.
     *
     * @param string $table
     *
     * @return mixed
     * @throws Exception
     */
    public static function dropIfExists(string $table)
    {
        return App::get('Database')->dropTable($table);
    }
}