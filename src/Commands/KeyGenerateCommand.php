<?php

namespace Devlob\Commands;

use Exception;

/**
 * Class KeyGenerateCommand
 *
 * Generate secret key.
 *
 * @package Devlob\Commands
 */
class KeyGenerateCommand extends Command
{
    /**
     * This will show up when running 'php tea'.
     *
     * @var string
     */
    protected $description = 'Generate secret key';

    /**
     * Code to execute when running the command.
     *
     * @param object $args
     *
     * @throws Exception
     */
    public function handle(object $args)
    {
        $envContent = file_get_contents('.env');

        $newKeyValue = 'APP_SECRET=' . bin2hex(random_bytes(32));
        $newContent  = str_replace('APP_SECRET=', $newKeyValue, $envContent);

        file_put_contents('.env', $newContent);

        $this->console->green('Secret key was generated');
    }
}