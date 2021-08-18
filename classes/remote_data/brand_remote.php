<?php
namespace waves;


class brand_remote extends brand{
    protected $entry_price;
    protected $total_price;
    

    public function __construct($brand_name, $description, $logo_url, $parent_co, &$industry_ref, $price){
        $this->child_obj_setup  = false;
        parent::__construct($brand_name, $description, $logo_url, $parent_co, $industry_ref);
        $this->price = $price;
    }

    public function filter_entries_by_keyword($array){
        //Do Array Filter Stuff here
        return $array;
    }

    public function setup(){
        if (!$this->child_obj_setup){
            $db_remote = db_remote::get_db_object();
            $db_remote->populate_brand_data($this);
            $this->child_obj_setup = true;
        }
    }

    public function get_entries_by_type($type){
        $type_exists = array_key_exists($type, $this->entries);
        if ($type_exists){
            return $this->entries[$type];
        }
    }

    public function &get_obj(){
        return $this;
    }

    public function get_all_entries(){
        return $this->entries;
    }

    public function get_total_price_str(){
        $price = $this->get_total_price_int();
        return utilities::format_usd($price);
    }

    public function get_total_price_int() {
        return $this->get_total_entries() * $this->entry_price;
    }

    public function get_entry_price(){
        return $this->entry_price;
    }

    public function get_accordion_top_row(){
        ?>
            <div class="flex flex-row">
                <div class="text-2xl w-1/2"><?php echo $this->get_name();?>
                    <span 
                        class="text-lg">(<?php echo $this->get_total_entries();?> URLs)
                    </span>
                </div>
                <div class="w-1/2 flex flex-row justify-end items-end space-x-5">
                    <?php $this->get_input_field();?>
                    <?php $this->get_add_remove_all_button();?>
                </div>
            </div>
        <?php
    }

    public function get_grid_thumbnail(){
        $text_industry  = $this->get_industry_name();
        $url_logo       = $this->get_logo_url();

        $slug           = $this->get_slug();
        $url_page       = Select_New_Brand_Page::add_brand_page($slug);

        $url_industry   = $this->get_industry_page_url();
        ?>
            <div class="flex flex-col align-middle justify-start font-roboto overflow-hidden">
                <a 
                    class="flex flex-col bg-white content-center items-center justify-center grid-square mb-5" 
                    href="<?php echo $url_page; ?>"
                >
                    <div 
                        class="flex content-center items-center justify-center w-3/5 h-3/5 bg-contain bg-no-repeat bg-center" 
                        style="background-image: url('<?php echo $url_logo; ?>');">
                    </div>
                </a>
                <div class="text-xl">
                    <?php echo $this->get_name(); ?>
                </div>
                <div class="text-base text-hex-646B6F">
                    <?php echo $this->get_parent_company(); ?>
                </div> 
                <div class="text-base">
                    <a 
                    class="<?php constants::get_ahref_classes();?> text-base" 
                    href="<?php echo $url_industry; ?>"><?php echo $text_industry;?></a>
                </div> 
            </div>
        <?php
    }
    
    public function get_add_remove_all_button(){
        $cart = cache::get_shopping_cart();
        $brand_id = $this->get_slug();
        $bool = $cart->is_brand_in_cart($brand_id);
        if ($bool){
            $url = shopping_cart::get_remove_all_brand_from_cart_url($brand_id);
            $text = "Remove All";
        } else {
            $url = shopping_cart::get_add_all_brand_to_cart_url($brand_id);
            $text = "Request All";    
        }
        $id = $brand_id;
        $classes = constants::return_waves_button_style_blue_classes() . " px-4 py-2 w-4/12 h-full ";
        utilities::make_button($id, $classes, $url, $text); 
    }

    
    public function add_blog($name, $url, $content, $price){
        $class_name = entry_blog_remote::class;
        $entry_type = constants::get_entry_type_blog();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content, $price);
    }

    public function add_kb($name, $url, $content, $price){
        $class_name = entry_kb_remote::class;
        $entry_type = constants::get_entry_type_kb();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content, $price);
    }

    public function add_product($name, $url, $content, $price){
        $class_name = entry_product_remote::class;
        $entry_type = constants::get_entry_type_product();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content, $price);
    }

    public function add_webpage($name, $url, $content, $price){
        $class_name = entry_webpage_remote::class;
        $entry_type = constants::get_entry_type_webpage();
        $this->add_new_entry($class_name, $entry_type, $name, $url, $content, $price);
    }

    protected function add_new_entry ($class_name, $type, $name, $url, $content, $price){
        $brand_id_str = $this->get_slug();
        $entry_id = count($this->entries[$type]);
        $entry_obj = new $class_name ($brand_id_str, $entry_id, $name, $url, $content, $price);
        
        $id = $entry_obj->get_id_string();
        if (!array_key_exists($type, $this->entries)){
            $this->entries[$type] = array();
        }
        $this->entries[$type][$id] = $entry_obj;
    }
}

