<?php

namespace NickStuer\EasyRouter;

class Route
{
    private $httpMethod;
    private $route;
    private $action;

    public function __construct($httpMethod, $route, $action)
    {
        $this->httpMethod = $httpMethod;
        $this->route = $route;
        $this->action = $action;
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getAction()
    {
        return $this->action;
    }
}