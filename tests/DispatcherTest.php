<?php

class testDispatcher extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerTestRoutes()
     *
     */
    public function testRoutes($route)
    {
        $collection = new \NickStuer\EasyRouter\RouteManager();
        $collection->addRoute('get', $route, 'Controller@Method');
        $dispatcher = new \NickStuer\EasyRouter\Dispatcher($collection, false, 'get', $route);

        $dispatcher->dispatch();

        $routeInfo = $dispatcher->getMatchedRoute();

        $this->assertEquals('Controller', $routeInfo['controller']);
        $this->assertEquals('Method', $routeInfo['method']);


    }

    public function providerTestRoutes()
    {
        return array(
            array('/'),
            array('/test/test2'),
            array('testing/testing2/testing3/testing4/testing5')
        );
    }


}