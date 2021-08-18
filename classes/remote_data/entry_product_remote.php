<?php
namespace waves;

class entry_product_remote extends entry_remote{
    
    protected function set_type(){
        $this->type = constants::get_entry_type_product();
    }
}