<?php

namespace NickStuer\EasyRouter;

class Route
{
    /**
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $action;

    /**
     * Route constructor.
     *
     * @param $httpMethod
     * @param $route
     * @param $action
     */
    public function __construct($httpMethod, $route, $action)
    {
        $this->httpMethod = $httpMethod;
        $this->route = $route;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}