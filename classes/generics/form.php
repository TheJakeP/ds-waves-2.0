<?php
namespace waves;

class form {

    private $form_entries = array();
    private $classes_input = "";
    private $classes_label = "";
    private $classes_row = "";

    public function __construct($url, $method){
        $this->url = $url;
        $this->method = $method;
    }

    private function add_entry($entry){
        array_push($this->form_entries, $entry);
    }

    public function add_title($title, $classes){
        $title = new form_title($title, $classes);

        $row = new entry_container($title, "");
        $this->add_entry($row);
    }


    public function add_input($label, $placeholder, $id){
        $classes_input = $this->classes_input . " " . constants::return_waves_input_style_classes();
        $classes_label = $this->classes_label;

        $input = new form_input($label, $placeholder, $id, $classes_input, $classes_label);

        $row = new entry_container($input, "");
        $this->add_entry($row);
    }

    public function add_select($label, $value_array, $placeholder, $id){
        
        $classes_select = $this->classes_select;
        
        $classes_label = $this->classes_label;

        $select = new form_select($label, $value_array, $placeholder, $id, $classes_select, $classes_label);
        
        $row = new entry_container($select, "");
        $this->add_entry($row);
    }

    public function add_textarea($label, $placeholder, $id){
        
        $classes_textarea = $this->classes_textarea;
        
        $classes_label = $this->classes_label;

        $textarea = new form_textarea($label, $placeholder, $id, $classes_textarea, $classes_label);
        
        $row = new entry_container($textarea, "");
        $this->add_entry($row);
    }

    public function add_blue_submit_button ($label, $id){
        $classes_button = $this->get_classes_button_blue();
        $button = new submit_button($label, $id, $classes_button);
        
        $row = new entry_container($button, "");
        $this->add_entry($row);
    }

    public function add_gray_submit_button ($label, $id, $classes){
        $classes_button = $this->get_classes_button_gray() . " " . $classes;
        $button = new submit_button($label, $id, $classes_button);
        
        $row = new entry_container($button, "");
        $this->add_entry($row);
    }



    public function add_input_and_button_row($input_label, $input_placeholder, $input_id, $button_label, $button_id, $classes_button){
        $classes_input = $this->classes_input . " " . constants::return_waves_input_style_classes();
        $classes_label = $this->classes_label;
        $classes_button = $this->get_classes_button_blue() . " " . $classes_button;

        $input = new form_input($input_label, $input_placeholder, $input_id, $classes_input, $classes_label);
        $select = new submit_button($button_label, $button_id, $classes_button);

        $row = new entry_container($input, " space-x-4 ");
        $row->add($select);
        $row->make_col();
        $this->add_entry($row);
    }

    public function show(){
        $classes_container = $this->get_classes_container();
        $classes_form =     $this->get_classes_form();
        $method = $this->get_method();
        $url = $this->get_url();
    ?>
    <div class="<?php echo $classes_container;?>">
        <form class="<?php echo $classes_form;?>" action="<?php echo $url;?>" method="<?php echo $method;?>">
    <?php
        foreach ($this->form_entries as $obj){
            echo $obj;
        }
    ?>
        </form>
    </div>
    <?php
    }


    public function get_classes_button_blue(){
        return constants::return_waves_button_style_blue_classes() . " h-full " . $this->classes_button;
    }

    public function get_classes_button_gray(){
        return constants::return_waves_button_style_gray_classes() . " h-full " . $this->classes_button;
    }


    public function get_classes_container(){
        return $this->classes_container;
    }
    
    public function get_classes_form(){
        return $this->classes_form;
    }
    
    public function get_classes_input(){
        return $this->classes_input;
    }

    public function get_classes_label(){
        return $this->classes_label;
    }

    public function get_classes_select(){
        return $this->classes_select;
    }

    public function get_classes_textarea(){
        return $this->classes_textarea;
    }

    public function get_classes_row(){
        return $this->classes_row;
    }

    public function get_method(){
        return $this->method;
    }

    public function get_url(){
        return $this->url;
    }


    public function set_classes_button($classes){
        $this->classes_button = $classes;
    }

    public function set_classes_container($classes){
        $this->classes_container = $classes;
    }

    public function set_classes_form($classes){
        $this->classes_form = $classes;
    }

    public function set_classes_input($classes){
        $this->classes_input = $classes;
    }

    public function set_classes_label($classes){
        $this->classes_label = $classes;
    }

    public function set_classes_select($classes){
        $this->classes_select = $classes;
    }

    public function set_classes_textarea($classes){
        $this->classes_textarea = $classes;
    }

    public function set_classes_row($classes){
        $this->classes_row = $classes;
    }
}


class entry_container implements form_entry {
    private $entry_list = array();
    private $classes;
    private $style;
    public function __construct($entry, $classes){
        $this->make_row();
        $this->add($entry);
        if (!empty($classes)){
            $this->classes = $classes;
        }
    }

    public function make_row(){
        $this->style = "flex flex-col flex-nowrap flex-auto";
    }

    public function make_col(){
        $this->style = "flex flex-row flex-nowrap flex-auto";
    }

    public function add($entry){
        array_push($this->entry_list, $entry);
    }

    public function __toString(){
        $classes_row = $this->style;
        $classes = $this->classes;
        $return = "<div class='$classes_row $classes'>";
        
        foreach ($this->entry_list as $entry){
            $return .= $entry;
        }
        
        $return .= "</div>";

        return $return;
    }
}

class form_title implements form_entry {
    private $title;
    
    public function __construct($title, $classes){
        $this->title = $title;
        $this->classes_title = $classes;
    }
    public function __toString(){
        $title = $this->title;
        $classes = $this->classes_title;
        return "<div class='$classes'>$title</div>";
    }
}

class form_input implements form_entry {
    private $classes_input;
    private $classes_label;
    private $label;
    private $id;
    private $placeholder;
    
    public function __construct($label, $placeholder, $id, $classes_input, $classes_label){
        $this->classes_input = $classes_input;
        $this->classes_label = $classes_label;
        $this->label = $label;
        $this->id = $id;
        $this->placeholder = $placeholder;
    
    }
    public function __toString(){
        $classes_input = constants::return_waves_input_style_classes() . "  " . $this->classes_input;
        $classes_label = $this->classes_label;
        $id = $this->id;
        $label = $this->label;
        $placeholder = $this->placeholder;
        $return = "<div class='flex flex-col flex-nowrap'>";
        $return .= "<label for='$id' class='$classes_label' >$label</label>";
        $return .= "<input type='text' id='$id' name='first_name' class='$classes_input' placeholder='$placeholder' >";
        $return .= "</div>";
        return $return;
    }
}

class form_select implements form_entry{

    public function __construct($label, $value_array, $placeholder, $id, $classes_select, $classes_label){
        $this->classes_select = constants::return_waves_input_style_classes() . " " . $classes_select;
        $this->classes_label = $classes_label;
        $this->label = $label;
        $this->id = $id;
        $this->placeholder = $placeholder;
        $this->value_array = $value_array;
    }

    public function __toString(){
        $classes_select = $this->classes_select;
        $classes_label = $this->classes_label;
        $id = $this->id;
        $label = $this->label;
        $placeholder = $this->placeholder;

        $placeholder = $this->placeholder;
        $value_array = $this->value_array;
        
        $return = "<div class='flex flex-col flex-nowrap'>";
        $return .= "<label for='$id' class='$classes_label'>$label</label>";
        $return .= "<select id='$id' name='$id' class='flex flex-row max-w-unset $classes_select'>";
        $return .= "<option hidden selected disabled>Choose $placeholder</option>";
        foreach ($value_array as $option => $value){
            $return .= "<option value='$value'>$option</option>";
        }
        $return .= "</select>";
        $return .= "</div>";
        return $return;
    }
}

class form_textarea implements form_entry {
    public function __construct($label, $placeholder, $id, $classes_select, $classes_label){
        $this->classes_textarea = "flex flex-col max-w-unset " . constants::return_waves_input_style_classes() . " " . $classes_select;
        $this->classes_label = $classes_label;
        $this->label = $label;
        $this->id = $id;
        $this->placeholder = $placeholder;
    }

    public function __toString(){
        $classes_textarea = $this->classes_textarea;
        $classes_label = $this->classes_label;
        $id = $this->id;
        $label = $this->label;
        $placeholder = $this->placeholder;

        $return = "<div class='flex flex-col flex-nowrap'>";
        $return .= "<label for='$id' class='$classes_label'>$label</label>";
        $return .= "<textarea id='$id' name='$id' class='$classes_textarea' placeholder='$placeholder' rows='4' cols='50'>";
        $return .= "</textarea>";
        $return .= "</div>";

        return $return;
    }
}

class submit_button implements form_entry {
    public function __construct($label, $id, $classes_button){
        $this->classes_button = constants::return_waves_button_style_blue_classes() . " " . $classes_button;
        $this->id = $id;
        $this->label = $label;
    }

    public function __toString(){
        $classes_button = $this->classes_button;
        $label = $this->label;
        $timestamp = time();

        $return = "<div class='flex flex-row flex-nowrap'>";
        $return .= "<button type='submit' class='$classes_button' name='$label' value='$timestamp'>$label</button>";
        $return .= "</div>";

        return $return;
    }
}