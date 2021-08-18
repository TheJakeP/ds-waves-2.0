<?php
namespace waves;


?>
<div class="flex flex-row justify-center content-center">
    <div class="flex flex-col font-roboto w-5/12 max-w-3xl text-2xl justify-center content-center text-hex-707070">
        <div class="flex flex-row justify-center content-center my-8">
            <?php constants::get_design_studio_logo(["style" => "asfd"]);?>
        </div>
        <div class="my-8 text-left text-hex-707070">
            <span class="font-bold">WAVES by DesignStudio</span> is a WordPress plugin that connects to our unique and proprietary 
            <a class="<?php constants::get_ahref_classes();?>" target="_BLANK" href="<?php constants::echo_real_rich_snippets_link_destination();?>">Content Syndication Platform</a> to help with keeping content in this sit up to date.</div>
        <div class="my-8 text-left text-hex-707070">
            <span class="text-xl text-hex-707070">Powered by</span>
            <?php constants::get_real_rich_snippets_logo();?>
        </div>
    </div>
</div>