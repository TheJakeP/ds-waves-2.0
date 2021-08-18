<?php
namespace waves;

use Exception;

class template{

    /* These are the same across all settings pages */
    protected const DOMAIN              = "waves";
    protected const CAPABILITY          = "manage_options";
    protected const PAGE_SLUG_BASE      = "ds-waves-";
    protected const PAGE_TITLE_BASE     = "DS Waves - ";
    protected const DEFAULT_PARENT_SLUG = self::PAGE_SLUG_BASE . "page";
    protected $sub_menu = false;



    /*  These variables vary by settings page */
    
    // protected $IMG_ICON                         = WAVES_PLUGIN_IMG . "icon.png"; // Select Image in waves/images
    protected $IMG_ICON                         = "dashicons-tagcloud"; // https://developer.wordpress.org/resource/dashicons/
    protected $PAGE_TITLE                       = "Default";
    
    protected $MENU_ENTRY_NAME                  = null;
    protected $MENU_SUB_ENTRY_NAME              = null;
    protected $MENU_ENTRY_POS                   = null;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;


    protected $PAGE_SLUG                        = null;

    protected $PAGE_TEMPLATE                    = null;
    protected $PAGE_TEMPLATE_IF_NOT_ACTIVATED   = null;
    protected $PARENT_SLUG                      = null;

    protected $REMOVE_FROM_SUB_MENU             = null;



    // These variables are used within the class
    protected $activation_status;
    protected $SHOW_MENU_ITEM;


    public function __construct(){
        $this->activation_status    = utilities::is_plugin_activated();
        $this->SHOW_MENU_ITEM       = $this->show_menu_item();
        if ($this->PAGE_TEMPLATE_IF_NOT_ACTIVATED == null){
            $this->PAGE_TEMPLATE_IF_NOT_ACTIVATED = $this->PAGE_TEMPLATE;
        }
    }

    public function add_action_waves_admin_menu(){
            add_action( 'admin_menu', array( &$this, 'add_waves_admin_menu' ) );
            $this->remove_from_admin_submenu();
    }

    public function remove_from_admin_submenu(){
        if ($this->REMOVE_FROM_SUB_MENU){
            add_action( 'admin_menu', array( &$this, 'remove_admin_menus' ), 999 );
        }
    }

    public function remove_admin_menus() {
        $menu_slug      = $this->get_parent_slug();
        $submenu_slug   = esc_html__($this->get_page_slug(), self::DOMAIN);
        remove_submenu_page( $menu_slug, $submenu_slug );
    }


    public function add_action_waves_admin_menu_sub(){
        if ($this->get_page_slug() == "ds-waves-page") {
            return;
        } else if ($this->SHOW_MENU_ITEM){
            add_action( 'admin_menu', array( &$this, 'add_waves_sub_menu' ) );
        }
    }

    public function add_action_waves_no_menu_entry(){
        if ($this->get_page_slug() == "ds-waves-page") {
            return;
        } else {
            add_action( 'admin_menu', array( &$this, 'add_waves_sub_menu' ) );
        }
    }

    protected function encode_icon(){
        $file_parts = pathinfo($this->IMG_ICON);

        switch($file_parts['extension']){
            case "svg":
                return 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($this->IMG_ICON));
            break;
        }

        return $this->IMG_ICON;

    }
    
    public function add_waves_admin_menu() {
        $capability     = self::CAPABILITY;
        $icon_url       = $this->encode_icon();
        $function       = array( &$this, 'render_settings_options' );
        $menu_slug      = esc_html__($this->get_page_slug(), self::DOMAIN);
        $menu_title     = esc_html__($this->get_menu_entry_title(), self::DOMAIN);
        $page_title     = esc_html__($this->get_page_title(), self::DOMAIN);
        $position       = $this->get_menu_main_entry_pos();
        
        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
    }

    public function add_waves_sub_menu(){
        $capability     = self::CAPABILITY;
        $function       = array( &$this, 'render_settings_options' );
        $menu_slug      = esc_html__($this->get_page_slug(), self::DOMAIN);
        $menu_title     = esc_html__($this->get_menu_sub_entry_title(), self::DOMAIN);
        $page_title     = esc_html__($this->get_page_title(), self::DOMAIN);
        $parent_slug    = $this->get_parent_slug();
        $position       = $this->get_menu_main_sub_pos();

        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
    }

    public function render_settings_options(){
        if ($this->PAGE_TEMPLATE == null) {
            echo "<b>ERROR</b><br>";
            echo "Page Template: " . $this->PAGE_TEMPLATE . "<br>";
            echo "Class: " . get_class() . "<br>";
            trigger_error("Default PAGE_TEMPLATE value in use. Change this value to get rid of this error.", E_USER_ERROR);
        } else {
            echo '<div class="ds-waves flex-col">';
            require(WAVES_PLUGIN_TEMPLATES . "header.php");

            if ($this->activation_status){
                require(WAVES_PLUGIN_TEMPLATES . $this->PAGE_TEMPLATE);
            } else {
                require(WAVES_PLUGIN_TEMPLATES . $this->PAGE_TEMPLATE_IF_NOT_ACTIVATED);
            }
            echo '</div>';
        }
        constants::after_page_load_actions();
    }

    public function show_menu_item(){
        $show_if_activated = utilities::is_plugin_activated();
        $show_if_deactivated = $this->MENU_HIDE_IF_DEACTIVATED == false;
        return ($show_if_activated) || ($show_if_deactivated);
    }

    public function get_menu_entry_title(){
        if ($this->MENU_ENTRY_NAME == null) {
            // trigger_error("Default MENU_ENTRY_NAME value in use. Change this value to get rid of this error.", E_USER_ERROR);
            $description = "Default MENU_ENTRY_NAME value in use. Change this value to get rid of this error.";
            throw new Exception($description);
            exit;
        } else {
            return $this->MENU_ENTRY_NAME;
        }
    }


    public function get_menu_sub_entry_title(){
        if ($this->MENU_SUB_ENTRY_NAME == null){
            return $this->get_menu_entry_title();
        } else {
            return $this->MENU_SUB_ENTRY_NAME;
        }
    }
    

    public function get_menu_main_entry_pos() {
        if ($this->MENU_ENTRY_POS == null){
            return 100;
        } else {
            return (int) $this->MENU_ENTRY_POS ;
        }
    }

    public function get_menu_main_sub_pos() {
        if ($this->MENU_ENTRY_POS == null){
            return 0;
        } else {
            return (int) $this->MENU_ENTRY_POS ;
        }
    }

    public function get_page_title(){
        return self::PAGE_TITLE_BASE . $this->PAGE_TITLE;
    }

    public function get_page_slug(){
        if ($this->PAGE_SLUG == null){
            return self::DEFAULT_PARENT_SLUG;
        } else {
            return self::PAGE_SLUG_BASE . $this->PAGE_SLUG;
        }
    }

    public function get_parent_slug(){
        if ($this->PARENT_SLUG != null) {
            return $this->PARENT_SLUG;
        } else {
            return self::DEFAULT_PARENT_SLUG;
        }
    }

    public function set_menu_position($i){
        if ($this->MENU_ENTRY_POS == null){
            $this->MENU_ENTRY_POS = $i;
        }
    }
    public function is_sub_menu(){
        return $this->sub_menu;
    }


    public function __toString(){
        return $this->get_menu_sub_entry_title();
    }

    public function is_this_the_active_page(){
        $active_page = get_current_screen()->base;
        $active_page = end(explode("_page_", $active_page));
        $this_page = self::PAGE_SLUG_BASE . $this->PAGE_SLUG;
        return strcmp($active_page, $this_page) == 0;
    }

    protected static function get_obj(){
        $this_class = get_called_class();
        return new $this_class;
    }

    protected static function get_slug(){
        $obj = self::get_obj();
        return $obj->get_page_slug();
    } 

    public function get_page_link(){
        $slug = $this->get_page_slug();
        return "/wp-admin/admin.php?page=$slug";
    }
    
    public static function get_page_url(){
        $slug = self::get_slug();
        return "/wp-admin/admin.php?page=$slug&";
    }
}