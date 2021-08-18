<?php
namespace waves;

/*

To-do: 



*/
class cart_accordion_brand_remote extends accordion_brand_remote {
    protected $array_data;
    
    protected $accordion_prefix = "cart";
    protected $accordion_slug;
    protected $accordion_title;

    protected $brand_obj;
    protected $brand_id;
    protected $entry_type;

    protected $type_short;
    protected $type_single;
    protected $type_plural;


    protected $rows_per_page = 12;
    protected $cols_per_page = 1;

    public function __construct($cart_brand){
        $this->cart_brand_obj = $cart_brand;
        $brand_obj = $cart_brand->get_brand_obj();
        $this->brand_obj = $brand_obj;


        $this->get_accordion();
    }

    
    public function get_accordion(){
        $outline_classes = constants::get_gray_outline_classes();
    ?>
        <div class="<?php echo $outline_classes;?> space-y-5 p-8 ">
        <?php
            $this->get_accordion_title();
            $this->get_accordion_content();
        ?>
        </div>
    <?php
    }
    
    public function get_accordion_title(){
        $content  = '<div class="flex flex-col justify-center">';
        $content .=     '<div class="flex flex-row justify-start">';
        $content .=         $this->get_brand_logo();
        $content .=     '</div>';
        $content .=     '<div class="flex flex-row justify-start text-waves-black text-xl mt-5">';
        $content .=         $this->get_x_of_y_entries_in_cart();
        $content .=     '</div>';
        $content .= '</div>';
        
        $classes    = "flex flex-row flex-nowrap justify-between";
        $slug       = $this->accordion_slug;

        $this->light_title($content, $classes, $slug);
    }

    protected function get_accordion_content(){
        $cart_brand_obj = $this->cart_brand_obj;
        $cart_entry_types = $cart_brand_obj->get_entry_types();
        ?>
        <accordion class="space-y-3 px-6 pt-3 max-h-80 overflow-y-auto <?php $this->echo_accordion_show_style(); ?> <?php constants::get_transition_classes();?> " >
        <?php
        foreach ($cart_entry_types as $type ){
            new cart_entries_accordion($cart_brand_obj, $type);
        }
        ?>
        </accordion>
        <div class="flex flex-row flex-nowrap justify-between pt-9">
            <div><?php $this->add_more_button();?></div>
            <div><?php $this->remove_all_button();?></div>
        </div>
        <?php
    }

    protected function add_more_button(){
        $brand_slug = $this->get_slug();
        $id = "";
        $classes = constants::return_waves_button_style_gray_outline_classes() . " px-4 py-2";
        $url = Select_New_Brand_Page::add_brand_page($brand_slug);
        $text = "Add More";
        utilities::make_button($id, $classes, $url, $text);
    }

    protected function remove_all_button(){
        $brand_obj = $this->get_brand_obj();
        $brand_obj->get_add_remove_all_button();
    }

    protected function get_x_of_y_entries_in_cart(){
        $in_cart = $this->cart_brand_obj->get_total_entries();
        $brand_obj = $this->get_brand_obj();
        $total = $brand_obj->get_total_entries();
        return "<b>$in_cart URLs Active</b>&nbspout of $total";
    }

    protected function get_brand_logo(){
        $brand_obj = $this->get_brand_obj();
        $logo_url = $brand_obj->get_logo_url();
        $alt_text = $brand_obj->get_name() . " logo";
        $style = "h-12";
        return utilities::return_img_tag( "", $logo_url, $style, $alt_text, null, null, "");
    }

    protected function get_brand_obj(){
        return $this->brand_obj;
    }
        

}