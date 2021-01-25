<?php

namespace Devlob\Auth;

use Exception;

trait HasApiTokens
{
    /**
     * Generate JWT token.
     *
     * @return string
     * @throws Exception
     */
    public function token(): string
    {
        return (new JWT())->generate(['id' => $this->id]);
    }
}