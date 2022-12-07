<?php
require 'table_utils.php';

function summit_review_from_sub( $record, $ajax_handler ) {
    $form_name = $record->get_form_settings( 'form_name' );
    if($form_name == 'Summit Review Form'){
        global $wpdb;
        $answer_table = $wpdb->prefix . "bcr_answers";
        $answer_ids = []; //used for when inserting into reviews answers
        //$question_ids = [];//manually entered into elementor form, until we can make a form dynamically 
        $raw_fields = $record->get( 'fields' );
        $fields_answers = [];
        $output = [];
        foreach ( $raw_fields as $id => $field ) {
            if($id != "step"){
                $fields_answers['questionID'] = $id;
                $fields_answers['answerContent'] = $field['value'];
                $output['success'] = wpdb->insert($answer_table, $fields_answers);
                //if($output == success){

                //}
                $last_answer_id = $wpdb->insert_id;//answer id generated upon entering into table
                                                    //!!!!!!may be susceptible to collisions with mulitple users
                array_push($answer_ids, $last_answer_id);
            }
        }

        //$id = summit_insert_into_review_table
        //summit_insert_into_review_answer_table($id)

        $ajax_handler->add_response_data( true, $output );
    }
}
function summit_insert_into_review_table($id){
       //insert review 
        $review_table = $wpdb->prefix . "bcr_reviews";
        $fields_review = [];
        $fields_review['userID'] = 1;//retrieve userID ()
        $fields_review['knowThyselfID'] = 1;//retrieve Knowhyself id using userid
        $fields_review['reviewFromID'] = 1;//some how get review form id upon submission, could first step id in form
        $output['success'] = wpdb->insert($review_table, $fields_review);
        $last_review_id = $wpdb->insert_id;
        return $last_review_id;
}

function summit_insert_into_review_answer_table($id){
    //insert answer ids int review answer table
        $review_answer_table = $wpdb->prefix . "bcr_reviews_answers";
        $fields_review_answers = [];
        foreach($answer_ids as $id){
            $fields_review_answers['reviewID'] = $last_review_id;
            $fields_review_answers['answerID'] = $id;
            $output['success'] = wpdb->insert($answer_table, $fields_answers);
            //if($output == success){

            //}
        }
}
?>