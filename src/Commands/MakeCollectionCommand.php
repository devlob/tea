<?php

namespace Devlob\Commands;

class MakeCollectionCommand extends Command
{
    /**
     * Store the parameters of the command.
     *
     * @var array
     */
    protected $params = ['name', 'resource'];

    /**
     * This will show up when running 'php tea'.
     *
     * @var string
     */
    protected $description = 'Create a collection';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Collection.stub');

        $path = "app/Http/Resources/$args->name.php";

        $template = str_replace(
            ['{{class}}', '{{resource}}'],
            [$args->name, $args->resource],
            $stub
        );

        if ( ! file_exists('app/Http/Resources')) {
            mkdir('app/Http/Resources');
        }

        file_put_contents($path, $template);

        $this->console->green("Collection $args->name created");
    }
}