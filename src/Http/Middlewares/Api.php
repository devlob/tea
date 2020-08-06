<?php

namespace Devlob\Http\Middlewares;

use Devlob\Auth\JWT;
use Exception;

/**
 * Class Api
 *
 * Deny access to non authenticated users.
 *
 * @package Devlob\Http\Middlewares
 */
class Api
{
    /**
     * Deny access to non authenticated users.
     *
     * @throws Exception
     */
    public function handle()
    {
        $jwt = str_replace('Bearer ', '', request()->httpAuthorization);

        if ((new JWT())->validate($jwt)) {
            return true;
        }

        return json([
            'message' => 'Unauthenticated'
        ], 401);
    }
}