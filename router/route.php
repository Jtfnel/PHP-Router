<?php

    /**
    * @author: Cobblestone Bridge
    * @license: GPL-3.0
    * @description: This is the route object that will save each route and
    *  generate it's regex string.
    * @link: https://github.com/Jtfnel/PHP-Router
    */

    class Route{

        /**
        * @type: string
        */
        private $route = "";

        /**
        * @type: string
        */
        private $regex = "";

        /**
        * @type: function
        */
        private $func;

        /**
        * @description: default constructor that takes route and generates regex
        * @param: $route(string) is the route this object will hold
        * @return: void
        */
        function __construct($route,$func){
            $this->route = strtolower($route);
            $this->func = $func;
            $this->generateRegex();
        }

        /**
        * @description: generates the regex string from the route provided
        * @param: void
        * @return: void
        */
        private function generateRegex(){
            $route = $this->route;
            $regex = "/^";
            while(strlen($route) > 0){
                if(strpos($route, "/", 1) !== false){
                    $key = substr($route, 0, strpos($route, "/", 1));
                    $pos = strpos($route, "/", 1);
                }else{
                    $key = $route;
                    $pos = strlen($route);
                }
                $regex = $regex . "\/";
                if(strpos($route, "[") == 1){
                    $type = substr($route, strpos($route, "|") + 1, 1);
                    if($type == "d"){
                        $regex = $regex . "\d+";
                    }else if($type == "w"){
                        $regex = $regex . "\w+";
                    }
                }else{
                    $regex = $regex . ltrim($key, "/");
                }
                $temp = str_split($route);
                for($i = 0;$i < $pos;$i++){
                    unset($temp[$i]);
                }
                $route = implode($temp);
            }
            $this->regex = $regex . "$/";
        }

        /**
        * @description: returns the route
        * @param: void
        * @return: string
        */
        public function getRoute(){
            return $this->route;
        }

        /**
        * @description: returns the regex string
        * @param: void
        * @return: string
        */
        public function getRegex(){
            return $this->regex;
        }

        /**
        * @description: executes the command
        * @param: array of arguments
        * @return: none
        */
        public function run($args){
            call_user_func($this->func, $args);
        }

    }

?>
