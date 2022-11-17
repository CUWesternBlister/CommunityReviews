<?php
/**
 * Plugin Name: Blister Community Reviews
 * Description: A plugin to facilitate Blister community created reviews.
 * Author: Gunnar Marquardt, Jayden Omi, Izak Litte, Jacob Vogel
 */

//BASIC INITIAL KNOW THYSELF POST

function know_thy_self_skiing_init() {
    $args = array(
        'label' => 'Skiing Know Thy Self',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'know-thy-self-skiing'),
        'query_var' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-video-alt',
        'delete_with_user' => false,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',)
    );
    register_post_type( 'know-thy-self-skiing', $args );
}
add_action( 'init', 'know_thy_self_skiing_init' );

// READ AND WRITE BASIC

// READ AND DISPLAY -> SHORTCODE CURRENTLY USED ON KNOW THY SELF SKIING PAGE
// FUNCTION UTILIZES MY CUSTOM KNOWTHYSELF. THIS CAN BE USED ONLY FOR REFERENCE.

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
?>

