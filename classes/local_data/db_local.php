<?php
namespace waves;

use Exception;

/*

To-do: 



*/
class db_local extends db_connector {

    public function __construct(){
        self::$page_url = Brands_Page::get_page_url();
        $this->build_catalog();
    }

    protected function build_catalog(){
        $this->build_test_catalog();
    }
        
    public function build_test_catalog(){
        $total_count = 28;

        
        $description = utilities::lorem_generator($$total_count + 10);
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

            $description = "Description for $title. " . $array_description[$i];
            $industry = $this->get_industry_by_name($industry_name);
            $brand = new brand_local ($title, $description, $img_url, $parent_company, $industry);
            
            $industry->add_brand($brand);
            $this->add_brand($brand);
        }
    }
    
    protected static function populate_brand_data_for_testing($brand_obj){
        $test_data_count = self::get_test_data_count();
        for ($i = 0; $i < $test_data_count; $i++){
            $brand_obj->add_blog("Blog title $i", "URL: $i", "Blog Content $i");
            $brand_obj->add_kb("KB $i", "URL: $i", "KB Content $i");
            $brand_obj->add_product("Product $i", "URL: $i", "Product Content $i");
            $brand_obj->add_webpage("webpage $i", "URL: $i", "WebPage Content $i");
        }
        
        cache::update_local_brand_obj($brand_obj);    //Must update or changes will not be saved to cache.
    }

    

}