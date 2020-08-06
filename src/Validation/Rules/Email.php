<?php

namespace Devlob\Validation\Rules;

/**
 * Class Email
 *
 * Email rule.
 *
 * @package Devlob\Validation\Rules
 */
class Email implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes(string $attribute, $value): bool
    {
        return isset($value) && filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "The :attribute field must be a valid email.";
    }
}