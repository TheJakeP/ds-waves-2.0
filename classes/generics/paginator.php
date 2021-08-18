<?php
namespace waves;

class paginator {
    protected $array_data;
    protected $pagination_style           = "flex justify-center items-center content-center bg-white border border-hex-D9D9D9 focus:color-waves-black focus:shadow-none";
    protected $pagination_style_current   = "flex justify-center items-center content-center bg-white border border-hex-D9D9D9 focus:color-waves-black focus:shadow-none";
    protected $current_page;
    protected $total_pages;

    protected $nav_button_style;

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
        "Price: Low to High" => [
            "sort_by" => "price",
            "sort_order" => "asc",
        ],
        "Price: High to Low" => [
            "sort_by" => "price",
            "sort_order" => "dsc",
        ],
    ];

    
    protected $array_prepared = null;



    public function __construct($array, $entries_per_page){
        $this->set_class_variables($array, $entries_per_page);
        $this->prepare_data();
    }

    
    public function set_class_variables($array, $entries_per_page){
        $this->array_data = $array;


        $this->entries_per_page = $entries_per_page;
        $this->total_entries = count($array);

        $total_pages    = ceil($this->total_entries/$this->entries_per_page);

        $this->current_page     = self::current_page();
        $this->total_pages      = ceil($total_pages);
        
        $this->flank_count      = 3;    //The number of pages to show on each side of center
    }
    
    protected function prepare_data(){
        $this->filter_array();
        $this->sort_array();
    }

    protected function filter_array(){
        if ($this->array_prepared == null){
            $this->array_prepared = $this->array_data;
        }
    }

    protected function get_entries_per_page(){
        return $this->entries_per_page;
    }

    protected function set_entries_per_page($int){
        $this->entries_per_page = $int;
    }

    protected function sort_array(){
        if ($this->is_sort_active()){
            $sort_by_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_by());
            $sort_order_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_order());
    
            switch ($sort_by_param) {
                case "industry":
                    $this->array_prepared = db_remote::get_brand_array_by_industry();
                    if (strcmp($sort_order_param, "dsc") == 0){
                        array_reverse($this->array_prepared);
                    }
                    break;

                case "name":
                    $this->array_prepared = db_remote::get_brand_array_by_name();
                    $this->ksort_string_array_prepared($sort_order_param);
                    break;
                    
                case "price":
                    $this->array_prepared = db_remote::get_brand_array_by_price();
                    $this->ksort_numeric_array_prepared($sort_order_param);
                    break;

            }
        }
    }

    protected function ksort_string_array_prepared($direction){
        if (strcmp($direction, "asc") == 0){
            ksort($this->array_prepared, SORT_STRING);
        } else if (strcmp($direction, "dsc") == 0) {
            krsort($this->array_prepared, SORT_STRING);
        }
    }

    protected function ksort_numeric_array_prepared($direction){
        if (strcmp($direction, "asc") == 0){
            ksort($this->array_prepared, SORT_NUMERIC);
        } else if (strcmp($direction, "dsc") == 0) {
            krsort($this->array_prepared, SORT_NUMERIC);
        }
    }

    protected function get_data_to_display(){
        if ($this->array_prepared == null){
            $this->array_prepared = $this->array_data;
        } 
        return $this->array_prepared;
    }

    protected function get_filter_field(){
        ?>
        <input 
            type="text"
            id="keyword_filter"
            placeholder="Filter by Keyword"
            class="<?php echo constants::get_waves_input_style_classes();?> h-10 max-h-10 min-w-40 "
        >
        <?php
    }

    public function get_sort_select_menu(){
        $sort_by_param = constants::_get_param_sort_by();
        $sort_order_param = constants::_get_param_sort_order();
        $params = json_encode(array($sort_by_param, $sort_order_param));

        $default_selected = "";
        if ($this->is_sort_active()){
            $default_selected = " selected ";
        }
    ?>
    <select 
        id="sort_by"
        class="flex flex-col max-w-unset <?php echo constants::get_waves_input_style_classes();?> h-10 max-h-10 w-48"
        onchange="dropdown_sort_changed(this);"
        sort_by_param='<?php echo $sort_by_param?>';
        sort_order_param='<?php echo $sort_order_param;?>';
        params='<?php echo $params;?>'
        
    >
        <option hidden disabled<?php echo $default_selected;?>>Sort by</option>
        <?php
        foreach ($this->sort_by_options as $name => $param_arr){
            $sort_by = $param_arr['sort_by'];
            $sort_order = $param_arr['sort_order'];

            $value = array(
                $sort_by_param => $sort_by,
                $sort_order_param => $sort_order,
            );
            $value = json_encode($value);
            $selected = "";
            if ($this->is_option_entry_selected($sort_by, $sort_order)){
                $selected = " selected ";
            }
            ?>
                <option <?php echo $selected;?> value='<?php echo $value;?>'><?php echo $name;?></option>
            <?php
        }
        ?>
    </select>
    <?php
    }

    public function is_sort_active(){
        $active_sort_by_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_by());
        $active_sort_order_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_order());
        
        if (is_null($active_sort_by_param) || is_null($active_sort_order_param)){
            return false;
        } else {
            return true;
        }
    }

    public function is_option_entry_selected($sort_by_param, $sort_order_param){
        $active_sort_by_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_by());
        $active_sort_order_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_order());
        if ( true == (strcmp($sort_by_param, $active_sort_by_param) == 0) && (strcmp($sort_order_param, $active_sort_order_param) == 0)){
            return true;
        } else {
            return false;
        }
    }

    public function change_flank_count($count){
        $this->flank_count = $count;
    }

    protected function paginator_bottom_row($nav_row_style, $text_style, $button_style, $nav_button_style){
        $this->nav_button_style = $nav_button_style;
    ?>
        <div class="flex flex-row flex-nowrap justify-between items-center <?php echo $nav_row_style;?>">
        <?php
            $this->get_x_of_y_text($text_style);
            $this->get_paginator_nav($button_style, $nav_button_style);
        ?>
        </div>
    <?php
    }

    public function get_x_of_total_items($text_style){
        $start = $this->get_filtered_count();
        $total = $this->get_total_count(); 
    ?>
        <div class="font-arial "><?echo $start;?> of <?echo $total;?></div>
    <?php
    }

    public function get_x_of_y_text($text_style){
        $start = $this->get_start_index() + 1;
        $stop = $this->get_stop_index();
        $total = $this->get_total_count(); 
    ?>
        <div class="font-arial ">Showing <b><?echo $start;?></b> to <b><?echo $stop;?></b> of <b><?echo $total;?></b> results</div>
    <?php
    }

    public function get_paginator_nav($style){
    ?>
        <div class="flex flex-row flex-nowrap <?php echo $style;?>">
        <?php
            $count = $this->get_total_count();
            $display_count = $this->get_entries_per_page();
            if ($count > $display_count){
                $this->get_left_arrow();
                $this->get_left_of_center();
                $this->get_middle_entry();
                $this->get_right_of_center();
                $this->get_right_arrow();
            }
        ?>
        </div>
    <?php
    }

    public function get_filtered_count(){
        if (is_null($this->array_filtered)){
            return $this->get_total_count();
        } else {
            return count($this->array_filtered);
        }
    }
    
    public function get_total_count(){
        return count($this->array_data);
    }

    public function get_start_index(){
        $current_page = self::current_page();
        return $this->entries_per_page * ($current_page - 1);
    }

    public function get_stop_index(){
        $start = $this->get_start_index();
        $end = $this->entries_per_page + ($start);
        $total = $this->get_total_count();
        if ($end > $total){
            return $total;
        }  else {
            return $this->entries_per_page + ($start);
        }
    }

    public static function current_page(){
        $page_no = utilities::get_and_clean_url_param_value(constants::_get_param_results_page());
        if(is_numeric($page_no)){
            return intval($page_no);
        } else{
            return 1;
        } 
    }

    public function get_current_page(){
        $page_no = utilities::get_and_clean_url_param_value(constants::_get_param_results_page());
        if(is_numeric($page_no)){
            return intval($page_no);
        } else{
            return 1;
        }
    }

    public function get_left_of_center(){
        $page = null;
        $i = 0;
        for ($i = $this->flank_count; $i > 0; $i--){
            $page = $this->current_page - $i;
            if  ($page > 0){
                $this->make_link($page);
            }
        }
    }

    public function get_right_of_center(){
        $page = null;
        $i = 0;
        for ($i = 1; $i <= $this->flank_count; $i++){
            $page = $this->current_page + $i;
            $this->make_link($page);
        }
    }

    public function get_middle_entry(){
        $not_first_page = $this->current_page <= 1;
        $not_last_page  = $this->current_page >= $this->total_pages;
        if (($not_first_page == true) && ($not_last_page == true)){
            $this->make_link($this->current_page);
        }
    }

    protected function make_link($page_number){
        $link = $this->page_url($page_number);
        $over_limit = $page_number > $this->total_pages;
        $under_limit = $page_number < 0;
        $out_of_bounds = ($over_limit == true) || ($under_limit == true);
        if ($out_of_bounds){
            return;
        } else if ($page_number == $this->current_page){
            $this->make_current_page($page_number);
        } else {
            $style = $this->pagination_style . " " . $this->nav_button_style;
            $this->make_ahref_with_link($page_number, $link, $style);
        }
    }

    protected function make_ahref_with_link($text, $link, $style){
        ?>
        <a href="<?php echo $link;?>" class="<?php echo $style;?>"><?php echo $text;?></a>
        <?php
    }

    protected function make_current_page($text){
        ?>
        <div class="<?php echo $this->pagination_style_current . " " . $this->nav_button_style;?>">
            <?php echo $text;?>
        </div>
        <?php
    }

    protected function page_url($page_number){
        if (is_numeric($page_number)){
            $params = $_GET;
            $params[constants::_get_param_results_page()] = urlencode(strval($page_number));
            $uri_path = $_SERVER['DOCUMENT_URI'];
            $uri_path = "";
            return  $uri_path . "?" . http_build_query($params);
        } else {
            return "#";
        }
    }

    protected function get_left_arrow(){
        if ($this->current_page > 1){
            $prev_page = $this->current_page - 1;
            $link = $this->page_url($prev_page);
            $style = $this->pagination_style . " " . "rounded-l" . $this->nav_button_style;
            $this->make_ahref_with_link("<", $link, $style);
        }
    }

    protected function get_right_arrow(){
        if ($this->current_page < $this->total_pages){
            $next_page = $this->current_page + 1;
            $link = $this->page_url($next_page);
            $style = $this->pagination_style . " " . "rounded-r" . " " . $this->nav_button_style;
            $this->make_ahref_with_link(">", $link, $style);
            
        }
    }

}