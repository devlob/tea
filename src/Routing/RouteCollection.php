<?php

namespace Devlob\Routing;

/**
 * Class RouteCollection
 *
 * Application routes.
 *
 * @package Devlob\Routing
 */
class RouteCollection
{
    /**
     * Store application routes.
     *
     * @var array
     */
    private $routes = [];

    /**
     * Keep track of the current HTTP method.
     *
     * @var string
     */
    private $currentHTTPMethod;

    /**
     * Keep track of the current pattern.
     *
     * @var string
     */
    private $currentPattern;

    /**
     * Add route.
     *
     * @param string $method
     * @param string $pattern
     * @param        $fn
     */
    public function addRoute(string $method, string $pattern, $fn)
    {
        $this->currentHTTPMethod = $method;
        $this->currentPattern    = $pattern;

        $this->routes[$method][] = [
            'pattern' => '/' . trim($pattern, '/'),
            'fn'      => $fn,
        ];
    }

    /**
     * Get application routes.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get current HTTP method.
     *
     * @return string
     */
    public function getCurrentHTTPMethod(): string
    {
        return $this->currentHTTPMethod;
    }

    /**
     * Get current pattern.
     *
     * @return string
     */
    public function getCurrentPattern(): string
    {
        return $this->currentPattern;
    }
}