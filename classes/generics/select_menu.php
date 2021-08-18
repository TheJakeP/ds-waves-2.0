<?php

namespace waves;

class select implements select_action{
    private $id;
    private $classes    = " h-10 max-h-10 w-48";
    private $on_change  = "dropdown_changed(this)";
    private $listener_changed = false;
    private static $listener = "select_listener";



    private $array_options = array();
    private $placeholder;

        public function __construct($id){
            $this->id = "select-$id";
        }

        public static function test($param){
            $str = "SELECT TEST FUNCTION " . $param;
        }
        
        public static function get(){
            $class_name = self::class;
            return new $class_name ("id");
        }

        public function set_on_change($action){
            $this->listener_changed = true;
            $this->on_change = $action;
        }
        
        public function set_placeholder($title){
            $value = "{}";
            $this->placeholder = new select_option($this, $value, $title);
            $this->placeholder->set_default();
        }

        public function add_classes($classes){
            $this->classes .= " $classes";
        }

        public function set_classes($classes){
            $this->classes = $classes;
        }


        public function add_option($title, $value, $selected){
            $option = new select_option($this, $value, $title);
            if($selected == true){
                $option->selected();
            }
            $this->array_options[$title] = $option;
        }

        /* Array must be in the following Format: 
        */
        /*
        private $array = [
            key_irrelevent => [ 
                "selected"  => bool,
                "title"     => "Option Title1",
                "value" => [
                        "param1" => "value",
                        "param2" => "value",
                    ],
            ],
            key_irrelevent => [ 
                "selected"  => bool,
                "title"     => "Option Title2",
                "value" => "value",
            ],
        ];
        */
        public function add_options_by_array($array){
            foreach ($array as $data){
                $selected   = $data["selected"];
                $title      = $data["title"];
                $value      = $data["value"];
                $this->add_option($title, $value, $selected);
            }
        }
        
        public function sort_asc(){
            ksort($this->array_options);
        }
        
        public function sort_dsc(){
            krsort($this->array_options);
        }

        public function get_menu(){
            echo $this;
        }

        public function __toString(){

            $listener = self::$listener;
            $on_change = "";
            if ($this->listener_changed){
                $on_change = "onchange='$this->on_change;'";
                $listener = "";
            }
            $classes = "flex flex-col max-w-unset" . constants::return_waves_input_style_classes() . $this->classes;
            $ret_str = "<select id='$this->id' class='$listener $classes' $on_change >";
            
            $ret_str .= $this->placeholder;
            foreach ($this->array_options as $option){
                $ret_str .= $option;
            }
            $ret_str .= "</select>";
            
            return $ret_str;
        }

}

class select_option {
    private $default = "";
    private $selected = "";
    private $title;
    private $value;

    public function __construct(&$parent, $value, $title){
        $this->select_menu = $parent;
        $this->title = $title;
        $this->value = $value;
    }

    public function selected(){
        $this->selected = " selected ";
    }

    public function set_default(){
        $this->default = " hidden disabled selected";
    }

    public function get_value(){
        $bool = is_array($this->value);
        if ($bool){
            return json_encode($this->value);
        }
        return $this->value;
    }

    public static function is_selected(){
        //check active param
    }

    public function __toString(){
        $selected = $this->selected;
        $title = $this->title;
        $value = $this->get_value();
        $default = $this->default;
        
        return "<option value='$value' $default $selected >$title</option>";
    }
}