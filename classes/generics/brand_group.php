<?php
namespace waves;


class brand_group {

    private $array_brand_slugs = array();
    private $array_brand_names = array();
    private $name;
    private $slug;

    public function __construct($name) {
        $this->name = $name;
        $this->get_slug();
    }

    public function get_name(){
        return strval($this->name);
    }

    public function get_slug(){
        
        if (is_null($this->slug)){
            $name = $this->get_name();
            $slug = utilities::make_slug_from_string($name);
            $this->slug = $slug;
        }
        return $this->slug;
    }

    public function get_array_by_name(){
        ksort($this->array_brand_names);
        return $this->array_brand_names;
    }


    public function get_array_by_slug() {
        ksort($this->array_brand_slugs);
        return $this->array_brand_slugs;
    }

    public function add_brand(&$brand_ref){
        $name = $brand_ref->get_name();
        $slug = $brand_ref->get_slug();
        
        array_push($this->array_brand_slugs, $slug) ;
        
        $this->array_brand_names[$name] = $slug ;

        ksort($this->array_brand_names);
        ksort($this->array_brand_slugs);
    }

    public function __toString(){
        return $this->get_name();
    }
}