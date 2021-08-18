<?php
namespace waves;

class Select_New_Brand_Page extends template_no_menu_item {
    protected $PAGE_TITLE                       = "Select Brand";
    protected $MENU_ENTRY_NAME                  = "Select Brand";
    protected $MENU_ENTRY_POS                   = 999;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "select";
    protected $PAGE_TEMPLATE                    = "brand_add_new_template.php";
    
    public static function add_brand_page($slug){
        return self::get_page_url() . constants::get_brand_page_parameter() . "=$slug";
    }
}