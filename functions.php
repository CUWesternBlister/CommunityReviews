<?php
add_action( 'elementor_pro/forms/new_record', 'elementor_summit_review_from_sub', 10, 2);
add_action( 'elementor_pro/forms/new_record', 'profile_info_sub', 10, 2);
add_action('fluentform_submission_inserted', 'fluent_summit_review_form_sub', 20, 3);

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

function get_record_from_form_submissions($atts) {
    $atts = shortcode_atts(

        array(
            'name'=>''
        ),
        $atts,
        'form_submissions'
    );
    global $wpdb;
    $name = $atts['name'];
    $nameget = 'SELECT * FROM KnowThySelfSkiing LIMIT 1';
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
        return "Skiing Style: ".esc_html(implode( ', ', $skiingStyle_subs))."<br><br>Confidence in Icy Groomers: ".esc_html(implode(', ', $confidenceIcyGroomer_subs))."<br><br>Confidence in Soft Groomers: ".esc_html(implode(', ', $confidenceSoftGroomer_subs));
    }
    return '';
}
add_shortcode( 'form_submissions', 'get_record_from_form_submissions' );

function display_user_info($atts){
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $myfile = fopen($file_path, "a") or die('fopen failed');
    $userEntry = get_user_information($myfile);
    if ( $userEntry ) {
        $measurement = $userEntry->unit_preference;
        if ($measurement == 'imperial'){
            $Height = esc_html((int)($userEntry->height / 12)) . "' ". esc_html($userEntry->height % 12) . '"';
            $Weight = esc_html($userEntry->weight)." lbs";
        }
        else{
            $Height = esc_html(round(2.54*$userEntry->height)) . " cm";
            $Weight = esc_html(round(0.4536*$userEntry->weight))." kg";
        }
        return "User Height: ".$Height.
            "<br><br>User Weight: ".$Weight.
            "<br><br>User Experience: ".esc_html($userEntry->skiAbility);
    }
    return '';
}
add_shortcode('user_info', 'display_user_info');

//https://developer.wordpress.org/reference/hooks/template_redirect/

function disable_BCR_redirects(){
    if( \Elementor\Plugin::$instance->preview->is_preview_mode() ){
        remove_action( 'template_redirect', 'summit_redirects', 10);
    }
}

add_action( 'template_redirect', 'disable_BCR_redirects', 5);

function summit_redirects() {
    if (is_page('Community Reviews Validation') and is_user_logged_in()){
        //redirects away from login page if already logged in
        wp_redirect(home_url( '/community-reviews-homepage/' ));
        die;
    }
    // for any other pages that need this redirect, just add page name to array

    if ( is_page(array('Backpack Review', 'Community Reviews Profile', 'Ski Review', 'Apparel Review',
        'Ski Boot Review', 'Skiing Know Thyself', 'Climbing Skins Review', 'Snowboard Review', 'Backpack Review'))){

       session_start();
        // Set the previous URL session variable
        global $wp;

        $_SESSION['prev_url'] = home_url( $wp->request );


        
        if (!is_user_logged_in()){
            //redirects to Blister Login
            wp_redirect(home_url('/community-reviews-validation/'));
            die;
            //exit;
        }
        $userEntry = get_bcr_user();
        if (!$userEntry) {
            wp_redirect( home_url('/profile-information-form/') );
            die;
            //exit;
        }
    }
}

add_action( 'template_redirect', 'summit_redirects', 10);

function wpse_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'reviewStyling', $plugin_url . 'reviewStyling.css' );
}
add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );

// WRITING KNOW THY SELF FORM TO KNOWTHYSELF TABLE. THIS CAN BE USED ONLY FOR REFERENCE.

    function capstone_write_to_table($record, $ajax_handler) {
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

    add_action( 'elementor_pro/forms/new_record', 'capstone_write_to_table', 10, 2);
?>
