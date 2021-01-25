<?php

namespace Devlob\Commands;

class DownCommand extends Command
{
    /**
     * This will show up when running 'php tea'.
     *
     * @var string
     */
    protected $description = 'Bring down the application';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     */
    public function handle(object $args)
    {
        file_put_contents('down', '');

        $this->console->green("Application is down");
    }
}