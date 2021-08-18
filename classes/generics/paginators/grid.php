<?php
namespace waves;
/*

To-do: 



*/
class grid extends paginator {

    private $rows_per_page = 3;
    private $cols_per_page = 5;

    public function __construct($data_array){
        $show = $this->rows_per_page * $this->cols_per_page;
        parent::__construct($data_array, $show);
    }

    public function get_grid_thumbnails(){
        $this->get_top_row();
    ?>

    
        <div class="grid grid-cols-5 gap-x-4 gap-y-8 ">
        <?php
            $array = array_values($this->get_data_to_display());

            $start = $this->get_start_index();
            $stop = $this->get_stop_index();
            
            for ($i = $start; $i < $stop; $i++){
                $brand_obj = $array[$i];
                if ($brand_obj != null){
                    $brand_obj->get_grid_thumbnail();
                }
            }
        ?>
        </div>
    <?php
        $this->get_paginator_bottom_row();
    }

    public function get_top_row(){
        ?>
        <div class="flex flex-row  font-roboto text-waves-black align-baseline justify-between mt-6 mb-12">
            <div class="text-3xl align-baseline">Available Brands <span class="text-xl align-baseline">(<?php 
                echo $this->get_filtered_of_total();
            ?> Brands)</span></div>
            <div class="flex flex-row flex-nowrap space-x-4 ">
            <?php 
                $this->get_filter_field();                
                $this->get_sort_select_menu();
            ?>
            </div>
        </div>
        <?php
    }

    protected function get_paginator_bottom_row(){
        $nav_row_style = "text-xl my-16 color-waves-black";
        $nav_button_style = "w-10 h-10 px-2 txt";
        $button_style = "";
        $text_style = "font-arial text-lg ";
        parent::paginator_bottom_row($nav_row_style, $text_style, $button_style, $nav_button_style);
    }

    public function get_filtered_of_total(){
        $filtered_count = $this->get_filtered_count();
        $total_count = $this->get_total_count();

        return "$filtered_count of $total_count";

    }
    
}