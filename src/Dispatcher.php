<?php

namespace NickStuer\EasyRouter;

class Dispatcher
{
    /**
     * @var RouteManager
     */
    private $collection;

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var string
     */
    private $requestUri;

    /**
     * @var string
     */
    private $matchedRoute;

    /**
     * Constructor
     *
     * To mock requests: Set $createFromGlobals to false and add a $requestMethod and $requestUri.
     *
     * @param RouteManager $collection
     *
     * @param bool|true $createFromGlobals
     *
     * @param string $requestMethod
     *
     * @param string $requestUri
     */
    public function __construct(RouteManager $collection, $createFromGlobals = true, $requestMethod = '', $requestUri = '')
    {
        $this->collection = $collection;
        $this->requestMethod = strtolower(($createFromGlobals) ? $_SERVER['REQUEST_METHOD'] : $requestMethod);
        $requestUri = ($createFromGlobals) ? $_SERVER['REQUEST_URI'] : $requestUri;

        /**
         * Removes GET variables from the URI.
         *
         * Example: http://www.website.com/about?name=Nick returns /about
         */
        $requestUri = parse_url($requestUri, PHP_URL_PATH);

        /**
         * Removes the trailing slash in the URI if it's there.
         *
         * Example:  http://www.website.com/about/ returns /about
         * Example: http://www.website.com returns /
         */
        $this->requestUri = ($requestUri !== '/') ? rtrim($requestUri, '/') : $requestUri;
    }


    /**
     *
     * Processes the request uri.
     *
     * TODO: Make this more efficient.
     * The following code works but only if the URI does not contain any wildcard variables ex: (any)
     * $routesArrayKey = array_search($this->requestUri, array_column($this->routes, 'uri'));
     */
    public function dispatch()
    {
        /**
         * @var $route Route
         */
        $routeMatches = false;
        $methodMatches = false;

        /**
         * Cycle through all of the routes in the routes array to locate a match.
         * Replace (any) in the route with (\w+) for regex matching.
         * Replace (int) in the route with (\d+) for regex matching.
         * Replace (abc) in the route with ([A-Za-z]+) for regex matching.
         */
        foreach ($this->collection->getRoutes() as $route)
        {
            $partialPattern = str_replace('/', '\/', $route->getPath());
            $partialPattern = str_replace('(any)', '(\w+)', $partialPattern);
            $partialPattern = str_replace('(int)', '(\d+)', $partialPattern);
            $partialPattern = str_replace('(abc)', '([A-Za-z]+)', $partialPattern);

            $regexPattern = "/^" . $partialPattern . '$/i';

            if (preg_match($regexPattern, $this->requestUri) ) {
                $routeMatches = true;
                if ($route->getHttpMethod() == $this->requestMethod) {
                    $methodMatches = true;
                    break;
                }
            }
        }

        if (!$routeMatches) {
            throw new Exceptions\RouteNotFoundException();
        }

        if (!$methodMatches) {
            throw new Exceptions\MethodNotAllowedException();
        }

        /**
         * Strip the wildcard variables from the URI for use by using (any),(int),(abc) in the route.
         *
         * TODO: Fix Bug
         * Bug Description: Returns an incorrect variables array when a wildcard is not used at the end of a string.
         * Example Fails: '/profile/(any)/show/(int)'  Returns: show,(WILDCARD VALUE)
         *
         * Does work if all wildcards are at the end of the route.
         * Example: '/profile/show/(any)/(int)/(abc)'
         */

        $strippedRoutePath = str_replace('/(any)', '', $route->getPath());
        $strippedRoutePath = str_replace('/(int)', '', $strippedRoutePath);
        $strippedRoutePath = str_replace('/(abc)', '', $strippedRoutePath);

        if (strlen($strippedRoutePath) >= 1) {
            $strippedRoutePath = substr_replace($this->requestUri, '', strpos($this->requestUri, $strippedRoutePath), strlen($strippedRoutePath));
        }

        $strippedRoutePath = ltrim($strippedRoutePath, '/');

        $variables = array();
        if ($strippedRoutePath != '') {
            $variables = explode('/', $strippedRoutePath);
        }

        /**
         * Separate the controller to call and the method to call.
         */
        $action = explode('@', $route->getAction());

        $this->matchedRoute = array('controller'=>$action[0], 'method'=>$action[1], 'variables'=>$variables);
    }

    /**
     * Returns the matched route information. (Controller, Method, Wildcard Variables)
     *
     * @return array
     */
    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }
}
