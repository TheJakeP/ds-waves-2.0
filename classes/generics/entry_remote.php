<?php
namespace waves;

class entry_remote extends entry_generic{

    public function __construct($brand_id, $id, $name, $url, $content, $price){
        $this->set_variables_remote($brand_id, $id, $name, $url, $content, $price);
        parent::__construct();
    }

    protected function set_variables_remote($brand_id, $id, $name, $url, $content, $price){
        $this->brand_id     = $brand_id;
        $this->content      = $content;
        $this->name         = $name;
        $this->id           = $id;
        $this->url          = $url;
        $this->price        = $price;
        $this->set_type_names();
    }
}