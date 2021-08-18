<?php
namespace waves;

class template_main_menu_item extends template {
    public function build_page(){
        $this->add_action_waves_admin_menu();
        $this->add_action_waves_admin_menu_sub();
    }
}