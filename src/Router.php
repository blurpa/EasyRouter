<?php

/**
 * EasyRouter - Created in August, 2015.
 *
 * Author: Nick Stuer
 * Contact: nickstuer@gmail.com
 *
 * License: MIT
 *
 */

namespace EasyRouter;

class Router
{
    private $routes = array();
    private $server;
    private $strippedUri;
    private $requestMethod;
    private $status;
    private $matchedRoute = array();

    public function __construct($createFromGlobals = true, $serverObject = '')
    {
        /**
         * Set the server object. To mock requests: Set first parameter to false and add a server object.
         */
        $this->server = ($createFromGlobals) ? $_SERVER : $serverObject;

        /**
         * When adding routes, lowercase is used for the request method so we
         * must convert the request method from the server object to lowercase.
         */
        $this->requestMethod = strtolower($this->server['REQUEST_METHOD']);

        /**
         * Removes the trailing slash in the URI if it's there.
         * Example:  http://www.website.com/about/
         */
        $this->strippedUri = ($this->server['REQUEST_URI'] !== '/') ? rtrim($this->server['REQUEST_URI'], '/') : $this->server['REQUEST_URI'];
    }

    /**
     * Load the routes from a file instead of the running application.
     *
     * @param $routePath
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
     * @param $httpMethod
     * @param $route
     * @param $action
     */
    public function addRoute($httpMethod, $route, $action)
    {
        $this->routes[] = array(
                                    "httpMethod" => $httpMethod,
                                    "route" => $route,
                                    "action" => $action
                                );
    }

    /**
     * Processes the request uri.
     *
     * TODO: Make this more efficient.
     * The following code works but only if the URI does not contain any wildcard variables ex: (any)
     * $routesArrayKey = array_search($this->requestUri, array_column($this->routes, 'uri'));
     */
    public function dispatch()
    {
        $routeMatches = false;
        $methodMatches = false;
        $key = 0;

        /**
         * Cycle through all of the routes in the routes array to locate a match.
         * Replace (any) in the route with (\w+) for regex matching.
         * Replace (int) in the route with (\d+) for regex matching.
         * Replace (abc) in the route with ([A-Za-z]+) for regex matching.
         */
        foreach ($this->routes as $key => $route)
        {
            $partialPattern = str_replace('/', '\/', $route['route']);
            $partialPattern = str_replace('(any)', '(\w+)', $partialPattern);
            $partialPattern = str_replace('(int)', '(\d+)', $partialPattern);
            $partialPattern = str_replace('(abc)', '([A-Za-z]+)', $partialPattern);

            $regexPattern = "/^" . $partialPattern . '$/i';

             if (preg_match($regexPattern, $this->strippedUri) ) {
                 $routeMatches = true;
                 if ($this->routes[$key]['httpMethod'] == $this->requestMethod) {
                     $methodMatches = true;
                     break;
                 }
             }
        }

        if (!$routeMatches) {
            $this->status = '404';
            return;
        }

        if (!$methodMatches) {
            $this->status = '400';
            return;
        }

        $this->status = '200';

        /**
         * Strip the wildcard variables from the URI for use by using (any),(int),(abc) in the route.
         *
         * TODO: Fix Bug
         * Bug Description: Returns an incorrect variables array when a wildcard is not used at the end of a string.@global
         * Example Fails: '/profile/(any)/show/(int)'  Returns: show,(WILDCARD VALUE)
         *
         * Does work if all wildcards are at the end of the route.
         * Example: '/profile/show/(any)/(int)/(abc)'
         */
        $strippedRoutePath = str_replace('/(any)', '', $this->routes[$key]['route']);
        $strippedRoutePath = str_replace('/(int)', '', $strippedRoutePath);
        $strippedRoutePath = str_replace('/(abc)', '', $strippedRoutePath);

        if (strlen($strippedRoutePath) >= 1) {
            $strippedRoutePath = substr_replace($this->strippedUri, '', strpos($this->strippedUri, $strippedRoutePath), strlen($strippedRoutePath));
        }

        $strippedRoutePath = ltrim($strippedRoutePath, '/');

	    $variables = array();
        if ($strippedRoutePath != '') {
            $variables = explode('/', $strippedRoutePath);
        }

        /**
         * Separate the controller to call and the method to call.
         */
        $handle = explode('@', $this->routes[$key]['action']);
        $controllerToCall = $handle[0];
        $methodToCall = $handle[1];

        $this->matchedRoute = array('controller'=>$controllerToCall, 'method'=>$methodToCall, 'variables'=>$variables);

        return;
    }

    /**
     * Return '200' if route found.
     * Return '404' if no route found.
     * Return '404' if invalid request method.
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the matched route information. (Controller, Method, Wildcard Variables)
     * @return array
     */
    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }

}