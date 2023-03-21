<?php
/**
 * Plugin Name: Blister Community Reviews
 * Description: A plugin to facilitate Blister community created reviews.
 * Author: Gunnar Marquardt, Jayden Omi, Izak Litte, Jacob Vogel, Tristan Riggan
 * Version: 1.3.1
 */

// Exit if accessed directily
if (!defined('ABSPATH')) exit;

// Register activation and deactivation procedures
register_activation_hook(__FILE__, 'bcr_activation');
register_deactivation_hook(__FILE__, 'bcr_deactivation');

define( 'BCR_PATH', plugin_dir_path( __FILE__ ));

/**
 * Load Blister Community Reviews activation functions
 * 
 * @return void
 */
function bcr_activation() {
    require_once( plugin_dir_path( __FILE__ ) . '/admin/activation.php');
}


/**
 * Load Blister Community Reviews deactivation functions
 * 
 * @return void
 */

function bcr_deactivation() {
    require_once( plugin_dir_path( __FILE__ ) . '/admin/deactivation.php');
}

/**
 * Load required functions
 * 
 * @return void
 */
function bcr_include() {
    require_once( plugin_dir_path( __FILE__ ) . 'functions.php');
    require_once( plugin_dir_path( __FILE__ ) . 'community_reviews_custom_post.php');
    require_once( plugin_dir_path( __FILE__ ) . 'form_to_custom_post.php');
    require_once( plugin_dir_path( __FILE__ ) . 'show_users_reviews.php');
    require_once( plugin_dir_path( __FILE__ ) . '/admin/adminPage.php');
    require_once( plugin_dir_path( __FILE__ ) . 'generate_taxonomies.php');
}

add_action( 'plugins_loaded', 'bcr_include');
