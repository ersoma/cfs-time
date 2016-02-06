<?php
/*
Plugin Name: CFS - Time picker add-on
Plugin URI: http://somaerdelyi.net/
Description: Adds a time picker field type.
Version: 1.1
Author: Soma Erdelyi
Author URI: http://somaerdelyi.net/
License: GPL2
Text Domain: cfs-time
Domain Path: /languages/
*/

$cfs_time_picker_addon = new cfs_time_picker_addon();

class cfs_time_picker_addon
{
    function __construct() {
        add_filter('cfs_field_types', array($this, 'cfs_field_types'));
        add_action( 'plugins_loaded', 'cfstime_load_textdomain' );
        function cfstime_load_textdomain() {
          load_plugin_textdomain( 'cfs-time', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
        }
    }

    function cfs_field_types( $field_types ) {
        $field_types['time_picker'] = dirname( __FILE__ ) . '/time.php';
        return $field_types;
    }
}
