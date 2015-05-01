<?php
/*
Plugin Name: CFS - Time picker Add-on
Plugin URI: http://somaerdelyi.net/
Description: Adds a time picker field type.
Version: 1.0.0
Author: Soma Erdelyi
Author URI: http://somaerdelyi.net/
License: GPL2
*/

$cfs_time_picker_addon = new cfs_time_picker_addon();

class cfs_time_picker_addon
{
    function __construct() {
        add_filter('cfs_field_types', array($this, 'cfs_field_types'));
    }

    function cfs_field_types( $field_types ) {
        $field_types['time_picker'] = dirname( __FILE__ ) . '/time.php';
        return $field_types;
    }
}
