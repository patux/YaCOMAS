<?php
/**
 * Include files from the core based on the Instance's ClassName being created
 * every class it gets it from includes/core/
 *
 * For classes that doesn't form part of the core, you must include the file
 * by hand.
 */
class Autoloader {

  public static function autoload($class_name)
  {
    $class_name = ucwords($class_name);
    
    /** Application Specific Classes are inside this directories **/
    $named_directories = array (
        'Model' => 'models/',
    );

    $is_core = true;

    foreach ( $named_directories AS $name => $directory ) {
        if ( stristr( $class_name, $name ) && $class_name != $name ) {
            $path = $directory . $class_name;
            $is_core = false;
            break;
        }
    }

    /** All other classes are inside the core **/
    if ( $is_core ) {
        $path = 'includes/core/' . $class_name;
    }
   
    /** add the application path and the php extension **/
    if ( !file_exists(TO_ROOT . '/' . $path . '.php') ) {
      return false;
    }
    require_once TO_ROOT . '/' . $path . '.php';
    return true;
  }
  
  /**
   * Configure autoloading using Core
   *
   * This is designed to play nicely with other autoloaders.
   */
  public static function registerAutoload()
  {
    spl_autoload_register(array('Autoloader', 'autoload'));
  }
}