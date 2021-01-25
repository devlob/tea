<?php

namespace Devlob\Database;

use Devlob\Database\Drivers\MySql;
use Devlob\Database\Drivers\PostgreSql;
use Devlob\Utils\Console;
use PDO;
use PDOException;

class Connection
{
    /**
     * PDO driver.
     *
     * @var
     */
    private $driver;

    /**
     * A utility class for console.
     *
     * @var Console
     */
    private $console;

    /**
     * Connection constructor.
     */
    public function __construct()
    {
        $this->console = new Console();
    }

    /**
     * Make a database connection.
     *
     * @param array $config
     *
     * @return PDO|null
     */
    public function make(array $config)
    {
        try {
            $connection = '';

            switch ($config['connection']) {
                case 'mysql':
                    $connection = ((new MySql())((object)$config));
                    break;
                case 'pgsql':
                    $connection = ((new PostgreSql())((object)$config));
                    break;
            }

            if ( ! empty($connection)) {
                $options = $connection['options'];

                $this->driver = new PDO($connection['connection_string'], $config['user'], $config['password'], $options);

                if ($this->driver) {
                    $options[] = "SET NAMES '{$config['charset']}'";

                    foreach ($options as $option) {
                        $this->driver->exec($option);
                    }
                }
            }

            $this->setDriverAttributes();

            return $this->driver;
        } catch (PDOException $e) {
            echo $e->getMessage();

            $this->console->red("\nYou won't be able to make database queries, ");
            $this->console->green("but you can still use Tea.\n");
        }

        return null;
    }

    /**
     * Set driver attributes.
     */
    private function setDriverAttributes(): void
    {
        $this->driver->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
}