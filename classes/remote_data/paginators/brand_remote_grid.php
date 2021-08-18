<?php
namespace waves;
/*

To-do: 



*/
class brand_remote_grid extends grid {
    private $rows_per_page = 3;
    private $cols_per_page = 5;

    public function __construct(){


        $array_data = $this->sort_array();

        $entries_per_page = $this->rows_per_page * $this->cols_per_page;
        $this->set_class_variables($array_data, $entries_per_page);
        $this->get_grid_thumbnails();
    }

    protected function sort_array(){
       
        if ($this->is_sort_active()){

            $sort_by_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_by());
            $sort_order_param = utilities::get_and_clean_url_param_value(constants::_get_param_sort_order());
    
            switch ($sort_by_param) {
                case "industry":
                    $this->array_prepared = db_remote::get_brand_array_by_industry();
                    if (strcmp($sort_order_param, "dsc") == 0){
                        $this->array_prepared = array_reverse($this->array_prepared);
                    }

                    
                    // $this->ksort_string_array_prepared($sort_order_param);
                    break;

                case "name":
                    $this->array_prepared = db_remote::get_brand_array_by_name();
                    $this->ksort_string_array_prepared($sort_order_param);
                    break;
                    
                case "price":
                    $this->array_prepared = db_remote::get_brand_array_by_price();
                    $this->ksort_numeric_array_prepared($sort_order_param);
                    break;

            }
        } else {
            $this->array_prepared = db_remote::get_brand_array_by_name();
            $this->ksort_string_array_prepared("asc");
        }

        return $this->array_prepared;

    }

}