<?php
namespace waves;

class entry_local extends entry_generic implements select_action {
    
    protected $approval_description;
    protected static $redirect_url = "#";
    
    public function __construct($brand_id, $id, $name, $url, $content){
        $this->set_variables_local($brand_id, $id, $name, $url, $content);
        parent::__construct();
    }

    public static function get() {
        return db_local::get_entry_by_brand_and_entry_page_param();
     }


    public function get_status(){
        return random_int(0, 1);
    }

    protected function set_variables_local($brand_id, $id, $name, $url, $content){
        // $this->brand_obj    = $brand_obj;
        $this->brand_id     = $brand_id;
        $this->content      = $content;
        $this->name         = $name;
        $this->id           = $id;
        $this->url          = $url;
        $this->set_type_names();
    }
    
    protected function get_toggle_button(){
        
        $status = $this->get_status();
        $name = $this->name;
        $slug = $this->get_slug();

        $button = new toggle_button($name, $slug);
            
        if ($status == 1) {
            $button->active();
        } else if ($status == 2) {
            $button->disabled();
        }
        return $button;
    }

    public function get_accordion_row(){
        
        $brand_id = $this->get_brand_id();
        $entry_id = $this->get_unique_id();
        $url = Entry_Settings_Page::get_url_by_slug($brand_id, $entry_id);
        $name = $this->name;
        $slug = $this->get_slug();

        $button = $this->get_toggle_button();

    ?>
        <div class="flex flex-row font-roboto text-2xl text-waves-black py-5 align-center items-center border-b ">
            <div class="w-1/5"><?php $button->display();?></div>
            <?php utilities::make_link($slug, $url, $name);?>
        </div>
    <?php
    }

    protected function get_section($html_left, $html_right, $border_full_bool){
        $border = "border-b border-hex-A0AEC0";
        if ($border_full_bool){
            $full_margin = "py-20";
            $short_margin = "";
        } else {
            $full_margin = "";
            $short_margin = "py-14";
        }
        ?>
        <div class="flex flex-row font-roboto text-xl space-x-24 ">
            <div class="flex flex-col w-2/5"><?php echo $html_left;?></div>
            <div class="flex flex-col w-3/5"><?php echo $html_right;?></div>
        </div>
        <div class="flex flex-row space-x-24 <?php echo $full_margin . $short_margin;?>">
        <?php
            if (is_null($border_full_bool)){
                // do nothing to add spacing
            } else if ($border_full_bool){
            ?>
                <div class="flex flex-col w-full <?php echo $border;?>"></div>
            <?php
            } else {
            ?>
                <div class="flex flex-col w-2/5 "></div>
                <div class="flex flex-col w-3/5 <?php echo $border;?>"></div>
            <?php
        }
        ?>
        </div>
        <?php
    }

    protected function get_settings_section($title, $description, $html_content, $border_full_bool){
        $html_left = "<div class='font-bold mb-5'>$title</div>";
        $html_left .= "<div>$description</div>";
        $html_content = "<div class='flex flex-col flex-nowrap'>$html_content</div>";
        $this->get_section($html_left, $html_content, $border_full_bool);
    }

    public function get_settings_page(){
        
        $this->get_top_row();
        ?>
        <div class="flex flex-col first:bg-red-400">
        <?php
            $this->get_require_approval();
            $this->get_advanced_permalinks();
            $this->get_pricing_form();
            $this->get_brochure_form();
            $this->get_financing_form();
            $this->get_trade_ins_form();
            $this->get_test_soak_form();
            $this->get_buyers_guide();
            $this->get_text_me_pricing();
            $this->get_add_to_wish_list();
        ?>
        </div>
        <?php
    }

    protected function get_top_row(){
        $brand = $this->get_brand_obj();
        $brand_name = $brand->get_name();
        $brand_slug = $this->get_brand_id();
        $brand_url = Brand_Settings_Page::get_url_by_slug($brand_slug);
        
        $text = "<div class='flex flex-row items-center'><span class='blue_back_arrow'></span><span class='pl-2.5'>Back</span></div>";
        utilities::make_link("back_btn", $brand_url, $text);
        
        echo "<span class='mt-11 mb-10' >$brand_name</span>";
        ?>

        <?php
    }

    protected function get_require_approval(){
        $html_left = "<div class='flex flex-row flex-nowrap space-x-7 items-center'>";
        $html_left .= "<span class='color-hex-2B2B2B text-2xl'>Require Approval</span>";
        $html_left .= $this->get_toggle_button();
        $html_left .= "</div>";
        $html_right = $this->get_approval_description();
		$this->get_section ($html_left, $html_right, true);
    }

    protected function get_approval_description(){
        if (empty($this->approval_description)){
            $this->approval_description = utilities::lorem_generator("1");
        }
        return $this->approval_description;

    }

    protected function get_advanced_permalinks(){
        $title = "Get Advanced Permalinks";
        $description = "We've alreay setup default permalinks but you can use this area to change them.";
        
        $default_url = $this->get_seo_url();
        $redirect_url = self::$redirect_url;

        $html_content = "<div>Default Url: ". utilities::return_link("", "", $default_url) . "</div>";
        $html_content .= $this->return_advanced_permalinks_select_menus();
        $html_content .= "<div>Are you sure you want edit this? Maybe think about using a " . utilities::return_link("", $redirect_url, "re-direct") . ".</div>";
		
        $this->get_settings_section($title, $description, $html_content, true);
    }

    protected function get_seo_url(){
        $category = "category";
        $collection = "collection";
        $product = "product";
        return "https:" . get_site_url() . "/$category/$collection/$product/";

    }


    protected function get_two_menus_for_testing($section_name){
        $section_slug = urlencode($section_name);
        $select1 = utilities::get_select_for_testing("Default", $section_slug);
        $select1->add_classes("my-2.5 w-full");

        $select2 = utilities::get_select_for_testing("", $section_name);
        $select2->add_classes("my-2.5 w-full");
        return $select1 . $select2;
    }

    protected function return_advanced_permalinks_select_menus(){
        // select_handler::register_param_and_callable("param1", array(select::class, "test"));

        $name = "Name";
        $select = utilities::get_select_for_testing($name, "permalinks");
        $select->add_classes("my-5 w-full");
        return $select;
    }

    protected function get_pricing_form(){
        $title = "Get Pricing Form";
        $description = "";
        $html_content = $this->return_pricing_form_select_menus();
		$this->get_settings_section($title, $description, $html_content, false);
    }

    protected function return_pricing_form_select_menus(){
        return $this->get_two_menus_for_testing("Pricing");
    }

    protected function get_brochure_form(){
        $title = "Get Brochure Form";
        $description = "";
        $html_content = $this->return_brochure_form_select_menus();
		$this->get_settings_section($title, $description, $html_content, false);
    }

    protected function return_brochure_form_select_menus(){
        return $this->get_two_menus_for_testing("Brochure");
    }

    protected function get_financing_form(){
        $title = "Financing Form";
        $description = "";
        $html_content = $this->return_financing_form_select_menus();
		$this->get_settings_section($title, $description, $html_content, false);
    }

    protected function return_financing_form_select_menus(){
        return $this->get_two_menus_for_testing("Financing");
    }


    protected function get_trade_ins_form(){
        $title = "Trade-Ins Form";
        $description = "";
        $html_content = $this->return_trade_ins_form_select_menus();
		$this->get_settings_section($title, $description, $html_content, false);
    }

    protected function return_trade_ins_form_select_menus(){
        return $this->get_two_menus_for_testing("Trade Ins");
    }


    protected function get_test_soak_form(){
        $title = "Schedule Test Soak";
        $description = "";
        $html_content = $this->return_test_soak_form_select_menus();
		$this->get_settings_section($title, $description, $html_content, false);
    }

    protected function return_test_soak_form_select_menus(){
        return $this->get_two_menus_for_testing("Test Soak");
    }


    protected function get_buyers_guide(){
        $title = "Buyers Guide";
        $description = "";
        $html_content = $this->return_buyers_guide_select_menus();
		$this->get_settings_section($title, $description, $html_content, false);
    }

    protected function return_buyers_guide_select_menus(){
        return $this->get_two_menus_for_testing("Buyers Guide");
    }


    protected function get_text_me_pricing(){
        $title = "Text Me Pricing";
        $description = "";
        $html_content = $this->return_text_me_pricing_select_menus();
		$this->get_settings_section($title, $description, $html_content, false);
    }

    protected function return_text_me_pricing_select_menus(){
        return $this->get_two_menus_for_testing("Text Me");
    }


    protected function get_add_to_wish_list(){
        $title = "Add to Wish List";
        $description = "";
        $html_content = $this->return_add_to_wish_list_select_menus();
		$this->get_settings_section($title, $description, $html_content, null);
    }

    protected function return_add_to_wish_list_select_menus(){
        return $this->get_two_menus_for_testing("Wish List");
    }

    
}