<?php
/**
 * Run all activation functions
 * 
 * @return void
 */
function bcr_activate() {
    //bcr_setup_tables();
    require_once( plugin_dir_path( __FILE__ ) . 'uploadInitialData.php');
    require_once( plugin_dir_path( __FILE__ ) . 'align_form_ids.php');
}

/**
 * Create the necessary mysql tables for storing Community Reviews
 * 
 * @return void
 */
function bcr_setup_tables() {
    global $wpdb;

    //ensure that the required dbDelta function is loaded
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $charset_collate = $wpdb->get_charset_collate();

    //Create User table
    $user_table_name = $wpdb->prefix . "bcr_users";

    $sql = "CREATE TABLE $user_table_name (
        userID int(9) NOT NULL,
        heightFeet int(9) NOT NULL,
        heightInches int(9) NOT NULL,
        weight int(9) NOT NULL,
        skiAbility varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (userID)
        ) $charset_collate;";

    dbDelta($sql);
    
    //Create Question table
    $questions_table_name = $wpdb->prefix . "bcr_questions";

    $sql = "CREATE TABLE $questions_table_name (
        questionID int(9) NOT NULL AUTO_INCREMENT,
        questionContent varchar(512) DEFAULT '' NOT NULL,
        questionDisplayContent varchar(512) DEFAULT '' NOT NULL,
        questionType varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (questionID)
        ) $charset_collate;";

    dbDelta($sql);
    
    //Create Answer table
    $answers_table_name = $wpdb->prefix . "bcr_answers";

    $sql = "CREATE TABLE $answers_table_name (
        answerID int(9) NOT NULL AUTO_INCREMENT,
        answerContent TEXT(4000) DEFAULT '' NOT NULL,
        questionID int(9) NOT NULL,
        preDefinedAnswer int(1),
        PRIMARY KEY  (answerID),
        FOREIGN KEY  (questionID) REFERENCES $questions_table_name(questionID)
        ) $charset_collate;";

    dbDelta($sql);
<<<<<<< HEAD

=======
/*
    $q_w_a_table_name = $wpdb->prefix . "bcr_questions_with_answers";

    $sql = "CREATE TABLE $q_w_a_table_name (
        answerID int(9) NOT NULL AUTO_INCREMENT,
        answerContent varchar(512) DEFAULT '' NOT NULL,
        questionID int(9) NOT NULL,
        PRIMARY KEY  (answerID),
        FOREIGN KEY  (questionID) REFERENCES $questions_table_name(questionID)
        ) $charset_collate;";

    dbDelta($sql);
*/
    //Create Sport table
    /*$sports_table_name = $wpdb->prefix . "bcr_sports";

    $sql = "CREATE TABLE $sports_table_name (
        sportID int(9) NOT NULL AUTO_INCREMENT,
        sportName varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (sportID)
        ) $charset_collate;";

    dbDelta($sql);*/
    
>>>>>>> code_review_updates
    //Create Category table
    $categories_table_name = $wpdb->prefix . "bcr_categories";

    $sql = "CREATE TABLE $categories_table_name (
        categoryID int(9) NOT NULL AUTO_INCREMENT,
        parentID int(9) DEFAULT NULL,
        categoryName varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (categoryID)
        ) $charset_collate;";

    dbDelta($sql);
    
    //Create Brands table
    $brands_table_name = $wpdb->prefix . "bcr_brands";

    $sql = "CREATE TABLE $brands_table_name (
        brandID int(9) NOT NULL AUTO_INCREMENT,
        brandName varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (brandID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Product table
    $products_table_name = $wpdb->prefix . "bcr_products";

    $sql = "CREATE TABLE $products_table_name (
        productID int(9) NOT NULL AUTO_INCREMENT,
        categoryID int(9) NOT NULL,
        brandID int(9) NOT NULL,
        productName varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (productID),
        FOREIGN KEY  (categoryID) REFERENCES $categories_table_name(categoryID),
        FOREIGN KEY  (brandID) REFERENCES $brands_table_name(brandID)
        ) $charset_collate;";

    dbDelta($sql);
<<<<<<< HEAD
=======
    
    //Create Know Thyself Form table
    /*$know_thyself_forms_table_name = $wpdb->prefix . "bcr_know_thyself_forms";

    $sql = "CREATE TABLE $know_thyself_forms_table_name (
        knowThyselfFormID int(9) NOT NULL AUTO_INCREMENT,
        knowThyselfFormName varchar(512) DEFAULT '' NOT NULL,
        sportID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfFormID),
        FOREIGN KEY  (sportID) REFERENCES $sports_table_name(sportID)
        ) $charset_collate;";

    dbDelta($sql);*/

    //Create Know Thyself Form Questions association table
    /*$know_thyself_forms_questions_table_name = $wpdb->prefix . "bcr_know_thyself_forms_questions";

    $sql = "CREATE TABLE $know_thyself_forms_questions_table_name (
        knowThyselfFormID int(9) NOT NULL,
        questionID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfFormID, questionID),
        FOREIGN KEY  (knowThyselfFormID) REFERENCES $know_thyself_forms_table_name(knowThyselfFormID),
        FOREIGN KEY  (questionID) REFERENCES $questions_table_name(questionID)
        ) $charset_collate;";

    dbDelta($sql);*/

    //Create Know Thyself table 
    /*$know_thyself_table_name = $wpdb->prefix . "bcr_know_thyself";

    $sql = "CREATE TABLE $know_thyself_table_name (
        knowThyselfID int(9) NOT NULL AUTO_INCREMENT,
        userID int(9) NOT NULL,
        knowThyselfFormID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfID),
        FOREIGN KEY  (knowThyselfFormID) REFERENCES $know_thyself_forms_table_name(knowThyselfFormID)
        ) $charset_collate;";

    dbDelta($sql);*/

    //Create Know Thyself Answers association table
    /*know_thyself_answers_table_name = $wpdb->prefix . "bcr_know_thyself_answers";

    $sql = "CREATE TABLE $know_thyself_answers_table_name (
        knowThyselfID int(9) NOT NULL,
        answerID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfID, answerID),
        FOREIGN KEY  (knowThyselfID) REFERENCES $know_thyself_table_name(knowThyselfID),
        FOREIGN KEY  (answerID) REFERENCES $answers_table_name(answerID)
        ) $charset_collate;";

    dbDelta($sql);*/
>>>>>>> code_review_updates

    //Create Review Forms table
    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";

    $sql = "CREATE TABLE $review_forms_table_name (
        reviewFormID int(9) NOT NULL AUTO_INCREMENT,
        reviewFormName varchar(512) DEFAULT '' NOT NULL,
        categoryID int(9) NOT NULL,
        PRIMARY KEY  (reviewFormID),
        FOREIGN KEY  (categoryID) REFERENCES $categories_table_name(categoryID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Review table
    $reviews_table_name = $wpdb->prefix . "bcr_reviews";

    $sql = "CREATE TABLE $reviews_table_name (
        reviewID int(9) NOT NULL AUTO_INCREMENT,
        userID int(9) NOT NULL,
        reviewFormID int(9) NOT NULL,
        isShown BOOLEAN NOT NULL DEFAULT 1,
        PRIMARY KEY  (reviewID),
        FOREIGN KEY  (reviewFormID) REFERENCES $review_forms_table_name(reviewFormID),
        FOREIGN KEY  (userID) REFERENCES $user_table_name(userID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Review Answers association table
    $review_answers_table_name = $wpdb->prefix . "bcr_reviews_answers";

    $sql = "CREATE TABLE $review_answers_table_name (
        reviewID int(9) NOT NULL,
        answerID int(9) NOT NULL,
        PRIMARY KEY  (reviewID, answerID),
        FOREIGN KEY  (reviewID) REFERENCES $reviews_table_name(reviewID),
        FOREIGN KEY  (answerID) REFERENCES $answers_table_name(answerID)
        ) $charset_collate;";

    dbDelta($sql);
}

//Execute activation
bcr_activate();
?>
