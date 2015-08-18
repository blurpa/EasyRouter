# EasyRouter

## Introduction

This is a basic PHP router mainly created for learning purposes.

Specify the controller and the controller method to call when a route is matched using addRoute().

To match wild cards use the phrase {any} in your route path. See example usage for proper usage. Currently Easy Router does not support limiting the wild card to numbers or strings only.

## Example Usage

#### Typical Use of Easy Router
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

#### Store Routes In A File

You can also store all of your routes in a seperate file for easy access. The following is an example filed saved as routes.php
```php
<?php

return [

    ['get', '/', 'Pages@showIndex'],
    ['get', '/auth/login', 'Auth@showLogin'],
    ['post', '/auth/login', 'Auth@handleLogin'],
    ['get', '/profile/(any)', 'Pages@getProfile']
];
```

Instead of using addRoute() we'll want to use the loadRoutes() method.
```php
$router->loadRoutes('routes.php');
```

### License

Easy Router is licensed under the [MIT license](http://opensource.org/licenses/MIT)