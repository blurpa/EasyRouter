<?php

class testRouteManager extends \PHPUnit_Framework_TestCase
{

    public function testNewRouteManager()
    {
        $httpMethod = 'get';
        $path = '/test';
        $action = 'test@test';

        $collection = new \NickStuer\EasyRouter\RouteManager();
        $collection->addRoute($httpMethod, $path, $action);

        $routes = $collection->getRoutes();

        $this->assertEquals($httpMethod, $routes[0]->getHttpMethod());
        $this->assertEquals($path, $routes[0]->getPath());
        $this->assertEquals($action, $routes[0]->getAction());
    }


}