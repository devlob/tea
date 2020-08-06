<?php

namespace Devlob\Commands;

/**
 * Class MakeRequestCommand
 *
 * Create a request class.
 *
 * @package Devlob\Commands
 */
class MakeRequestCommand extends Command
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
    protected $description = 'Create a request';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Request.stub');

        $path = "app/Http/Requests/$args->name.php";

        $template = str_replace(
            ['{{class}}'],
            [$args->name],
            $stub
        );

        if ( ! file_exists('app/Http/Requests')) {
            mkdir('app/Http/Requests');
        }

        file_put_contents($path, $template);

        $this->console->green("Request $args->name created");
    }
}