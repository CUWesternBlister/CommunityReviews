<?php
/*
*All function is this file write to the tables of bcr reviews
*
*
*
*/

function insert_into_answer_table($record,$file){
    global $wpdb;
    $answer_table = $wpdb->prefix . "bcr_answers";
    $answer_ids = [];
    $output = [];
    foreach($record as $id => $field ){
        $fields_answers = [];
        $fields_answers['questionID'] = $id;
        $fields_answers['answerContent'] = $field;
        $output['success'] = $wpdb->insert($answer_table, $fields_answers);
        $last_answer_id = $wpdb->insert_id;
        array_push($answer_ids, $last_answer_id);
    }
    return $answer_ids;
}

function insert_into_review_table($RF_id, $file){
       //insert review 
        global $wpdb;
        $output2 = [];
        $review_table = $wpdb->prefix . "bcr_reviews";
        $fields_review = [];

        $current_userID = get_current_userID($file);
        if(gettype($current_userID)=="string"){
            die("user not found"); //should be a redirct to another page
        }
        $fields_review['userID'] = $current_userID;
        
        $fields_review['reviewFormID'] = $RF_id;

        
        $output2['success'] = $wpdb->insert($review_table, $fields_review);
        $last_review_id = $wpdb->insert_id; 
        return $last_review_id;
}

function insert_into_review_answer_table($review_id, $answer_ids,$file){
    //insert answer ids int review answer table
        global $wpdb;
        $review_answer_table = $wpdb->prefix . "bcr_reviews_answers";
        $fields_review_answers = [];
        $output=[];
        foreach($answer_ids as $id){
            $fields_review_answers['reviewID'] = $review_id;
            $fields_review_answers['answerID'] = $id;
            $output['success'] = $wpdb->insert($review_answer_table, $fields_review_answers);
        }
}
?>