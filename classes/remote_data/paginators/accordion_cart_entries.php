<?php
namespace waves;

class cart_entries_accordion extends accordion_brand_remote {
    protected $array_data;
    
    protected $accordion_prefix = "cart";
    protected $accordion_slug;
    protected $accordion_title;

    protected $brand_obj;
    protected $brand_id;

    protected $count;

    protected $entry_type;

    protected $type_short;
    protected $type_single;
    protected $type_plural;


    protected $rows_per_page = 12;
    protected $cols_per_page = 1;
    

    public function __construct($cart_brand, $type) {

        $array_data = $cart_brand->get_type_array($type);
        $brand_obj = $cart_brand->get_brand_obj();
        parent::__construct($brand_obj, $array_data);
    }

    public function get_accordion(){
        ?>
        <div class="flex flex-col">
        <?php
            $this->get_accordion_title();
            $this->get_accordion_content();
        ?>
            </div>
        <?php
    }

        
    public function get_accordion_title(){

        $brand_id = $this->brand_obj->get_slug();
        $count = $this->count;
        $slug = $this->get_type_slug();
        $title = $this->get_type_name_plural();
        
        $outline_classes = constants::get_gray_outline_classes();

        $content = "<div><b>$title</b> ($count URLs)</div>";
        $classes = "flex flex-row flex-nowrap justify-between p-6 " . $outline_classes;
        
        $slug = utilities::encode_accordion_slug($brand_id, $slug);
        
        $this->light_title($content, $classes, $slug);
    }

    protected function get_accordion_content(){
        ?>
        <accordion class="space-y-3 px-6 pt-3 max-h-80 overflow-y-auto <?php $this->echo_accordion_show_style(); ?> <?php constants::get_transition_classes();?> " >
        <?php
        foreach ($this->array_data as $key => $brand_entry_obj){
            $brand_entry_obj->get_shopping_cart_row($brand_entry_obj);
        }
        ?>
        </accordion>
        <?php
    }

}