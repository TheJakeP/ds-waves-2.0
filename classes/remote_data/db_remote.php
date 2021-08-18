<?php
namespace waves;

use Exception;

/*

To-do: 



*/
class db_remote extends db_connector {
    protected $array_by_brand_price = array();
    protected $array_price = array();

    protected function build_catalog(){
        self::$page_url = Request_Brands_Page::get_page_url();
        $this->build_test_catalog();
    }
    
    public function build_test_catalog(){
        $total_count = 280;

        $description = utilities::lorem_generator($total_count + 10);
        $array_description = explode("\n\n", $description);

        for ($i = $total_count; $i > 0; $i--){
            $title = "Brand name $i";
            
            $img = $i % 10;
            $img = str_pad($img, 2, "0", STR_PAD_LEFT);
            $img_url = "/wp-content/plugins/waves/assets/images/prototype/placeholder_$img.png";

            $parent = $i % 3;

            $parent_company = "Parent Co $parent";
            
            $industry_name = $i % 5;
            $industry_name = "industry $industry_name";

            $price = $i + 10;

            $description = "Description for $title. " . $array_description[$i];
            $industry = $this->get_industry_by_name($industry_name);
            $brand = new brand_remote($title, $description, $img_url, $parent_company, $industry, $price);
            
            $industry->add_brand($brand);
            $this->add_brand($brand);
        }
    }

    public function populate_brand_data($brand_obj){
        /* To Do setup Pull Brand data from Remote db */
        self::populate_brand_data_for_testing($brand_obj);
    }

    protected static function populate_brand_data_for_testing($brand_obj){
        $test_data_count = self::get_test_data_count();
        $price = $brand_obj->get_entry_price();
        for ($i = 0; $i < $test_data_count; $i++){
            $brand_obj->add_blog("Blog title $i", "URL: $i", "Blog Content $i", $price);
            $brand_obj->add_kb("KB $i", "URL: $i", "KB Content $i", $price);
            $brand_obj->add_product("Product $i", "URL: $i", "Product Content $i", $price);
            $brand_obj->add_webpage("webpage $i", "URL: $i", "WebPage Content $i", $price);
        }
        
        cache::update_remote_brand_obj($brand_obj);    //Must update or changes will not be saved to cache.
    }

    public static function get_entries_for_brand_by_slug($brand_slug){
        /* To Do setup Pull Brand data from db */
        return self::get_test_data_count() * 4;
    }
    
    public function add_brand(&$brand){
        parent::add_brand($brand);
        $this->array_by_brand_price[$brand->get_total_price_int()] = $brand;
        $this->add_brand_to_price($brand);
    }

    protected function add_brand_to_price($brand){
        $total_price = $brand->get_total_price_int();
        $group = $this->get_price_group_by_int($total_price);
        $group->add_brand($brand);
    }

    public function get_price_group_by_int($price_int){
        $array = &$this->get_price_array();
        return $this->get_entry_from_array_group($array, $price_int);
    }


    public static function get_brand_array_by_price(){
        return self::get_db_object()->array_by_brand_price;
    }
    
    protected function &get_price_array(){
        return $this->array_price;
    }

}