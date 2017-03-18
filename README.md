# PHP-Router
This is a very basic php router.
This is library is still in development but it is fully operational at this moment.

## How to use?
Add the files found in the htaccess folder to your project and then use the code
below to implement the router in a php file.
For 403 and 404 errors just add it as the second parameter in the addRoute function otherwise
add NULL
### Basic Route
```php
$router = new Router();
//addRoute(Route, Special-Type, Function-To-Execute);
$router->addRoute("/", NULL, function(){echo "This will be executed!";});
$router->addRoute("/home", NULL, function(){echo "This will be executed!";});
$router->addRoute("/about", NULL, function(){echo "This will be executed!";});
$router->addRoute("/404", "404", function(){echo "This will be executed for 404!";});
$router->respond();
```
### Advance Route
More advance routes with parameters can be created, by adding a [] after a slash
with the first being the parameter name  followed by a | and then it's type:  
* d: digit
* w: word
```php
$router = new Router();
$router->addRoute("/posts", NULL, function(){echo "This will be executed!";});
$router->addRoute("/post/[id|d]", NULL, function($args){echo $args['id'];});
$router->respond();
```

## TODO
* Fix router not handling routes starting with a variable
* Add error handling.
* Add an option to install via composer
* Add htaccess equivalent for nginx
