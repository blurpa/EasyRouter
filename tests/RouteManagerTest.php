<?php

class testRouteManager extends \PHPUnit_Framework_TestCase
{

    public function testNewRouteManager()
    {
        $httpMethod = 'get';
        $path = '/test';
        $action = 'test@test';

        $manager = new \NickStuer\EasyRouter\RouteManager();
        $manager->addRoute(new \NickStuer\EasyRouter\Route($httpMethod, $path, $action));

        /**
         * @var \NickStuer\EasyRouter\Route[] $routes
         */
        $routes = $manager->getRoutes();

        $this->assertEquals($httpMethod, $routes[0]->getHttpMethod());
        $this->assertEquals($path, $routes[0]->getPath());
        $this->assertEquals($action, $routes[0]->getAction());
    }
}
