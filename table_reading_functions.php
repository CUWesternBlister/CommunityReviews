<?php
/* 
* all of these functions read form bcr tables
*
*
*
*/

function get_question_read_content($q_id){
    global $wpdb;
    $question_table = $wpdb->prefix . "bcr_questions";
    $q = "SELECT questionDisplayContent, questionType FROM $question_table WHERE questionID = $q_id;";
    $q_content = $wpdb->get_row($q);
    return $q_content;
}

//another function that gets brand info and will change the custom post
function get_brand_info($brand_name, $file){
    global $wpdb;
    $brand_table = $wpdb->prefix . "bcr_brands";
    $q = "SELECT * FROM $brand_table WHERE brandName = '".$brand_name."';";
    $res = $wpdb->get_row($q);
    return $res;
}

function get_category_info($form_id, $file){//may just want to return res !!!!!!
    global $wpdb;
    $form_table = $wpdb->prefix . "bcr_review_forms";
    $q1 = "SELECT * FROM $form_table WHERE reviewFormID = $form_id;";
    $res1 = $wpdb->get_row($q1);
    $category_id = $res1->categoryID;
    $category_table = $wpdb->prefix . "bcr_categories";
    $q = "SELECT * FROM $category_table WHERE categoryID = $category_id;";
    $res = $wpdb->get_row($q);
    return $res;
}

/*this may be removed from the structure */
function get_sport_info($category_name){ //may just want to return res !!!!!!
    global $wpdb;
    $cate_table_name = $wpdb->prefix . "bcr_categories";
    $q = $wpdb->prepare("SELECT * FROM $cate_table_name WHERE categoryName = %s;", $category_name);
    $res = $wpdb->get_row($q);
    $parent_id = $res->parentID;
    if($parent_id != 0){
        $q = $wpdb->prepare("SELECT * FROM $cate_table_name WHERE categoryID = %s;", $parent_id);
        $res = $wpdb->get_row($q);
    }
    return $res;
}

function get_user_information($file){
    global $wpdb;
    $userID = get_current_userID($file);
    $user_table_name = $wpdb->prefix . "bcr_users";
    $queryString = $wpdb->prepare("SELECT * FROM $user_table_name WHERE userID=%s", $userID);
    $userInformation = $wpdb->get_row($queryString);
    return $userInformation;
}


function get_flagged_reviews(){
    global $wpdb;
    //echo "get_flagged_reviews<br>";
    $review_table_name = $wpdb->prefix . "bcr_reviews";
    $sql = $wpdb->prepare("SELECT * FROM $review_table_name WHERE FlaggedForReview=1");
    $flagged_reviews = $wpdb->get_results($sql);
    return $flagged_reviews;
}


function get_brand_id($brand_name){
    global $wpdb;
    $brand_table = $wpdb->prefix . "bcr_brands";
    $q_brand = $wpdb->prepare("SELECT * FROM $brand_table WHERE brandName = %s;", $brand_name);
    $res_brand = $wpdb->get_row($q_brand);
    $brand_id = -1;
    if($res_brand){
        $brand_id = $res_brand->brandID;
    }
    return $brand_id;
}

function get_category_id($category_name){
    global $wpdb;
    $category_table = $wpdb->prefix . "bcr_categories";
    $q_category = $wpdb->prepare("SELECT * FROM $category_table WHERE categoryName = %s;", $category_name);
    $res_category = $wpdb->get_row($q_category);
    $category_id = -1;
    if($res_category){
        $category_id = $res_category->categoryID;
    }
    return $category_id;
}
?>