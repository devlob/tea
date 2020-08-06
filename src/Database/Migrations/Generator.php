<?php

namespace Devlob\Database\Migrations;

use App\Bootstrap\App;
use Exception;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class Generator
 *
 * Generate columns.
 *
 * @package Devlob\Database\Migrations
 */
class Generator
{
    /**
     * Column driver.
     *
     * @var
     */
    private $driver;

    /**
     * Generator constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->driver = App::get('Database')->getColumnDriver();
    }

    /**
     * Generate columns.
     *
     * @param array $columns
     * @param array $constraints
     *
     * @return string
     */
    public function generate(array $columns, array $constraints): string
    {
        $cols = '';

        foreach ($columns as $key => $value) {
            $method = $value['type'];

            if (method_exists($this->driver, $method)) {
                $columnDefinitions = isset($constraints[$key]) ? $constraints[$key] : [];

                if (count($value) > 1) {
                    $cols .= $this->driver->$method($key, $value, $columnDefinitions) . ', ';
                } else {
                    $cols .= $this->driver->$method($key, $columnDefinitions) . ', ';
                }
            }
        }

        return rtrim(trim($cols), ',');
    }

    /**
     * Execute driver specific commands.
     *
     * @param       $pdo
     * @param array $columns
     */
    public function driverSpecific($pdo, array $columns)
    {
        $class                 = new ReflectionClass($this->driver);
        $driverSpecificMethods = $class->getMethods(ReflectionMethod::IS_FINAL);

        foreach ($driverSpecificMethods as $driverSpecificMethod) {
            foreach ($columns as $key => $value) {
                $sql = $this->driver->{$driverSpecificMethod->getName()}($key, $value);

                if ($sql) {
                    $pdo->exec($sql);
                }
            }
        }
    }
}