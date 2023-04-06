<?php
/**
 * Run all activation functions
 * 
 * @return void
 */
function bcr_activate() {
    bcr_setup_tables();
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
        unit_preference varchar(512) DEFAULT 'imperial' NOT NULL,
        height int(9) NOT NULL,
        weight int(9) NOT NULL,
        skiAbility varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (userID)
        ) $charset_collate;";
    dbDelta($sql);

    $q = $wpdb->prepare("SELECT heightFeet FROM $user_table_name");
    $res = $wpdb->query($q);
    if ($res){
        $sql = "UPDATE $user_table_name SET height = (12*heightFeet + heightInches) WHERE height = 0;";
        $wpdb->query($sql);
        $sql = "ALTER TABLE $user_table_name 
        DROP COLUMN heightFeet,
        DROP COLUMN heightInches;";
        $wpdb->query($sql);
    }

    
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
