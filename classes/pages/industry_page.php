<?php
namespace waves;

class Industry_Page extends template_no_menu_item {
    protected $PAGE_TITLE                       = "Industry";
    protected $MENU_ENTRY_NAME                  = "Industry";
    protected $MENU_ENTRY_POS                   = 990;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "industry";
    protected $PAGE_TEMPLATE                    = "industry_group_template.php";
    
    public static function get_url_by_slug ($slug){
        return self::get_page_url() . constants::get_industry_page_parameter() . "=$slug";
    }
}