<?php

namespace Devlob\Validation;

use Devlob\Validation\Rules\Boolean;
use Devlob\Validation\Rules\Email;
use Devlob\Validation\Rules\Numeric;
use Devlob\Validation\Rules\Required;
use Exception;
use FilesystemIterator;

/**
 * Class Validator
 *
 * Validate request.
 *
 * @package Devlob\Validation
 */
class Validator
{
    /**
     * A look up for available predefined validation rules.
     */
    private $lookup
        = [
            'boolean'  => Boolean::class,
            'email'    => Email::class,
            'numeric'  => Numeric::class,
            'required' => Required::class
        ];

    /**
     * Validator constructor.
     *
     * Concatenate to the lookup user defined validation rules.
     */
    public function __construct()
    {
        if (file_exists('app/Rules')) {
            $fileSystemIterator = new FilesystemIterator('app/Rules');

            foreach ($fileSystemIterator as $fileInfo) {
                $className = 'App\Rules\\' . basename($fileInfo->getFilename(), '.php');
                $class     = get_class_vars($className);

                $this->lookup[$class['key']] = $className;
            }
        }
    }

    /**
     * Validate request.
     *
     * @param array $data
     *
     * @return array
     * @throws Exception
     */
    public function validate(array $data): array
    {
        $errors = [];

        foreach ($data as $key => $value) {
            $rules = explode('|', $value);

            foreach ($rules as $rule) {
                if (isset($this->lookup[$rule])) {
                    $class = new $this->lookup[$rule]();

                    $validate = $class->passes($key, request()->get($key));

                    if ( ! $validate) {
                        $errors[$key] = str_replace(':attribute', $key, $class->message());
                    }
                } else {
                    throw new Exception("Rule '$rule' is not defined.");
                }
            }
        }

        return $errors;
    }
}