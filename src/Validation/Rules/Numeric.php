<?php

namespace Devlob\Validation\Rules;

/**
 * Class Numeric
 *
 * Numeric rule.
 *
 * @package Devlob\Validation\Rules
 */
class Numeric implements Rule
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
        return isset($value) && is_numeric($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "The :attribute field must be numeric.";
    }
}