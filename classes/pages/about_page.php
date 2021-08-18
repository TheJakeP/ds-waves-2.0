<?php
namespace waves;

class About_Page extends template_sub_menu_item {
    protected $PAGE_TITLE                       = "About";
    protected $MENU_ENTRY_NAME                  = "About";
    protected $MENU_ENTRY_POS                   = 600;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "about";
    protected $PAGE_TEMPLATE                    = "about_template.php";
    // protected $PAGE_TEMPLATE_IF_NOT_ACTIVATED   = "about_template.php";
}