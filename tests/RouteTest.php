<?php

class testRoute extends \PHPUnit_Framework_TestCase
{

    public function testNewRoute()
    {
        $httpMethod = 'get';
        $path = '/test';
        $action = 'test@test';
        $route = new \NickStuer\EasyRouter\Route($httpMethod, $path, $action);

        $this->assertEquals($httpMethod, $route->getHttpMethod());
        $this->assertEquals($path, $route->getRoute());
        $this->assertEquals($action, $route->getAction());
    }


}