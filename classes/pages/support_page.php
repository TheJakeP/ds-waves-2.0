<?php
namespace waves;

class Support_Page extends template_sub_menu_item {
    protected $PAGE_TITLE                       = "Support";
    protected $MENU_ENTRY_NAME                  = "Support";
    protected $MENU_ENTRY_POS                   = 400;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "support";
    protected $PAGE_TEMPLATE                    = "support_template.php";
    // protected $PAGE_TEMPLATE_IF_NOT_ACTIVATED   = null;

}