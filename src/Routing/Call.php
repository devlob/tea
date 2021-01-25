<?php

namespace Devlob\Routing;

use Devlob\Validation\Validator;
use Exception;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

class Call
{
    /**
     * Call anonymous function.
     *
     * @param       $fn
     * @param array $params
     *
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     */
    public function anonymous($fn, array $params = [])
    {
        $functionParams = (new ReflectionFunction($fn))->getParameters();

        $request = $this->getRequest($functionParams);

        if ($request !== null) {
            array_unshift($params, $request);
        }

        return call_user_func_array($fn, $params);
    }

    /**
     * Call controller action.
     *
     * @param       $fn
     * @param array $params
     *
     * @return mixed|void
     * @throws ReflectionException
     * @throws Exception
     */
    public function action($fn, array $params = [])
    {
        list($controller, $method) = explode('@', $fn);

        $controller = "App\Http\Controllers\\$controller";

        if (class_exists($controller)) {
            $functionParams = (new ReflectionMethod($controller, $method))->getParameters();

            $request = $this->getRequest($functionParams);

            if (is_array($request) && $request['continue'] === false) {
                return;
            } elseif ( ! is_array($request) && $request !== null) {
                array_unshift($params, $request);
            }

            return call_user_func_array([new $controller(), $method], $params);
        }

        throw new Exception("$controller does not exist.");
    }

    /**
     * Get request class.
     *
     * @param $functionParams
     *
     * @return array|null
     * @throws Exception
     */
    private function getRequest($functionParams)
    {
        foreach ($functionParams as $functionParam) {
            // It will return a simple request object.
            if ($functionParam->getClass() && $functionParam->getClass()->getParentClass() === false) {
                $requestName = $functionParam->getClass()->getName();

                return new $requestName;
            } // It will return a request class.
            elseif ($functionParam->getClass() && $functionParam->getClass()->getParentClass()->getName() === 'Devlob\Request\Request') {
                $requestClassName = $functionParam->getClass()->getName();
                $requestClass     = new $requestClassName;

                $response = call_user_func([$requestClass, 'authorize']);

                if ($response === false) {
                    $requestClass->authorizationFailed();

                    return [
                        'continue' => false
                    ];
                }

                $errors = (new Validator())->validate(call_user_func([$requestClass, 'rules']));

                if ($errors) {
                    $requestClass->setErrors($errors)->validationFailed();

                    return [
                        'continue' => false
                    ];
                }

                return $requestClass;
            }
        }

        return null;
    }
}