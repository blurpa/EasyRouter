<?php

namespace EasyRouter;

class Router
{
    private $routes = array();
    private $request;
    private $uri;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->uri = $this->request->getRequestUri();

        // Remove trailing slash from URL if it's there
        if ($this->uri !== '/') {
            $this->uri = rtrim($this->uri, '/');
        }
    }

    public function loadRoutes($routePath)
    {
        $routes = require $routePath;

        foreach ($routes as $route)
        {
            $this->addRoute($route[0], $route[1], $route[2]);
        }
    }

    public function addRoute($httpMethod, $route, $action)
    {
        $this->routes[] = array(
                                    "httpMethod" => $httpMethod,
                                    "route" => $route,
                                    "action" => $action
                                );
    }

    public function dispatch()
    {
        /*
         * TODO: Make this more efficient.
         * The following code works but only if the URI does not contain any dynamic variables ex: (any)
         * $routesArrayKey = array_search($this->uri, array_column($this->routes, 'uri'));
         */

        $routeMatches = false;
        $methodMatches = false;
        $key = 0;

        $requestMethod = strtolower($this->request->getRequestMethod());

        foreach ($this->routes as $key => $route)
        {
            $partialPattern = str_replace('/', '\/', $route['route']);
            $partialPattern = str_replace('(any)', '(\w+)', $partialPattern);
            $pattern = "/^" . $partialPattern . '$/i';

             if (preg_match($pattern, $this->uri) ) {
                 $routeMatches = true;
                 if ($this->routes[$key]['httpMethod'] == $requestMethod) {
                     $methodMatches = true;
                     break;
                 }
             }
        }

        if (!$routeMatches) {
            return array('controller'=>'\Framework\Controllers\Pages', 'method'=>'notFound', 'variables'=>array());
        }

        if (!$methodMatches) {
            return array('controller'=>'\Framework\Controllers\Pages', 'method'=>'badMethod', 'variables'=>array());
        }

        /* Strip the variables from the URI for use by using (any) in the route */
        $strippedRoutePath = strstr($this->routes[$key]['route'], '(any)', true);
        $strippedRoutePath = substr_replace($this->uri,'',strpos($this->uri,$strippedRoutePath),strlen($strippedRoutePath));

        /* Don't process variables if not needed. Return empty variables */
        $variables = array();
        if ($strippedRoutePath != '/') {
            $variables = explode('/', $strippedRoutePath);
        }

        $handle = explode('@', $this->routes[$key]['action']);
        $controllerToCall = '\Framework\Controllers\\' . $handle[0];
        $methodToCall = $handle[1];
        return array('controller'=>$controllerToCall, 'method'=>$methodToCall, 'variables'=>$variables);
    }

}