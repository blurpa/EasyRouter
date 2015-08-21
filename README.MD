# EasyRouter

## Introduction

This is a basic PHP router mainly created for learning purposes. This works well for when you don't want the router
function of your application tied so tightly to the rest of your application. It allows easier to read code and usability.

Specify the controller and the controller method to call when a route is matched using addRoute().

This library does support multiple wildcard values, however, they must all be at the end of the route currently until fixed.

To match wild cards use the following phrases in your route path.
(any) - Matches any combination of upper case/lower case letters and numbers.
(int) - Matches only integers.
(abc) - Matches any combination of upper case/lower case letters.

## Example Usage

#### Typical Use of Easy Router
```php
include (__DIR__ . '/vendor/autoload.php');

$router = new \EasyRouter\Router();

$router->addRoute('get', '/', 'ControllerToCall@methodToCall');
$router->addRoute('get', '/about', 'PagesController@showAbout');
$router->addRoute('get', '/contact', 'PagesController@showContact');
$router->addRoute('get', '/profile/(abc)', 'ProfileController@showProfile');

$router->dispatch();

switch ($router->getStatus())
{
    case '404':
        echo '404 - Not Found';
        exit;
        break;
    case '400':
        echo '400 - Bad Request';
        exit;
        break;
    default:
	    $routeInfo = $router->getMatchedRoute();
        
	    $controller = new $routeInfo['controller'];
	    $controller->$routeInfo['method']($routeInfo['variables']);
        break;
}

```

#### Store Routes In A File

You can also store all of your routes in a separate file for easy access. The following is an example file saved as routes.php
```php
<?php
return [

    ['get', '/', 'Pages@showIndex'],
    ['get', '/auth/login', 'Auth@showLogin'],
    ['post', '/auth/login', 'Auth@handleLogin'],
    ['get', '/profile/(any)', 'Pages@getProfile']
];
```

Instead of using addRoute() in our main application we'll want to use the loadRoutes() method.
```php
$router->loadRoutes('routes.php');
```

### License

Easy Router is licensed under the [MIT license](http://opensource.org/licenses/MIT)