<?php
namespace waves;

$brand = db_local::get_brand_by_brand_page_param();

$logo_img = $brand->return_logo_img("", 200, "");

?>

<div class="flex flex-row justify-start font-roboto text-2xl space-x-16 ">
    <div class="flex flex-col w-7/12">
        <div class="mt-6"><?php echo $logo_img;?></div>
        <div class="mt-9 mb-16"><b><?php echo $brand->get_total_entries();?></b> URLs in Active syndication</div>
        <div class="mb-7"><?php $brand->get_toggle_buttons(); ?></div>
        
        <?php $brand->paginate_as_accordion_rows();?>
        
        
    </div>
</div>