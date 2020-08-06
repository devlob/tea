<?php

namespace Devlob\Database\Drivers;

/**
 * Class MySql
 *
 * MySql driver.
 *
 * @package Devlob\Database\Drivers
 */
class MySql
{
    /**
     * Setup MySQL connection.
     *
     * @param object $config
     *
     * @return array
     */
    public function __invoke(object $config): array
    {
        return [
            'connection_string' => "mysql:host=$config->host;port=$config->port;dbname=$config->db",
            'options'           => [
                'commands' => 'SET SQL_MODE=ANSI_QUOTES'
            ]
        ];
    }
}