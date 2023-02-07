<?php
require 'table_utils.php';
function insert_into_ski_review($header, $questions, $answers, $file, $formName) {
        global $wpdb;
    
        $userInfo = $header['userInfo'];
        $u_info = print_r($userInfo, true);
        //fwrite($file, "post user info: \n".$u_info."\n\n");
        

        $userName = get_userName_by_userID($userInfo->userID,$file);
        //fwrite($file, "user name: ".$userName." \n");
        
        if ( NULL === $header || 0 === $header || '0' === $header || empty( $header ) ) {
            return;
        }
        
        $html = format_questions_answers_post_content($header["questionContent"], $header["answerContent"],$formName,$file);
        
        $user_html.='<div id= "userName" class = "userInfo">Username: '.esc_html($userName). '</div>
                    <div id= "userHeight" class = "userInfo">Height: '.esc_html($userInfo->heightFeet).'feet, '.esc_html($userInfo->heightInches).' inches</div>
                    <div id "userWeight" class = "userInfo">Weight: '.esc_html($userInfo->weight).' lbs</div>
                    <div id= "userAbility" class = "userInfo">Ski Ability: '.esc_html($userInfo->skiAbility).'</div>';
        $html = $user_html . $html;

        //fwrite($file, "\n".$html."\n");
//fetch user name to insert
        $ski_review = array(
                            'post_title' =>wp_strip_all_tags( $header['brandName'] . ' ' . $header['productName']),
                            'post_content' => $html,
                            'meta_input' => array(
                                                  'id'            => $header['reviewID'],
                                                  'userID'        => $userInfo->userID,
                                                  'userName'      => $userName,
                                                  'heightFeet'    => $userInfo->heightFeet,
                                                  'heightInches'  => $userInfo->heightInches,
                                                  'weight'        => $userInfo->weight,
                                                  'skiAbility'    => $userInfo->skiAbility,
                                                  'product_tested'=> $header['productName'],
                                                  'category'      => $header['categoryName'],
                                                  'sport'         => $header['sportName']
                                                  ),
                            'post_type'   => 'Community Reviews',
                            'post_excerpt' => $user_html,
                            'post_status' => 'publish',
                            );
        //$custom_post_input = print_r($ski_review, true);
        //fwrite($file, "Post array: \n".$custom_post_input."\n\n");
        wp_insert_post( $ski_review );
        //fwrite($file,"\n\nHERE\n\n");      
}

function get_userName_by_userID($userID, $file){
    global $wpdb;
    //fwrite($file, "userID to get userName: ".$userID."\n");
    $wp_user_table = $wpdb->prefix."users";
    $q = $wpdb->prepare("SELECT display_name FROM $wp_user_table WHERE ID = %s;", $userID);
    $res = $wpdb->get_row($q);
    return $res->display_name;
}

function format_questions_answers_post_content($questions, $answers, $form_name, $file){
    //php assertion that question and answers atre same length
    if (count($questions) !== count($answers)) {
        die("questions and answers");
    }
    $html = "";
    //fwrite($file, $formName."\n")
if ($form_name == 'Ski Review Form'){//ski
        
        $html .= '<div id= "skiReviewContainer" class = "long_container">
            <div id= "skiReviewTitle" class = "section_title">Product Review</div>
            <div id= "skiQuestion6" class = "question_title">Stablility?</div>
            <div id= "skiAnswer6" class = "answer">  '.esc_html($answers[6]).'</div>
            <div id= "skiQuestion5"class = "question_title">Maniveurability?</div>
            <div id= "skiAnswer5" class = "answer">'.esc_html($answers[5]).'</div>
            <div id= "skiQuestion7" class = "question_title">How forgiving?</div>
            <div id= "skiAnswer7" class = "answer">'.esc_html($answers[7]).'</div>
            <div id= "skiQuestion8" class = "question_title"> Suspension</div>
            <div id= "skiAnswer8" class = "answer">'.esc_html($answers[8]).'</div>
            <div id= "skiQuestion9" class = "question_title"> Fun Factor</div>
            <div id= "skiAnswer9" class = "answer">'.esc_html($answers[9]).'</div>
            <div id= "skiQuestion10" class = "question_title"> Ski felt good on...</div>
            <div id= "skiAnswer10" class = "answer">'.esc_html($answers[10]).'</div>              
            <div id= "skiQuestion11" class = "question_title"> Correct Lenght?</div>
            <div id= "skiAnswer11" class = "answer">'.esc_html($answers[11]).'</div> 
            <div id= "skiQuestion13" class = "question_title"> How likely to buy?</div>
            <div id= "skiAnswer13" class = "answer">'.esc_html($answers[13]).'</div>          
            </div>';
        
        $html .= '<div id= "skiConditionsContainer" class = "short_container">
            <div id= "skiConditionsTitle" class = "section_title">Testing Context</div>
            <div id= "skiQuestion3" class = "question_title">Terrain Tested</div>
            <div id= "skiAnswer3" class = "answer">'.esc_html($answers[3]).'</div>
            <div id= "skiQuestion2" class = "question_title">Where</div>
            <div id= "skiAnswer2" class = "answer">'.esc_html($answers[2]).'</div>
            <div id= "skiQuestion4" class = "question_title">Conditions Tested</div>
            <div id= "skiAnswer4" class = "answer">'.esc_html($answers[4]).'</div>
            </div>'; 
        
        $html .= '<div id= "skiTestimonyContainer" class = "whole_container">
            <div id= "skiTestimonyTitle" class = "section_title2">Tester Testimony</div>
            <div id= "skiQuestion12" class = "question_title"> What type of skier will ike this product?</div>
            <div id= "skiAnswer12" class = "answer">'.esc_html($answers[12]).'</div>
            <div id= "skiQuestion14" class = "question_title"> Better Products?</div>
            <div id= "skiAnswer14" class = "answer">'.esc_html($answers[14]).'</div>          
            <div id= "skiQuestion15" class = "question_title"> Personal Comment</div>
            <div id= "skiAnswer15" class = "answer">'.esc_html($answers[15]).'</div>
            </div>'; 
            
        return $html;
    }
    
    if ($form_name == 'Summit_Ski_Boot_Review_Form'){//boot

        $html .= '<div id= "bootReviewContainer" class = "long_container">
            <div id= "bootReviewTitle" class = "section_title">Product Review</div>
            <div id= "bootQuestion6" class = "question_title">Boot Fit</div>
            <div id= "bootAnswer6" class = "answer">  '.esc_html($answers[6]).'</div>
            <div id= "bootQuestion8" class = "question_title">Suspension?</div>
            <div id= "bootAnswer8" class = "answer">'.esc_html($answers[8]).'</div>
            <div id= "bootQuestion7" class = "question_title">Flex Pattern</div>
            <div id= "bootAnswer7" class = "answer">  '.esc_html($answers[7]).'</div>

            <div id= "bootQuestion9" class = "question_title"> Boot Walking?</div>
            <div id= "bootAnswer9" class = "answer">'.esc_html($answers[9]).'</div>
            <div id= "bootQuestion11" class = "question_title"> How likely to buy?</div>
            <div id= "bootAnswer11" class = "answer">'.esc_html($answers[11]).'</div>
            </div>';

        $html .= '<div id= "bootConditionsContainer"class = "short_container">
            <div id= "bootCondtionsTitle" class = "section_title">Testing Context</div>
            <div id= "bootQuestion4" class = "question_title">Terrain Tested</div>
            <div id= "bootAnswer4" class = "answer">'.esc_html($answers[4]).'</div>
            <div id= "bootQuestion3" class = "question_title">Where</div>
            <div id= "bootAnswer3" class = "answer">'.esc_html($answers[3]).'</div>
            <div id= "bootQuestion5" class = "question_title">Conditions Tested</div>
            <div id= "bootAnswer5" class = "answer">'.esc_html($answers[5]).'</div>
            </div>'; 

        $html .= '<div id= "bootTestimonyContainer" class = "whole_container">
            <div id= "bootTestimonyTitle"class = "section_title2">Tester Testimony</div>

            <div id= "bootQuestion10" class = "question_title"> What type of skier will ike this product?</div>
            <div id= "bootAnswer10" class = "answer">'.esc_html($answers[10]).'</div>
            <div id= "bootQuestion12" class = "question_title"> Better Products?</div>
            <div id= "bootAnswer12" class = "answer">'.esc_html($answers[12]).'</div>          
            <div id= "bootQuestion13" class = "question_title"> Personal Comment</div>
            <div id= "bootAnswer13" class = "answer">'.esc_html($answers[13]).'</div>
            </div>'; 
        return $html;
    }

    if ($form_name == 'Summit_Apparel_Form'){ //apparel

        $html .= '<div id= "apparelReviewContainer" class = "whole_container">
            <div id= "apparelReviewTitle" class = "section_title2">Product Review</div>
            <div id= "apparelQuestion2" class = "short_question"> Where</div>
            <div id= "apparelAnswer2" class = "long_answer">'.esc_html($answers[2]).'</div>          
            <div id= "apparelQuestion3" class = "short_question"> Conditions Tested</div>
            <div id= "apparelAnswer3" class = "long_answer">'.esc_html($answers[3]).'</div>
            <div id= "apparelQuestion4" class = "short_question"> Fit</div>
            <div id= "apparelAnswer4" class = "long_answer">'.esc_html($answers[4]).'</div> 
            <div id= "apparelQuestion5" class = "short_question"> Comfort</div>
            <div id= "apparelAnswer5" class = "long_answer">'.esc_html($answers[5]).'</div>      
            <div id= "apparelQuestion7" class = "short_question"> Breathability</div>
            <div id= "apparelAnswer7" class = "long_answer">'.esc_html($answers[7]).'</div>
            <div id= "apparelQuestion6" class = "question_title"> Wind/Water Resistence</div>
            <div id= "apparelAnswer6" class = "answer">'.esc_html($answers[6]).'</div>
            <div id= "apparelQuestion8" class = "question_title"> Who will like this?</div>
            <div id= "apparelAnswer8" class = "answer">'.esc_html($answers[8]).'</div> 
            <div id= "apparelQuestion9" class = "question_title"> How likely to buy?</div>
            <div id= "apparelAnswer9" class = "answer">'.esc_html($answers[9]).'</div> 
            <div id= "apparelQuestion10" class = "question_title"> Similar Products?</div>
            <div id= "apparelAnswer10" class = "answer">'.esc_html($answers[10]).'</div>
            <div id= "apparelQuestion11" class = "question_title"> Personal Comment?</div>
            <div id= "apparelAnswer11" class = "answer">'.esc_html($answers[11]).'</div>
            </div>';
        return $html;
    }

    if ($form_name == 'Snowboard Review'){//snowboard

        $html .= '<div id= "snowboardReviewContainer" class = "long_container">
            <div id= "snowboardReviewTitle" class = "section_title">Product Review</div>
            <div id= "snowboardQuestion5" class = "question_title">Stablility?</div>
            <div id= "snowboardAnswer5" class = "answer">  '.esc_html($answers[5]).'</div>
            <div id= "snowboardQuestion6" class = "question_title">Maniveurability?</div>
            <div id= "snowboardAnswer6" class = "answer">'.esc_html($answers[6]).'</div>
            <div id= "snowboardQuestion7" class = "question_title">How forgiving?</div>
            <div id= "snowboardAnswer7" class = "answer">  '.esc_html($answers[7]).'</div>
            <div id= "snowboardQuestion8" class = "question_title"> Suspension</div>
            <div id= "snowboardAnswer8" class = "answer">'.esc_html($answers[8]).'</div>
            <div id= "snowboardQuestion9" class = "question_title"> Fun Factor</div>
            <div id= "snowboardAnswer9" class = "answer">'.esc_html($answers[9]).'</div>
            <div id= "snowboardQuestion10" class = "question_title"> Ideal Terrain</div>
            <div id= "snowboardAnswer10" class = "answer">'.esc_html($answers[10]).'</div>              
            <div id= "snowboardQuestion11" class = "question_title"> Correct Lenght?</div>
            <div id= "snowboardAnswer11" class = "answer">'.esc_html($answers[11]).'</div> 
            <div id= "snowboardQuestion13" class = "question_title"> How likely to buy?</div>
            <div id= "snowboardAnswer13" class = "answer">'.esc_html($answers[13]).'</div>          
            </div>';

        $html .= '<div id= "snowboardCondtionsContainer" class = "short_container">
            <div id= "snowboardConditionsTitle" class = "section_title">Testing Context</div>
            <div id= "snowboardQuestion3" class = "question_title">Terrain Tested</div>
            <div id= "snowboardAnswer3" class = "answer">'.esc_html($answers[3]).'</div>
            <div id= "snowboardQuestion2" class = "question_title">Where</div>
            <div id= "snowboardAnswer2" class = "answer">'.esc_html($answers[2]).'</div>
            <div id= "snowboardQuestion4" class = "question_title">Conditions Tested</div>
            <div id= "snowboardAnswer4" class = "answer">'.esc_html($answers[4]).'</div>
            </div>'; 

        $html .= '<div id= "snowboardTestimonyContainer" class = "whole_container">
            <div id= "snowboardTestimonyTitle" class = "section_title2">Tester Testimony</div>
            <div id= "snowboardQuestion12" class = "question_title"> What type of Snowboarder will ike this product?</div>
            <div id= "snowboardAnswer12" class = "answer">'.esc_html($answers[12]).'</div>
            <div id= "snowboardQuestion14" class = "question_title"> Better Products?</div>
            <div id= "snowboardAnswer14" class = "answer">'.esc_html($answers[14]).'</div>          
            <div id= "snowboardQuestion15" class = "question_title"> Personal Comment</div>
            <div id= "snowboardAnswer15" class = "answer">'.esc_html($answers[15]).'</div>
            </div>'; 
        return $html;
    }


    if ($form_name == 'Climbing Skin Review'){ //climbing skins

        $html .= '<div id= "climbingSkinReviewContainer" class = "whole_container">
            <div id= "climbingSkinReviewTitle" class = "section_title2">Product Review</div>
            <div id= "climbingSkinQuestion2" class = "short_question"> Where</div>
            <div class = "long_answer">'.esc_html($answers[2]).'</div>          
            <div id= "climbingSkinQuestion3" class = "short_question"> Glide</div>
            <div class = "long_answer">'.esc_html($answers[3]).'</div>
            <div id= "climbingSkinQuestion4" class = "short_question"> Grip</div>
            <div class = "long_answer">'.esc_html($answers[4]).'</div> 
            <div id= "climbingSkinQuestion5" class = "short_question"> Packability</div>
            <div class = "long_answer">'.esc_html($answers[5]).'</div>      
            <div id= "climbingSkinQuestion6" class = "question_title"> Who will like this?</div>
            <div class = "answer">'.esc_html($answers[6]).'</div> 
            <div id= "climbingSkinQuestion7" class = "question_title"> How likely to buy?</div>
            <div class = "answer">'.esc_html($answers[7]).'</div> 
            <div id= "climbingSkinQuestion8" class = "question_title"> Similar Products?</div>
            <div class = "answer">'.esc_html($answers[8]).'</div>
            <div id= "climbingSkinQuestion9" class = "question_title"> Personal Comment?</div>
            <div class = "answer">'.esc_html($answers[9]).'</div>
            </div>';
        return $html;
    }

}

function profile_info_sub( $record, $ajax_handler ){
    global $wpdb;
    $user_table_name = $wpdb->prefix . "bcr_users";
    $form_name = $record->get_form_settings( 'form_name' );
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "w") or die('fopen failed');
    $fields = [];
    if($form_name == 'Profile Builder') {
        $userID = get_current_userID($file);
        //fwrite($file,$userID);
        $fields['userID'] = $userID;
        $raw_fields = $record->get('fields');
        $fields['heightFeet'] = $raw_fields["height_feet"]['value'];
        $fields['heightInches'] = $raw_fields["height_inches"]['value'];
        $fields['weight'] = $raw_fields["weight"]['value'];
        $fields['skiAbility'] = $raw_fields["user_experience"]['value'];

        $q = $wpdb->prepare("SELECT userID FROM $user_table_name WHERE userID = %s;", $userID);
        $res = $wpdb->query($q);

        if($res){
            $output['success'] = $wpdb->update($user_table_name, $fields, array("userID"=>$userID));
        }else {
            $output['success'] = $wpdb->insert($user_table_name, $fields);
        }
        $ajax_handler->add_response_data(true, $output);
    }
    fclose($file);
}

function fluent_summit_review_from_sub($entryId, $formData, $form) {
    global $wpdb;
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt'; 
    $file = fopen($file_path, "w") or die('fopen failed');
    //fwrite($file,"fluent_summit_review_from_sub\n\n");
    $existing_form_names = get_all_form_names($file);
    //$form_name_str = print_r($existing_form_names, true);
    //fwrite($file, "registered form names:\n".$form_name_str."\n");
    $current_form_name = $form->title; 
    //fwrite($file, $current_form_name."\n");
    $current_form_id = $form->id;

    //$form_p = print_r($form, true);
    //$form_fields = $form->get( "form_fields" );
    $formData_p = print_r($formData, true);
    //fwrite($file, "formData 1: \n".$formData_p."\n\n");

    $qs_and_as = fluent_get_fields_array($formData, $file);
    //$qs_and_as_p = print_r($qs_and_as, true);
    //fwrite($file, "questions and answers: \n".$qs_and_as_p."\n\n");
    
    if(in_array($current_form_name, $existing_form_names)){
        //fwrite($file, "Starting the Process \n\n");
        //-----------------write to tables-----------------------------
        $review_id = summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);
        //---------------create custom post-----------------------------------
        $header = summit_form_submission_custom_post_content($review_id, $current_form_id, $qs_and_as, $file);
        //$header_info_read = print_r($header, true);
        //fwrite($file, "HEADER: \n".$header_info_read."\n\n");
        insert_into_ski_review($header, $qs, $as, $file, $current_form_name);
    }
    fclose($file);
}

function fluent_get_fields_array($formData, $file){
    $formData = array_slice($formData, 3, sizeof($formData)-1, $preserve_keys = true);
    $return_array = [];
    foreach($formData as $key => $value){
        if(gettype($key) == "string"){
            $key = intval(substr($key, 0, 2));
        }
        if(gettype($value) == "array"){
            $return_array[$key] = implode(", ", $value);
        }else{
           $return_array[$key] = $value; 
        }
    }
    return $return_array;
}

function elementor_summit_review_from_sub( $record, $ajax_handler ) {
    global $wpdb;
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt'; 
    $file = fopen($file_path, "w") or die('fopen failed');
    $existing_form_names = get_all_form_names($file);
    //fwrite($file,"form names: ".$existing_form_names."\n");

    $current_form_name = $record->get_form_settings( 'form_name' ); 
    //fwrite($file,"current form name: ".$current_form_name."\n");

    $current_form_id = $record->get_form_settings('form_id');
    //fwrite($file,"current form id: ".$current_form_id."\n");

    $raw_fields = $record->get( 'fields' );
    //$raw_fields_p = print_r($raw_fields, true);
    //fwrite($file, "elementor raw fields: \n".$raw_fields_p."\n\n");

    $qs_and_as = elementor_get_fields_array($raw_fields, $file);
    //$q_and_a_p = print_r($qs_and_as, true);
    //fwrite($file, "elementor q and a: \n".$q_and_a_p."\n\n");

    if(in_array($current_form_name, $existing_form_names)){
    	//-----------------write to tables----------------------------
        $review_id = summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);

        //---------------create custom post-----------------------------------
        $header = summit_form_submission_custom_post_content($review_id, $current_form_id, $qs_and_as, $file);
        //$header_info_read = print_r($header, true);
        //fwrite($file, "HEADER: \n".$header_info_read."\n\n");
    	insert_into_ski_review($header, $qs, $as, $file, $current_form_name);
	}
    fclose($file);
}

function elementor_get_fields_array($raw_fields, $file){
    $return_array = [];
    foreach($raw_fields as $key => $fields){
        if($key != "step"){
            $return_array[$key] = $fields["value"];
        }
    }
    return $return_array;
}

//------------------------------table writing functions-----------------------------------
function summit_form_submission_write_to_tables($current_form_id, $record, $file){
    //$start = "\n\n start summit_form_submission_write_to_tabless \n";
    //fwrite($file, $start);
    $answer_ids = summit_insert_into_answer_table($record, $file);
    //fwrite($file, "write to answer table ids:\n".$answer_ids."\n\n") or die('fwrite 1 failed');
    //$res1 = implode(", ", $answer_ids)."\n";
    //fwrite($file, $res1) or die('fwrite 2 failed');

    //$ajax_handler->add_response_data( true, $output );
    //fwrite($file, "write to review table id:\n") or die('fwrite 3 failed');
    $id = summit_insert_into_review_table($current_form_id,$file);
    //fwrite($file, "last inserted review id: ".strval($id)."\n") or die('fwrite 2 failed');
    //$ajax_handler->add_response_data( true, $output );
    summit_insert_into_review_answer_table($id, $answer_ids, $file);
    //fwrite($file, "\n\nsummit_insert_into_review_answer_table function exited\n\n");
    //$ajax_handler->add_response_data( true, $output );
    return $id;
}

function get_all_form_names($file){
    global $wpdb;
    //$start = "\n\n SUMMIT get all form names \n";
    //fwrite($file, $start);
    $review_forms_table = $wpdb->prefix . "bcr_review_forms";
    $q = "SELECT reviewFormName FROM $review_forms_table;";
    //$q = "SELECT reviewFormName FROM `wp_bcr_review_forms`";
    //$wpdb->prepare("SELECT %s FROM %s;", array("reviewFormName", $review_table));
    $res = $wpdb->get_results($q);
    $return_array = [];//json_decode(json_encode($res),true);
    foreach ($res as $value) {
        $return_array[] = $value->reviewFormName;
    }
    //$res_p = print_r($return_array, true);
    //fwrite($file, "all form names:\n".$res_p."\n");
    return $return_array;
}

function summit_insert_into_answer_table($record,$file){
    global $wpdb;
    //$start = "\n\n SUMMIT INSERT INTO ANSWER TABLE \n";
    //fwrite($file, $start);
    $answer_table = $wpdb->prefix . "bcr_answers";
    $answer_ids = []; //used for when inserting into reviews answers
    //$question_ids = [];//manually entered into elementor form, until we can make a form dynamically 
    //$raw_fields = $record->get( 'fields' );
    $output = [];
    foreach($record as $id => $field ){
        $fields_answers = [];
        $fields_answers['questionID'] = $id;
        $fields_answers['answerContent'] = $field;
        $output['success'] = $wpdb->insert($answer_table, $fields_answers);
        //if($output == success){

        //}
        $last_answer_id = $wpdb->insert_id;//answer id generated upon entering into table
        //!!!!!!may be susceptible to collisions with mulitple users
        //maybe there is a lock for each form submitted idk
        array_push($answer_ids, $last_answer_id);
    }
    //$ajax_handler->add_response_data( true, $output );
    return $answer_ids;
}

function summit_insert_into_review_table($RF_id, $file){
       //insert review 
        //echo "in review table functions!<br>";
        global $wpdb;
        //$start = "\n\nSUMMIT INSERT INTO REVIEW TABLE \n";
        //fwrite($file, $start);
        $output2 = [];
        $review_table = $wpdb->prefix . "bcr_reviews";
        $fields_review = [];

        $current_userID = get_current_userID($file);
        if(gettype($current_userID)=="string"){
        //  fwrite($file,"user id grab failed: ".$current_userID."\n");
            die("user not found"); //should be a redirct to another page
        }
        //fwrite($file,"userID = ".strval($current_userID)."\n");
        $fields_review['userID'] = $current_userID;
        
        //$KTSid = get_knowthyself_id($current_userID);
        //$fields_review['knowThyselfID'] = 4;//NOT NESSESARY ANYMORE get this id from bcr_know_thyself using userid HAS TO EXIST BEFORE SUBMISSION
        //remove!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

        $fields_review['reviewFormID'] = $RF_id;

        //fwrite($file, "fields_review: ".implode(", ",$fields_review)."\n");
        $output2['success'] = $wpdb->insert($review_table, $fields_review);
        //$str = "number of rows inserted: ".strval($output['success'])." ||| did not work if false\n";
        //fwrite($file, $str);
        //echo strval(output['success'])."<br>";
        //$ajax_handler->add_response_data( true, $output );
        $last_review_id = $wpdb->insert_id; //STILL NOT SURE IF THIS IS THREAD SAFE
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
function summit_insert_into_review_answer_table($review_id, $answer_ids,$file){
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

// --------------getters for creating custom post--------------------------

function summit_form_submission_custom_post_content($current_review_id, $current_form_id,$record,$file){
    //$start = "\n\n summit_form_submission_custom_post_content \n";
    //fwrite($file, $start);
    
    $product_info = [];
    $product_info['productName'] = $record[2];//get_product_info($current_form_id,$file);
    //$p_info_read = print_r($product_info, true);
    //fwrite($file, "product info: \n".$p_info_read."\n\n");
    //$p_info_read = print_r($product_info, true);
    //fwrite($file, "product info: \n".$p_info_read."\n\n");
    
    $brand_name = $record[1];
    $brand_info = get_brand_info($brand_name, $file);
    //$b_info_read = print_r($brand_info, true);
    //fwrite($file, "brand info: \n".$b_info_read."\n\n");

    $category_info = get_category_info($current_form_id, $file);
    //$c_info_read = print_r($category_info, true);
    //fwrite($file, "categroy info: \n".$c_info_read."\n\n");

    /*
    $sport_info = get_sport_info($category_info['sportID']);
    //$s_info_read = print_r($sport_info, true);
    //fwrite($file, "sport info: \n".$s_info_read."\n\n");
    */

    $q_and_a_content = get_answer_and_question_content($record,$file);
    //$qa_info_read = print_r($q_and_a_content, true);
    //fwrite($file, "QandA info: \n".$qa_info_read."\n\n");
    
    $user_info = get_user_information($file);
    //$u_info_read = print_r($user_info, true);
    //fwrite($myfile, "user info: \n".$u_info_read."\n\n");

   $qs = $q_and_a_content['question_content'];
   $as = $q_and_a_content['answer_content'];

    $header = array(
        'reviewID' => $current_review_id, 
        'formID' => $current_form_id,
        'productName' => $product_info['productName'],
        'brandName' => $brand_name,
        'categoryName' => $category_info->categoryName,
        'questionContent' => $q_and_a_content['question_content'],
        'answerContent' => $q_and_a_content['answer_content'],
        'userInfo' => $user_info
    );
    
    return $header;
}

function get_answer_and_question_content($record,$file){
    global $wpdb;
    $start = "\n\n GET ANSWERS AND QUESTIONS \n";
    fwrite($file, $start);

    $return_array = [];
    //$answer_ids = []; //used for when inserting into reviews answers
    $answer_content = array_values($record);
    $question_ids = array_keys($record);//manually entered into 

    //fwrite($file, implode(", ", $question_ids)." \n");
    $question_content = [];
    $question_table = $wpdb->prefix . "bcr_questions";
    $desired_column = "questionContent";
    $where_column = "questionID";
    foreach($question_ids as $id){
    	$q = "SELECT questionDisplayContent, questionType FROM $question_table WHERE questionID = $id;";
    	$q_content = $wpdb->get_row($q);
    	$var = print_r($q_content, true);
    	fwrite($file,"get redults: \n".$var."\n");
    	//$content = $q_content->questionDisplayContent;
    	//fwrite($file,"contne from get result: \n".$content."\n");
    	array_push($question_content, $q_content);
    }
    $var = print_r($question_content, true);
    fwrite($file,"get redults: \n".$var."\n");
    //fwrite($file, implode(", ", $question_content)." \n");
    fwrite($file, implode(", ", $answer_content)." \n");
    $return_array['question_content'] = $question_content;
    $return_array['answer_content'] = $answer_content;
   
    return $return_array;
}

/*
function get_product_info($form_id,$file){//may just want to return res2 !!!!!!
    global $wpdb;
    $start = "\n\n GET PRODUCT INFORMATION \n";
    fwrite($file, $start);
    fwrite($file, "\n\nCurrent Form ID: ".$form_id."\n\n");
    //fwrite($file, "form id: ".$form_id."\n");
    $form_table = $wpdb->prefix . "bcr_review_forms";
    $q1 = "SELECT * FROM $form_table WHERE reviewFormID = $form_id;";
    $res1 = $wpdb->get_row($q1);
    $var = print_r($res1, true);
    fwrite($file,"get results: \n".$var."\n");
    $product_id = $res1->productID;
    fwrite($file, "product id: ".$product_id."\n");
    $product_table = $wpdb->prefix . "bcr_products";
    $q2 = "SELECT * FROM $product_table WHERE productID = $product_id;";
    $res2 = $wpdb->get_row($q2);
    $return_array = [];
    $return_array['productID'] = $res2->productID;
    $return_array['productName'] = $res2->productName;
    $return_array['categoryID'] = $res2->categoryID;
    //add brand id or band name 
    return $return_array;
}
function get_product_info($product_name, $file){
    $product_table = $wpdb->prefix . "bcr_products";
    $q = "SELECT * FROM $product_table WHERE productName = $product_name;";
    $res = $wpdb->get_row($q);
    //$var = print_r($res, true);
    //fwrite($file,"get results: \n".$var."\n");
    return $res;
}
*/

//another function that gets brand info and will change the custom post
function get_brand_info($brand_name, $file){
    global $wpdb;
    //fwrite($file, "brand name: ". $brand_name . "\n");
    $brand_table = $wpdb->prefix . "bcr_brands";
    $q = "SELECT * FROM $brand_table WHERE brandName = $brand_name;";
    $res = $wpdb->get_row($q);
    //$var = print_r($res, true);
    //fwrite($file,"get results: \n".gettype($var)."\n");
    return $res;
}

function get_category_info($form_id, $file){//may just want to return res !!!!!!
    global $wpdb;
    //$start = "\n\n GET CATEGORY INFORMATION \n";
    //fwrite($file, $start);
    //fwrite($file, "category id: ".$category_id."\n");

    $form_table = $wpdb->prefix . "bcr_review_forms";
    $q1 = "SELECT * FROM $form_table WHERE reviewFormID = $form_id;";
    $res1 = $wpdb->get_row($q1);
    //$var = print_r($res1, true);
    //fwrite($file,"get results: \n".$var."\n");
    $category_id = $res1->categoryID;

    $category_table = $wpdb->prefix . "bcr_categories";
    $q = "SELECT * FROM $category_table WHERE categoryID = $category_id;";
    $res = $wpdb->get_row($q);
    $var = print_r($res, true);
    //fwrite($file,"get results: \n".$var."\n");
    return $res;
    /*
    $return_array = [];
    $return_array['categoryID'] = $category_id;
    $return_array['categoryName'] = $res->categoryName;
    $return_array['parentID'] = $res->parentID;
    $return_array['sportID'] = $res->sportID;
    return $return_array;
    */
}

/*
//this may be removed from the structure 
function get_sport_info($sport_id){ //may just want to return res !!!!!!
	global $wpdb;
	$sports_table = $wpdb->prefix . "bcr_sports";
	$q = $wpdb->prepare("SELECT * FROM $sports_table WHERE sportID = %s;", $sport_id);
	$res = $wpdb->get_row($q);
	$return_array = [];
	$return_array['sportID'] = $sport_id;
	$return_array['sportName'] = $res->sportName;
	return $return_array;
}
*/

function get_user_information($file){
    global $wpdb;
    //$start = "\n\n GET USER INFORMATION \n";
    //fwrite($file, $start);
    $userID = get_current_userID($file);
    //fwrite($file, "user id: ".$userID."\n");
    $user_table_name = $wpdb->prefix . "bcr_users";//i do not have this same able 
    $queryString = $wpdb->prepare("SELECT * FROM $user_table_name WHERE userID=%s", $userID);
    $userInformation = $wpdb->get_row($queryString);

    return $userInformation;
}

?>
