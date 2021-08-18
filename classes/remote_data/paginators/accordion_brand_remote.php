<?php
namespace waves;

/*

To-do: 



*/
class accordion_brand_remote extends accordion_brand {

    protected function get_first_row(){
        $title = $this->get_type_name();
    ?>
        <div class="flex flex-row font-roboto text-2xl text-waves-black py-5 align-center justify-between items-center border-b ">
            <div class=""><?php echo $title;?> URLs</div>
            <div class="w-40 align-center justify-center items-center text-center">
                <?php $this->get_toggle_section_entries_in_cart();?>
            </div>
        </div>
    <?php
    }

}