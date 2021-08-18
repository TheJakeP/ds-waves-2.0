<?php
namespace waves;


// class brand implements pagination_as_grid, paginate_as_accordion, waves_objects{
class table extends paginator {

    public function __construct($array, $entries_per_page){
        $this->set_class_variables($array, $entries_per_page);
        $this->prepare_data();
    }

    /* Build these in child class */
    public function display() {}
    private function get_table() {}
    private function make_rows() {}
    private function row() {}


}