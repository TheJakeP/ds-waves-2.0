<?php
namespace waves;


class active_brands_table extends table {
    private $classes_brand      = "w-3/12";
    private $classes_industry   = "w-3/12 font-normal";
    private $classes_urls       = "w-3/12 font-bold";
    private $classes_status     = "w-2/12";
    private $classes_configure  = "flex justify-center items-center w-1/12";

    public function __construct(){
        $entries_per_page = 12;
        $array_data = $this->sort_array();
        $array_data = db_local::get_brand_array_by_name();
        parent::__construct($array_data, $entries_per_page);
        $this->display();
    }

    protected $sort_by_options = [
        /*
        "name" => [
            "sort_by" => "",
            "sort_order" => "",
        ]
        */
        "All" => [
            "sort_by" => "",
            "sort_order" => "",
        ],
        "A to Z" => [
            "sort_by" => "name",
            "sort_order" => "asc",
        ],
        "Z to A" => [
            "sort_by" => "name",
            "sort_order" => "dsc",
        ],
        "Industry: A to Z" => [
            "sort_by" => "industry",
            "sort_order" => "asc",
        ],
        "Industry: Z to A" => [
            "sort_by" => "industry",
            "sort_order" => "dsc",
        ],
    ];

    protected function sort_array(){
       
        if ($this->is_sort_active()){

            $sort_by_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_by());
            $sort_order_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_order());
    
            switch ($sort_by_param) {
                case "industry":
                    $this->array_prepared = db_local::get_brand_array_by_industry();
                    if (strcmp($sort_order_param, "dsc") == 0){
                        $this->array_prepared = array_reverse($this->array_prepared);
                    }

                    
                    $this->ksort_string_array_prepared($sort_order_param);
                    break;

                case "name":
                    $this->array_prepared = db_local::get_brand_array_by_name();
                    $this->ksort_string_array_prepared($sort_order_param);
                    break;
            }
        } else {
            $this->array_prepared = db_local::get_brand_array_by_name();
            $this->ksort_string_array_prepared("asc");
        }

        return $this->array_prepared;

    }

    public function display(){
    ?>
        <div class="flex flex-col w-9/12 max-w-screen-xl">
    <?php
            $this->get_before_table();
        ?>
            <div class="flex flex-col w-full text-roboto bg-white">
        <?php
                $this->get_top_row();
                $this->make_rows();
                $this->get_paginator_bottom_row();
        ?>
            </div>
        </div>
    <?php
    }

    private function get_before_table(){
        $total = $this->get_total_count(); 
    ?>
        <div class="flex flex-row flex-nowrap justify-between items-center text-2xl my-16">
            <div class="flex flex-row"><span><b><?php echo $total;?> Brands</b> are connected to this site</span></div>
            <div class="flex flex-row flex-nowrap space-x-4 ">
        <?php 
                $this->get_filter_field();                
                $this->get_sort_select_menu();
        ?>
            </div>
        </div>
    <?php
    }

    protected function get_top_row(){
        $classes    = "flex flex-row justify-start " . constants::return_blue_header_style();

        $brand = "Brand";
        $industry = "Industry";
        $urls = "URL's Syndicated";
        $status = "Status";
        $configure = "Configure";
        $this->row($classes, $brand, $industry, $urls, $status, $configure);
        
    }
    
    private function make_rows() {
        $array = $this->array_data;
        $classes    = "bg-white flex flex-row items-center justify-center px-8 h-32 text-xl ";
        foreach ($array as $brand_obj){
            $brand = $brand_obj->get_logo_image();
            $brand_name = $brand_obj->get_name();
            $industry = $brand_obj->industry_link();
            $urls = $brand_obj->get_total_entries() . " URLs Active";
            $status = $brand_obj->return_display_status();

            $href = $brand_obj->get_Brand_Settings_Page_url();
            $configure = self::get_gear_link($brand_name, $href);
            $this->row($classes, $brand, $industry, $urls, $status, $configure);
        }

    }

    private static function get_gear_link($brand, $url){
        return "<a href='$url' alt=''>" . constants::get_black_gear($brand) . "</a>";
        
    }

    private function row($classes_row, $brand, $industry, $urls, $status, $configure){
    ?>
        <div class="<?php echo $classes_row;?> justify-between">
                <div class="<?php echo $this->classes_brand;?>"><?php echo $brand;?></div>
                <div class="<?php echo $this->classes_industry;?>"><?php echo $industry;?></div>
                <div class="<?php echo $this->classes_urls;?>"><?php echo $urls;?></div>
                <div class="<?php echo $this->classes_status;?>"><?php echo $status;?></div>
                <div class="<?php echo $this->classes_configure;?>"><?php echo $configure;?></div>
        </div>
    <?php
    }

    
    protected function get_paginator_bottom_row(){
        $nav_row_style = "";
        $text_style = "";
        $button_style = ""; 
        $nav_button_style = "";
        parent::paginator_bottom_row($nav_row_style, $text_style, $button_style, $nav_button_style);
    }
}