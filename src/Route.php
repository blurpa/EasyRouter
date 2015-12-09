<?php

namespace NickStuer\EasyRouter;

class Route implements RouteInterface
{
    /**
     * @var string[]
     */
    private $allowedMethods = array('get', 'post');

    /**
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $action;

    /**
     * Route constructor.
     *
     * @param string $httpMethod
     * @param string $path
     * @param string $action
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

    /**
     * Verifies that the route's http method is in the allowed array.
     */
    public function verify()
    {
        if (!in_array($this->httpMethod, $this->allowedMethods)) {
            throw new Exceptions\MethodNotAllowedException;
        }
    }
}
