<?php
namespace waves;

use ReflectionClass;

class constants{
    private static $asset_path = WAVES_PLUGIN_ASSETS; //Defined in Waves.php
    private static $design_studio_svg_path = WAVES_PLUGIN_ASSETS . "images/logos/design_studio_black_horizontal.svg";
    private static $real_rich_snippets_svg_path = WAVES_PLUGIN_ASSETS . "images/logos/real_rich_snippets_logo.svg";
    private static $waves_logo_svg_path = WAVES_PLUGIN_ASSETS . "images/logos/waves_logo.svg";
    private static $white_caret_down = WAVES_PLUGIN_ASSETS . "images/icons/white_caret_down.svg";
    private static $white_caret_up = WAVES_PLUGIN_ASSETS . "images/icons/white_caret_up.svg";
    private static $gray_caret_down = WAVES_PLUGIN_ASSETS . "images/icons/gray_caret_down.svg";
    private static $gray_caret_up = WAVES_PLUGIN_ASSETS . "images/icons/gray_caret_up.svg";
    private static $black_gear = WAVES_PLUGIN_ASSETS . "images/icons/black_gear.svg";
    
    private static $real_rich_snippets_link_destination = "https://DesignStudios.com/real-rich-snippest";

    private static $STR_SEPARATOR = "-";

    private static $SHOPPING_CART_GROUP_ADD_VALUES = [
        "brand_add_all"   => "brand",
        "group_prefix"    => "entry_",
    ];

    private static $_GET_PARAMS = [
        // "Description/Index" => "url_param",
        "request_brand_id"  => "add_brand",
        "request_link_id"   => "add_link",
        "remove_brand_id"   => "rm_brand",
        "remove_link_id"    => "rm_link",
        // "brand_page"        => db_connector::get_page_parameter(), //Set in class
        "sort_by"           => "sort_by",
        "sort_order"        => "sort_order",
        "results_page"      => "results_page",
        "accordion_active"  => "active",
        "config_page"       => "config",
        "industry_page"     => "industry",
        "brand_page"        => "brand",
        "entry_page"        => "entry",
    ];
    
    private static $ID_LENGTH   = 3;
    private static $ID_PAD_CHAR = "0";

    private static $ENTRY_TYPES = [
        "blog" => [
            "class_name"    => entry_blog_remote::class,
            "short"         => "blog",
            "single"        => "Blog",
            "plural"        => "Blogs",
        ],
        "kb" => [
            "class_name"    => entry_kb_remote::class,
            "short"         => "kb",
            "single"        => "Knowledge Base",
            "plural"        => "Knowledge Base",
        ],
        "product" => [
            "class_name"    => entry_product_remote::class,
            "short"         => "prod",
            "single"        => "Product",
            "plural"        => "Products",
        ],
        "webpage" => [
            "class_name"    => entry_webpage_remote::class,
            "short"         => "web",
            "single"        => "Web Page",
            "plural"        => "Web Pages",
        ],
    ];

    private static $ENTRY_TYPES_SOON = [
        "Promotions" => [
            "short"         => "promo",
            "single"        => "Promotion",
            "plural"        => "Promotions",
        ],
        "Testimonials" => [
            "short"         => "test",
            "single"        => "Testimonial",
            "plural"        => "Testimonials",
        ],
        "Hero Blocks" => [
            "short"         => "Hero",
            "single"        => "Hero Block",
            "plural"        => "Hero Blocks",
        ],
    ];

    private static $ENTRY_TYPES_CONVERTER;

    private static $ENTRY_SHORT_KEYS;
    private static $ENTRY_SHORT_KEYS_SOON;

    private static $SLUG_POS = [
        "brand_id"  => 0,
        "entry_type"=> 1,
    ];

    private static $SORT_PARAMS = [
        "ascending"     => "asc",
        "descending"    => "dsc"
    ];

    private static $UNIQUE_ID = [
        "type"  => 0,
        "id"    => 1,
    ];

    public static function before_wp_loads_actions(){
        // cache::destroy();

        cache::session();
        // select_handler::handle_changes();
        shopping_cart::check_for_changes();
    }

    public static function before_page_load_actions(){

    }

    public static function after_page_load_actions(){
        cache::save_changes();
    }

    public static function get_design_studio_ahref(){
    ?>
        &nbsp<a class='<?php self::get_ahref_classes(); ?>' href='https://designstudio.com/'>Design Studio</a>
    <?php 
    }

    public static function get_design_studio_logo(){
    ?>
        <img class="border-none " src="<?php echo self::$design_studio_svg_path;?>" alt="Design Studio Logo" >
    <?php
    }

    public static function return_real_rich_snippets_link_destination(){
        return self::$real_rich_snippets_link_destination;
    }

    public static function echo_real_rich_snippets_link_destination(){
        echo self::$real_rich_snippets_link_destination;
    }

    public static function get_real_rich_snippets_logo(){
    ?>
        <img class="border-none " src="<?php echo self::$real_rich_snippets_svg_path;?>" alt="Design Studio Logo" >
    <?php
    }

    public static function return_blue_header_style(){
        return "font-roboto text-2xl text-white bg-hex-13252C py-5 px-8 rounded align-center items-center";
    }

    public static function get_blue_header_style(){
        echo self::return_blue_header_style();
    }


    public static function return_button_shape(){
        return "flex flex-col items-center justify-center rounded uppercase text-base border-2 font-arial font-bold ";
    }
    public static function get_button_shape(){
        echo self::return_button_shape();
    }

    public static function return_waves_button_style_blue_classes(){
        return "text-center border-waves-blue bg-waves-blue hover:bg-white text-white hover:text-waves-blue " . self::return_button_shape() . self::return_transition_classes() ;
    }
    public static function get_waves_button_style_blue_classes(){
        echo self::return_waves_button_style_blue_classes(); 
    }

    public static function return_waves_button_style_gray_classes(){
        return " text-center border-hex-B9BDC4 bg-hex-E4E6EA hover:bg-white text-hex-8F969A hover:text-hex-707070 " . self::return_button_shape() . self::return_transition_classes() ;
    }
    public static function get_waves_button_style_gray_classes(){
        echo self::return_waves_button_style_gray_classes(); 
    }

    public static function return_waves_button_style_blue_outline_classes(){
        return " text-center border-waves-blue bg-white hover:bg-white text-waves-blue hover:text-waves-blue " . self::return_button_shape() . self::return_transition_classes() ;
    }
    public static function get_waves_button_style_blue_outline_classes(){
        echo self::return_waves_button_style_blue_outline_classes(); 
    }

    
    public static function return_waves_button_style_gray_outline_classes(){
        return " text-center border-hex-B9BDC4 bg-white hover:bg-white text-hex-8F969A hover:text-hex-707070" .  self::return_button_shape() . self::return_transition_classes() ;
    }
    public static function get_waves_button_style_gray_outline_classes(){
        echo self::return_waves_button_style_blue_outline_classes(); 
    }

    public static function get_gray_outline_classes(){
        return "border-2 rounded border-hex-B9BDC4";
    }
    public static function echo_gray_outline_classes(){
        echo self::get_gray_outline_classes();
    }

    public static function get_gray_top_border_classes(){
        return "border-t-2 border-hex-B9BDC4";
    }

    public static function echo_gray_top_border_classes(){
        echo self::get_gray_top_border_classes();
    }


    public static function return_waves_input_style_classes(){
        return " bg-white border-solid border-menu-inactive border-2 rounded resize-none py-1 px-5 placeholder-waves-placeholder ";
    }

    public static function get_waves_input_style_classes(){
        echo self::return_waves_input_style_classes(); 
    }

    public static function return_ahref_classes(){
        return " font-bold text-waves-blue hover:text-waves-blue-hover focus:shadow-none" . self::return_transition_classes();
    }

    public static function get_ahref_classes(){
        echo self::return_ahref_classes(); 
    }

    public static function return_waves_icon_path(){
        return self::$asset_path . "images/logos/waves_menu_icon.svg";
    }

    public static function return_waves_svg_path(){
        return self::$waves_logo_svg_path;
    }
    public static function get_waves_logo(){
    ?>
        <img class="border-none " src="<?php echo self::$waves_logo_svg_path;?>" alt="Waves by Design Studio Logo" >
    <?php
    }

    public static function get_white_caret_down($id){
        $alt_tags = "caret " . "opposite='" . self::$white_caret_up . "'";
        return utilities::return_img_tag( $id, self::$white_caret_down, "", "white caret pointing down", null, null, $alt_tags);
    }

    public static function get_white_caret_up($id){
        $alt_tags = "caret " . "opposite='" . self::$white_caret_down . "'";
        return utilities::return_img_tag( $id, self::$white_caret_up, "", "white caret pointing up", null, null, $alt_tags);
    }
    
    public static function get_gray_caret_down($id){
        $alt_tags = "caret " . "opposite='" . self::$gray_caret_up . "'";
        return utilities::return_img_tag( $id, self::$gray_caret_down, "", "gray caret pointing down", null, null, $alt_tags);
    }

    public static function get_gray_caret_up($id){
        $alt_tags = "caret " . "opposite='" . self::$gray_caret_down . "'";
        return utilities::return_img_tag( $id, self::$gray_caret_up, "", "gray caret pointing up", null, null, $alt_tags);
    }

    public static function get_black_gear ($id){
        return utilities::return_img_tag( $id, self::$black_gear, "", "gray gear", null, null, null);
    }

    public static function return_transition_classes(){
        return " ds-transition ";
    }

    public static function get_transition_classes(){
        echo self::return_transition_classes();
    }

    public static function _get_param_request_brand_id(){
        return self::$_GET_PARAMS['request_brand_id'];
    }

    public static function _get_param_request_link_id(){
        return self::$_GET_PARAMS['request_link_id'];
    }

    public static function _get_param_remove_brand_id(){
        return self::$_GET_PARAMS['remove_brand_id'];
    }

    public static function _get_param_remove_link_id(){
        return self::$_GET_PARAMS['remove_link_id'];
    }

    private static $brand_parameter;
    public static function _get_param_brand_page(){
        if (is_null(self::$brand_parameter)){
            self::$brand_parameter = db_connector::get_page_parameter();
        } else {
            return self::$brand_parameter;
        }
    }

    public static function get_brand_page_parameter(){
        return self::$_GET_PARAMS['brand_page']; 
    }

    public static function get_entry_page_parameter(){
        return self::$_GET_PARAMS['entry_page']; 
    }

    public static function get_config_page_parameter(){
        return self::$_GET_PARAMS['config_page'];
    }

    public static function get_industry_page_parameter(){
        return self::$_GET_PARAMS['industry_page'];
    }

    public static function sort_param_asc(){
        return self::$SORT_PARAMS['ascending'];
    }

    public static function sort_param_dsc(){
        return self::$SORT_PARAMS['descending'];
    }

    public static function _get_param_sort_by(){
        return self::$_GET_PARAMS['sort_by'];
    }

    public static function _get_param_sort_order(){
        return self::$_GET_PARAMS['sort_order'];
    }

    public static function _get_param_results_page(){
        return self::$_GET_PARAMS['results_page'];
    }

    public static function _get_param_active_accordion(){
        return self::$_GET_PARAMS['accordion_active'];
    }

    public static function get_menu_key(){
        // return self::$_SESSION_PARAMS['menu_manager'];
        return menu_manager::cache_id();
    }
    
    public static function get_cache_keys(){
        $classes = utilities::get_classes_in_this_namespace();
        $cached = array();
        foreach($classes as $klass) {
            $reflect = new ReflectionClass($klass);
            if($reflect->implementsInterface('waves\cached')){
                $cache_id = call_user_func($klass . '::cache_id');
                $cached[$klass] = $cache_id;
            }
        }
        return $cached;
    }

    

    public static function get_shopping_cart_param_value_add_entire_brand(){
        return self::$SHOPPING_CART_GROUP_ADD_VALUES['brand_add_all'];
    }

    public static function get_shopping_cart_prefix_add_all_entry_type(){
        return self::$SHOPPING_CART_GROUP_ADD_VALUES['group_prefix'];
    }

    public static function get_str_separator(){
        return self::$STR_SEPARATOR;
    }

    public static function get_entry_types(){
        if (is_null(self::$ENTRY_SHORT_KEYS)){
            $tmp = array();
            foreach (self::$ENTRY_TYPES as $key => $val_arr){
                $val = $val_arr['short'];
                array_push($tmp, $val);
            }
            $tmp = array_values($tmp);
            self::$ENTRY_SHORT_KEYS = $tmp;
        }
        return self::$ENTRY_SHORT_KEYS;
    }

    
    public static function get_all_entry_types_soon(){
        if (is_null(self::$ENTRY_SHORT_KEYS_SOON)){
            $tmp = array();
            foreach (self::$ENTRY_TYPES_SOON as $key => $val_arr){
                $val = $val_arr['short'];
                array_push($tmp, $val);
            }

            $tmp = array_values($tmp);
            self::$ENTRY_SHORT_KEYS_SOON = $tmp;
        }
        return self::$ENTRY_SHORT_KEYS_SOON;
    }

    public static function get_entry_soon(){
        if (is_null(self::$ENTRY_SHORT_KEYS)){
            $tmp = array();
            foreach (self::$ENTRY_TYPES as $key => $val_arr){
                $val = $val_arr['short'];
                array_push($tmp, $val);
            }
            $tmp = array_values($tmp);
            self::$ENTRY_SHORT_KEYS = $tmp;
        }
        return self::$ENTRY_SHORT_KEYS;
    }
   

    private static $ENTRY_TYPE_SHORT_TO_ALL;
    public static function get_entry_short_to_all_arr(){
            $tmp = array();
            foreach (self::$ENTRY_TYPES as $key => $val_arr){
                $short = $val_arr['short'];
                $local_arr = array();
                foreach ($val_arr as $name => $val){
                    $local_arr[$name] = $val;
                }
                $tmp[$short] = $local_arr;
            }
            foreach (self::$ENTRY_TYPES_SOON as $key => $val_arr){
                $short = $val_arr['short'];
                $local_arr = array();
                foreach ($val_arr as $name => $val){
                    $local_arr[$name] = $val;
                }
                $tmp[$short] = $local_arr;
            }
            self::$ENTRY_TYPE_SHORT_TO_ALL = $tmp;
    }


    public static function get_class_from_entry_type($type){
        if (is_null(self::$ENTRY_TYPE_SHORT_TO_ALL)){
            self::get_entry_short_to_all_arr();
        }   
        return self::$ENTRY_TYPE_SHORT_TO_ALL[$type]['class_name'];
    }

    public static function get_entry_type_name($type){
        if (is_null(self::$ENTRY_TYPE_SHORT_TO_ALL)){
            self::get_entry_short_to_all_arr();
        }   
        return self::$ENTRY_TYPE_SHORT_TO_ALL[$type]['single'];
    }

    public static function get_entry_type_name_plural($type){
        if (is_null(self::$ENTRY_TYPE_SHORT_TO_ALL)){
            self::get_entry_short_to_all_arr();
        } 
        return self::$ENTRY_TYPE_SHORT_TO_ALL[$type]['plural'];
    }


    public static function get_entry_type_blog(){
        return self::$ENTRY_TYPES['blog']['short'];
    }
    public static function get_entry_type_blog_name(){
        return self::$ENTRY_TYPES['blog']['single'];
    }
    public static function get_entry_type_blog_name_plural(){
        return self::$ENTRY_TYPES['blog']['plural'];
    }


    public static function get_entry_type_kb(){
        return self::$ENTRY_TYPES['kb']['short'];
    }
    public static function get_entry_type_kb_name(){
        return self::$ENTRY_TYPES['kb']['single'];
    }
    public static function get_entry_type_kb_name_plural(){
        return self::$ENTRY_TYPES['kb']['plural'];
    }    


    public static function get_entry_type_product(){
        return self::$ENTRY_TYPES['product']['short'];
    }
    public static function get_entry_type_product_name(){
        return self::$ENTRY_TYPES['product']['single'];
    }
    public static function get_entry_type_product_name_plural(){
        return self::$ENTRY_TYPES['product']['plural'];
    }


    public static function get_entry_type_webpage(){
        return self::$ENTRY_TYPES['webpage']['short'];
    }
    public static function get_entry_type_webpage_name(){
        return self::$ENTRY_TYPES['webpage']['single'];
    }
    public static function get_entry_type_webpage_name_plural(){
        return self::$ENTRY_TYPES['webpage']['plural'];
    }

    public static function get_id_length(){
        return self::$ID_LENGTH;
    }
    public static function get_id_pad_char(){
        return self::$ID_PAD_CHAR;
    }

    public static function get_slug_pos_of_brand(){
        return self::$SLUG_POS['brand_id'];
    }

    public static function get_slug_pos_of_entry_type(){
        return self::$SLUG_POS['entry_type'];
    }

    public static function get_unique_id_pos_of_id(){
        return self::$UNIQUE_ID['id'];
    }

    public static function get_unique_id_pos_of_type(){
        return self::$UNIQUE_ID['type'];
    }


}


