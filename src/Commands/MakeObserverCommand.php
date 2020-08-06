<?php

namespace Devlob\Commands;

/**
 * Class MakeObserverCommand
 *
 * Create an observer class.
 *
 * @package Devlob\Commands
 */
class MakeObserverCommand extends Command
{
    /**
     * Store the parameters of the command.
     *
     * @var array
     */
    protected $params = ['name', 'model'];

    /**
     * This will show up when running 'php tea'.
     *
     * @var string
     */
    protected $description = 'Create an observer';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Observer.stub');

        $path = "app/Http/Observers/$args->name.php";

        $template = str_replace(
            ['{{class}}', '{{model}}', '{{modelVar}}'],
            [$args->name, $args->model, strtolower($args->model)],
            $stub
        );

        if ( ! file_exists('app/Http/Observers')) {
            mkdir('app/Http/Observers');
        }

        file_put_contents($path, $template);

        $this->console->green("Observer $args->name created");
    }
}