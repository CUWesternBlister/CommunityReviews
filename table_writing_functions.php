<?php
/*
*All function is this file write to the tables of bcr reviews
*
*
*
*/

function insert_into_answer_table($record,$file){
    global $wpdb;
    //$start = "\n\n SUMMIT INSERT INTO ANSWER TABLE \n";
    //fwrite($file, $start);
    $answer_table = $wpdb->prefix . "bcr_answers";
    $answer_ids = [];
    $output = [];
    foreach($record as $id => $field ){
        $fields_answers = [];
        $fields_answers['questionID'] = $id;
        $fields_answers['answerContent'] = $field;
        $output['success'] = $wpdb->insert($answer_table, $fields_answers);
        //if($output == success){
        //}
        $last_answer_id = $wpdb->insert_id;
        array_push($answer_ids, $last_answer_id);
    }
    //$ajax_handler->add_response_data( true, $output );
    return $answer_ids;
}

function insert_into_review_table($RF_id, $product_name, $brand_name, $file){
       //insert review 
        //echo "in review table functions!<br>";
        global $wpdb;
        //$start = "\n\nSUMMIT INSERT INTO REVIEW TABLE \n";
        //fwrite($file, $start);
        $output2 = [];
        $review_table = $wpdb->prefix . "bcr_reviews";
        $fields_review = [];

        $current_userID = get_current_userID($file);
        if(gettype($current_userID) == "string"){
            die("user not found"); //should be a redirct to another page
        }
        //echo "insert_into_review_table() with Falgged_for_review <br>";
        if((!check_for_brand($brand_name)) || (!check_for_product($product_name))){
            $fields_review['FlaggedForReview'] = 1;
            echo "review flagged<br>";
        }

        $fields_review['userID'] = $current_userID;
        
        $fields_review['reviewFormID'] = $RF_id;

        
        $output2['success'] = $wpdb->insert($review_table, $fields_review);
        $last_review_id = $wpdb->insert_id; 
        return $last_review_id;
}

/*
function get_knowthyself_id($userID){
    global $wpdb;
    $KTS_table = $wpdb->prefix . "bcr_know_thyself";
    $q = "SELECT * FROM $KTS_table WHERE %s = %d;";
    $res = $wpdb->get_results($q);
    return $res->knowThyselfID;
}
*/

function insert_into_review_answer_table($review_id, $answer_ids,$file){
    //insert answer ids int review answer table
        global $wpdb;
        //$start = "\n\nSUMMIT INSERT INTO REVIEW ANSWER TABLE \n";
        //fwrite($file, $start);
        $review_answer_table = $wpdb->prefix . "bcr_reviews_answers";
        $fields_review_answers = [];
        $output=[];
        foreach($answer_ids as $id){
            $fields_review_answers['reviewID'] = $review_id;
            $fields_review_answers['answerID'] = $id;
            $output['success'] = $wpdb->insert($review_answer_table, $fields_review_answers);
            //if($output == success){
            //}
        }
}

function insert_brand($brand_name){
    global $wpdb;
    $brand_table = $wpdb->prefix . "bcr_brands";
    $success = $wpdb->insert($brand_table, array('brandName'=>$brand_name));
    if(!$success){return $success;} 
    return $wpdb->insert_id;
}
function insert_product($product_name, $brand_id, $category_id){
    global $wpdb;
    $products_table = $wpdb->prefix . "bcr_products";
    $success = $wpdb->insert($products_table, array('categoryID'=>$category_id, 'brandID'=>$brand_id, 'productName'=>$product_name));
    if(!$success){return $success;} 
    return $wpdb->insert_id;
}
?>