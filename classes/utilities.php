<?php
namespace waves;

use ReflectionClass;

class utilities {

    public static function is_plugin_activated(){
        // To Do
        $activated = true;
        // $activated = false;
        if ($activated){
            return true;
        } else{
            return false;
        }
        
    }

    public static function lorem_generator($paragraph_count){
        $url = "http://loripsum.net/api/$paragraph_count/short/plaintext";
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($httpCode != 200) {
            $array = array();
            $value = "This is some generic text because we can't connect to the ipsum generator api at the moment.";
            $array = array_fill(0, $paragraph_count, $value);
            $text = implode("</p>\n\n<p>", $array);
            return "<p>$text</p>";
        }
        curl_close($handle);
        return trim($response);
    }
    
    public static function encode_accordion_slug ($brand_id, $entry_type) {
        $arr = [
            constants::get_slug_pos_of_brand()      => $brand_id,
            constants::get_slug_pos_of_entry_type() => $entry_type,
        ];
        ksort($arr);
        $str = implode(constants::get_str_separator(), $arr);
        $str = self::generate_obj_id_from_str($str);
        return $str; 
    }

    public static function decode_accordion_slug ($slug){
        $arr_from_str = explode(constants::get_str_separator(), $slug);

        $brand  = $arr_from_str[constants::get_slug_pos_of_brand()];
        $type   = $arr_from_str[constants::get_slug_pos_of_entry_type()];
        
        $array = [
            'brand' => $brand,
            'type'  => $type,
        ];
        return $array;

    }
    
    public static function encode_unique_id_with_type_and_id ($type, $id) {
        $id = str_pad($id, constants::get_id_length(), constants::get_id_pad_char(), STR_PAD_LEFT);
        $arr = [
            constants::get_unique_id_pos_of_id()      => $id,
            constants::get_unique_id_pos_of_type()    => $type, 
        ];
        ksort($arr);
        $str = implode(constants::get_str_separator(), $arr);
        $str = self::generate_obj_id_from_str($str);
        return $str; 
    }

    private static function decode_unique_id_to_arr($unique_id){
        $arr_from_str = explode(constants::get_str_separator(), $unique_id);

        $type   = $arr_from_str[constants::get_unique_id_pos_of_type()];
        $id     = $arr_from_str[constants::get_unique_id_pos_of_id()];
        
        $array = [
            'type'  => $type,
            'id'    => $id,
        ];
        return $array;

    }

    public static function decode_type_from_unique_id($unique_id){
        return self::decode_unique_id_to_arr($unique_id)['type'];
    }

    public static function decode_entry_id_from_unique_id($unique_id){
        return self::decode_unique_id_to_arr($unique_id)['id'];
    }

    public static function generate_entry_unique_entry_id($type, $array){
        $count = count($array);
        return self::encode_unique_id_with_type_and_id($type, $count);
    }


    public static function generate_obj_id_from_str($title){
        $title = self::remove_whitespace_from_str($title);
        $title = strtolower($title);
        $title = urlencode($title);
        return $title;
    }
    
    public static function remove_whitespace_from_str($str){
        return preg_replace('/\s+/', '_', $str);
    }

    public static function make_slug_from_string($string){
        $string = strtolower($string);
        $string = self::remove_whitespace_from_str($string);
        return urlencode($string);
    }

    public static function url_decode_string($string){
        return strtolower(json_decode(urldecode($string)));
    }

    public static function url_decode_param($param){
        return self::get_and_clean_url_param_value($param);
    }
    
    public static function get_and_clean_url_param_value($param){
        if (isset($_GET[$param])){
            $filtered = filter_input(INPUT_GET, $param, FILTER_SANITIZE_STRING);
            return strtolower($filtered);
        } else {
            return null;
        }
    }

    public static function echo_img_tag($id, $url, $styles, $alt, $width, $height){
        echo self::return_img_tag ($id, $url, $styles, $alt, $width, $height, "");
    }

    public static function return_img_tag ($id, $url, $styles, $alt, $width, $height, $custom_attributes){
        if (!is_null($width) || $width > 0){
            $width = " width='$width'";
        }

        if (!is_null($height) || $height > 0){
            $height = " height='$height'";
        }

        $str = "<img $custom_attributes id='$id' class='border-none $styles ' src='$url' alt='$alt' $width $height />";
        return $str;
    }

    public static function string_is_for_entry_type_group($string){
        $prefix = constants::get_shopping_cart_prefix_add_all_entry_type();
        $bool = utilities::does_str_start_with($string, $prefix);
        return $bool;
    }

    public static function decode_entry_type_group_from_string($string){
        
        $prefix = constants::get_shopping_cart_prefix_add_all_entry_type();
        if (utilities::does_str_start_with($string, $prefix)){
            $needle_length = strlen($prefix);
            $string_length = strlen($string);
            $sub_str = substr($string, $needle_length, $string_length);
            return $sub_str;
        }
        return null;
    }

    public static function does_str_start_with($haystack, $needle){
        $needle_length = strlen($needle);
        $sub_str = substr($haystack, 0, $needle_length);
        if ($sub_str === $needle){
            return true;
        } else {
            return false;
        }
    }
    
    public static function format_usd($int){
        return "$" . number_format($int, 2, ".", ",");
    }

    public static function return_button($id, $classes, $url, $text){
        
        if (empty($id)){
            $id = 'id="' . $id . '" ';
        }
        
        if (empty($classes)){
            $classes = constants::return_waves_button_style_blue_classes() . "  px-4 py-2";
        }
        
        if (empty($url)){
            $url = "#";
        }
        
        return "<a href='$url' $id class='$classes'>$text</a>";
    }

    public static function make_button($id, $classes, $url, $text){
        echo self::return_button($id, $classes, $url, $text);
    }

    public static function return_link($id, $url, $text){
                
        if (!empty($id)){
            $id = 'id="' . $id . '" ';
        }
        
        $classes = constants::return_ahref_classes();
        
        if (empty($url)){
            $url = "#";
        }
        
        return "<a href='$url' $id class='$classes'>$text</a>";
    }

    public static function make_link($id, $url, $text){
        echo self::return_link($id, $url, $text);
    }

    public static function check_obj_type($obj, $full_class_name){
        return is_a($obj, $full_class_name);
    }

    public static function array_contains_key($key, $array){
        $bool = array_key_exists($key, $array);
        if ($bool) {
            $val = $array[$key];
            if (!is_null($val) || !empty($val)){
                return true;
            }
        }
        return false;
    }
    
    
    public static function get_classes_in_this_namespace() {
        $namespace = __NAMESPACE__ . '\\';
        $myClasses  = array_filter(get_declared_classes(), function($item) use ($namespace) { return substr($item, 0, strlen($namespace)) === $namespace; });
        $theClasses = [];
        foreach ($myClasses as $class){
            //   $theParts = explode('\\', $class);
              $theClasses[] = $class;
        }
        return $theClasses;
    }

    public static function get_select_for_testing($placeholder, $id){
        // select_handler::register_param_and_callable("param", array(select::class, "callable_function"));
        $select = new select($id);
        $select->set_placeholder($placeholder);
        $random_int = random_int(2, 8);
        for ($i = 0; $i < $random_int; $i++){
            $select->add_option("Random Generated Option $i", "$id-$i", false);
        }
        return $select;
    }

}