<?php

    /**
    * @author: Cobblestone Bridge
    * @license: GPL-3.0
    * @description: This is the router class which will handle all requests to
    *  the site
    * @link: https://github.com/Jtfnel/PHP-Router
    */

    require_once("route.php");

    class Router{

        /**
        * @type: array of Route objects
        */
        private $routes = [];

        /**
        * @type: string
        */
        private $request = "";

        /**
        * @description: default constructor getting the request from globals
        * @param: void
        * @return: void
        */
        function __construct(){
            $uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "/", 1) + 1);
            $script = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "/", 1) + 1);
            if(strpos($uri, "%20") !== false){
                $temp = str_replace("%20", " ", $uri);
            }
            if(strpos($script, "%20") !== false){
                $script = str_replace("%20", " ", $script);
            }
            if($temp == $script){
                $this->request = "/" . ltrim($_SERVER['REQUEST_URI'], $uri);
            }
            $this->request = strtoupper($this->request);
        }

        /**
        * @description: adds the route to the
        * @param: $route(string) the route to be added to the router
        * @return: void
        */
        public function addRoute($route){
            // @TODO: add checking to ensure route is valid.
            if(strpos($route, "/", 1) !== false){
                $key = substr($route, 0, strpos($route, "/", 1));
            }else{
                $key = $route;
            }
            $key = strtoupper($key);
            $this->routes[$key][] = new Route($route);
        }

        /**
        * @description: resolves the request to a specific route
        * @param: void
        * @return: void
        */
        public function respond(){
            if(strpos($this->request, "/", 1) !== false){
                $key = substr($this->request, 0, strpos($this->request, "/", 1));
            }else{
                $key = $this->request;
            }
            $found = false;
            if(array_key_exists($key, $this->routes)){
                foreach($this->routes[$key] as $route){
                    $regex = $route->getRegex();
                    if(preg_match($regex, $this->request)){
                        $found = true;
                        break;
                    }
                }
            }
            if($found){
                // @TODO: implement calling of correct controller or function
            }else{
                // @TODO: implement handling of erros like 404
            }
        }

    }

?>
