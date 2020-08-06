<?php

namespace Devlob\Validation\Rules;

/**
 * Class Required
 *
 * Required rule.
 *
 * @package Devlob\Validation\Rules
 */
class Required implements Rule
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
        return isset($value) && strlen($value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "The :attribute field is required.";
    }
}