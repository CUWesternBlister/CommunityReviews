<?php
require 'table_utils.php';

function summit_review_from_sub( $record, $ajax_handler ) {
    $form_name = $record->get_form_settings( 'form_name' );
    if($form_name == 'Summit Review Form'){
        global $wpdb;
        $answer_table = $wpdb->prefix . "bcr_answers";
        $review_answer_table = $wpdb->prefix . "bcr_reviews_answers";
        $answer_ids = []; //used for when inserting into reviews answers
        //$question_ids = [];//manually entered into elementor form, until we can make a form dynamically 
        $raw_fields = $record->get( 'fields' );
        $fields_answers = [];
        $output = [];
        foreach ( $raw_fields as $id => $field ) {
            $fields_answers['questionID'] = $id;
            $fields['answerContent'] = $field['value'];
            $$output['success'] = wpdb->insert($answer_table, $fields);
            //if($output == success){

            //}
            $lastid = $wpdb->insert_id;//answer id generated upon entering into table
            array_push($answer_ids, $lastid);
            //array_push($question_ids,$id);
        }
        //$review_table = $wpdb->prefix . "bcr_reviews";
        //$fields_review = [];
        //$fields_review['userID'] = 1;
        //$fields_review['knowThyselfID'] = 1;/////////////////////////////////////////////////////////////////
        //$review_answer_table = $wpdb->prefix . "bcr_reviews_answers";
        //$str_q_ids = array_to_string($question_ids);
        //$str_a_ids = array_to_string($answer_ids);
        //$output['success'] = $wpdb->insert($table_name, $fields);
        $ajax_handler->add_response_data( true, $output );
    }
}
//add_action( 'elementor_pro/forms/new_record', 'summit_review_from_sub', 10, 2);
?>