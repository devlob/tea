<?php

namespace Devlob\Commands;

class MakeControllerCommand extends Command
{
    /**
     * Store the parameters of the command.
     *
     * @var array
     */
    protected $params = ['name'];

    /**
     * This will show up when running 'php tea'.
     *
     * @var string
     */
    protected $description = 'Create a controller';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Controller.stub');

        $path = "app/Http/Controllers/$args->name.php";

        $template = str_replace(
            ['{{class}}'],
            [$args->name],
            $stub
        );

        if ( ! file_exists('app/Http/Controllers')) {
            mkdir('app/Http/Controllers');
        }

        file_put_contents($path, $template);

        $this->console->green("Controller $args->name created");
    }
}