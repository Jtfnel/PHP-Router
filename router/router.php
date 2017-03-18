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
        * @type: string
        */
        private $e403 = "";

        /**
        * @type: string
        */
        private $e404 = "";

        /**
        * @description: default constructor getting the request from globals
        * @param: void
        * @return: void
        */
        function __construct(){
            $uri = strtolower(substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "/", 1) + 1));
            $script = strtolower(substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "/", 1) + 1));
            if(strpos($uri, "%20") !== false){
                $temp = str_replace("%20", " ", $uri);
            }
            if(strpos($script, "%20") !== false){
                $script = str_replace("%20", " ", $script);
            }
            if($temp == $script){
                $this->request = substr_replace(strtolower($_SERVER['REQUEST_URI']), '', 0, strpos($_SERVER['REQUEST_URI'], "/", 1));
            }
            $this->request = strtolower($this->request);
        }

        /**
        * @description: adds the route to the
        * @param: $route(string) the route to be added to the router
        * @param: $type(string) the route to be added to the router
        * @param: $func(function) the function to be executed for that route
        * @return: void
        */
        public function addRoute($route,$type,$func){
            // @TODO: add checking to ensure route is valid.
            if(strpos($route, "/", 1) !== false){
                $key = substr($route, 0, strpos($route, "/", 1));
            }else{
                $key = $route;
            }
            $key = strtolower($key);
            $newroute = new Route($route,$func);
            $this->routes[$key][] = $newroute;
            if($type == "403"){
                $this->e403 = $newroute;
            }else if($type == "404"){
                $this->e404 = $newroute;
            }
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
            $routeto;
            if(array_key_exists($key, $this->routes)){
                foreach($this->routes[$key] as $route){
                    $regex = $route->getRegex();
                    if(preg_match($regex, $this->request)){
                        $routeto = $route;
                        $found = true;
                        break;
                    }
                }
            }
            if($found){
                if(strpos($routeto->getRoute(), "[") !== false){
                    $args = $this->getArgs($routeto->getRoute());
                }else{
                    $args = array(NULL);
                }
                $routeto->run($args);
            }else{
                $this->e404->run(array(NULL));
            }
        }

        /**
        * @description: resolves the request to a specific route
        * @param: $route(string) the route to be followed
        * @return: array of arguments for the function
        */
        private function getArgs($route){
            $args = array();
            $request = $this->request;
            if(strpos($route, "[") !== false){
                while(strlen($route) > 0){
                    if(strpos($route, "/", 1) !== false){
                        $posroute = strpos($route, "/", 1);
                    }else{
                        $posroute = strlen($route);
                    }
                    if(strpos($request, "/", 1) !== false){
                        $posrequest = strpos($request, "/", 1);
                    }else{
                        $posrequest = strlen($request);
                    }
                    if((strpos($route, "/") + 1) == strpos($route, "[")){
                        $varname = substr($route, 2, strpos($route, "|") - 2);
                        $var = substr($request, 1, $posrequest - 1);
                        $args[$varname] = $var;
                    }
                    $temp = str_split($route);
                    for($i = 0;$i < $posroute;$i++){
                        unset($temp[$i]);
                    }
                    $route = implode($temp);
                    $temp = str_split($request);
                    for($i = 0;$i < $posrequest;$i++){
                        unset($temp[$i]);
                    }
                    $request = implode($temp);
                }
                return $args;
            }else{
                die($this->e404->run(array(NULL)));
            }
        }

    }

?>
