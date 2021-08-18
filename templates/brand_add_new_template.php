<?php
namespace waves;


$brand = db_remote::get_brand_by_brand_page_param();

?>
    <div class="flex flex-row justify-center content-center font-roboto space-x-16 ">
        <div class="flex flex-col w-7/12 mt-9 ">
            <div class="flex flex-row mb-12">
                <img 
                    class="border-none"
                    src="<?php echo $brand->get_logo_url();?>"/>
            </div>
            <div class="flex flex-row flex-nowrap text-sm justify-between">
                <div class="flex flex-row w-2/5 text-xl">
                    <?php echo $brand->get_description();?>
                </div>
                <div class="flex flex-col">
                    <div class=" font-bold">Total Cost</div>
                    <div class="text-2xl">
                        <?php echo $brand->get_total_price_str();?>
                    </div>
                </div>
            </div>
            <div class="flex flex-row flex-nowrap justify-between my-8">
                <?php 
                    $brand->paginate_as_accordion_rows();
                ?>
            </div>
        </div>
        <div class="flex flex-col w-5/12 ">
            <?php 
            shopping_cart::display();
            ?>
        </div>

    </div>