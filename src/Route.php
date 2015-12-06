<?php

namespace NickStuer\EasyRouter;

class Route
{
    /**
     * @var string $httpMethod
     */
    private $httpMethod;

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var string $action
     */
    private $action;

    /**
     * Route constructor.
     *
     * @param $httpMethod
     * @param $path
     * @param $action
     */
    public function __construct($httpMethod, $path, $action)
    {
        $this->httpMethod = $httpMethod;
        $this->path = $path;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}