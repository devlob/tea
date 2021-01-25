<?php

namespace Devlob\Commands;

use FilesystemIterator;

class MigrateRefreshCommand extends Command
{
    /**
     * This will show up when running 'php tea'.
     *
     * @var string
     */
    protected $description = 'Reset and re-run all migrations';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $fileSystemIterator = new FilesystemIterator('database/migrations');

        $firstIteration = false;

        foreach ($fileSystemIterator as $fileInfo) {
            $class = basename($fileInfo->getFilename(), '.php');

            if (class_exists($class)) {
                (new $class())->down();

                if ($firstIteration) {
                    $this->console->green("\nDrop '$class'");
                } else {
                    $this->console->green("Drop '$class'");

                    $firstIteration = true;
                }

                (new $class())->up();
                $this->console->green("\nMigrate '$class'");
            } else {
                $this->console->red("Class '$class' does not exist. Make sure you run 'composer dump-autoload' after creating a new migration class.\n");
            }
        }
    }
}