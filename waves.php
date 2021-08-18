<?php
/**
 * @package Waves

 *
 * @wordpress-plugin
 * Plugin Name: Waves by DesignStudio
 * Plugin URI: 
 * Description: A plugin that needs a description.
 * Version: 2.0.1-b
 * Author: Design Studios by Jacob Phelps
 * Author URI: https://designstudio.com
 */
namespace waves;

  function load_plugin(){
    
  }

  new waves_plugin();

  class waves_plugin{
    
    
    private $include_directories = array(
      "classes/interfaces",
      
      "classes/generics",
      "classes/generics/paginators",
      
      "classes/menu",
      "classes/pages",
      "classes",


      "classes/local_data",
      "classes/local_data/paginators",


      "classes/remote_data",
      "classes/remote_data/paginators",

      "classes/shopping_cart",


    );

    public function __construct(){
      
      $this->define_plugin_constants();
      $this->register_js_and_css();
      $this->include_files();
      new menu_manager();
      constants::before_wp_loads_actions();
    }

    private static function is_waves_admin_page(){
      $is_admin = is_admin();

      if (!$is_admin){
        return false;
      }

      $page_slug = $_GET['page'];
      if (is_null($page_slug)){
        return false;
      }

      $slug_prefix = "ds-waves-";
      $prefix_len = strlen($slug_prefix);
      $page_sub_str = substr( $page_slug, 0, $prefix_len );
      $bool = strcmp($page_sub_str, $slug_prefix) == 0;
      return $bool;
    }

    public static function register_js_and_css(){
      add_action( 'admin_enqueue_scripts', array(self::class, 'admin_js_and_css'));      
    }

    public function admin_js_and_css() {
      $bool = self::is_waves_admin_page();
      if ($bool){
        wp_enqueue_style( 'waves-admin-style', WAVES_PLUGIN_ASSETS . 'style.clean.min.css' );
        wp_enqueue_style( 'waves-tailwind-style', WAVES_PLUGIN_ASSETS . 'TailWindCSS/tailwind.post.css' );
        wp_enqueue_style( 'waves-webfonts', WAVES_PLUGIN_ASSETS . 'fonts/webfonts.css' );
        wp_enqueue_script( 'admin_enqueue_scripts', WAVES_PLUGIN_ASSETS . "global.js" );
      }
    }

    private function define_plugin_constants(){
      define( 'WAVES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
      define( 'WAVES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
      define( 'WAVES_PLUGIN_FILE', plugin_basename( __FILE__ ) );
      define( 'WAVES_PLUGIN_ASSETS', plugins_url("waves/assets/") );
      define( 'WAVES_PLUGIN_IMG', WAVES_PLUGIN_ASSETS . "images/" );
      define( 'WAVES_PLUGIN_TEMPLATES', WAVES_PLUGIN_DIR . "templates/" );
      
    }

    private function include_files(){
      foreach ($this->include_directories as $dir){
        $this->import_php_in_this_directory_root($dir);
      }
    }

    private function import_php_in_this_directory_root ($plugin_directory_dirty){
      $plugin_directory_cleaned = trim($plugin_directory_dirty, "/\\");
      $php_files =  glob( WAVES_PLUGIN_DIR . $plugin_directory_cleaned . "/*.php");
      foreach ($php_files as $file){
        include_once $file;
      }
    } 

}