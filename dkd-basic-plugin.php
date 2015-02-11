<?php
/*
Plugin Name: DKD Basic Plugin
Description: Basic core template for plugins.
Version: 0.5
Author: David Kissinger
Author URI: http://www.davidkdaily.com/
Plugin URI: http://www.davidkdaily.com/dkd-basic-plugin
*/

/* Set up core foundation of plugin */
/* Define where the files are located */
define( 'DKD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DKD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* Register and enqueue dedicated CSS */
function dkd_set_up_assets() {
  wp_register_style( 'font-awesome', ( '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') );
  wp_enqueue_style( 'font-awesome' );
  wp_register_style( 'dkd-plugin-css', plugins_url( 'assets/css/dkd-plugin.css', __FILE__ ) );
  wp_enqueue_style( 'dkd-plugin-css' );
}
add_action( 'wp_enqueue_scripts', 'dkd_set_up_assets' );

/* Register and enqueue jQuery from cdn, after clearing out any old version. Thanks, Paulund! */
add_action( 'wp_enqueue_scripts', 'register_jquery' );
function register_jquery() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', ( 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' ), false, null, true );
    wp_enqueue_script( 'jquery' );
}

/* Register and enqueue dedicated js */
function dkd_set_up_js() {
  wp_register_script( 'dkd-plugin-js', plugins_url( 'assets/js/dkd-plugin.js', __FILE__ ), array( 'jquery' ), null, true );
  wp_enqueue_script( 'dkd-plugin-js' );
}
add_action( 'wp_enqueue_scripts', 'dkd_set_up_js' );

/* Load other plugin files */
function dkd_plugin_load() {
  if( is_admin() ) // load files for admin pages
    require_once( DKD_PLUGIN_DIR . 'includes/admin.php' );
  require_once( DKD_PLUGIN_DIR . 'includes/core.php' );
}
dkd_plugin_load();

/* Set up activation and deactivation hooks */
register_activation_hook( __FILE__, 'dkd_plugin_activation');
register_deactivation_hook( __FILE__, 'dkd_plugin_deactivation');
function dkd_plugin_activation() {
  // do activation stuff
  // register uninstaller
  register_uninstall_hook( __FILE__, 'dkd_plugin_uninstall');
  
}
function dkd_plugin_deactivation() {
  // do deactivation stuff
}
function dkd_plugin_uninstall() {
  // do uninstall stuff here. bye!
}

?>