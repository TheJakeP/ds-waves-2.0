<?php
namespace waves;

class cache {
    
    public static function session(){
        self::init_session();
        
        foreach (constants::get_cache_keys() as $class_name => $key){
            $obj = self::get_variable_by_key_and_class($class_name);
            $GLOBALS[$key] = $obj;
            $_SESSION[$key] = $obj;
        }
    }

    public static function &get_variable_by_key_and_class($full_class_name){
        $key = $full_class_name;
        $bool_1 = self::global_variable_exists($key, $full_class_name);
        $bool_2 = self::session_variable_exists($key, $full_class_name);
        if ($bool_1){
            $obj = $GLOBALS[$key]; //ret global
        } else if ($bool_2){
            $obj = $_SESSION[$key]; // return session_variable
        } else {
            $obj = new $full_class_name;
            $key = $obj::cache_id();
        }
        $GLOBALS[$key]  = $obj;
        $_SESSION[$key] = $obj;
        return $obj;
    }

    public static function global_variable_exists($key, $full_class_name){
        $array = &$GLOBALS;
        return self::in_array_by_key_class_name($array, $key, $full_class_name);
    }

    private static function session_variable_exists($key, $full_class_name){
        $array = &$_SESSION;
        return self::in_array_by_key_class_name($array, $key, $full_class_name);
    }

    private static function in_array_by_key_class_name(&$array, $key, $full_class_name){
        if (array_key_exists($key, $array)){
            $obj = $_SESSION[$key];
            if (utilities::check_obj_type($obj, $full_class_name)){
                return true;
            }
        }
        return false;
    }

    public static function save_changes(){
        self::init_session();
        $namespace = __NAMESPACE__ . '\\';
        foreach (constants::get_cache_keys() as $class_name => $key){
            if (key_exists($key, $GLOBALS)) {
                $obj = $GLOBALS[$key];
            } else {
                $full_class_name = $namespace . $class_name;
                $obj = new $full_class_name;
            }
            $_SESSION[$key] = $obj;
        }
    }

    public static function update_variable_by_key_obj($key, $new_obj){
        $_SESSION[$key] = $new_obj;
        $GLOBALS[$key] = $new_obj;
    }

    private static function session_active(){
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }

    private static function init_session(){
        if(!self::session_active()){
            session_start();
        }
    }

    public static function destroy(){
        self::destroy_session();
        self::destroy_globals();
    }

    private static function destroy_session(){
            self::init_session();
            session_destroy();
    }

    private static function destroy_globals(){
        foreach (constants::get_cache_keys() as $key){
            unset($GLOBALS[$key]);
        }
    }

    public static function &get_db_remote(){
        $key = db_remote::cache_id();
        $class_name = db_remote::class;
        return self::get_variable_by_key_and_class($class_name);
    }

    public static function &get_db_local(){
        $key = db_local::cache_id();
        $class_name = db_local::class;
        return self::get_variable_by_key_and_class($class_name);
    }

    public static function &get_select_handler(){
        $key = select_handler::cache_id();
        $class_name = select_handler::class;
        return self::get_variable_by_key_and_class($class_name);
    }
    
    public static function &get_shopping_cart(){
        $key = shopping_cart::cache_id();
        $class_name = shopping_cart::class;
        return self::get_variable_by_key_and_class($class_name);
    }

    public static function &get_menu_manager(){
        $key = menu_manager::cache_id();
        $class_name = menu_manager::class;
        return self::get_variable_by_key_and_class($class_name);
    }

    public static function update_db_remote($db_remote){
        if ($db_remote instanceof cached){
            $key = $db_remote->cache_id();
            self::update_variable_by_key_obj($key, $db_remote);
        }
    }

    public static function update_remote_brand_obj($brand_obj){
        $db_remote = self::get_db_remote();
        $db_remote->update_brand($brand_obj);
        self::update_db_remote($db_remote);
    }

    public static function update_db_local($db_local){
        if ($db_local instanceof cached){
            $key = $db_local->cache_id();
            self::update_variable_by_key_obj($key, $db_local);
        }
    }
        
    public static function update_local_brand_obj(&$brand_obj){
        $db_local = self::get_db_local();
        $db_local->update_brand($brand_obj);
        self::update_db_local($db_local);
    }

}