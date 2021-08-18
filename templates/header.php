<?php
namespace waves;
?>

<div class="flex flex-row flex-nowrap font-montserrat justify-between">
    <div class="flex flex-row flex-nowrap">
        <div class="flex-initial">
            <div class="flex h-full justify-center content-center w-20 pr-6">
                <?php constants::get_waves_logo();?>
            </div>
        </div>
        <div class="flex-initial">
            <h1 class="text-5xl align-baseline">Waves by DesignStudio <span class="text-3xl align-baseline">(v 2.0)</span></h1>
        </div>
    </div>
    <div class="flex text-right justify-end font-arial text-xl">
        <div class="flex self-end">A content syndication plugin from<?php echo constants::get_design_studio_ahref();?></div>
    </div>
</div>


<div class="flex flex-row my-6">
    <?php menu_manager::get_nav_menu(); ?>
</div>