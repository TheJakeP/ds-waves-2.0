<?php
namespace waves;
$label_style = "uppercase font-bold text-hex-707070";

class brands_to_approve_list {
    private $only_add_this;
    
    private $update_list = array();

    public function __construct(){
        $this->only_add_this = get_class(new brand_to_approve("", "", "", "", "", ""));
    }

    public function add_update($update_to_approve){
        $obj_class_name = get_class($update_to_approve);
        if (strcmp($this->only_add_this, $obj_class_name) == 0){
            array_push($this->update_list, $update_to_approve);
        } else {
            $message = "Did not add object to brand list. It is the wrong type";
            trigger_error($message, $error_level = E_USER_WARNING );
        }
    }

    public function build_table(){
        $row_style = "w-2/12 flex justify-start content-center pl-6 text-waves-table-head";
        ?>
        <div class="flex flex-row justify-stretch content-center uppercase text-hex-646B6F text-lg font-arial font-bold my-4 py-2 border-b-2 border-waves-table-border">
            <div class="w-4 flex flex-row justify-center content-center items-center">
                <input type="checkbox" class="appearance-none checked:bg-blue-600 checked:border-transparent">
            </div>
            <div class="<?php echo $row_style;?>">
                <a href="#">
                    Brand
                <span 
                    class="ml-2"
                    script=""
                >▼</span>
                </a>
            </div>
            <div class="<?php echo $row_style;?>">
                <a href="#">
                    Type
                <span 
                    class="ml-2"
                    script=""
                >▼</span>
                </a>
            </div>
            <div class="<?php echo $row_style;?> content-center items-center">
                <a href="#">
                    Content Name
                <span 
                    class="ml-2"
                    script=""
                >▼</span>
                </a>
            </div>
            <div class="<?php echo $row_style;?> justify-center content-center items-center">
                <a href="">Latest Version</a>
            </div>
            <div class="<?php echo $row_style;?> justify-center content-center items-center">
                <a href="">Current Version</a>
            </div>
            <div class="<?php echo $row_style;?> justify-center content-center items-center">
                <a href="">What's New</a>
            </div>
        </div>
        <?php
        foreach($this->update_list as $entry){
            $entry->row_entry();
        }

    }
}



class brand_to_approve{
    public $brand_name;
    public $update_type;
    public $content_name;
    public $latest_version;
    public $current_version;
    public $release_notes;

    public function __construct($brand, $update_type, $content_name, $latest_version, $current_version, $release_notes){
        $this->brand_name = $brand;
        $this->update_type = $update_type;
        $this->content_name = $content_name;
        $this->latest_version = number_format(floatval($latest_version), 1);
        $this->current_version = number_format(floatval($current_version), 1);
        $this->release_notes = $release_notes;
    }

    public function row_entry(){
        $row_style = "w-2/12 flex content-center pl-6 flex-1 my-3 items-center";
        ?>
        <div class="flex flex-row justify-stretch content-center items-center color-waves-black text-base">
            <div 
                class="w-4 flex flex-row justify-center content-center items-center"
            >
                <input 
                    type="checkbox"
                    class="appearance-none checked:bg-blue-600 checked:border-transparent"
                    data="<?php echo json_encode($this);?>">
            </div>
            <div 
                class="<?php echo $row_style;?>  justify-start content-center items-center"
            >
                <?php echo $this->brand_name; ?>
            </div>
            <div 
                class="<?php echo $row_style;?> justify-start content-center items-center"
            >
                <?php echo $this->update_type; ?>
            </div>
            <div 
                class="<?php echo $row_style;?> content-center items-center"
            >
            <?php 
                utilities::make_link("approvals_brand_name", "#", $this->content_name);
            ?>
            </div>
            <div 
                class="<?php echo $row_style;?> justify-center content-center items-center"
            >
                <?php utilities::make_link("approvals_latest_version", "#", $this->latest_version); ?>
            </div>
            <div 
                class="<?php echo $row_style;?> justify-center content-center items-center"
            >
                <?php utilities::make_link("approvals_current_version", "#", $this->current_version); ?>
            </div>
            <div 
                class="<?php echo $row_style;?> justify-center content-center items-center"
                script=""
                notes="<?php echo $this->release_notes; ?>"
            >
                <?php utilities::make_link("approvals_id", "#", "What's New"); ?>
            </div>
        </div>
        <?php
    }
}

$brand_list = new brands_to_approve_list();

$test_data = array(
    array( 
        "Brand"             => "Hot Spring Spas",
        "Type"              => "Product Update",
        "Content Name"      => "Grandee®",
        "Latest Version"    => 2.3,
        "Current Version"   => 2.1,
        "What's New"        => "Release Notes",
    ),
    array( 
        "Brand"             => "Hot Spring Spas",
        "Type"              => "Product Update",
        "Content Name"      => "Sovereign®",
        "Latest Version"    => 1.2,
        "Current Version"   => 1.0,
        "What's New"        => "Release Notes",
    ),
    array( 
        "Brand"             => "Hot Spring Spas",
        "Type"              => "Product Update",
        "Content Name"      => "Envoy®",
        "Latest Version"    => 1.8,
        "Current Version"   => 1.6,
        "What's New"        => "Release Notes",
    ),
    array( 
        "Brand"             => "Hot Spring Spas",
        "Type"              => "Product Update",
        "Content Name"      => "Prodigy®",
        "Latest Version"    => 2.0,
        "Current Version"   => 1.5,
        "What's New"        => "Release Notes",
    ),
);

foreach ($test_data as $test){
    $test_brand = new brand_to_approve($test['Brand'], $test['Type'], $test['Content Name'], $test['Latest Version'], $test['Current Version'], $test["What's New"]);
    $brand_list->add_update($test_brand);
}



?>
<div class="flex flex-col justify-center content-center space-y-4 space-x-2 text-xl ">
    <div class="flex flex-row justify-between">
        <div class="">
            Content waiting for approval.
        </div>
        <div class="flex flex-row space-x-4">
            <button 
                type="submit"
                id="reject_button"
                name="reject"
                value="reject"
                class="<?php constants::get_waves_button_style_blue_classes();?> px-4 py-2">Reject Update</button>
            <button 
                type="submit" 
                id="allow_button" 
                name="allow" 
                value="allow"
                class="<?php constants::get_waves_button_style_blue_classes();?> px-4 py-2">Allow Update</button>
        </div>
    </div>
    <div class="bg-white font-roboto p-4">
        <?php $brand_list->build_table();?>
    </div>
</div>