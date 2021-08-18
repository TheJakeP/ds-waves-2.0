<?php
namespace waves;

class Brand_Settings_Page extends template_no_menu_item {
    protected $PAGE_TITLE                       = "Configure Brand";
    protected $MENU_ENTRY_NAME                  = "Configure Brand";
    protected $MENU_ENTRY_POS                   = 980;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "brand-settings";
    protected $PAGE_TEMPLATE                    = "brand_settings.php";
    
    public static function get_url_by_slug ($slug){
        return self::get_page_url() . constants::get_brand_page_parameter() . "=$slug";
    }
}