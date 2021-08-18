<?php
namespace waves;

use ReflectionClass;
use Exception;

/*

To-do: 



*/
class db_connector implements cached{
    protected static $session_key;
    protected static $page_param = "brand";

    public static $making_session_var = false;
    protected static $page_url = "";

    protected $array_brand_obj = array();
    protected $array_by_brand_name = array();
    protected $array_by_brand_slug = array();

    protected $array_industry = array();
    protected $array_industry_by_name = array();
    protected $array_industry_by_slug = array();

    /* Do this in the child class */
    protected static function populate_brand_data_for_testing($brand_obj){
        $caller = get_called_class();
        $caller::populate_brand_data_for_testing($brand_obj);
    }
    public function build_test_catalog(){}

    public function __construct(){
        $this->build_catalog();
    }

    public static function cache_id() {
        return get_called_class();
    }

    protected function build_catalog(){
        $this->build_test_catalog();
    }

    public function populate_brand_data($brand_obj){
        //To-Do setup Pull Brand data from db
        self::populate_brand_data_for_testing($brand_obj);
    }

    protected static function get_test_data_count(){
        $number = 15;
        return abs(random_int($number - 7, $number));
    }


    public static function get_entries_for_brand_by_slug($brand_slug){
        // TO DO: Ping database and ask for number of URLS for brand
        return self::get_test_data_count() * 4;
    }
    
    public function add_brand(&$brand){
        array_push($this->array_brand_obj, $brand);
        $this->array_by_brand_slug[$brand->get_slug()] = $brand;
        $this->array_by_brand_slug[$brand->get_slug()] = $brand;
        $this->array_by_brand_name[$brand->get_name()] = $brand;
    }

    protected function get_industry_by_name ($name){
        $array_name = &$this->get_industry_array_by_name();
        $bool = utilities::array_contains_key($name, $array_name);
        if ($bool) {
            return $array_name[$name];
        } else {
            return $this->make_and_save_industry($name);;
        }
    }

    protected function get_industry_by_slug ($slug){
        $array_name = &$this->get_industry_array_by_name();
        $bool = utilities::array_contains_key($slug, $array_name);
        if ($bool) {
            return $array_name[$slug];
        } else {
            return $this->make_and_save_industry($slug);;
        }
    }

    protected function make_and_save_industry($name) {
        $array_name = &$this->get_industry_array_by_name();
        $array_slug = &$this->get_industry_array_by_slug();
        $industry = new brand_group ($name);
        $name = $industry->get_name();
        $slug = $industry->get_slug();
        $array_name[$name] = &$industry;
        $array_slug[$slug] = &$industry;

        ksort($array_name);
        ksort($array_slug);
        return $industry;
    }

    protected function get_entry_from_array_group(&$array, $key){
        $bool = utilities::array_contains_key($key, $array);
        if ($bool) {
            return $array[$key];
        } else {
            $industry = new brand_group ($key);
            $array[$key] = $industry;
            ksort($array);
            return $array[$key];
        }
    }

    public static function get_db_object(){
        $class_name = get_called_class();
        $obj = cache::get_variable_by_key_and_class($class_name);
        return $obj;
    }

    public static function get_brand_obj_by_brand_slug_string($slug_string){
        $db_object = self::get_db_object();
        $brand_obj = $db_object->get_brand_obj_from_slug($slug_string);
        return $brand_obj;
    }

    public static function get_industry_from_url_parameter(){
        $param = constants::get_industry_page_parameter();
        $value = utilities::get_and_clean_url_param_value($param);
        $db_obj = self::get_db_object();
        return $db_obj->get_industry_by_slug($value);
    }

    public static function get_page_parameter(){
        $caller = get_called_class();
        return $caller::$page_param;
    }

    public static function &get_brand_by_brand_page_param(){
        $page_param = constants::get_brand_page_parameter();
        $brand_name = utilities::get_and_clean_url_param_value($page_param);
        $brand = &self::get_brand_from_slug($brand_name);

        if ($brand == null){
            $brand_page = new Request_Brands_Page();
            
            $title = $brand_page->get_page_title();
            $url = self::$page_url;
            echo "Looks like something is missing, please select a brand from the <a href='$url'>$title page</a>";
            echo("<script>window.location = '$url';</script>");
        } else {
            $brand->setup();
            return $brand;
        }
    } 

    public static function &get_entry_by_brand_and_entry_page_param(){
        $brand = self::get_brand_by_brand_page_param();

        $page_param = constants::get_entry_page_parameter();
        $entry_uid = utilities::get_and_clean_url_param_value($page_param);

        $type   = utilities::decode_type_from_unique_id($entry_uid);
        $id     = utilities::decode_entry_id_from_unique_id($entry_uid);
        $entry = &$brand->get_entry_by_type_and_id($type, $id);
        return $entry;
    } 

    public static function get_brand_from_slug($slug){
        $db = self::get_db_object();
        return $db->get_brand_by_slug($slug);
    }

    public function get_brand_by_slug($slug){
        $bool = array_key_exists($slug,  $this->array_by_brand_slug);
        if ($bool){
            $obj = $this->array_by_brand_slug[$slug];
            return $obj;
        } else {
            return null;
        }
    }


    public static function get_brand_array_by_name(){
        return self::get_db_object()->array_by_brand_name;
    }

    public static function get_brand_array_by_industry(){
        $db = self::get_db_object();
        return $db->get_flattened_brand_array();
    }

    protected function get_flattened_brand_array(){
        if (empty($this->array_flat_industry_name)){
            $categories_arr = $this->get_industry_array_by_name();
        
            $flat_array = array();
            foreach ($categories_arr as $industry_obj){
                $name_array = $industry_obj->get_array_by_name();
                foreach ($name_array as $name => $slug){
                    $brand = $this->get_brand_obj_from_slug($slug);
                    array_push($flat_array, $brand);
                }
            }
            $this->array_flat_industry_name = $flat_array;    
        }
        return $this->array_flat_industry_name;

    }

    public function get_brand_obj_from_slug($brand_slug){
        $bool = array_key_exists($brand_slug,  $this->array_by_brand_slug);
        if ($bool){
            $obj = $this->array_by_brand_slug[$brand_slug];
            return $obj;
        }
        return null;
    }

    public function update_brand($brand){
        $slug = $brand->get_slug();
        $array_by_slug[$slug] = $brand;
    }

    protected function &get_industry_array_by_name(){
        return $this->array_industry_by_name;
    }

    protected function &get_industry_array_by_slug(){
        return $this->array_industry_by_slug;
    }
    
}