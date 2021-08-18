<?php
namespace waves;

class entry_blog_remote extends entry_remote{

    protected function set_type(){
        $this->type = constants::get_entry_type_blog(); 
    }
    
}