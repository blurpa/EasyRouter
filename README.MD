# EasyRouter

## Introduction

This is a basic PHP router mainly created for learning purposes.

Specify the controller and the controller method to call when a route is matched using addRoute().

To match wild cards use the phrase {any} in your route path. See example usage for proper usage. Currently Easy Router does not support limiting the wild card to numbers or strings only.

## Example Usage
```php
<?php

include (__DIR__ . '/vendor/autoload.php');

$router = new \EasyRouter\Router();

$router->addRoute('get', '/', 'ControllerToCall@methodToCall');
$router->addRoute('get', '/about', 'PagesController@showAbout');
$router->addRoute('get', '/about', 'PagesController@showContact');
$router->addRoute('get', '/profile/{any}', 'UsersController@showProfile');

$routeInfo = $router->dispatch();

$controller = new $routeInfo['controller'];
$controller->$routeInfo['method']($routeInfo['variables']);
```

### License

Easy Router is licensed under the [MIT license](http://opensource.org/licenses/MIT)