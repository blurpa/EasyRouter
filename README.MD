# EasyRouter

This is a basic PHP router mainly created for learning purposes.

Specify the controller and the controller method to call when a route is matched using addRoute()

# Example Usage

```php
<?php

$router = new \EasyRouter\Router();

$router->addRoute('get', '/', 'ControllerToCall@methodToCall');
$router->addRoute('get', '/about', 'PagesController@showAbout');
$router->addRoute('get', '/about', 'PagesController@showContact');

$routeInfo = $router->dispatch();

$controller = new $routeInfo['controller'];
$controller->$routeInfo['method']($routeInfo['variables']);
```