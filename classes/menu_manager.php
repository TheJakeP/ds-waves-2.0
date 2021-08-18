<?php
namespace waves;

class menu_manager {

    private static $cache_id = "menu_manager";
    
    private $menu_main_page_list = array();
    private $menu_sub_page_list = array();
    private $menu_sub_page_list_ordered = array();

    public $timestamp;

    public function __construct(){
        $this->timestamp = time();
        $this->call_page_classes();

        $this->set_sub_menu_order();

        $this->populate_menu_pages(); 
        $this->populate_menu_sub_pages();

        $this->save_menu_to_cache();
    }

    private function save_menu_to_cache(){
        $key = self::cache_id();

        if (empty($GLOBALS[$key])){
            $GLOBALS[$key] = $this;
        }

        if (empty($_SESSION[$key])){
            $_SESSION[$key] = $this;
        }
    }

    public static function cache_id() {
        return self::$cache_id;
     }


    private function call_page_classes(){
        $class_list = utilities::get_classes_in_this_namespace();
        
        foreach ($class_list as $class_name){
            $class_name_as_arr = explode("_", $class_name);
            $class_type = strtolower(end($class_name_as_arr));
            if (strcmp($class_type, "page") == 0){
                $menu_entry_obj = new $class_name;
                $menu_entry_title = $menu_entry_obj->get_menu_entry_title();
                if ($menu_entry_obj->is_sub_menu()){
                    $this->menu_sub_page_list[$menu_entry_title] = $menu_entry_obj;
                } else {
                    $this->menu_main_page_list[$menu_entry_title] = $menu_entry_obj;
                }

            } 
        }
        ksort($this->menu_main_page_list);
        ksort($this->menu_sub_page_list);
    }


    private function set_sub_menu_order(){
        $i = 100;
        foreach ($this->menu_sub_page_list as $page){
                $page->set_menu_position($i);
                $pos = $page->get_menu_main_sub_pos();
                $this->menu_sub_page_list_ordered[$pos] = $page;
                $i += 100;
        }
        ksort($this->menu_sub_page_list_ordered);
    }

    private function populate_menu_sub_pages(){
        foreach ($this->menu_sub_page_list_ordered as $page){
            $page->build_page();
        }
    }

    private function populate_menu_pages(){
        foreach ($this->menu_main_page_list as $page){
            $page->build_page();
        }
    }

    public static function get(){
        $obj = cache::get_variable_by_key_and_class(self::class);
        return $obj;
    }

    public static function get_nav_menu(){
        $manager = menu_manager::get();
        $manager->output_menu();
    }

    public function output_menu(){
        $height         = "h-22";
        $color_active   = "waves-blue";
        $color_inactive = "menu-inactive";
        $color_hover    = "waves-blue-hover";
        ?>
        <div class="flex w-full justify-items-end font-roboto text-2xl <?php echo $height;?> space-x-12 border-b-4 border-accent-gray text-<?php echo $color_inactive;?>">
        <?php
        foreach ($this->menu_sub_page_list_ordered as $item){
            $link_text  = $item->get_menu_sub_entry_title();
            $link_dest  = $item->get_page_url();
            if ($item->show_menu_item() == false){
                continue;
            }
            else if ($item->is_this_the_active_page()){
                $menu_style = "text-$color_active border-$color_active";
            } else {
                $menu_style = "text-$color_inactive border-accent-gray hover:border-$color_hover";
            }
            ?>
            <div class="border-b-4 flex py-5 justify-center <?php echo "$height $menu_style";?> -my-1 group focus:shadow-none">
                <a class="flex font-bold justify-center items-center group-hover:text-<?php echo $color_hover;?> focus:shadow-none"  href="<?php echo $link_dest;?>" alt="<?php echo $link_text;?>"><?php echo $link_text;?></a>
            </div>
            <?php
        }
        ?>
        </div>
        <?php
    } 

}