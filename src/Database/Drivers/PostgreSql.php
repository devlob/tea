<?php

namespace Devlob\Database\Drivers;

class PostgreSql
{
    /**
     * Setup PostgreSql connection.
     *
     * @param object $config
     *
     * @return array
     */
    public function __invoke(object $config): array
    {
        return [
            'connection_string' => "pgsql:host=$config->host;port=$config->port;dbname=$config->db",
            'options'           => [
                'commands' => 'SET SQL_MODE=ANSI_QUOTES'
            ]
        ];
    }
}