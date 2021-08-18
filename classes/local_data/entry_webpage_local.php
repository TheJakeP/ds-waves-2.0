<?php
namespace waves;

class entry_webpage_local extends entry_local{

    protected function set_type(){
        $this->type = constants::get_entry_type_webpage();
    }
}