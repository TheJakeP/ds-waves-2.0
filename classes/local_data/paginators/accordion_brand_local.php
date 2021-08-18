<?php

namespace waves;

class accordion_brand_local extends accordion_brand {

    
    protected function get_first_row(){
        $title = $this->get_type_name();
    ?>
        <div class="flex flex-row font-roboto text-2xl text-waves-black py-5 align-center items-center border-b ">
            <div class="w-1/5">Auto-Update</div>
            <div class="w-4/5"><?php echo "$title Name";?></div>
        </div>
    <?php
    }

}