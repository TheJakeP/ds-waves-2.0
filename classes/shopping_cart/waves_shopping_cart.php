<?php
namespace waves;

class shopping_cart implements cached, waves_objects {
    private static $cache_id = "shopping_cart";
    private $cart_entries = array();
    

    public static function cache_id() {
        return self::$cache_id;
    }
    
    public static function empty(){
        $key = self::cache_id();
        $class_name = get_called_class();
        $class_obj = new $class_name;
        cache::update_variable_by_key_obj($key, $class_obj );
    }

    public function get_obj(){
        return $this;
    }

    public static function get(){
        return cache::get_shopping_cart();
    }

    public static function display(){
        $cart_obj =  cache::get_shopping_cart();
        $cart_obj->show();
    }

    public static function check_for_changes(){
        $cart = cache::get_shopping_cart();
        $cart->check_for_cart_additions_and_subtractions();
    }

    public function show(){
        $this->cleanup();
        ?>
        <div class="flex flex-col font-roboto text-waves-black align-baseline justify-between mt-6 bg-white h-full">
            <div class="<?php constants::get_blue_header_style();?>">Brands List</div>
            <div class="flex flex-col w-full  h-full content-center justify-start items-stretch text-center font-roboto text-2xl color-hex-707070 p-14 space-y-8">
            <?php
                $count = $this->get_cart_entry_count();
                if ($count == 0){
                    ?>
                        You don’t have any brands yet.<br>Select a brand to get started.
                    <?php
                } else {
                    $this->get_shopping_cart_entries_as_accordion();
                    $this->get_shopping_cart_total();
                }
                
            ?>
            </div>
        </div>
        <?php
    }

    private function cleanup(){
        $cart_entries = $this->cart_entries;
        foreach ($cart_entries as $key => $cart_brand){
            $bool = $cart_brand->is_empty();
            if ($bool) {
                $this->brand_remove_all($key);
            }
            
        }
    }

    private function get_shopping_cart_entries_as_accordion(){
        $cart_entries = $this->cart_entries;
        foreach ($cart_entries as $key => $cart_brand){
            new cart_accordion_brand_remote($cart_brand);
        }
    }

    private function get_shopping_cart_total(){
    
        $total_int = $this->get_total_price();
        $total_str = utilities::format_usd($total_int);;
        
        ?>
        <div class="flex flex-col flex-nowrap font-roboto">
            <div class="flex flex-row mt-6 pt-9 <?php constants::echo_gray_top_border_classes();?>pt-9 <?php constants::echo_gray_top_border_classes();?>" ></div>
            <div class="flex flex-row flex-nowrap justify-between font-montserrat font-bold text-2xl">Your List Summary</div>
            <div class="flex flex-col font-roboto text-xl space-y-3 my-9">
                <?php $this->get_brand_total_list();?>
            </div>
            <div class="flex flex-row flex-nowrap justify-between font-roboto text-xl font-bold">
                <div class="">TOTAL</div>
                <div class=""><?php echo $total_str;?></div>
            </div>
            <div class="flex flex-row flex-nowrap justify-center my-6 text-base">
                <?php $this->get_interest_breakdown($total_int);?>
            </div>
            <div class="flex flex-row flex-nowrap justify-center">
                <?php $this->get_request_button();?>
            </div>
        </div>
        <?php
    }

    private function get_brand_total_list(){
        $cart_entries = $this->get_cart_entries();
        $total = 0;
        foreach ($cart_entries as $cart_brand_obj){
            $brand_name     = $cart_brand_obj->get_brand_name();
            $brand_price    = $cart_brand_obj->get_total_price();
            $price_str      = utilities::format_usd($brand_price);
            $total += $brand_price;
            ?>
            <div class="flex flex-row justify-between">
                <div class=""><?php echo $brand_name;?></div>
                <div class=""><?php echo $price_str;?></div>
            </div>
            <?php
        }
        $this->price_timestamp = time();
        $this->price_total = $total;
    }

    private function get_total_price(){
        $current_time = time();
        if ($this->price_timestamp != $current_time){
            $cart_entries = $this->get_cart_entries();
            $total = 0;
            foreach ($cart_entries as $cart_brand_obj){
                $total += $cart_brand_obj->get_total_price();
            }
            $this->price_timestamp = time();
            $this->price_total = $total;
        }
        return $this->price_total;
    }

    private function get_interest_breakdown($price){
        $payment_no = 4;
        $price = $price / $payment_no;
        $price_str = utilities::format_usd($price);
        ?>
        <div class="">
            Pay in <?php echo $payment_no;?> interest-free payments of <?php echo $price_str;?>. 
            <a 
                class="<?php constants::get_ahref_classes();?>" 
                href="#"
                >SEE DETAILS</a>
        </div>
        <?php

    }

    private function get_request_button(){
        $id = "";
        $classes = constants::return_waves_button_style_blue_classes() . " px-6 py-4 ";
        $url = "#";
        $text = "REQUEST BRANDS";
        utilities::make_button($id, $classes, $url, $text); 
    }

    public function check_for_cart_additions_and_subtractions(){
        $add_brand_param = constants::_get_param_request_brand_id();
        $add_entry_param = constants::_get_param_request_link_id();
        
        $param_array = array($add_brand_param, $add_entry_param);

        $bool = $this->check_if_get_params_exist($param_array);
        if ($bool){
            $add_brand_id = utilities::get_and_clean_url_param_value($add_brand_param);
            $add_value = utilities::get_and_clean_url_param_value($add_entry_param);
            $this->handle_add_requests($add_brand_id, $add_value);
        } 

        $rm_brand_param = constants::_get_param_remove_brand_id();
        $rm_entry_param = constants::_get_param_remove_link_id();
        $param_array = array($rm_brand_param, $rm_entry_param);

        $bool = $this->check_if_get_params_exist($param_array);
        if ($bool){
            $rm_brand_id = utilities::get_and_clean_url_param_value($rm_brand_param);
            $rm_value = utilities::get_and_clean_url_param_value($rm_entry_param);
            $this->handle_remove_requests($rm_brand_id, $rm_value);
        }

        $this->clear_params_from_url();
    }

    private function handle_add_requests($add_brand_id, $add_value){
        if ($this->check_if_change_is_entire_brand($add_value)){
            $this->brand_add_all($add_brand_id);
        } else if (utilities::string_is_for_entry_type_group($add_value)){
            $this->entry_type_add_all($add_brand_id, $add_value);
        } else {
            $this->single_entry_add($add_brand_id, $add_value);
        }
    }

    private function handle_remove_requests($add_brand_id, $add_value){
        if ($this->check_if_change_is_entire_brand($add_value)){
            $this->brand_remove_all($add_brand_id);
        } else if (utilities::string_is_for_entry_type_group($add_value)){
            $this->entry_type_remove_all($add_brand_id, $add_value);
        } else {
            $this->single_entry_remove($add_brand_id, $add_value);
        }
    }

    private function check_if_change_is_entire_brand($add_value){
        $str_one = $add_value;
        $str_two = constants::get_shopping_cart_param_value_add_entire_brand();
        return (strcmp($str_one, $str_two) == 0);
    }
    
    private function brand_add_all ($brand_id){
        $cart_brand = $this->get_or_create_shopping_cart_brand($brand_id);
        $cart_brand->add_all_of_brand();
        $this->cart_entries[$brand_id] = $cart_brand;
    }

    private function brand_remove_all ($brand_id){
        $bool = $this->is_brand_in_cart($brand_id);
        if ($bool){
            unset($this->cart_entries[$brand_id]);
        }
    }

    private function entry_type_add_all ($brand_id, $add_value){
        $type = utilities::decode_entry_type_group_from_string($add_value);
        $cart_brand = $this->get_or_create_shopping_cart_brand($brand_id);
        $cart_brand->add_all_of_type($type);
        $this->cart_entries[$brand_id] = $cart_brand;
    }

    private function entry_type_remove_all ($brand_id, $add_value){
        $type = utilities::decode_entry_type_group_from_string($add_value);
        $cart_brand = $this->get_or_create_shopping_cart_brand($brand_id);
        $cart_brand->remove_all_of_type($type);
    }

    private function single_entry_add ($brand_id, $entry_id){
        $cart_brand = $this->get_or_create_shopping_cart_brand($brand_id);
        $cart_brand->add_single($entry_id);
        $this->cart_entries[$brand_id] = $cart_brand;
    }

    private function single_entry_remove ($brand_id, $entry_id){
        if ($this->is_brand_in_cart($brand_id)){
            $cart_brand = $this->get_or_create_shopping_cart_brand($brand_id);
            $cart_brand->remove_single($entry_id);
        }
    }



    private function get_or_create_shopping_cart_brand($brand_id){
        $brand_obj = null;
        if ($this->is_brand_in_cart($brand_id)){
            $brand_obj = $this->cart_entries[$brand_id];
        } else {
            $brand_obj = new shopping_cart_brand($brand_id);
        }
        return $brand_obj;
    }



    private function check_if_get_params_exist($array_of_git_params){
        foreach ($array_of_git_params as $param){
            if (!array_key_exists($param, $_GET)){
                return false;
            }
        }
        return true;
    }

    public function get_cart_entry_count(){
        $cart_entries = $this->get_cart_entries();
        return count($cart_entries);
    }

    public function get_cart_entries(){
        $this->cleanup();
        return $this->cart_entries;
    }

    public function clear_cart(){
        $this->cart_entries = array();
    }

    private function clear_params_from_url () {
        $param_list = array(
            constants::_get_param_request_brand_id(),
            constants::_get_param_request_link_id(),
            constants::_get_param_remove_brand_id(),
            constants::_get_param_remove_link_id(),
        );

        $cur_params = $_GET;

        foreach ($param_list as $param){
            if (array_key_exists($param, $cur_params)){
                unset($cur_params[$param]);
            }
        }

        $_GET = $cur_params;
        $uri_path = $_SERVER['DOCUMENT_URI'];
        $url = $uri_path . "?" . http_build_query($cur_params);
        ?>
        <script>
            var url = "<?php echo $url;?>";
            document.addEventListener('DOMContentLoaded', function() {
                clear_params(url);
            }, false);
         </script>

          
         <?php
    }

    public function is_brand_in_cart($brand_id){
        return array_key_exists($brand_id, $this->cart_entries);
    }

    public function is_entry_in_cart($brand_id, $type, $id){
        
        $bool = $this->is_brand_in_cart($brand_id);
        if ($bool){
            $cart_entries = $this->get_cart_entries();
            $cart_brand = $cart_entries[$brand_id];
            return $cart_brand->contains_entry($type, $id);
        }
        return false;
    }

    public function is_entry_type_in_cart($brand_id, $type){
        $cart_entries = $this->get_cart_entries();
        
        $bool = array_key_exists($brand_id, $cart_entries);
        if ($bool){
            $cart_brand = $cart_entries[$brand_id];
            return $cart_brand->contains_any_entries_of_type($type);
        }
        return false;
    }

    private static function get_add_link($slug, $brand_val, $id_val){
        $params = $_GET;

        if (!is_null($brand_val)){
            $params[constants::_get_param_request_brand_id()]   = $brand_val;
        }

        if (!is_null($id_val)){
            $params[constants::_get_param_request_link_id()]    = $id_val;
        }

        if (!is_null($slug)){
            $params[constants::_get_param_active_accordion()]    = $slug;
        }
        $uri_path = $_SERVER['DOCUMENT_URI'];
        return $uri_path . "?" . http_build_query($params);
    }

    private static function get_remove_link($slug, $brand_val, $id_val){
        $params = $_GET;

        if (!is_null($brand_val)){
            $params[constants::_get_param_remove_brand_id()]   = $brand_val;
        }

        if (!is_null($id_val)){
            $params[constants::_get_param_remove_link_id()]    = $id_val;
        }

        if (!is_null($slug)){
            $params[constants::_get_param_active_accordion()]   = $slug;
        }
        $uri_path = $_SERVER['DOCUMENT_URI'];
        return $uri_path . "?" . http_build_query($params);
    }


    public static function get_add_single_brand_entry_to_cart($entry){
        
        $brand_id   = $entry->get_brand_id();
        $class_slug = $entry->get_slug();
        $unique_id  = $entry->get_unique_id();

        return self::get_add_link($class_slug, $brand_id, $unique_id);
    }

    public static function get_remove_single_brand_entry_from_cart_url($entry){
        
        $brand_id   = $entry->get_brand_id();
        $class_slug = $entry->get_slug();
        $unique_id  = $entry->get_unique_id();

        return self::get_remove_link($class_slug, $brand_id, $unique_id);
    }

    public static function get_add_brand_entry_type_to_cart_url_by_obj_sect_slug($accordion_slug, $brand_id, $type){
        $value = constants::get_shopping_cart_prefix_add_all_entry_type() . $type;
        return self::get_add_link($accordion_slug, $brand_id, $value);
    }

    public static function get_remove_brand_entry_type_to_cart_url_by_obj_sect_slug($accordion_slug, $brand_id, $type){
        $value = constants::get_shopping_cart_prefix_add_all_entry_type() . $type;
        return self::get_remove_link($accordion_slug, $brand_id, $value);
    }

    public static function get_add_all_brand_to_cart_url($brand_id){
        $value = constants::get_shopping_cart_param_value_add_entire_brand();
        $var = self::get_add_link(null, $brand_id, $value);
        return $var;
    }

    public static function get_remove_all_brand_from_cart_url($brand_id){
        $value = constants::get_shopping_cart_param_value_add_entire_brand();
        $var = self::get_remove_link(null, $brand_id, $value);
        return $var;
    }
}
