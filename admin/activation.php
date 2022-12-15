<?php
/**
 * Run all activation functions
 * 
 * @return void
 */
function bcr_activate() {
    bcr_setup_tables();
    bcr_create_test_requirements();
}

/**
 * Create the necessary mysql tables for storing Community Reviews
 * 
 * @return void
 */
function bcr_setup_tables() {
    global $wpdb;

    //ensure that the required function is loaded
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $charset_collate = $wpdb->get_charset_collate();

    //Create User table
    $questions_table_name = $wpdb->prefix . "bcr_users";

    $sql = "CREATE TABLE $questions_table_name (
        userID int(9) NOT NULL AUTO_INCREMENT,
        heightFeet int(9) DEFAULT '' NOT NULL,
        heightInches int(9) DEFAULT '' NOT NULL,
        weight int(9) DEFAULT '' NOT NULL,
        skiAbility varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (userID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Question table
    $questions_table_name = $wpdb->prefix . "bcr_questions";

    $sql = "CREATE TABLE $questions_table_name (
        questionID int(9) NOT NULL AUTO_INCREMENT,
        questionContent varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (questionID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Answer table
    $answers_table_name = $wpdb->prefix . "bcr_answers";

    $sql = "CREATE TABLE $answers_table_name (
        answerID int(9) NOT NULL AUTO_INCREMENT,
        answerContent varchar(512) DEFAULT '' NOT NULL,
        questionID int(9) NOT NULL,
        PRIMARY KEY  (answerID),
        FOREIGN KEY  (questionID) REFERENCES $questions_table_name(questionID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Sport table
    $sports_table_name = $wpdb->prefix . "bcr_sports";

    $sql = "CREATE TABLE $sports_table_name (
        sportID int(9) NOT NULL AUTO_INCREMENT,
        sportName varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (sportID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Category table
    $categories_table_name = $wpdb->prefix . "bcr_categories";

    $sql = "CREATE TABLE $categories_table_name (
        categoryID int(9) NOT NULL AUTO_INCREMENT,
        parentID int(9) DEFAULT NULL,
        sportID int(9) NOT NULL,
        categoryName varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (categoryID),
        FOREIGN KEY  (sportID) REFERENCES $sports_table_name(sportID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Product table
    $products_table_name = $wpdb->prefix . "bcr_products";

    $sql = "CREATE TABLE $products_table_name (
        productID int(9) NOT NULL AUTO_INCREMENT,
        categoryID int(9) NOT NULL,
        productName varchar(512) DEFAULT '' NOT NULL,
        PRIMARY KEY  (productID),
        FOREIGN KEY  (categoryID) REFERENCES $categories_table_name(categoryID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Know Thyself Form table
    $know_thyself_forms_table_name = $wpdb->prefix . "bcr_know_thyself_forms";

    $sql = "CREATE TABLE $know_thyself_forms_table_name (
        knowThyselfFormID int(9) NOT NULL AUTO_INCREMENT,
        knowThyselfFormName varchar(512) DEFAULT '' NOT NULL,
        sportID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfFormID),
        FOREIGN KEY  (sportID) REFERENCES $sports_table_name(sportID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Know Thyself Form Questions association table
    $know_thyself_forms_questions_table_name = $wpdb->prefix . "bcr_know_thyself_forms_questions";

    $sql = "CREATE TABLE $know_thyself_forms_questions_table_name (
        knowThyselfFormID int(9) NOT NULL,
        questionID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfFormID, questionID),
        FOREIGN KEY  (knowThyselfFormID) REFERENCES $know_thyself_forms_table_name(knowThyselfFormID),
        FOREIGN KEY  (questionID) REFERENCES $questions_table_name(questionID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Know Thyself table 
    $know_thyself_table_name = $wpdb->prefix . "bcr_know_thyself";

    $sql = "CREATE TABLE $know_thyself_table_name (
        knowThyselfID int(9) NOT NULL AUTO_INCREMENT,
        userID int(9) NOT NULL,
        knowThyselfFormID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfID),
        FOREIGN KEY  (knowThyselfFormID) REFERENCES $know_thyself_forms_table_name(knowThyselfFormID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Know Thyself Answers association table
    $know_thyself_answers_table_name = $wpdb->prefix . "bcr_know_thyself_answers";

    $sql = "CREATE TABLE $know_thyself_answers_table_name (
        knowThyselfID int(9) NOT NULL,
        answerID int(9) NOT NULL,
        PRIMARY KEY  (knowThyselfID, answerID),
        FOREIGN KEY  (knowThyselfID) REFERENCES $know_thyself_table_name(knowThyselfID),
        FOREIGN KEY  (answerID) REFERENCES $answers_table_name(answerID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Review Forms table
    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";

    $sql = "CREATE TABLE $review_forms_table_name (
        reviewFormID int(9) NOT NULL AUTO_INCREMENT,
        reviewFormName varchar(512) DEFAULT '' NOT NULL,
        productID int(9) NOT NULL,
        PRIMARY KEY  (reviewFormID),
        FOREIGN KEY  (productID) REFERENCES $products_table_name(productID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Review Form Questions association table
    $review_forms_questions_table_name = $wpdb->prefix . "bcr_review_forms_questions";

    $sql = "CREATE TABLE $review_forms_questions_table_name (
        reviewFormID int(9) NOT NULL,
        questionID int(9) NOT NULL,
        PRIMARY KEY  (reviewFormID, questionID),
        FOREIGN KEY  (reviewFormID) REFERENCES $review_forms_table_name(reviewFormID),
        FOREIGN KEY  (questionID) REFERENCES $questions_table_name(questionID)
        ) $charset_collate;";

    dbDelta($sql);

    //Create Review table
    $reviews_table_name = $wpdb->prefix . "bcr_reviews";

    $sql = "CREATE TABLE $reviews_table_name (
        reviewID int(9) NOT NULL AUTO_INCREMENT,
        userID int(9) NOT NULL,
        knowThyselfID int(9) NOT NULL,
        reviewFormID int(9) NOT NULL,
        PRIMARY KEY  (reviewID),
        FOREIGN KEY  (knowThyselfID) REFERENCES $know_thyself_table_name(knowThyselfID),
        FOREIGN KEY  (reviewFormID) REFERENCES $review_forms_table_name(reviewFormID)
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

/**
 * Create infrastructure for test reviews
 */
function bcr_create_test_requirements() {
    global $wpdb;

    $sports_table_name = $wpdb->prefix . "bcr_sports";
    $categories_table_name = $wpdb->prefix . "bcr_categories";
    $products_table_name = $wpdb->prefix . "bcr_products";
    $know_thyself_forms_table_name = $wpdb->prefix . "bcr_know_thyself_forms";
    $review_forms_table_name = $wpdb->prefix . "bcr_review_forms";
    $questions_table_name = $wpdb->prefix . "bcr_questions";
    $know_thyself_forms_questions_table_name = $wpdb->prefix . "bcr_know_thyself_forms_questions";
    $review_forms_questions_table_name = $wpdb->prefix . "bcr_review_forms_questions";

    $wpdb->insert($sports_table_name, array('sportName' => "Test Sport"));
    $wpdb->insert($categories_table_name, array('sportID' => 1, 'categoryName' => "Test Category"));
    $wpdb->insert($products_table_name, array('categoryID' => 1, 'productName' => "Test Product"));
    $wpdb->insert($know_thyself_forms_table_name, array('knowThyselfFormName' => 'Test Sport Know Thyself', 'sportID' => 1));
    $wpdb->insert($review_forms_table_name, array('reviewFormName' => "Test Product Review", 'productID' => 1));
    $wpdb->insert($questions_table_name, array('questionContent' => "How good are you at Test Sport?"));
    $wpdb->insert($know_thyself_forms_questions_table_name, array('knowThyselfFormID' => 1, 'questionID' => 1));
    $wpdb->insert($questions_table_name, array('questionContent' => "How do you like Test Product?"));
    $wpdb->insert($questions_table_name, array('questionContent' => "Would you recommend Test Product?"));
    $wpdb->insert($questions_table_name, array('questionContent' => "Any final thoughts?"));
    $wpdb->insert($review_forms_questions_table_name, array('reviewFormID' => 1, 'questionID' => 2));
    $wpdb->insert($review_forms_questions_table_name, array('reviewFormID' => 1, 'questionID' => 3));
    $wpdb->insert($review_forms_questions_table_name, array('reviewFormID' => 1, 'questionID' => 4));
}

//Execute activation
bcr_activate();
?>