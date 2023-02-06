<?php
/**
 * Plugin Name: Blister Community Reviews
 * Description: A plugin to facilitate Blister community created reviews.
 * Author: Gunnar Marquardt, Jayden Omi, Izak Litte, Jacob Vogel, Tristan Riggan
 * Version: 1.2
 */

// Exit if accessed directily
if (!defined('ABSPATH')) exit;

register_activation_hook(__FILE__, 'bcr_activation');
register_deactivation_hook(__FILE__, 'bcr_deactivation');
add_action( 'plugins_loaded', 'bcr_include');
//add_action( 'plugins_loaded', 'bcr_include_table_write_functions');
require_once( plugin_dir_path( __FILE__ ) . '/admin/adminPage.php');
define( 'BCR_PATH', plugin_dir_path( __FILE__ ));
/*
function get_record_from_knowthyself($atts) {
    $atts = shortcode_atts(

        array(
            'name'=>''
        ),
        $atts,
        'form_submissions'
    );
    global $wpdb;
    $name = $atts['name'];
    $nameget = $wpdb->prepare('SELECT * FROM KnowThySelfSkiing LIMIT 1');
    $nameresults = $wpdb->get_results($nameget);
    if ( $nameresults ) {
        $skiingStyle_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->skiingStyle;
            },
            $nameresults
        );
        $confidenceIcyGroomer_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->confidenceIcyGroomer;
                
            },
            $nameresults
        );
        $confidenceSoftGroomer_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->confidenceSoftGroomer;
            },
            $nameresults
        );
        return "Skiing Style: ".implode( ', ', $skiingStyle_subs)."<br><br>Confidence in Icy Groomers: ".implode(', ', $confidenceIcyGroomer_subs)."<br><br>Confidence in Soft Groomers: ".implode(', ', $confidenceSoftGroomer_subs);
    }
    return '';
}
add_shortcode( 'form_submissions', 'get_record_from_knowthyself' );

// WRITING KNOW THY SELF FORM TO KNOWTHYSELF TABLE. THIS CAN BE USED ONLY FOR REFERENCE.

    function knowthyself_write_to_table($record, $ajax_handler) {
        $form_name = $record->get_form_settings( 'form_name' );
        
        if($form_name == 'Know_Thyself_Form'){
        
            $raw_fields = $record->get('fields');

            $fields = [];

            foreach($raw_fields as $id => $field) {
                $fields[$id] = $field['value'];
            }

            global $wpdb;

            $table_name = 'KnowThySelfSkiing';
            
            $output['success'] = $wpdb->insert($table_name, $fields);
            
            $ajax_handler->add_response_data( true, $output);
        }
    }

    add_action( 'elementor_pro/forms/new_record', 'knowthyself_write_to_table', 10, 2);
*/
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

function bcr_include() {
    require_once( plugin_dir_path( __FILE__ ) . 'functions.php');
    require_once( plugin_dir_path( __FILE__ ) . 'community_reviews_custom_post.php');
    require_once( plugin_dir_path( __FILE__ ) . 'form_to_custom_post.php');
    require_once( plugin_dir_path( __FILE__ ) . 'fluent_form_products_from_database.php');
    require_once( plugin_dir_path( __FILE__ ) . 'show_users_reviews.php');
}


add_action( 'elementor_pro/forms/new_record', 'elementor_summit_review_from_sub', 10, 2);
add_action( 'elementor_pro/forms/new_record', 'profile_info_sub', 10, 2);
add_action('fluentform_submission_inserted', 'fluent_summit_review_from_sub', 20, 3);

