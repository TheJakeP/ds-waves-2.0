<?php
namespace waves;

class template_no_menu_item extends template{
    protected $sub_menu = true;
    // protected $REMOVE_FROM_SUB_MENU = true;
    
    protected $PARENT_SLUG                      = "ds-waves-requests";

    public function build_page(){
        $this->add_action_waves_no_menu_entry();
    }

    public function show_menu_item(){
        return false;
    }
}