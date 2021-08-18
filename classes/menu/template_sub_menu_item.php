<?php
namespace waves;

class template_sub_menu_item extends template{
    protected $sub_menu = true;

    public function build_page(){
        $this->add_action_waves_admin_menu_sub();
    }
}