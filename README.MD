# EasyRouter

## Introduction

This is a basic PHP router mainly created for learning purposes. This works well for when you don't want the router
function of your application tied so tightly to the rest of your application. It provides easy to read code as well.

Supports PHP 7.

If you have any suggestions or bug fixes please feel free to let me know.

Specify the controller and the controller method to call when a route is matched using addRoute().

This library does support multiple wildcard values, however, they must all be at the end of the route currently until fixed. '/(abc)/profile' doesn't work but '/profile/(abc)' does work.

To match wild cards use the following phrases in your route path:
* (any) - Matches any combination of upper case/lower case letters and numbers.
* (int) - Matches only integers.
* (abc) - Matches any combination of upper case/lower case letters.

## Example Usage

#### Typical Use of Easy Router
```php
include (__DIR__ . '/vendor/autoload.php');

$collection = new \NickStuer\EasyRouter\Collection();
$dispatcher = new \NickStuer\EasyRouter\Dispatcher($collection);

$collection->addRoute('get', '/', 'ControllerToCall@methodToCall');
$collection->addRoute('get', '/about', 'PagesController@showAbout');
$collection->addRoute('get', '/contact', 'PagesController@showContact');
$collection->addRoute('get', '/profile/(abc)', 'ProfileController@showProfile');

try {
    $dispatcher->dispatch();
} catch (\NickStuer\EasyRouter\Exceptions\RouteNotFoundException $e) {
    echo "Route Not Found - 404";
    exit;
} catch (\NickStuer\EasyRouter\Exceptions\MethodNotFoundException $e) {
    echo "Method Not Allowed - 405";
    exit;
}

$routeInfo = $dispatcher->getMatchedRoute();

$controller = new $routeInfo['controller'];
$method = $routeInfo['method'];
$controller->$method($routeInfo['variables']);
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

## License

Easy Router is licensed under the [MIT license](http://opensource.org/licenses/MIT)