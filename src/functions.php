<?php

use App\User;
use Devlob\Auth\JWT;
use Devlob\External\Inflector;
use Devlob\Http\Responses\JsonResponse;
use Devlob\Request\Request;

/**
 * Var dump and die.
 */
if ( ! function_exists('dd')) {
    function dd($data)
    {
        var_dump($data);

        die();
    }
}

/**
 * Return a JSON response.
 */
if ( ! function_exists('json')) {
    function json($message, $code = 200)
    {
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContentType('application/json')
                     ->setStatusCode($code)
                     ->setMessage($message);

        $jsonResponse->getMessage();
    }
}

/**
 * Return a view.
 */
if ( ! function_exists('view')) {
    function view($view, $data = [])
    {
        $pieces = explode('/', 'resources/views');

        $exploded = explode('.', $view);

        foreach ($exploded as $piece) {
            $pieces[] = $piece;
        }

        $page = implode('/', $pieces) . ".html";

        if (file_exists($page)) {
            if ($data) {
                $file = file_get_contents($page);

                foreach ($data as $key => $value) {
                    $file = str_replace("{{ $$key }}", $value, $file);
                    $file = str_replace("{{\$$key}}", $value, $file);
                    $file = str_replace("{{\$$key }}", $value, $file);
                    $file = str_replace("{{ $$key}}", $value, $file);
                }

                return $file;
            }

            require_once $page;

            return;
        }

        throw new Exception('View doesn\'t exist');
    }
}

/**
 * Access .env attributes.
 */
if ( ! function_exists('env')) {
    function env($value, $default = null)
    {
        $output = getenv($value);

        if ($output) {
            return $output;
        } elseif ($output === false && $default) {
            return $default;
        }

        return null;
    }
}

/**
 * Pluralize a word.
 */
if ( ! function_exists('pluralize')) {
    function pluralize(string $word)
    {
        return (new Inflector())->pluralize($word);
    }
}

/**
 * Singularize a word.
 */
if ( ! function_exists('singularize')) {
    function singularize(string $word)
    {
        return (new Inflector())->singularize($word);
    }
}

/**
 * Return current request.
 */
if ( ! function_exists('request')) {
    function request()
    {
        return new Request();
    }
}

/**
 * Return current authenticated user.
 */
if ( ! function_exists('user')) {
    function user()
    {
        $jwt = str_replace('Bearer ', '', request()->httpAuthorization);

        $payload = json_decode((new JWT())->getPayload($jwt));

        return User::find($payload->id);
    }
}
