<?php

namespace Devlob\Commands;

/**
 * Class MakeMiddlewareCommand
 *
 * Create a middleware class.
 *
 * @package Devlob\Commands
 */
class MakeMiddlewareCommand extends Command
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
    protected $description = 'Create a middleware';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Middleware.stub');

        $path = "app/Http/Middlewares/$args->name.php";

        $template = str_replace(
            ['{{class}}'],
            [$args->name],
            $stub
        );

        if ( ! file_exists('app/Http/Middlewares')) {
            mkdir('app/Http/Middlewares');
        }

        file_put_contents($path, $template);

        $this->console->green("Middleware $args->name created");
    }
}