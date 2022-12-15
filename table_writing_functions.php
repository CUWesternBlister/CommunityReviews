<?php
//require 'table_utils.php';
/*
function summit_review_from_sub( $record, $ajax_handler ) {
    global $wpdb;
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt'; 
    $myfile = fopen($file_path, "a") or die('fopen failed');
    $existing_form_names = get_all_form_names($myfile);
    fwrite($myfile,"form names: ".$existing_form_names."\n");
    $current_form_name = $record->get_form_settings( 'form_name' ); // this may be a way to get the form id!!!!!!
    fwrite($myfile,"current form name: ".$current_form_name."\n");
    $current_form_id = $record->get_form_settings('form_id');
    fwrite($myfile,"current form id: ".$current_form_id."\n"); 
    if(in_array($current_form_name, $existing_form_names)){
        
        $answer_ids = summit_insert_into_answer_table($record, $myfile);
        fwrite($myfile, "write to answer table ids:\n") or die('fwrite 1 failed');
        $res1 = implode(", ", $answer_ids)."\n";
        fwrite($myfile, $res1) or die('fwrite 2 failed');


        //$ajax_handler->add_response_data( true, $output );
        fwrite($myfile, "write to review table id:\n") or die('fwrite 3 failed');
        $id = summit_insert_into_review_table($current_form_id,$myfile);
        fwrite($myfile, "last inserted review id".strval($id)."\n") or die('fwrite 2 failed');
        //$ajax_handler->add_response_data( true, $output );
        

        summit_insert_into_review_answer_table($id, $answer_ids,$myfile);
        //$ajax_handler->add_response_data( true, "this is  another test" );
    }
    fwrite($myfile, "\n\n-------------------------------------------\n\n") or die('fwrite 1 failed');
    fclose($myfile);
}

function get_all_form_names($file){
    global $wpdb;
    $start = "\n\n SUMMIT get all form names \n";
    fwrite($file, $start);
    $review_forms_table = $wpdb->prefix . "bcr_review_forms";
    $q = "SELECT reviewFormName FROM $review_forms_table;";
    //$wpdb->prepare("SELECT %s FROM %s;", array("reviewFormName", $review_table));
    $res = $wpdb->get_results($q);
    $ret_arr = [];
    foreach($res as $r){
        fwrite($file,$r->reviewFormName."\n");
        array_push($ret_arr, $r->reviewFormName);
    }
    return $ret_arr;
}

function summit_insert_into_answer_table($record,$file){
    global $wpdb;
    $start = "\n\n SUMMIT INSERT INTO ANSWER TABLE \n";
    fwrite($file, $start);
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

function summit_insert_into_review_table($RF_id, $file){
       //insert review 
        //echo "in review table functions!<br>";
        global $wpdb;
        $start = "\n\n SUMMIT INSERT INTO REVIEW TABLE \n";
        fwrite($file, $start);
        $output2 = [];
        $review_table = $wpdb->prefix . "bcr_reviews";
        $fields_review = [];



        $current_userID = get_current_userID($file);
        if(gettype($current_userID)=="string"){
            fwrite($file,"user id grab failed: ".$current_userID."\n");
            die("user not found"); //should be a redirct to another page
        }
        fwrite($file,"userID = ".strval($current_userID)."\n");
        $fields_review['userID'] = $current_userID;
        


        //$KTSid = get_knowthyself_id($current_userID);
        $fields_review['knowThyselfID'] = 4;//get this id from bcr_know_thyself using userid HAS TO EXIST BEFORE SUBMISSION
        



        $fields_review['reviewFormID'] = $RF_id;//some how get review form id upon submission, could first step id in form HAS TO EXIST BEFORE SUBMISSION



        fwrite($file, "fields_review: ".implode(", ",$fields_review)."\n");
        $output2['success'] = $wpdb->insert($review_table, $fields_review);
        //$str = "number of rows inserted: ".strval($output['success'])." ||| did not work if false\n";
        //fwrite($file, $str);
        //echo strval(output['success'])."<br>";
        //$ajax_handler->add_response_data( true, $output );
        $last_review_id = $wpdb->insert_id;
        return $last_review_id;
}

function get_current_userID($file){
    global $wpdb;
    $start = "          SUMMIT get user id \n";
    fwrite($file, $start);
    if ( ! function_exists( 'get_current_user_id' ) ) {
        return 0;
    }
    $cur_userID = get_current_user_id();
    $str = "-------- " . strval($cur_userID) . " ----------\n";
    fwrite($file, $str);
    if($cur_userID == 0){
        //then not logged in
        //we should check this field when they click to start a review form.
        return "userID does not exist, or user is not logged in";
    }
    $user_table = $wpdb->prefix . "bcr_users";
    $q = "SELECT 1 userID FROM $user_table WHERE userID = $cur_userID;";
    $res = $wpdb->query($q);
    if($res == false){
        //should not be allowed to start a form untill they are in bcr users
        return "userID does not exist in bcr user table, has not registerd";
    }
    //check if user in wp bcr users
    return intval($cur_userID);
}

function get_knowthyself_id($userID){
    global $wpdb;
    $KTS_table = $wpdb->prefix . "bcr_know_thyself";
    $q = "SELECT * FROM $KTS_table WHERE %s = %d;";/////////start here with SELECT * FROM wp_bcr_know_thyself WHERE userID = 1;
    $res = $wpdb->get_results($q);
    return $res->knowThyselfID;
}

function summit_insert_into_review_answer_table($review_id, $answer_ids,$file){
    //insert answer ids int review answer table
        global $wpdb;
        $start = "\n\n SUMMIT INSERT INTO REVIEW ANSWER TABLE \n";
        fwrite($file, $start);
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
*/
?>