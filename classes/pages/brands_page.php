<?php
namespace waves;

class Brands_Page extends template_sub_menu_item {
    protected $PAGE_TITLE                       = "Brands";
    protected $MENU_ENTRY_NAME                  = "Brands";
    protected $MENU_ENTRY_POS                   = 80;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "brands";
    protected $PAGE_TEMPLATE                    = "brand_activated_template.php";
    protected $PAGE_TEMPLATE_IF_NOT_ACTIVATED   = "brand_not_activated_template.php";

    // public static function add_brand_page($slug){
        // return self::get_page_url() . constants::_get_param_brand_page() . "= $slug";
    // }
}