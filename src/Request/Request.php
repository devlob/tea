<?php

namespace Devlob\Request;

use Devlob\Validation\Validator;
use Exception;

class Request
{
    /**
     * Store errors.
     *
     * @var array
     */
    private $errors = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->camelCaseServerKeys();
    }

    /**
     * Camelcase server keys.
     */
    private function camelCaseServerKeys(): void
    {
        foreach ($_SERVER as $key => $value) {
            $result = strtolower($key);

            preg_match_all('/_[a-z]/', $result, $matches);

            foreach ($matches[0] as $match) {
                $c      = str_replace('_', '', strtoupper($match));
                $result = str_replace($match, $c, $result);
            }

            $this->$result = $value;
        }
    }

    /**
     * Get errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set errors.
     *
     * @param array $errors
     *
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Get request body.
     *
     * @return array|null
     */
    public function getBody()
    {
        if ($this->requestMethod === 'POST') {
            $body = [];

            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        } elseif ($this->requestMethod === 'PUT') {
            $body = [];

            mb_parse_str(file_get_contents('php://input'), $body);

            return $body;
        }

        return null;
    }

    /**
     * Failed authorization response.
     */
    public function authorizationFailed()
    {
        return json([
            'message' => 'Authorization failed'
        ], 401);
    }

    /**
     * Failed validation response.
     */
    public function validationFailed()
    {
        return json([
            'errors'  => $this->getErrors(),
            'message' => 'Validation failed.'
        ], 422);
    }

    /**
     * Perform validation on request data.
     *
     * @param array $data
     *
     * @return bool|mixed
     * @throws Exception
     */
    public function validate(array $data)
    {
        $errors = (new Validator())->validate($data);

        if ($errors) {
            return json([
                'errors'  => $errors,
                'message' => 'Validation failed.'
            ], 422);
        }

        return true;
    }

    /**
     * Get request data.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if (isset($this->getBody()[$name])) {
            return $this->getBody()[$name];
        }

        return null;
    }

    /**
     * Magic get method to get request data.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if (isset($this->getBody()[$name])) {
            return $this->getBody()[$name];
        }

        return null;
    }
}