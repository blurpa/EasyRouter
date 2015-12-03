<?php

namespace NickStuer\EasyRouter;

class Collection
{
    /**
     * @var array
     */
    private $routes = array();

    /**
     * @var string
     */
    private $allowedMethods = array('get','post');


    /*
     * @var array
     */
    private $matchedRoute = array();

    /**
     * Collection constructor.
     *
     */
    public function __construct()
    {

    }

    /**
     * Returns the collected routes array.
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
     * @param string $routePath
     */
    public function loadRoutes($routePath)
    {
        $routes = require __DIR__ . '/' . $routePath;

        foreach ($routes as $route) {
            $this->addRoute($route[0], $route[1], $route[2]);
        }
    }

    /**
     * Add a route to the route array.
     *
     * @param string $httpMethod
     *
     * @param string $route
     *
     * @param string $action
     *
     * @throws Exceptions\RouteInvalidException
     */
    public function addRoute($httpMethod, $route, $action)
    {
        if (!in_array($httpMethod, $this->allowedMethods)) {
            throw new Exceptions\RouteInvalidException;
        }

        $this->routes[] = array(
                                    "httpMethod" => $httpMethod,
                                    "route" => $route,
                                    "action" => $action
                                );
    }
}
