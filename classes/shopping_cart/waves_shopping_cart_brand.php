<?php
namespace waves;

class shopping_cart_brand {

    private $brand;
    private $brand_id;
    private $data = array();
    
    public function __construct($brand_id){
        $this->brand_id = $brand_id;
        $this->cleanup();
    }

    private function cleanup(){
        foreach ($this->data as $key => $arr){
            if (count ($arr) == 0){
                unset($this->data[$key]);
            }
        }
    }

    public function get_brand_name(){
        $brand_obj = $this->get_brand_obj();
        return $brand_obj->get_name();
    }

    public function get_total_price(){
        $entries = $this->get_entries();
        $count = 0;
        foreach ($entries as $type => $arr){
            $entry_array = $this->get_type_array($type);
            foreach ($entry_array as $index => $entry){
                $count += $entry->get_price();
            }
        }
        return $count;
    }

    public function add_single($entry_id){
        $type = utilities::decode_type_from_unique_id($entry_id);
        $id = utilities::decode_entry_id_from_unique_id($entry_id);

        $this->add_id_to_type($type, $id);
    }
    private function add_id_to_type($type, $id){
        $this->create_type_array_if_null($type);
        $brand = $this->get_brand_obj();
        $entry = $brand->get_entry_by_type_and_id($type, $id);
        $this->data[$type][$id] = $entry;
        ksort($this->data[$type]);
    }

    public function remove_single($entry_id){
        $type = utilities::decode_type_from_unique_id($entry_id);
        $id = utilities::decode_entry_id_from_unique_id($entry_id);

        $this->remove_id_from_type($type, $id);
    }
    private function remove_id_from_type($type, $id){
        $bool = $this->check_entry_exists($type, $id);
        if ($bool){
            unset($this->data[$type][$id]);
        }
    }

    public function add_all_of_brand(){
        $brand_obj = $this->get_brand_obj();
        $this->data = $brand_obj->get_all_entries();

    }

    public function add_all_of_type($type){
        
        $this->create_type_array_if_null($type);
        $brand_obj = $this->get_brand_obj();
        $all_type_entries = $brand_obj->get_entries_by_type($type);
        $this->data[$type] = $all_type_entries;
        ksort($this->data[$type]);
    }

    public function remove_all_of_type($type){
        $this->data[$type] = array();
    }


    private function check_entry_exists($type, $id){
        $entries_arr = $this->get_entries();
        $bool_1 = key_exists($type, $entries_arr);
        if ($bool_1){
            $type_arr = $this->get_type_array($type);
            $bool_2 = key_exists($id, $type_arr);
            if ($bool_2){
                return true;
            }
        }
        return false;
    }
    

    private function create_type_array_if_null($type){
        $entries_arr = $this->get_entries();
        if (!key_exists($type, $entries_arr)){
            $this->data[$type] = array();
        }
    }

    private function get_brand_obj_id(){
        return $this->brand_id;
    }

    public function get_brand_obj(){
        if (is_null($this->brand)){
            $db_remote = db_remote::get_db_object();
            $brand_id = $this->get_brand_obj_id();
            $brand = $db_remote->get_brand_obj_from_slug($brand_id);
            $this->brand = $brand;
        }
        return $this->brand;
    }



    public function is_empty(){
        return $this->get_total_entries() == 0;
    }

    public function get_type_count($type){
        $type_array = $this->get_type_array($type);
        if (is_null($type_array)){
            return 0;
        } else {
            return count($type_array);
        }
    }

    public function get_entries(){
        return $this->data;
        
    }
    public function get_entry_types(){
        
        $this->cleanup();
        $entries_arr = $this->get_entries();
        return array_keys($entries_arr);
    }
    public function get_type_array($type){
        $entries_arr = $this->get_entries();
        return $entries_arr[$type];
    }

    private function type_array_exists($type){
        
        $this->cleanup();
        $entries_arr = $this->get_entries();
        return array_key_exists($type, $entries_arr);
    }

    public function get_total_entries(){
        
        $count = 0;
        $entries_arr = $this->get_entries();
        foreach ($entries_arr as $type => $discard){
            $type_arr = $this->get_type_array($type);
            $count += count($type_arr);
        }
        return $count;
    }

    public function contains_entry($type, $id){
        if ($this->type_array_exists($type)){
            $type_arr = $this->get_type_array($type);
            return array_key_exists($id, $type_arr);
        } 
        return false;
    }

    public function contains_any_entries_of_type ($type){
        if ($this->type_array_exists($type)){
            $arr = $this->get_type_array($type);
            return (count($arr) > 0);
        } 
        return false;
    }

}