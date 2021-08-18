<?php
namespace waves;

class Request_Brands_Page extends template_sub_menu_item {
    protected $PAGE_TITLE                       = "Request Brands";
    protected $MENU_ENTRY_NAME                  = "Request Brands";
    protected $MENU_ENTRY_POS                   = 300;
    protected $MENU_HIDE_IF_DEACTIVATED         = false;
    protected $PAGE_SLUG                        = "requests";
    protected $PAGE_TEMPLATE                    = "brand_remote_template.php";

}