<?php
add_action( 'elementor_pro/forms/new_record', 'elementor_summit_review_from_sub', 10, 2);
add_action( 'elementor_pro/forms/new_record', 'profile_info_sub', 10, 2);
add_action('fluentform_submission_inserted', 'fluent_summit_review_from_sub', 20, 3);

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
        return "Skiing Style: ".esc_html(implode( ', ', $skiingStyle_subs))."<br><br>Confidence in Icy Groomers: ".esc_html(implode(', ', $confidenceIcyGroomer_subs))."<br><br>Confidence in Soft Groomers: ".esc_html(implode(', ', $confidenceSoftGroomer_subs));
    }
    return '';
}
add_shortcode( 'form_submissions', 'get_record_from_form_submissions' );

function display_user_info($atts){
    if(is_user_logged_in()){
        $userEntry = get_bcr_user();//---------------------------------
    }   
    if ( $userEntry ) {
        $heightF = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->heightFeet;
            },
            $userEntry
        );
        $heightI = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->heightInches;
            },
            $userEntry
        );
        $weight = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->weight;
            },
            $userEntry
        );
        $ability = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->skiAbility;
            },
            $userEntry
        );
        return "User Height: ".esc_html(implode( '  ', $heightF)) ."' ".esc_html(implode( '  ', $heightI)) .'"'.
            "<br><br>User Weight: ".esc_html(implode('  ', $weight))." lbs".
            "<br><br>User Experience: ".esc_html(implode('  ', $ability));
    }
    return '';
}
add_shortcode('user_info', 'display_user_info');

function BCR_login_shortcode(){
    if(!is_user_logged_in()){
        $args = array(
          'echo' => 0,
          'redirect' => home_url('/summit-home-page/')
        );
        return wp_login_form( $args ) . '<a href="https://blisterreview.com/my-account" target="_blank">Click here to Register at BlisterReviews.com</a>';
    }
    // you can set where you will be redirected to after form is completed
}

add_shortcode('BCR_login', 'BCR_login_shortcode');

//Testing https://developer.wordpress.org/reference/hooks/template_redirect/

function summit_redirects() {
    if (is_page('Validation Page') and is_user_logged_in()){
        //redirects away from login page if already logged in
        wp_redirect(home_url("summit-home-page"));
        die;
    }
    // for any other pages that need this redirect, just add page name to array
    if ( is_page(array('Backpack Review','Summit Homepage','Community Reviews Profile', 'Ski Review', 'Apparel Review',
        'Ski Boot Review', 'Skiing Know Thyself', 'Climbing Skins Review', 'Snowboard Review', 'Summit Read Reviews Prototype'))){
        
        session_start();
        // Set the previous URL session variable
        if (isset($_SERVER['HTTP_REFERER'])) {
          $_SESSION['prev_url'] = $_SERVER['HTTP_REFERER'];
        }

        if (!is_user_logged_in()){
            //redirects to Blister Login
            wp_redirect(home_url('/validation-page/'));
            die;
            //exit;
        }
        $userEntry = get_bcr_user();
        if (!$userEntry) {
            wp_redirect(home_url('/profile-information-form/'));
            die;
            //exit;
        }
    }
}

add_action( 'template_redirect', 'summit_redirects' );

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

/**
 * Convert the category name to a slug
 * 
 * @param string    categoryName
 * 
 * @return string   slug
 */
function bcr_convert_name_to_slug($categoryName) {
    $categoryName = strtolower($categoryName);
    $categoryName = str_replace(' ', '-', $categoryName);

    return $categoryName;
}
?>
