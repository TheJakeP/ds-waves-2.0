<?php
namespace waves;

class brand_asfdcatalog {

    
    private $thumbs_per_page = 15;

    private $array_brand_obj = array();
    private $array_by_name = array();
    private $array_by_industry = array();
    private $array_by_price = array();

    //TO-DO Logic for filtering
    public function get_filtered_count(){
        return count($this->array_brand_obj);
    }

    public function add_brand($brand){
        array_push($this->array_brand_obj, $brand);
        $this->array_by_name[$brand->get_name()] = $brand;
        $this->array_by_industry[$brand->get_industry_name()] = $brand;
        $this->array_by_price[$brand->get_total_price_int()] = $brand;
    }

    public function get_total_count(){
        return count($this->array_brand_obj);
    }

    public function get_catalog_index_start(){
        $current_page = paginator::current_page();
        return $this->thumbs_per_page * ($current_page - 1);
    }



    public function get_catalog_index_stop(){
        $start = $this->get_catalog_index_start();
        return $this->thumbs_per_page + ($start);
    }

    public function get_grid_thumbnails($sorted_keys, $catalog){

        $start = $this->get_catalog_index_start();
        $stop = $this->get_catalog_index_stop();

        for ($i = $start; $i < $stop; $i++){
            $key = $sorted_keys[$i];
            $brand_obj = $catalog[$key];
            $this->get_brand_thumb($brand_obj);
        }
    }

    public function sort_by_a_to_z(){
        $this->sort_asc($this->array_by_name);
    }

    public function sort_by_z_to_a(){
        $this->sort_desc($this->array_by_name);
    }

    public function sort_by_industry(){
        $this->sort_asc($this->array_by_industry);
    }

    public function sort_by_price_low_to_high(){
        $this->sort_asc($this->array_by_price);
    }

    public function sort_by_price_high_to_low(){
        $this->sort_desc($this->array_by_price);
    }

    public function sort_asc($array){
        $array_keys = array_keys($array);
        ksort($array_keys, SORT_STRING);
        $sorted_keys = array_values($array_keys);
        $this->get_grid_thumbnails($sorted_keys, $array);
    }

    public function sort_desc($array){
        $array_keys = array_keys($array);
        krsort($array_keys, SORT_STRING);
        $sorted_keys = array_values($array_keys);
        $this->get_grid_thumbnails($sorted_keys, $array);
    }

    public function get_brand_thumb($brand){
        $brand_slug = $brand->get_slug();
        $brand_url = Select_New_Brand_Page::add_brand_page($brand_slug);
        $industry_link = $brand->get_industry_url();
        $industry_name = $brand->get_industry_name();
        $parent_co = $brand->get_parent_company();
        ?>
            <div class="flex flex-col align-middle justify-start font-roboto overflow-hidden">
                <a 
                    class="flex flex-col bg-white content-center items-center justify-center grid-square mb-5" 
                    href="<?php echo $brand_url;?>"
                >
                    <div class="flex content-center items-center justify-center w-3/5 h-3/5 bg-contain bg-no-repeat bg-center" style="background-image: url('<?php echo $brand->get_logo_url(); ?>');"></div>
                </a>
                <div class="text-xl"><?php echo $brand->get_name(); ?></div>
                <div class="text-base text-hex-646B6F"><?php echo $parent_co; ?></div> 
                <div class="text-base"><a class="<?php constants::get_ahref_classes();?> text-base" href="<?php echo $industry_link;?>"><?php echo $industry_name;?></a></div> 
            </div>
        <?php
    }

    public function get_brand_by_url(){
        $brand_name = urldecode($_GET[constants::_get_param_brand_page()]);
        return $this->array_by_name[$brand_name];
    }

    public function get_brand_by_slug($slug){
        $brand_name = $slug;
        if (in_array($slug, $this->array_by_name)){
            return $this->array_by_name[$brand_name];
        } else {
            return null;
        }
    }

}