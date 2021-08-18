<?php
namespace waves;

class entry_generic implements paginate_as_accordion_row {

    protected $brand_obj;
    protected $content;
    protected $id;
    protected $name;
    protected $unique_id;
    protected $url;

    protected $type;
    protected $type_name;
    protected $type_name_plural;
    protected $type_name_short;

    public function __construct(){
        $this->set_unique_id();
    }


    protected function set_type_names(){
        if (is_null($this->type)){
            $this->set_type();
            $type = $this->type;
            $this->type_name        = constants::get_entry_type_name($type);
            $this->type_name_short  = $type;
            $this->type_name_plural = constants::get_entry_type_name_plural($type);
        }
    }

    protected function set_type(){
        /*
        This should be done in the extended class
        */
        return false;
    }

    public function get_brand(){
        return strval($this->brand);
    }

    public function &get_brand_obj(){
        if (is_null($this->brand_obj)){
            $slug = $this->get_brand_id();
            $this->brand_obj = db_remote::get_brand_from_slug($slug);
        }
        return $this->brand_obj;
    }

    public function get_name(){
        return $this->name;
    }

    public function get_url(){
        return $this->url;
    }

    public function get_type(){
        return $this->type;
    }

    public function get_type_name(){
        return $this->type_name;
    }

    public function get_type_name_plural(){
        if (is_null($this->type_name_plural)){
            $this->set_type_names();
        }
        return $this->type_name_plural;
    }

    public function get_slug(){
        if (is_null($this->type_name_short)){
            $this->set_type_names();
        }
        return $this->type_name_short;
    }

    public function get_content(){
        return $this->content;
    }

    public function get_id(){
        return $this->id; 
    }

    public function get_id_string(){
        return str_pad($this->id, constants::get_id_length(), constants::get_id_pad_char(), STR_PAD_LEFT);
    }

    public function get_unique_id(){
        if (is_null($this->unique_id)){
            $this->set_unique_id();
        }
        return $this->unique_id; 
    }

    protected function set_unique_id(){
        $type   = $this->get_type();
        $id     = $this->id;
        $this->unique_id    = utilities::encode_unique_id_with_type_and_id($type, $id);
    }

    public function get_brand_id(){
        return $this->brand_id; 
    }

    public function get_price(){
        return $this->price; 
    }

    public function __toString() {
        $id = $this->get_id();
        $name = $this->name;
        $type = $this->get_type();
        return "->Type: $type, Name: $name, ID: $id<br>";
    }

    public function is_this_in_the_cart(){
        $shopping_cart = cache::get_shopping_cart();
        $brand_id = $this->get_brand_id();
        $type = $this->get_type();
        $id = $this->get_id_string();
        // $var = $shopping_cart->is_this_brand_item_in_cart($brand_id, $type, $id);
        $var = $shopping_cart->is_entry_in_cart($brand_id, $type, $id);
        return $var;
    }

    public function get_accordion_row(){
        $title = $this->name;
        $open_accordion = explode("\\", get_class($this))[1];
        $open_accordion = end(explode("_", $open_accordion));
    ?>
        <div class="flex flex-row font-roboto text-2xl py-5 align-center justify-between items-center border-b text-hex-1473E2">
            <div class="align-center items-center text-xl font-bold">
                <a class="text-xl " href="#">
                    <?php echo $title;?>
                    <?php echo $open_accordion;?>
                </a>
            </div>
            <?php 
                $bool = $this->is_this_in_the_cart();
                if ($bool){
                    $class  = "minus_circle";
                    $text   = "Remove URL";
                    $url    = shopping_cart::get_remove_single_brand_entry_from_cart_url($this);
                } else {
                    $class  = "plus_circle";
                    $text   = "Add URL";
                    $url    = shopping_cart::get_add_single_brand_entry_to_cart($this);
                }
                ?>
                    <a
                        
                        href="<?php echo $url?>"
                        class="w-40 align-center justify-center items-center text-center flex flex-nowrap space-x-5 text-lg"
                    >
                        <span
                            class="<?php echo $class?>"
                        ></span>
                        <span><?php echo $text;?></span>
                    </a>

        </div>
    <?php
    }

    public function get_shopping_cart_row(){
        $title = $this->get_name();
        if ($this->is_this_in_the_cart()){
            $symbol  = "minus_circle";
            $url    = shopping_cart::get_remove_single_brand_entry_from_cart_url($this);
        } else {
            $symbol  = "plus_circle";
            $url    = shopping_cart::get_add_single_brand_entry_to_cart($this);
        }

    ?>
        <div class="flex flex-row flex-nowrap justify-between font-roboto text-xl font-bold <?php constants::get_ahref_classes();?>">
            <div><?php echo $title;?></div>
            <a
                href="<?php echo $url;?>"
                class="<?php echo $symbol;?>"></a>
        </div>    
    <?php
    }
}
