<?php

namespace NickStuer\EasyRouter;

class RouteManager
{
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
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $route->verify();
        $this->routes[] = $route;
    }
}
