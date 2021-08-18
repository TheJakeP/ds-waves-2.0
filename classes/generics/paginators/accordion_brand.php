<?php
namespace waves;

/*

To-do: 



*/
class accordion_brand extends accordion {
    protected $array_data;
    
    protected $accordion_prefix = "main";
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


    public function __construct($brand_obj, $array_data){
        $this->brand_id     = $brand_obj->get_slug();
        $this->brand_obj    = $brand_obj;
        $this->array_data   = $array_data;

        $this->count        = count($array_data);

        $first_entry        = reset($array_data);
        $this->type_short   = $first_entry->get_slug();
        $this->type_single  = $first_entry->get_type_name();
        $this->type_plural  = $first_entry->get_type_name_plural();

        $title = $this->type_single;
        $slug = $this->type_short;
        
        parent::__construct($title, $array_data, $slug);
    }

    /* Implement this in child class */
    protected function get_first_row(){}

    protected function get_brand_id(){
        if (is_null($this->brand_id)){
            $brand_obj = $this->get_brand_obj();
            $brand_id = $brand_obj->get_slug();
            $this->brand_id = $brand_id;
        }
        return $this->brand_id;
    }

    protected function get_type_slug(){
        return $this->type_short;
    }
    
    protected function get_type_name(){
        return $this->type_single;
    }
    
    protected function get_type_name_plural(){
        return $this->type_plural;
    }

    public function get_accordion(){
    ?>
        <div class="flex flex-col my-5 bg-white">
    <?php
            $this->get_accordion_title();
            $this->get_accordion_content();
    ?>
        </div>
    <?php
    }

    protected function get_accordion_title(){
        $title      = $this->get_type_name_plural();
        $content    = "<span>$title</span>";
        $classes    = "flex flex-row " . constants::return_blue_header_style() . " justify-between ";
        $slug       = $this->accordion_slug;
        $this->dark_title($content, $classes, $slug);
    }
    


    protected function get_accordion_content(){
        $start = $this->get_start_index();
        $stop = $this->get_stop_index();

        $array_keys = array_keys($this->array_data);
        ?>
            <accordion class="mx-8 <?php $this->echo_accordion_show_style(); ?> <?php constants::get_transition_classes();?> " >
                <?php $this->get_first_row();?>
            <?php
            for ($i = $start; $i < $stop; $i++){
                $key        = $array_keys[$i];
                $entry_obj  = $this->array_data[$key];
                if (!is_null($entry_obj)){
                $entry_obj->get_accordion_row();
                }
            }
            ?>
            </accordion>
        <?php
    }

    protected function get_brand_obj(){
        return $this->brand_obj;
    }

    protected function is_there_a_brand_associated(){
        if ($this->brand_obj == null){
            $array = $this->array_data;
            if (is_array($array)){                
                $first_entry = reset($this->array_data);
                $parent_class = get_parent_class($first_entry);
                $match  = entry_generic::class;
                if (strcmp($parent_class, $match) == 0){
                    return true;
                }
            } 
            return false;
        } else {
            return true;
        }
    }

    
    protected function check_for_associated_brand_obj(){
        $bool = $this->is_there_a_brand_associated();

        if ($bool) {
            if ($this->brand_obj == null){
                $first_entry = reset($this->array_data);
                $this->brand_obj = $first_entry->get_obj();
            }
            return $this->brand_obj;
        }
        return null;
    }

    
    protected function get_toggle_section_entries_in_cart(){

        $accordion_slug = $this->get_slug();
        $brand_id = $this->get_brand_id();
        $type = $this->get_type_slug();
        
        
        $bool = $this->are_all_entries_in_cart();
        if ($bool){
            $link = shopping_cart::get_remove_brand_entry_type_to_cart_url_by_obj_sect_slug($accordion_slug, $brand_id, $type);
            $text = "Remove All";
        } else {
            $link = shopping_cart::get_add_brand_entry_type_to_cart_url_by_obj_sect_slug($accordion_slug, $brand_id, $type);
            $text = "Add All";
        }

        $id = "";
        $classes = constants::return_waves_button_style_blue_outline_classes() . " px-4 py-2 ";
        utilities::make_button($id, $classes, $link, $text); 
    }
    protected function are_all_entries_in_cart(){
        $brand_id = $this->get_brand_id();
        $cart = cache::get_shopping_cart();
        $type = $this->get_type_slug();
        return $cart->is_entry_type_in_cart($brand_id, $type);
    }

}