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