<?php

namespace Devlob\Http\Middlewares;

use Exception;

class PresentSecretKey
{
    /**
     * Deny access if secret key is not present.
     *
     * @throws Exception
     */
    public function handle()
    {
        if (env('APP_SECRET') === null) {
            throw new Exception('Secret key is not present. Stop the server if running and run \'php tea key:generate\' to generate a secret key.');
        }

        return true;
    }
}