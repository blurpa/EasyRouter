<?php

namespace EasyRouter;

class Router
{
    private $routes = array();
    private $server;
    private $requestUri;
    private $requestMethod;
    private $status;
    private $matchedRoute = array();

    public function __construct($createFromGlobals = true, $server = '')
    {
        /**
         * Set the server object. Set first parameter to false and add a server object for mock requests.
         */
        $this->server = ($createFromGlobals) ? $_SERVER : $server;

        /**
         * When adding routes, lowercase is used for the request method so we
         * must convert the request method from the server object to lowercase.
         */
        $this->requestMethod = strtolower($this->server['REQUEST_METHOD']);

        /**
         * Removes the trailing slash in the URI if it's there.
         * Example:  http://www.website.com/about/
         */
        $this->requestUri = ($this->server['REQUEST_URI'] !== '/') ? rtrim($this->server['REQUEST_URI'], '/') : $this->server['REQUEST_URI'];
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
     *
     * TODO: Assign limitations to wildcard routes. Example: (int) (chars)
     */
    public function dispatch()
    {
        $routeMatches = false;
        $methodMatches = false;
        $key = 0;

        /**
         * Cycle through all of the routes in the routes array to locate a match.
         * Replace (any) in the route with (\w+) for regex matching.
         */
        foreach ($this->routes as $key => $route)
        {
            $partialPattern = str_replace('/', '\/', $route['route']);
            $partialPattern = str_replace('(any)', '(\w+)', $partialPattern);
            $pattern = "/^" . $partialPattern . '$/i';

             if (preg_match($pattern, $this->requestUri) ) {
                 $routeMatches = true;
                 if ($this->routes[$key]['httpMethod'] == $this->requestMethod) {
                     $methodMatches = true;
                     break;
                 }
             }
        }

        if (!$routeMatches) {
            $this->status = 'notfound';
            return;
        }

        if (!$methodMatches) {
            $this->status = 'invalidmethod';
            return;
        }

        $this->status = 'found';

        /**
         * Strip the wildcard variables from the URI for use by using (any) in the route.
         * Bug exists!
         * TODO: Fix this so it returns an empty array if no wildcard variables are used.
         */
        $strippedRoutePath = strstr($this->routes[$key]['route'], '(any)', true);
        $strippedRoutePath = substr_replace($this->requestUri,'',strpos($this->requestUri,$strippedRoutePath),strlen($strippedRoutePath));
	    $variables = array();
        if ($strippedRoutePath != '/') {
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
     * Return 'found' if route found.
     * Return 'notfound' if no route found.
     * Return 'invalidmethod' if invalid method.
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