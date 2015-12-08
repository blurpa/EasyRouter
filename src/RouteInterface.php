<?php

namespace NickStuer\EasyRouter;

interface RouteInterface
{
    /**
     * RouteInterface constructor.
     *
     * @param string $httpMethod
     * @param string $path
     * @param string $action
     */
    public function __construct($httpMethod, $path, $action);

    public function getHttpMethod();

    public function getPath();

    public function getAction();

    public function verify();
}
