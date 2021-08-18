<?php
namespace waves;


// class brand implements pagination_as_grid, paginate_as_accordion, waves_objects{
class brand {
    protected $brand_id = null;
    protected $brand_name;

    
    protected $child_obj_setup = false;
    // protected $description;
    protected $entries = array();
    protected $entry_count = 0;


    protected $industry;
    protected $industry_name;

    protected $logo_url;
    protected $parent_co;


    public function __construct($brand_name, $description, $logo_url, $parent_co, &$industry_ref){
        $this->brand_name   = $brand_name;
        $this->industry_ref = $industry_ref;
        $this->description  = $description;
        $this->logo_url     = $logo_url;
        $this->parent_co    = $parent_co;
        $this->init_entries_array();
    }


    /* Methods to build in child classes */
    public function get_accordion_top_row(){}
    public function get_grid_thumbnail(){}
    public function get_add_remove_all_button(){}


    protected function init_entries_array(){
        $vals = constants::get_entry_types();
        foreach($vals as $val){
            $this->entries[$val] = array();
        }
    }

    protected function get_input_field(){
        $input_classes =   $classes = constants::return_waves_input_style_classes() . " h-14 w-full ";
        ?>
        <div class="flex flex-row items-center w-full min-w-90 relative ">
            <input 
            type="text"
            id="keyword_filter"
            placeholder="Filter by Keyword"
            class="<?php echo $input_classes;?>" 
                >
            <button type="submit" class=" background-transparent right-7 bg-transparent flex h-7 w-7 absolute" >
                <i class="gray_search_icon bg-transparent h-full w-full"></i>
            </button>
        </div>
        <?php
    }

    protected function &get_entries_array(){
        return $this->entries;
    }

    public function get_entry_by_type_and_id($type, $id){
        $type_exists = array_key_exists($type, $this->entries);
        if ($type_exists){
            $entry_exists = array_key_exists($id, $this->entries[$type]);
            if ($entry_exists){
                return $this->entries[$type][$id];
            }
        } 
        return null;
    }

    public function get_industry_page_url(){
        $slug = $this->industry_ref->get_slug();
        $url = Industry_Page::get_url_by_slug($slug);
        return $url;
    }

    public function industry_link(){
        $text = $this->get_industry_name();
        $url = $this->get_industry_page_url();
        $link = "<a class='" . constants::return_ahref_classes() . "'href='$url' >$text</a>";
        return $link;
    }

    public function get_industry_link(){
        echo $this->industry_link();
    }

    public function get_description(){
        return $this->description;
    }

    public function get_name() {
        return $this->brand_name;
    }

    public function get_logo_url() {
        return $this->logo_url;
    }

    public function return_logo_img($classes, $width, $height){

        $id = "logo-" . $this->get_slug();
        $url = $this->get_logo_url();

        $classes .= " ";
        $alt = $this->get_name() . " logo";

        return utilities::return_img_tag($id, $url, $classes, $alt, $width, $height, "");
    }

    public function get_slug(){
        if ($this->brand_id == null){
            $this->brand_id = utilities::make_slug_from_string($this->get_brand_id());
        }
        return $this->brand_id;
    }

    public function get_brand_id(){
        return utilities::generate_obj_id_from_str($this->brand_name);
    }

    public function get_parent_company() {
        return $this->parent_co;
    }

    public function &get_industry_obj() {
        return $this->industry_ref;
    }

    public function get_industry_name(){
        if (is_null($this->industry_name)){
            $industry_obj = $this->get_industry_obj();
            $this->industry_name = $industry_obj->get_name();
        }
        return $this->industry_name;
    }

    public function get_entry_count_by_type($type_slug){
        $array = $this->get_entries_array();
        
        $bool = utilities::array_contains_key($type_slug, $array);
        if ($bool) {
            return count($array[$type_slug]);
        } else {
            return 0;
        }
        
    }
        
    public function get_total_entries(){
        if (is_null($this->entry_count) || $this->entry_count == 0){
            $count = null;
            if ($this->child_obj_setup){
                foreach ($this->entries as $entry_type => $entry_array){
                    $count += count($entry_array);
                }
            } else {
                $slug = $this->get_slug();
                $count = db_remote::get_entries_for_brand_by_slug($slug);
            }
            $this->entry_count = $count;
        } 
        return $this->entry_count;
    }

    public function paginate_as_accordion_rows(){

    ?>
    <div class="flex flex-col w-full">
    <?php
        $this->get_accordion_top_row();
        new accordion_brand_remote($this, $this->entries[constants::get_entry_type_product()],  12);
        new accordion_brand_remote($this, $this->entries[constants::get_entry_type_webpage()],  12);
        new accordion_brand_remote($this, $this->entries[constants::get_entry_type_blog()],     12);
        new accordion_brand_remote($this, $this->entries[constants::get_entry_type_kb()],       12);
        
        ?>
    </div>
        <?php
    }

}