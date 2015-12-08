<?php

namespace NickStuer\EasyRouter;

class RouteManager
{
    /**
     * @var array
     */
    private $routes = array();

    /**
     * @var string
     */
    private $allowedMethods = array('get', 'post');

    /**
     * RouteManager constructor.
     *
     * @param string $routePath
     */
    public function __construct($routePath = '')
    {
        if ($routePath !== '') {
            $this->loadRoutes($routePath);
        }
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
     * Load the routes from a file instead of the running application.
     *
     * @param string $routesFilePath
     */
    private function loadRoutes($routesFilePath)
    {
        $routes = require __DIR__ . '/' . $routesFilePath;

        foreach ($routes as $route) {
            $this->addRoute($route[0], $route[1], $route[2]);
        }
    }

    /**
     * Add a route to the route array.
     *
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }
}
