<?php
/**
 * Plugin Name: Blister Community Reviews
 * Description: A plugin to facilitate Blister community created reviews.
 * Author: Gunnar Marquardt, Jayden Omi, Izak Litte, Jacob Vogel, Tristan Riggan
 */

// Exit if accessed directily
if (!defined('ABSPATH')) exit;

register_activation_hook(__FILE__, 'bcr_activation');
register_deactivation_hook(__FILE__, 'bcr_deactivation');
add_action( 'plugins_loaded', 'bcr_include');

//if (!function_exists('bcr_activation')) {
    /**
     * Load Blister Community Reviews activation functions
     * 
     * @return void
     */
    function bcr_activation() {
        require_once( plugin_dir_path( __FILE__ ) . '/admin/activation.php');
    }
//}

//if(!function_exists('bcr_deactivation')) {
    /**
     * Load Blister Community Reviews deactivation functions
     * 
     * @return void
     */
    function bcr_deactivation() {
        require_once( plugin_dir_path( __FILE__ ) . '/admin/deactivation.php');
    }
//}

//if(!function_exists('bcr_include')) {
    function bcr_include() {
        require_once( plugin_dir_path( __FILE__ ) . 'functions.php');
    }
//}