<?php
namespace waves;
?>
<div class="flex flex-row justify-start font-roboto text-2xl space-x-16 ">
    <div class="flex flex-col w-7/12">
    <?php
        $entry = db_local::get_entry_by_brand_and_entry_page_param();
        $entry->get_settings_page();
    ?>
    </div>
</div> 