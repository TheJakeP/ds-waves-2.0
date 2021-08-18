<?php
namespace waves;

use Exception;

/*

To-do: 



*/
class accordion extends paginator {
    protected $accordion_prefix;
    protected $accordion_type;
    protected $accordion_slug;
    protected $accordion_title;
    
    protected $rows_per_page = 12;
    protected $cols_per_page = 1;

    
    public function __construct($title, $data_array, $type){
        $this->accordion_type = $type;
        $this->accordion_title = $title;
        $show = $this->rows_per_page * $this->cols_per_page;

        parent::__construct($data_array, $show);
        $this->get_accordion();
    }

    /* Build these in the child class */
    public function get_accordion(){}
    protected function get_toggle_section_entries_in_cart(){}
    protected function get_accordion_title(){}
    protected function get_accordion_content(){}


    protected function build_slug($type){
        $this->accordion_slug = utilities::encode_accordion_slug($this->accordion_prefix, $type);

        $string_builder = "";
        $bool = !is_null($this->accordion_prefix);
        if ($bool){
            $string_builder = $this->accordion_prefix . constants::get_str_separator();
        } 
        $string_builder .= $type;

        $this->accordion_slug = $string_builder;
        return $string_builder;
    }

    protected function get_slug(){
        if (is_null($this->accordion_slug)){
            $this->accordion_slug = utilities::encode_accordion_slug($this->accordion_prefix, $this->accordion_type);
        }
        return $this->accordion_slug;
    }

    protected function echo_accordion_show_style(){
        $bool = self::is_accordion_open($this->accordion_slug);
        if ($bool){
            echo "show";
        } else{
            echo "hidden";
        }
    }


    public static function is_accordion_open($slug){
        $param = constants::_get_param_active_accordion();
        $active_accordion = utilities::url_decode_param($param);
        if (is_null($active_accordion)){
            return false;
        } else if (strcmp($active_accordion, $slug) == 0){
            return true;
        } else {
            return false;
        }
    }

    
    protected function dark_title($content, $classes, $slug){
        ?>
            <div 
                id="<?php echo urlencode($slug);?>"
                onclick="toggle_accordion(this);"
                class="<?php echo $classes;?>">
                <?php echo $content;?>
                <?php echo $this->get_white_caret();?>
            </div>
        <?
        }
    
        protected function light_title($content, $classes, $slug){
            ?>
            <div 
                id="<?php echo urlencode($slug);?>"
                onclick="toggle_accordion(this);"
                class="<?php echo $classes;?>">
                <?php echo $content;?>
                <?php echo $this->get_gray_caret();?>
            </div>
        <?
        }

    protected function get_white_caret(){
        $id = $this->get_slug();
        $open = self::is_accordion_open($id);
        if ($open){
            return constants::get_white_caret_up($id);
        } else {
            return constants::get_white_caret_down($id);
        }
    }

    protected function get_gray_caret(){
        $id = $this->get_slug();
        $open = self::is_accordion_open($id);
        if ($open){
            return constants::get_gray_caret_up($id);
        } else {
            return constants::get_gray_caret_down($id);
        }
    }

}