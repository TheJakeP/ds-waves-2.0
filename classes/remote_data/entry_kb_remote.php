<?php
namespace waves;

class entry_kb_remote extends entry_remote{
    protected function set_type(){
        $this->type = constants::get_entry_type_kb();
    }
}