<?php
namespace waves;
$settings_page_url  = Settings_Page::get_page_url();
$request_brands_url = Brands_Page::get_page_url();

$classes = "my-10 text-center "  . constants::return_waves_button_style_blue_classes() . " self-center px-8 py-5";
$button = utilities::return_button("request", $classes, $request_brands_url, "Request Brands");
?>
<div class="flex flex-row justify-center content-center">
    <div class="flex flex-col font-roboto w-5/12 max-w-3xl text-2xl justify-center content-center bg-white shadow p-16">
        <div class="my-8 text-center font-montserrat text-4xl">Welcome to Waves<br>by DesignStudio
            <span class="font-roboto text-2xl">(v2.0)</span>
        </div>
        <div class="my-8 text-center">
            <span>Unlock you plugin by activating your <a class="<?php constants::get_ahref_classes();?>" href="<?php echo $settings_page_url;?>">License Key</a>.</span>
            <span>If you don't have one yet, you can request one after you have chosen the brands you wish to syndicate.</span>
        </div>
        <?php echo $button;?>
    </div>
</div>