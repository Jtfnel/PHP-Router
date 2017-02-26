# PHP-Router
This is a very basic php router.
This is library is still in development and should be finished soon.

## How to use?
Add the files found in the htaccess folder to your project and then use the code
below to implement the router in a php file.
### Basic Route
```php
$router = new Router();
$router->addRoute("/");
$router->addRoute("/home");
$router->addRoute("/about");
$router->respond();
```
### Advance Route
More advance routes with parameters can be created, by adding a [] after a slash
with the first being the parameter name  followed by a | and then it's type:  
* d: digit
* w: word

```php
$router = new Router();
$router->addRoute("/posts");
$router->addRoute("/post/[id|d]");
$router->respond();
```

## TODO
* Add function caller to finish the router and make it functional.
* Fix regex only reading one character or digit with an advance route.
* Add error checking.
* Add error handling.
* Add an option to install via composer
* Add htaccess equivalent for nginx
