<?php
namespace waves;

?>

<div class="flex flex-row justify-center content-center font-roboto space-x-16">
    <div class="flex flex-col w-7/12">
        <?php 
            new brand_remote_grid(); 
        ?>
    </div>
    <div class="flex flex-col w-5/12">
    <?php 
        $shopping_cart = cache::get_shopping_cart();
        shopping_cart::display();
    ?>
    </div>
</div>