<?php
namespace waves;

class Main_Page extends template_main_menu_item {
    protected $PAGE_TITLE                       = "Menu";
    protected $MENU_ENTRY_NAME                  = "DS Waves";
    protected $MENU_SUB_ENTRY_NAME              = "Home";
    protected $MENU_POSITION                    = 100;
    protected $PAGE_SLUG                        = "page";
    protected $PAGE_TEMPLATE                    = "brand_activated_template.php";
    protected $PAGE_TEMPLATE_IF_NOT_ACTIVATED   = "brand_not_activated_template.php";

    protected $REMOVE_FROM_SUB_MENU             = true;

    public function __construct(){
        $this->IMG_ICON = constants::return_waves_icon_path();
        parent::__construct();
    }

}