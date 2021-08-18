<?php
namespace waves;

class settings {
    
    public function __construct(){
        
    }



    public function get_form(){
        $bool = utilities::is_plugin_activated();
        if ($bool){
            $this->deactivate_form();
        } else {
            $this->activate_form();
        }
    }

    private function activate_form(){
        $url = "#";
        $method = "post";
        $form = new form($url, $method);
        $form->set_classes_container("flex flex-col text-xl text-hex-707070 space-y-2");
        $form->set_classes_form("flex flex-col text-xl text-hex-707070 space-y-2");

        $form->add_title("Activate Plugin License", "font-bold text-2xl text-waves-black");        
        
        $string = "To activate your license of <span class='font-bold text-waves-black'>WAVES by DesignStudio</span> add it below and click on 'Activate License'.";
        $form->add_title($string, "");
        
        $form->add_input_and_button_row("", "License Key", "license_key", "ACTIVATE LICENSE", "ACTIVATE", "h-12 px-8");

                
        $settings_page = Settings_Page::get_page_url();
        $classes= constants::get_ahref_classes();
        $string = "If you don't have a license, you can get it <a class='$classes' target='_BLANK' href='$settings_page'>here</a>.";
        $form->add_title($string, "");
        
        $form->show();
    }

    private function deactivate_form(){
        $url = "#";
        $method = "post";
        $form = new form($url, $method);
        $form->set_classes_container("flex flex-col text-xl text-hex-707070 space-y-2");
        $form->set_classes_form("flex flex-col text-xl text-hex-707070 space-y-2");

        $form->add_title("Deactivate Plugin License", "font-bold text-2xl text-waves-black");        
        
        $string = "To deactivate your license of <span class='font-bold text-waves-black'>WAVES by DesignStudio</span> add it below and click on 'Deactivate License'.";
        $form->add_title($string, "");
        
        $form->add_gray_submit_button("deactivate license", "deactivate", "h-12 px-8");
        $form->show();
    }
}
