<?php

namespace NickStuer\EasyRouter;

class Dispatcher
{
    /**
     * @var RouteManager
     */
    private $manager;

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
     * @param RouteManager $manager
     *
     * @param bool|true $createFromGlobals
     *
     * @param string $requestMethod
     *
     * @param string $requestUri
     */
    public function __construct(RouteManager $manager, $createFromGlobals = true, $requestMethod = '', $requestUri = '')
    {
        $this->manager = $manager;
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
         * Replace / in the route with \/ for regex matching.
         * Replace (any) in the route with (\w+) for regex matching.
         * Replace (int) in the route with (\d+) for regex matching.
         * Replace (abc) in the route with ([A-Za-z]+) for regex matching.
         */
        foreach ($this->manager->getRoutes() as $route)
        {
            $regexPattern = str_replace('/', '\/', $route->getPath());
            $regexPattern = str_replace('(any)', '(\w+)', $regexPattern);
            $regexPattern = str_replace('(int)', '(\d+)', $regexPattern);
            $regexPattern = str_replace('(abc)', '([A-Za-z]+)', $regexPattern);

            $regexPattern = "/^" . $regexPattern . '$/i';

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

        $variables = array();

        $routePathArray = explode('/', ltrim($route->getPath(), '/'));
        $requestUriArray = explode('/', ltrim($this->requestUri, '/'));

        foreach ($routePathArray as $key => $item) {
            if ($item === '(abc)' || $item === '(int)' || $item === '(any)') {
                $variables[] = $requestUriArray[$key];
            }
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
