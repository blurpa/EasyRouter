<?php

include(__DIR__ . '/../src/Router.php');

class testRouter extends \PHPUnit_Framework_TestCase
{
    public function testTrueIsTrue()
    {
        $value = true;
        $this->assertTrue($value);
    }

    /**
     * @dataProvider providerTestRouter
     */
    public function testRouterIndex($route)
    {
        $router = new \Blurpa\EasyRouter\Router(false, 'get', $route);
        $router->addRoute('get', $route, 'Controller@Method');

        $router->dispatch();
        $routeInfo = $router->getMatchedRoute();

        $this->assertEquals($router->getStatus(), '200');
        $this->assertEquals($routeInfo['controller'], 'Controller');
        $this->assertEquals($routeInfo['method'], 'Method');
        $this->assertEquals(array(), $routeInfo['variables']);
    }

    public function providerTestRouter()
    {
        return array(
            array('/'),
            array('/test/test2'),
            array('/testing/testing2/testing3/testing4/testing5/testing6/testing7')
        );
    }




    /**
     * @dataProvider providerTestRouterWildcard200Routes
     */
    public function testRouterWildcard200($wildcard, $route, $variables)
    {
        $router = new \Blurpa\EasyRouter\Router(false, 'get', $route);
        $router->addRoute('get', $wildcard, 'Controller@Method');

        $router->dispatch();
        $routeInfo = $router->getMatchedRoute();

        $this->assertEquals('200', $router->getStatus());
        $this->assertEquals('Controller', $routeInfo['controller']);
        $this->assertEquals('Method', $routeInfo['method']);
        $this->assertEquals($variables, $routeInfo['variables']);
    }

    public function providerTestRouterWildcard200Routes()
    {
        return array(
            array('/test/(any)','/test/abcABC123', array('abcABC123')),
            array('/test/(int)','/test/123', array('123')),
            array('/test/(abc)','/test/abc', array('abc'))
        );
    }



    /**
     * @dataProvider providerTestRouterWildcard404Routes
     */
    public function testRouterWildcard404($wildcard, $route)
    {
        $router = new \Blurpa\EasyRouter\Router(false, 'get', $route);
        $router->addRoute('get', $wildcard, 'Controller@Method');

        $router->dispatch();

        $this->assertEquals('404', $router->getStatus());
    }

    public function providerTestRouterWildcard404Routes()
    {
        return array(
            array('/test/(int)','/test/abc123'),
            array('/test/(abc)','/test/abc123')
        );
    }

    /**
     * @dataProvider providerTestRouterMultipleWildcards200
     */
    public function testRouterMultipleWildcards200($wildcard, $route, $variables)
    {
        $router = new \Blurpa\EasyRouter\Router(false, 'get', $route);
        $router->addRoute('get', $wildcard, 'Controller@Method');

        $router->dispatch();
        $routeInfo = $router->getMatchedRoute();

        $this->assertEquals('200', $router->getStatus());
        $this->assertEquals('Controller', $routeInfo['controller']);
        $this->assertEquals('Method', $routeInfo['method']);
        $this->assertEquals($variables, $routeInfo['variables']);
    }

    public function providerTestRouterMultipleWildcards200()
    {
        return array(
            array('/test/(any)/(any)','/test/abcABC123/xyzXYZ789', array('abcABC123', 'xyzXYZ789')),
            array('/test/(int)/(int)','/test/123/789', array('123', '789')),
            array('/test/(abc)/(abc)','/test/abc/xyz', array('abc', 'xyz')),
            array('/test/(abc)/(int)','/test/abc/123', array('abc', '123')),
            array('/test/(int)/(any)','/test/123/abc123', array('123', 'abc123')),
        );
    }


    /**
     * @dataProvider providerTestRouterMultipleWildcards404
     */
    public function testRouterMultipleWildcards404($wildcard, $route)
    {
        $router = new \Blurpa\EasyRouter\Router(false, 'get', $route);
        $router->addRoute('get', $wildcard, 'Controller@Method');

        $router->dispatch();

        $this->assertEquals('404', $router->getStatus());
    }

    public function providerTestRouterMultipleWildcards404()
    {
        return array(
            array('/test/(int)/(int)','/test/abc123/abc123'),
            array('/test/(abc)/(abc)','/test/abc123/abc123'),
            array('/test/(abc)/(abc)/test','/test/abc123/abc123'),
        );
    }




}