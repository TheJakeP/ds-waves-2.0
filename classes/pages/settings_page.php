<?php
namespace waves;

class Settings_Page extends template_sub_menu_item {
    protected $PAGE_TITLE                       = "Settings Page Title";
    protected $MENU_ENTRY_NAME                  = "Settings";
    protected $MENU_ENTRY_POS                   = 500;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "settings";
    protected $PAGE_TEMPLATE                    = "settings_template.php";
    // protected $PAGE_TEMPLATE_IF_NOT_ACTIVATED   = null;
} 