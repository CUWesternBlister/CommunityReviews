<?php
//----------------------------------------------------------------------FOR TESTING ONLY REMOVE FOR SUMMIT FINAL VERSION!!!------------------------------------------------------------------------

/**
 * Run all uninstall functions
 * 
 * @return void
 */
function bcr_uninstall() {
    //enter functions in here for deactivation
}

/**
 * Drop all custom tables from database 
 * 
 * @return void
 */
function bcr_remove_tables() {
    global $wpdb;

    //Remove Review Answer association table
    $reviews_answers_table_name = $wpdb->prefix . "bcr_reviews_answers";
    $sql = "DROP TABLE IF EXISTS $reviews_answers_table_name";
    $wpdb->query($sql);

    //Remove Review table
    $reviews_table_name = $wpdb->prefix . "bcr_reviews";
    $sql = "DROP TABLE IF EXISTS $reviews_table_name";
    $wpdb->query($sql);

    //Remove Review Form table
    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";
    $sql = "DROP TABLE IF EXISTS $review_forms_table_name";
    $wpdb->query($sql);

    //Remove Know Thyself table
    $know_thyself_table_name = $wpdb->prefix . "bcr_know_thyself";
    $sql = "DROP TABLE IF EXISTS $know_thyself_table_name";
    $wpdb->query($sql);

    //Remove Product table
    $products_table_name = $wpdb->prefix . "bcr_products";
    $sql = "DROP TABLE IF EXISTS $products_table_name";
    $wpdb->query($sql);

     //Remove Brands table
     $brands_table_name = $wpdb->prefix . "bcr_brands";
     $sql = "DROP TABLE IF EXISTS $brands_table_name";
     $wpdb->query($sql);

    //Remove Category table
    $categories_table_name = $wpdb->prefix . "bcr_categories";
    $sql = "DROP TABLE IF EXISTS $categories_table_name";
    $wpdb->query($sql);

    //Remove Answer table
    $answers_table_name = $wpdb->prefix . "bcr_answers";
    $sql = "DROP TABLE IF EXISTS $answers_table_name";
    $wpdb->query($sql);
    
    //Remove Question table
    $questions_table_name = $wpdb->prefix . "bcr_questions";
    $sql = "DROP TABLE IF EXISTS $questions_table_name";
    $wpdb->query($sql);

    //Remove User table
    $user_table_name = $wpdb->prefix . "bcr_users";
    $sql = "DROP TABLE IF EXISTS $user_table_name";
    $wpdb->query($sql);
}

//Execute uninstall
bcr_uninstall();
?>