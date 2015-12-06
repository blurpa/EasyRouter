<?php

class testCollection extends \PHPUnit_Framework_TestCase
{

    public function testNewCollection()
    {
        $httpMethod = 'get';
        $path = '/test';
        $action = 'test@test';

        $collection = new \NickStuer\EasyRouter\Collection();
        $collection->addRoute($httpMethod, $path, $action);

        $routes = $collection->getRoutes();

        $this->assertEquals($httpMethod, $routes[0]->getHttpMethod());
        $this->assertEquals($path, $routes[0]->getRoute());
        $this->assertEquals($action, $routes[0]->getAction());
    }


}