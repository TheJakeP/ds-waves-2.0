<?php
namespace waves;


class brand_local extends brand {

    private $status_code;

    private static $array_status = [
        0   =>  [
            "classes"   =>  "",
            "color"     =>  "hex-289500",
            "icon"      =>  "white_check_mark",
            "text"      =>  "Current Data",
        ],
        1   =>  [
            "classes"   =>  "",
            "color"     =>  "hex-EF9300",
            "icon"      =>  "white_question_mark",
            "text"      =>  "Pending Data",
        ],
        2   =>  [
            "classes"   =>  "",
            "color"     =>  "hex-EB0000",
            "icon"      =>  "white_exclamation_mark",
            "text"      =>  "Problem Detected",
        ],
    ];

    public function __construct($brand_name, $description, $logo_url, $parent_co, &$industry_ref){
        $this->child_obj_setup  = false;
        parent::__construct($brand_name, $description, $logo_url, $parent_co, $industry_ref);
    }

    /* To Do: */
    private function get_status_code(){
        if (is_null($this->status_code)){
            $this->status_code = random_int(0, 2);
        }
        return $this->status_code;
    }
    
    public function get_Brand_Settings_Page_url(){
        $slug = $this->get_slug();
        return Brand_Settings_Page::get_url_by_slug($slug);
    }

    public function get_logo_image(){
        $slug = "logo_" . $this->get_slug();
        $url = $this->get_logo_url();
        $styles = "w-36";
        $alt = "Logo for " . $this->get_name();
        $width = "";
        $height = "";
        return utilities::return_img_tag($slug, $url, $styles, $alt, $width, $height, "");
    }

    public function return_display_status(){
        $status = $this->get_status_code();
        
        $classes    = self::$array_status[$status]['classes'];
        $color      = self::$array_status[$status]['color'];
        $icon       = self::$array_status[$status]['icon'];
        $text       = self::$array_status[$status]["text"];
        
        $return      = "<div class='flex flex-row flex-nowrap items-center text-$color $classes'>";
        $return     .= "<span class='rounded-full h-7 w-7 mr-3 flex items-center justify-center bg-$color $icon'>";
        $return     .= "</span>";
        $return     .= "$text</div>";
        return $return;
    }

    public function setup(){
        if (!$this->child_obj_setup){
            $db_local = db_local::get_db_object();
            $db_local->populate_brand_data($this);
            $this->child_obj_setup = true;
        }
    }

    
    public function add_blog($name, $url, $content){
        $class_name = entry_blog_local::class;
        $entry_type = constants::get_entry_type_blog();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content);
    }

    public function add_kb($name, $url, $content){
        $class_name = entry_kb_local::class;
        $entry_type = constants::get_entry_type_kb();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content);
    }

    public function add_product($name, $url, $content){
        $class_name = entry_product_local::class;
        $entry_type = constants::get_entry_type_product();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content);
    }

    public function add_webpage($name, $url, $content){
        $class_name = entry_webpage_local::class;
        $entry_type = constants::get_entry_type_webpage();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content);
    }

    protected function add_new_entry ($class_name, $type, $name, $url, $content){
        $slug = $this->get_slug();
        $entry_slug = count($this->entries[$type]);

        $entry_obj = new $class_name ($slug, $entry_slug, $name, $url, $content);
        
        $slug = $entry_obj->get_id_string();
        if (!array_key_exists($type, $this->entries)){
            $this->entries[$type] = array();
        }
        $this->entries[$type][$slug] = $entry_obj;
    }

    private function get_status_of_type_array(){
        
        $array = array();

        $array_types = constants::get_entry_types();
        foreach ($array_types as $key => $type_slug){
            $status = $this->get_status_of_type($type_slug);
            $array[$type_slug] = $status;
        }

        $array_types_preview = constants::get_all_entry_types_soon();
        foreach ($array_types_preview as $key => $type_slug){
            // $status = $this->get_status_of_type($type_slug);
            $status = 2;
            $array[$type_slug] = $status;
        }

        return $array;

    }

    private function get_status_of_type($slug){
        return random_int(0, 1);
    }

    public function get_toggle_buttons(){
    ?>
        <div class="flex flex-col flex-wrap max-h-72 font-roboto text-2xl">
        <?php
            $array = $this->get_status_of_type_array();
            $this->make_toggle_buttons($array);
        ?>
        </div>
    <?php
    }

    private function make_toggle_buttons($array){
        foreach ($array as $slug => $status){
            $name = constants::get_entry_type_name_plural($slug);
            $type_count = $this->get_entry_count_by_type($slug);

            if ($type_count == 0){
                $type_count = "Coming Soon";
            }

            
            $button = new toggle_button($name, $slug);
            
            if ($status == 1) {
                $button->active();
            } else if ($status == 2) {
                $button->disabled();
            }
            
            ?>
            <div class="flex flex-row items-center text-roboto text-2xl my-2.5 flex-auto w-1/2 max-w-lg flex-grow-0">
                <div class="">
                    <?php $button->display(); ?>
                </div>
                <span><?php echo "$name"; ?>
                    <span class="text-hex-B9BDC4">(<?php echo $type_count;?>)</span>
                </span>
            </div>
            <?
        }
    }

    public function get_accordion_top_row_old(){
        $title = $this->get_name();
        ?>
        <div class="my-10">Manage <?php echo $title;?> Syndicated URLS <input></div>
        <?
    }

    
    public function paginate_as_accordion_rows(){

        ?>
        <div class="flex flex-col w-full">
        <?php
            $this->get_accordion_top_row();
            new accordion_brand_local($this, $this->entries[constants::get_entry_type_product()],  12);
            new accordion_brand_local($this, $this->entries[constants::get_entry_type_webpage()],  12);
            new accordion_brand_local($this, $this->entries[constants::get_entry_type_blog()],     12);
            new accordion_brand_local($this, $this->entries[constants::get_entry_type_kb()],       12);
            
            ?>
        </div>
            <?php
        }

    public function get_accordion_top_row(){
        ?>
            <div class="flex flex-row items-center">
                <div class="text-2xl w-4/6">Manage <?php echo $this->get_name();?> Syndicated URLs</div>
                <div class="w-2/6 flex flex-row justify-end items-end space-x-5">
                    <?php $this->get_input_field();?>
                </div>
            </div>
        <?php
    }
}