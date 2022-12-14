<?php
//require 'table_utils.php';
function profile_info_sub( $record, $ajax_handler ){
    global $wpdb;
    $user_table_name = $wpdb->prefix . "bcr_users";
    $form_name = $record->get_form_settings( 'form_name' );
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $myfile = fopen($file_path, "a") or die('fopen failed');
    $fields = [];
    if($form_name == 'Profile Builder') {
        $userID = get_current_userID();
        $fields['userID'] = $userID;
        $raw_fields = $record->get('fields');
        $fields['heightFeet'] = $raw_fields["height_feet"]['value'];
        $fields['heightInches'] = $raw_fields["height_inches"]['value'];
        $fields['weight'] = $raw_fields["weight"]['value'];
        $fields['skiAbility'] = $raw_fields["user_experience"]['value'];

        $q = $wpdb->prepare("SELECT * %s FROM %s WHERE %s = %d;", array("userID", $user_table_name, "userID", $userID));
        $res = $wpdb->query($q);

        if($res){
            fwrite($myfile,"update attempted");
            $output['success'] = $wpdb->update($user_table_name, $fields, array("userID"=>$userID));
        }else {
            $output['success'] = $wpdb->insert($user_table_name, $fields);
        }
        $ajax_handler->add_response_data(true, $output);
    }
    fclose($myfile);
}
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
        fwrite($myfile, strval($id)."\n") or die('fwrite 2 failed');
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
        $current_userID = get_current_userID();
        if(strcmp(gettype($current_userID),"string")){
            fwrite($file,$current_userID."\n");
            die("user not found"); //should be a redirct to another page
        }
        fwrite($file,"userID = ".strval($current_userID)."\n");
        $fields_review['userID'] = $current_userID;
        $KTSid = get_knowthyself_id($current_userID);
        $fields_review['knowThyselfID'] = 4;//get this id from bcr_know_thyself using userid HAS TO EXIST BEFORE SUBMISSION
        $fields_review['reviewFormID'] = $RF_id;//some how get review form id upon submission, could first step id in form HAS TO EXIST BEFORE SUBMISSION
        fwrite($file, "fields_review: ".implode(", ",$fields_review)."\n");
        $output2['success'] = $wpdb->insert($review_table, $fields_review);
        $str = "number of rows inserted: ".strval($output2['success'])." ||| did not work if false\n";
        fwrite($file, $str);
        //echo strval(output['success'])."<br>";
        //$ajax_handler->add_response_data( true, $output );
        $last_review_id = $wpdb->insert_id;
        return $last_review_id;
}

function get_current_userID(){
    global $wpdb;
    if ( ! function_exists( 'get_current_user_id' ) ) {
        return 0;
    }
    $userID = get_current_user_id();
    if($userID == 0){
        //then not logged in
        //we should check this field when they click to start a review form.
        return "userID does not exist, or user is not logged in";
    }
    $user_table = $wpdb->prefix . "bcr_users";
    $q = $wpdb->prepare("SELECT * %s FROM %s WHERE %s = %d;", array("userID", $user_table, "userID", $userID));
    $res = $wpdb->query($q);
    if($res == false){
        //should not be allowed to start a form untill they are in bcr users
        return "userID does not exist in bcr user table, has not registerd";
    }
    //check if user in wp bcr users
    return $userID;
}

function get_knowthyself_id($userID){
    global $wpdb;
    $KTS_table = $wpdb->prefix . "bcr_know_thyself";
    $q = $wpdb->prepare("SELECT * FROM %s WHERE %s = %d;", array($KTS_table, "userID", $userID));
    $res = $wpdb->get_results($q);

}

function summit_insert_into_review_answer_table($review_id, $answer_ids,$file){
    //insert answer ids int review answer table
        global $wpdb;
        $start = "\n\n SUMMIT INSERT INTO REVIEW TABLE \n";
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
?>