<?php
namespace waves;
/*

This class utilizes the following assets:
    -/assets/script/toggle.js
    -/assets/scss/toggle.scss

*/
class toggle_button{

    private static $background = "h-11 w-24 border-2 rounded-full border-hex-E4E6EA rounded-3xl items-center px-1 mr-2";
    private static $circle = "h-9 w-9 bg-white rounded-full";

    protected $id;
    protected $slug;
    protected $status = false;
    protected $url = true;
    
    public function __construct($id, $slug){
        $this->id = $id;
        $this->slug = $slug;
    }
    
    public function active(){
        $this->status = true;
    }

    public function disabled(){
        $this->deactivated = "-disabled cursor-default ";
        $this->url = false;
    }

    public function display(){
        echo $this;
    }

    protected function get_url(){
        if ($this->url){
            $params = $_GET;

            $params["toggle"] = $this->slug;

            $uri_path = $_SERVER['DOCUMENT_URI'];
            return $uri_path . "?" . http_build_query($params);
        } else {
            return "#";
        }
    }

    protected function on(){
        $background = self::$background . " active ";
        echo $this->toggle_builder($background);
    }

    protected function off(){
        $background = self::$background;
        echo $this->toggle_builder($background);
    }

    protected function toggle_builder($background){
        
        $circle = self::$circle . " ds-transition" ;
        $url = $this->get_url();
        
        $classes = "flex items-center toggle-button " . $this->deactivated . " " . $background;
        $ret_val = "<a href='$url' class='$classes'>";
        $ret_val .= "<div class='inner-circle $circle'></div>";
        $ret_val .= "</a>";
        return $ret_val;
?>
        <a 
            href="<?php echo $url;?>"
            class="flex items-center toggle-button<?php echo $this->deactivated;?> <?php echo $background;?>">
            <div class="inner-circle <?php echo $circle;?>"></div>
        </a>
    <?php
    }

    public function __toString(){
        if ($this->status){
            $background = self::$background . " active ";
        } else {
            $background = self::$background;
        }
        return $this->toggle_builder($background);
    }
}