<?php

namespace waves;

use ReflectionClass;

class select_handler implements cached {

    private static $cache_id = "select_handler";
    private $params_list = array();

    public function __construct(){
        $this->timestamp = time();

    }

    public static function cache_id() { 
        return self::$cache_id;
    }

    public static function handle_changes(){
        $obj = self::get();
        
        $obj->handle();
    }

    public static function &get(){
        return cache::get_select_handler();
    }

    private function handle(){
        $var = utilities::get_and_clean_url_param_value("select");
        if ($var === "changed") {
            $this->check_for_parameters();
        }
        
    }

    private function check_for_parameters(){
        var_dump($this->params_list);
        foreach ($this->params_list as $param => $callable){
            $class = $callable[0];
            $function = $callable[1];
            $reflect = new ReflectionClass($class);
            
            $class = select::class;
            echo "Param: $param, callable: " . $callable[0] . "  " .  $callable[1] . "<br>";
            if($reflect->implementsInterface('waves\select_action')){
                $obj = $class::get();
                $obj->$function($param . 'asfas');
            } 
        }
    }

    public static function register_param_and_callable($param, $callable_arr){
        $obj = self::get();
        $obj->add_param_and_action($param, $callable_arr);
    }

    private function add_param_and_action($param, $callable_function){
        $bool = in_array($param, $this->params_list);
        if (!$bool){
            // array_push($this->params_list, $param);
            $this->params_list[$param] = $callable_function;
        }
    }
}
