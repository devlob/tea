<?php

namespace Devlob\Commands;

/**
 * Class MakeModelCommand
 *
 * Create a model class.
 *
 * @package Devlob\Commands
 */
class MakeModelCommand extends Command
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
    protected $description = 'Create a model';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Model.stub');

        $path = "app/{$args->name}.php";

        $template = str_replace(
            ['{{class}}'],
            [$args->name],
            $stub
        );

        file_put_contents($path, $template);

        $this->console->green("Model $args->name created");
    }
}