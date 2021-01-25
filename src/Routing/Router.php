<?php

namespace Devlob\Routing;

use App\Http\Kernel;
use Exception;
use ReflectionException;

class Router
{
    /**
     * Application route collection.
     *
     * @var array|RouteCollection
     */
    private $routes = [];

    /**
     * Store route middlewares.
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * Run the file on destruction.
     *
     * @throws ReflectionException
     */
    public function __destruct()
    {
        $this->run();
    }

    /**
     * Run the class and call the desired route if available.
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function run(): void
    {
        $this->handleGlobalMiddlewares();

        if (isset($this->middlewares[$_SERVER['REQUEST_METHOD']])) {
            $response = $this->handleRouteMiddlewares($this->middlewares[$_SERVER['REQUEST_METHOD']]);

            if ( ! $response['passed']) {
                return;
            }
        }

        if (isset($this->routes->getRoutes()[$_SERVER['REQUEST_METHOD']])) {
            $this->handleRoute($this->routes->getRoutes()[$_SERVER['REQUEST_METHOD']]);

            return;
        }

        throw new Exception('No action could be taken for this request');
    }

    /**
     * Handle global middlewares.
     *
     * @throws Exception
     */
    private function handleGlobalMiddlewares(): void
    {
        $globalMiddlewares = (new Kernel())->getGlobalMiddlewares();

        foreach ($globalMiddlewares as $middleware) {
            if (class_exists($middleware)) {
                call_user_func([new $middleware, 'handle']);
            } else {
                throw new Exception("Middleware $middleware is not defined.");
            }
        }
    }

    /**
     * Handle route middlewares.
     *
     * @param array $middlewares
     *
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    private function handleRouteMiddlewares(array $middlewares): array
    {
        $routeMiddlewares = (new Kernel())->getRouteMiddlewares();
        $passed           = true;

        foreach ($middlewares as $middleware) {
            $uri                   = '/' . trim($_SERVER['REQUEST_URI'], '/');
            $middleware['pattern'] = preg_replace('/\/{(.*?)}/', '/(.*?)', $middleware['pattern']);

            if (preg_match_all('#^' . $middleware['pattern'] . '$#', $uri, $matches, PREG_OFFSET_CAPTURE)) {

                if (isset($routeMiddlewares[$middleware['key']])) {
                    if (class_exists($routeMiddlewares[$middleware['key']])) {
                        $response = call_user_func([new $routeMiddlewares[$middleware['key']], 'handle']);

                        if ($response !== true) {
                            $passed = false;
                            (new Renderer())->render($response);
                            break;
                        }
                    } else {
                        throw new Exception("Class {$routeMiddlewares[$middleware['key']]} is not defined.");
                    }
                } else {
                    throw new Exception("Middleware {$middleware['key']} is not defined.");
                }
            }
        }

        return [
            'passed' => $passed
        ];
    }

    /**
     * Handle route.
     *
     * @param array $routes
     *
     * @throws ReflectionException
     * @throws Exception
     */
    private function handleRoute(array $routes): void
    {
        $handled = false;

        foreach ($routes as $route) {
            $uri              = '/' . trim($_SERVER['REQUEST_URI'], '/');
            $route['pattern'] = preg_replace('/\/{(.*?)}/', '/(.*?)', $route['pattern']);

            if (preg_match_all('#^' . $route['pattern'] . '$#', $uri, $matches, PREG_OFFSET_CAPTURE)) {
                $matches = array_slice($matches, 1);

                $params = array_map(function ($match, $index) use ($matches) {
                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                    }

                    return isset($match[0][0]) ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                $this->invoke($route['fn'], $params);

                $handled = true;

                break;
            }
        }

        if ( ! $handled) {
            throw new Exception('Route not found');
        }
    }

    /**
     * Invoke the callback.
     *
     * @param       $fn
     * @param array $params
     *
     * @throws ReflectionException
     */
    private function invoke($fn, array $params = []): void
    {
        $response = null;

        if (is_callable($fn)) {
            $response = (new Call())->anonymous($fn, $params);
        } elseif (stripos($fn, '@') !== false) {
            $response = (new Call())->action($fn, $params);
        }

        (new Renderer())->render($response);
    }

    /**
     * Add middlewares to the route.
     *
     * @param array $middlewares
     */
    public function middlewares(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[$this->routes->getCurrentHTTPMethod()][] = [
                'pattern' => '/' . trim($this->routes->getCurrentPattern(), '/'),
                'key'     => $middleware,
            ];
        }
    }

    /**
     * Get method.
     *
     * @param string $pattern
     * @param        $fn
     *
     * @return $this
     */
    public function get(string $pattern, $fn)
    {
        $this->routes->addRoute('GET', $pattern, $fn);

        return $this;
    }

    /**
     * Post method.
     *
     * @param string $pattern
     * @param        $fn
     *
     * @return $this
     */
    public function post(string $pattern, $fn)
    {
        $this->routes->addRoute('POST', $pattern, $fn);

        return $this;
    }

    /**
     * Put method.
     *
     * @param string $pattern
     * @param        $fn
     *
     * @return $this
     */
    public function put(string $pattern, $fn)
    {
        $this->routes->addRoute('PUT', $pattern, $fn);

        return $this;
    }

    /**
     * Delete method.
     *
     * @param string $pattern
     * @param        $fn
     *
     * @return $this
     */
    public function delete(string $pattern, $fn)
    {
        $this->routes->addRoute('DELETE', $pattern, $fn);

        return $this;
    }
}