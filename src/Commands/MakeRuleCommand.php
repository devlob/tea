<?php

namespace Devlob\Commands;

class MakeRuleCommand extends Command
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
    protected $description = 'Create a rule';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        $stub = file_get_contents(__DIR__ . '/Stubs/Rule.stub');

        $path = "app/Rules/{$args->name}.php";

        $template = str_replace(
            ['{{class}}', '{{key}}'],
            [$args->name, lcfirst($args->name)],
            $stub
        );

        if ( ! file_exists('app/Rules')) {
            mkdir('app/Rules');
        }

        file_put_contents($path, $template);

        $this->console->green("Rule $args->name created");
    }
}