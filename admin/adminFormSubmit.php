<?php
    add_action('wp_ajax_bcr_admin_form_response', 'bcr_admin_form_submit');

    /**
     * Update tables acording to parameters set in the admin form
     */
    function bcr_admin_form_submit() {
        global $wpdb;

        $reviews_table_name = $wpdb->prefix . "bcr_reviews";

        foreach($_POST as $key => $value) {
            if($value == "false") {
                $isShown_value = "1";
            } else if($value == "true") {
                $isShown_value = "0";
            } else {
                continue;
            }
            $wpdb->update($reviews_table_name, array("isShown" => $isShown_value), array("reviewID" => $key));
        }
    }
?>