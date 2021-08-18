<?php
namespace waves;

class Entry_Settings_Page extends template_no_menu_item {
    protected $PAGE_TITLE                       = "Entry Settings";
    protected $MENU_ENTRY_NAME                  = "Entry Settings";
    protected $MENU_ENTRY_POS                   = 970;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "entry";
    protected $PAGE_TEMPLATE                    = "entry_settings_template.php";
    
    public static function get_url_by_slug ($brand_slug, $entry_slug){
        return self::get_page_url() . constants::get_brand_page_parameter() . "=$brand_slug" . "&" . constants::get_entry_page_parameter() . "=$entry_slug";
    }
}