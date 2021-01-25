<?php

namespace Devlob\Commands;

abstract class ConsoleKernel
{
    /**
     * Store system commands.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Get commands.
     *
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
}