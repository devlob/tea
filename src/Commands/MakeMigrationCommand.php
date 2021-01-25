<?php

namespace Devlob\Commands;

class MakeMigrationCommand extends Command
{
    /**
     * Store the parameters of the command.
     *
     * @var array
     */
    protected $params = ['name', 'table'];

    /**
     * This will show up when running 'php tea'.
     *
     * @var string
     */
    protected $description = 'Create a migration';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Migration.stub');

        $path = "database/migrations/$args->name.php";

        $template = str_replace(
            ['{{class}}', '{{table}}'],
            [$args->name, $args->table],
            $stub
        );

        if ( ! file_exists('database/migrations')) {
            mkdir('database/migrations');
        }

        file_put_contents($path, $template);

        $this->console->green("Migration $args->name created");
    }
}