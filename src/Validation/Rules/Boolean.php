<?php

namespace Devlob\Validation\Rules;

/**
 * Class Boolean
 *
 * Boolean rule.
 *
 * @package Devlob\Validation\Rules
 */
class Boolean implements Rule
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
        if (isset($value) && ($value == "true" || $value == "false" || $value == "1" || $value == "0")) {
            $isBoolean = true;
        } else {
            $isBoolean = false;
        }

        return $isBoolean;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "The :attribute field must be a boolean.";
    }
}