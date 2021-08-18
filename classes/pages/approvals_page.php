<?php
namespace waves;

class Approvals_Page extends template_sub_menu_item {
    protected $PAGE_TITLE                       = "Approvals";
    protected $MENU_ENTRY_NAME                  = "Approvals";
    protected $MENU_ENTRY_POS                   = 200;
    protected $MENU_HIDE_IF_DEACTIVATED         = true;
    protected $PAGE_SLUG                        = "approvals";
    protected $PAGE_TEMPLATE                    = "approvals_template.php";
    // protected $PAGE_TEMPLATE_IF_NOT_ACTIVATED   = null;

}