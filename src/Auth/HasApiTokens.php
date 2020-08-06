<?php

namespace Devlob\Auth;

use Exception;

/**
 * Trait HasApiTokens
 *
 * Trait for token management.
 *
 * @package Devlob\Auth
 */
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