<?php
namespace waves;

?>

<div class="flex flex-row justify-center content-center font-roboto space-x-16">
    <div class="flex flex-col w-7/12">
    Industry Page. To be designed.
    <?php 
        // echo db_local::get_industry_from_url_parameter();
        var_dump(db_local::get_industry_from_url_parameter());
        var_dump(db_remote::get_industry_from_url_parameter());
        echo db_remote::get_industry_from_url_parameter()
    ?>
    </div>
    <div class="flex flex-col w-5/12">
    </div>
</div>