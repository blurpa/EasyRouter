<?php

class testDispatcher extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerTestRoutes()
     *
     */
    public function testRoutes($routePath, $requestUri, $expected)
    {
        $manager = new \NickStuer\EasyRouter\RouteManager();
        $manager->addRoute(new \NickStuer\EasyRouter\Route('get', $routePath, 'Controller@Method'));
        $dispatcher = new \NickStuer\EasyRouter\Dispatcher($manager, false, 'get', $requestUri);

        $passed = true;
        try {
            $dispatcher->dispatch();
        } catch (Exception $ex) {
            $passed = false;
        }

        $this->assertEquals($passed, $expected);
    }

    public function providerTestRoutes()
    {
        return array(
            array('/', '/', true),
            array('/test/test2', '/', false),
            array('/test/test2', '/test2/test', false),
            array('/test/test2', '/test/test2', true),
            array('/test/', '/test', false),
            array('/test1', '/test2', false),
            array('testing/testing2/testing3/testing4/testing5', '/', false),
            array('/(abc)', '/testing', true),
            array('/(abc)', '/testing/testing', false),
            array('/(abc)', '/123', false),
            array('/(int)', '/123', true),
            array('/(int)', '/abc123', false),
        );
    }

    /**
     * @dataProvider providerTestRoutesVariables()
     *
     */
    public function testRoutesVariables($routePath, $requestUri, $expected)
    {
        $manager = new \NickStuer\EasyRouter\RouteManager();
        $manager->addRoute(new \NickStuer\EasyRouter\Route('get', $routePath, 'Controller@Method'));
        $dispatcher = new \NickStuer\EasyRouter\Dispatcher($manager, false, 'get', $requestUri);

        $data = array();
        try {
            $dispatcher->dispatch();
            $routeInfo = $dispatcher->getMatchedRoute();
            $data = $routeInfo['variables'];
        } catch (Exception $ex) {
        }

        $this->assertEquals($expected, $data);
    }

    public function providerTestRoutesVariables()
    {
        return array(
            array('/(abc)', '/testing', array('testing')),
            array('/testing/(abc)', '/testing/testing', array('testing')),
            array('/testing/(abc)/(abc)', '/testing/testing/mytest', array('testing', 'mytest')),
            array('/(abc)/profile', '/nick/profile', array('nick')),
            array('/(any)/profile/(int)/id/show/(any)', '/nick/profile/1337/id/show/ninedogger88', array('nick', 1337, 'ninedogger88'))
        );
    }


}