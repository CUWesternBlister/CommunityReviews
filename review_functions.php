<?php
function bcr_change_height_units() {
    $post_id = intval( sanitize_text_field( $_POST['post_id'] ) );

    $height_in_inches = get_post_meta($post_id, 'height')[0];

    $feet = floor($height_in_inches/12);
    $inches = $height_in_inches % 12;

    $display_height = strval($feet) . "' " . strval($inches) . '"';

    if(is_user_logged_in()) {
        if ( function_exists( 'get_current_user_id' ) ) {
            $uid = get_current_user_id();

            global $wpdb;

            $user_table_name = $wpdb->prefix . "bcr_users";

            $sql = $wpdb->prepare("SELECT unit_preference FROM $user_table_name WHERE userID = %s;", $uid);

            $unit_preference = $wpdb->get_var($sql, 0, 0);

            if($unit_preference == "metric") {
                $height_cm = floor($height_in_inches * 2.54);

                $display_height = strval($height_cm) . " cm";
            }
        }
    }

    echo "Height: " . esc_html($display_height);

    wp_die();
}

add_action( 'wp_ajax_bcr_change_height_units', 'bcr_change_height_units' );
add_action( 'wp_ajax_nopriv_bcr_bcr_change_height_units', 'bcr_change_height_units' );

function bcr_change_weight_units() {
    $post_id = intval( sanitize_text_field( $_POST['post_id'] ) );

    $weight_in_pounds = get_post_meta($post_id, 'weight')[0];

    $display_height = strval($weight_in_pounds) . " lbs";

    if(is_user_logged_in()) {
        if ( function_exists( 'get_current_user_id' ) ) {
            $uid = get_current_user_id();

            global $wpdb;

            $user_table_name = $wpdb->prefix . "bcr_users";

            $sql = $wpdb->prepare("SELECT unit_preference FROM $user_table_name WHERE userID = %s;", $uid);

            $unit_preference = $wpdb->get_var($sql, 0, 0);

            if($unit_preference == "metric") {
                $weight_kg = floor($weight_in_pounds * 0.453592);

                $display_height = strval($weight_kg) . " kg";
            }
        }
    }

    echo "Weight: " . esc_html($display_height);

    wp_die();
}

add_action( 'wp_ajax_bcr_change_weight_units', 'bcr_change_weight_units' );
add_action( 'wp_ajax_nopriv_bcr_bcr_change_weight_units', 'bcr_change_weight_units' );

function bcr_enqueue_review_scripts() {       
	wp_enqueue_script( 'bcr_review_modifiers', plugin_dir_url( __FILE__ ) . 'review_scripts.js', array('jquery'), 1.1, true );

	wp_localize_script( 'bcr_review_modifiers', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'bcr_enqueue_review_scripts' );
?>