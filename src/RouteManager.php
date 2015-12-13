<?php

namespace NickStuer\EasyRouter;

class RouteManager
{
    /**
     * @var string[]
     */
    private $allowedMethods = array('get', 'post');

    /**
     * @var array
     */
    private $routes = array();

    /**
     * RouteManager constructor.
     */
    public function __construct()
    {

    }

    /**
     * Returns the collected array of Routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add a route to the route array.
     *
     * @param RouteInterface $route
     *
     * @throws \Exception
     */
    public function addRoute(RouteInterface $route)
    {
        if (!$this->isValidRoute($route)) {
            throw new \Exception('invalid route');
        }
        $this->routes[] = $route;
    }

    /**
     * Verifies that the Route matches expected setup.
     *
     * Currently only checks if the http post method is valid.
     * TODO: Check if action is a callable.
     * TODO: Check if route is valid.
     *
     * @param RouteInterface $route
     * 
     * @return bool
     */
    protected function isValidRoute(RouteInterface $route)
    {
        $routeHttpMethod = $route->getHttpMethod();

        return (in_array($routeHttpMethod, $this->allowedMethods));

    }
}
