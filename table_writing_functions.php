<?php
//require 'table_utils.php';

function summit_review_from_sub( $record, $ajax_handler ) {
    $form_name = $record->get_form_settings( 'form_name' ); // this may be a way to get the form id!!!!!!
    $str = 'here---------';
    echo '<script>console.log("PHP error: ' . $str . '")</script>';
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt'; 
    $myfile = fopen($file_path, "a") or die('fopen failed');
    if($form_name == 'Summit Review Form'){
        $answer_ids = summit_insert_into_answer_table($record, $myfile);
        fwrite($myfile, "write to answer table ids:\n") or die('fwrite 1 failed');
        $res1 = implode(", ", $answer_ids) . "\n";
        fwrite($myfile, $res1) or die('fwrite 2 failed');
        //$ajax_handler->add_response_data( true, $output );
        fwrite($myfile, "write to review table id:\n") or die('fwrite 3 failed');
        $id = summit_insert_into_review_table($myfile);
        fwrite($myfile, strval($id)."\n") or die('fwrite 2 failed');
        //$ajax_handler->add_response_data( true, $output );
        //summit_insert_into_review_answer_table($id, $answer_ids)
        $ajax_handler->add_response_data( true, "this is  another test" );
        fwrite($myfile, "\n\n-------------------------------------------\n\n") or die('fwrite 1 failed');
        fclose($myfile);
    }
}

function summit_insert_into_answer_table($record,$file){
    global $wpdb;
    $answer_table = $wpdb->prefix . "bcr_answers";
    $answer_ids = []; //used for when inserting into reviews answers
    //$question_ids = [];//manually entered into elementor form, until we can make a form dynamically 
    $raw_fields = $record->get( 'fields' );
    $output = [];
    foreach ( $raw_fields as $id => $field ) {
        if($id != "step"){
            $fields_answers = [];
            $fields_answers['questionID'] = $id;
            $fields_answers['answerContent'] = $field['value'];
            $output['success'] = $wpdb->insert($answer_table, $fields_answers);
            //if($output == success){

            //}
            $last_answer_id = $wpdb->insert_id;//answer id generated upon entering into table
                                                //!!!!!!may be susceptible to collisions with mulitple users
            array_push($answer_ids, $last_answer_id);
        }
    }
    //$ajax_handler->add_response_data( true, $output );
    return $answer_ids;
}

function summit_insert_into_review_table($file){
       //insert review 
        //echo "in review table functions!<br>";
        global $wpdb;
        $output = [];
        $review_table = $wpdb->prefix . "bcr_reviews";
        $fields_review = [];
        $fields_review['userID'] = 1;//retrieve userID ()
        $fields_review['knowThyselfID'] = 1;//retrieve Knowhyself id using userid
        $fields_review['reviewFormID'] = 1;//some how get review form id upon submission, could first step id in form
        $output['success'] = $wpdb->insert($review_table, $fields_review);
        $str = "number of rows inserted: ".strvl($output['success'])." ||| did not work if false\n";
        fwrite($file, $str);
        //echo strval(output['success'])."<br>";
        //$ajax_handler->add_response_data( true, $output );
        $last_review_id = $wpdb->insert_id;
        return $last_review_id;
}

function summit_insert_into_review_answer_table($review_id, $answer_ids){
    //insert answer ids int review answer table
        global $wpdb;
        $review_answer_table = $wpdb->prefix . "bcr_reviews_answers";
        $fields_review_answers = [];
        foreach($answer_ids as $id){
            $fields_review_answers['reviewID'] = $review_id;
            $fields_review_answers['answerID'] = $id;
            $output['success'] = $wpdb->insert($answer_table, $fields_answers);
            //if($output == success){

            //}
        }
}
?>