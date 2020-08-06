<?php

namespace Devlob\Commands;

/**
 * Class MakeResourceCommand
 *
 * Create an API resource class.
 *
 * @package Devlob\Commands
 */
class MakeResourceCommand extends Command
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
    protected $description = 'Create a resource';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Resource.stub');

        $path = "app/Http/Resources/$args->name.php";

        $template = str_replace(
            ['{{class}}'],
            [$args->name],
            $stub
        );

        if ( ! file_exists('app/Http/Resources')) {
            mkdir('app/Http/Resources');
        }

        file_put_contents($path, $template);

        $this->console->green("Resource $args->name created");
    }
}