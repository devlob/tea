<?php

namespace Devlob\Commands;

/**
 * Class MakeCommandCommand
 *
 * Create a command class.
 *
 * @package Devlob\Commands
 */
class MakeCommandCommand extends Command
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
    protected $description = 'Create a Tea command';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Command.stub');

        $path = "app/Console/$args->name.php";

        $template = str_replace(
            ['{{class}}'],
            [$args->name],
            $stub
        );

        if ( ! file_exists('app/Console')) {
            mkdir('app/Console');
        }

        file_put_contents($path, $template);

        $this->console->green("Command $args->name created");
    }
}