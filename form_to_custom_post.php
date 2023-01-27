<?php
require 'table_utils.php';
function insert_into_ski_review($header, $questions, $answers, $file) {
        global $wpdb;

        //$answersContent = $header['answersContent'];
        //fwrite($file, "answer content grabbed\n".implode(', ', $answers)."---------\n\n");
    
        $userInfo = $header['userInfo'];
        //fwrite($file, "user info grabbed \n");
        $u_info = print_r($header, true);
        fwrite($file, "post user info: \n".$u_info."\n\n");
        
        if ( NULL === $header || 0 === $header || '0' === $header || empty( $header ) ) {
            return;
        }

        $html = format_questions_answers_post_content($header["questionContent"], $header["answerContent"],$header['reviewID']);
        //fwrite($file, "HTML STRING: \n".$html."\n\n");

        //$user_html = "<div>".$userInfo->heightFeet.":\n<br/>".$answers[$i]."\n\n</div>";
        $user_html.= "<div>Reviewers height: ".$userInfo->heightFeet." feet, ".$userInfo->heightInches." inches</div>";
        $user_html .= "<div>Reviewers weight: ".$userInfo->weight." lbs</div>";
        $user_html .= "<div>Reviewers ski ability: ".$userInfo->skiAbility."</div>";
        $user_html .= "<br/>";

        $html = $user_html . $html;

        $ski_review = array(
                            'post_title' => wp_strip_all_tags( $answers[1] . ' ' . $header['productName'] . ' ' . $answers[2]),
                            'post_content' => $html,
                            'meta_input' => array(
                                                  'id'        => $header['reviewID'],
                                                  'userID'          => $userInfo->userID,
                                                  'heightFeet'          => $userInfo->heightFeet,
                                                  'heightInches'            => $userInfo->heightInches,
                                                  'weight'          => $userInfo->weight,
                                                  'skiAbility'          => $userInfo->skiAbility,
                                                  'product_tested'        => $header['productName'],
                                                  'category'           => $header['categoryName'],
                                                  'sport'          => $header['sportName'],
                                                  'questions'          => $questions,
                                                  'answers'           => $answers
                                                  ),
                            //'post_content' => wp_strip_all_tags( $answers[1] . ' ' . $header['productName'] . ' ' . $answers[2]),
                            'post_type'   => 'Community Reviews',
                            'post_excerpt' => $user_html,
                            'post_status' => 'publish',
                            );
        $custom_post_input = print_r($ski_review, true);
        fwrite($file, "Post array: \n".$custom_post_input."\n\n");
        wp_insert_post( $ski_review );
        
    }

function format_questions_answers_post_content($questions, $answers, $form_id){
	//php assertion that question and answers atre same length
	if (count($questions) !== count($answers)) {
		die("questions and answers");
	}
	$html = "";
	//foreach ($questions as $key = &gt; $value) {
	//for ($i = 0; $i < count($questions); $i++) {
	    //$html .= '&lt;div class="row"&gt;';
	    //$html .= '&lt;div class="col-md-6"&gt;'.$questions[$i].'&lt;/div&gt;';
	   // $html .= '&lt;div class="col-md-6"&gt;'.$answers[$i].'&lt;/div&gt;';
	    //$html .= '&lt;/div&gt;';


	    //$html .= "<strong>".$questions[$i]."\n</strong>";
	    //$html .= "<div>".$answers[$i]."\n</div>";

	//}

    if ($form_id == 4){

        $html .= '<div class = "long_container">
            <div class = "section_title">Product Review</div>
            <div class = "question_title">Stablility?</div>
            <div class = "answer">  '.$answers[7].$answers[8].'</div>
            <div class = "question_title">Maniveurability?</div>
            <div class = "answer">'.$answers[6].'</div>
            <div class = "question_title">Demand of ski</div>
            <div class = "answer">  '.$answers[9].'</div>

            <div class = "question_title"> Ride Quality</div>
            <div class = "answer">'.$answers[10].'</div>
            <div class = "question_title"> Fun Factor</div>
            <div class = "answer">'.$answers[12].'</div>
            <div class = "question_title"> Ski felt good on...</div>
            <div class = "answer">'.$answers[11].'</div>              
            <div class = "question_title"> Correct Lenght?</div>
            <div class = "answer">'.$answers[13].'</div>
            <div class = "question_title"> Who will like this Ski?</div>
            <div class = "answer">'.$answers[14].'</div>          
            </div>';

        $html .= '<div class = "short_container">
            <div class = "section_title">Testing Context</div>
            <div class = "question_title">Terrain Tested</div>
            <div class = "answer">'.$answers[4].'</div>
            <div class = "question_title">Where</div>
            <div class = "answer">'.$answers[3].'</div>
            <div class = "question_title">Conditions Tested</div>
            <div class = "answer">'.$answers[5].'</div>
            </div>'; 

        $html .= '<div class = "whole_container">
            <div class = "section_title2">Tester Testimony</div>
            <div class = "short_question"> How good 1-10?</div>
            <div class = "long_question"> elaboration </div>
            <div class = "short_answer">'.$answers[15].'</div>
            <div class = "long_answer">'.$answers[16].'</div>          
            <div class = "short_question"> Recommend?</div>
            <div class = "long_question"> elaboration </div>
            <div class = "short_answer">'.$answers[17].'</div>
            <div class = "long_answer">'.$answers[18].'</div>          
            <div class = "question_title"> Prefered Similar Products? and why?</div>
            <div class = "answer">'.$answers[19].'</div>
            <div class = "question_title"> Personal Comment</div>
            <div class = "answer">'.$answers[20].'</div>
            </div>'; 
        return $html;
    }
    if ($form_id == 5){

        $html .= '<div class = "long_container">
            <div class = "section_title">Product Review</div>
            <div class = "question_title">Boot Fit</div>
            <div class = "answer">  '.$answers[6].'</div>
            <div class = "question_title">Boot Stance?</div>
            <div class = "answer">'.$answers[8].'</div>
            <div class = "question_title">Flex Pattern</div>
            <div class = "answer">  '.$answers[9].'</div>

            <div class = "question_title"> Weight of Boot</div>
            <div class = "answer">'.$answers[10].'</div>
            <div class = "question_title"> Difficulty Putting on 1-10?</div>
            <div class = "answer">'.$answers[11].'</div>';
        if ($answers[12] == 'yes'){
            $html.= '<div class = "question_title"> Touring Boot?</div>
                <div class = "answer">'.$answers[12].'</div>    
                <div class = "question_title"> Touring Buckles</div>
                <div class = "answer">'.$answers[13].'</div>
                <div class = "question_title"> Touring Walking?</div>
                <div class = "answer">'.$answers[14].'</div>
                <div class = "question_title"> Touring Power Straps</div>
                <div class = "answer">'.$answers[15].'</div>
                <div class = "question_title"> Touring Walk Mode?</div>
                <div class = "answer">'.$answers[16].'</div>
                </div>';
            $html .= '<div class = "short_container">
                <div class = "section_title">Testing Context</div>
                <div class = "question_title">Terrain Tested</div>
                <div class = "answer">'.$answers[4].'</div>
                <div class = "question_title">Where</div>
                <div class = "answer">'.$answers[3].'</div>
                <div class = "question_title">Conditions Tested</div>
                <div class = "answer">'.$answers[5].'</div>
                </div>'; 

            $html .= '<div class = "whole_container">
                <div class = "section_title2">Tester Testimony</div>
                <div class = "short_question">Looks 1-10?</div>
                <div class = "long_question"> How likely to buy </div>
                <div class = "short_answer">'.$answers[17].'</div>
                <div class = "long_answer">'.$answers[18].'</div>                   
                <div class = "question_title"> Boot Performance</div>
                <div class = "answer">'.$answers[7].'</div>
                <div class = "question_title"> Personal Comment and why?</div>
                <div class = "answer">'.$answers[19].'</div>
                </div>';
                return $html;
        }
        else{
            $html.= '<div class = "question_title"> Touring Boot?</div>
                <div class = "answer">'.$answers[12].'</div>
                </div>';

                $html .= '<div class = "short_container">
                <div class = "section_title">Testing Context</div>
                <div class = "question_title">Terrain Tested</div>
                <div class = "answer">'.$answers[4].'</div>
                <div class = "question_title">Where</div>
                <div class = "answer">'.$answers[3].'</div>
                <div class = "question_title">Conditions Tested</div>
                <div class = "answer">'.$answers[5].'</div>
                </div>'; 

            $html .= '<div class = "whole_container">
                <div class = "section_title2">Tester Testimony</div>
                <div class = "short_question">Looks 1-10?</div>
                <div class = "long_question"> How likely to buy </div>
                <div class = "short_answer">'.$answers[13].'</div>
                <div class = "long_answer">'.$answers[14].'</div>                   
                <div class = "question_title"> Boot Performance</div>
                <div class = "answer">'.$answers[7].'</div>
                <div class = "question_title"> Personal Comment?</div>
                <div class = "answer">'.$answers[15].'</div>
                </div>'; 
                return $html;
        }

    }

    if ($form_id == 6){ 

        $html .= '<div class = "whole_container">
            <div class = "section_title2">Product Review</div>
            <div class = "short_question"> Where</div>
            <div class = "long_answer">'.$answers[2].'</div>          
            <div class = "short_question"> Feel 1-10</div>
            <div class = "long_answer">'.$answers[5].'</div>
            <div class = "short_question"> Weather Resistance</div>
            <div class = "long_answer">'.$answers[6].'</div> 
            <div class = "short_question"> Breathability</div>
            <div class = "long_answer">'.$answers[7].'</div>      
            <div class = "short_question"> Warmth</div>
            <div class = "long_answer">'.$answers[8].'</div>
            <div class = "short_question"> Do you run hot or cold?</div>
            <div class = "long_answer">'.$answers[9].'</div>
            <div class = "short_question"> Looks 1-10?</div>
            <div class = "long_answer">'.$answers[12].'</div> 
            <div class = "short_question"> How likely to buy?</div>
            <div class = "long_answer">'.$answers[13].'</div> 
            <div class = "short_question"> Has the features you want?</div>
            <div class = "long_question"> elaboration </div>
            <div class = "short_answer">'.$answers[10].'</div>
            <div class = "long_answer">'.$answers[11].'</div>
            <div class = "short_question"> Fit</div>
            <div class = "long_question"> elaboration </div>
            <div class = "short_answer">'.$answers[3].'</div>
            <div class = "long_answer">'.$answers[4].'</div>

            <div class = "question_title"> Personal Comment?</div>
            <div class = "answer">'.$answers[14].'</div>
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
        fwrite($file,$userID);
        $fields['userID'] = $userID;
        $raw_fields = $record->get('fields');
        $fields['heightFeet'] = $raw_fields["height_feet"]['value'];
        $fields['heightInches'] = $raw_fields["height_inches"]['value'];
        $fields['weight'] = $raw_fields["weight"]['value'];
        $fields['skiAbility'] = $raw_fields["user_experience"]['value'];

        $q = "SELECT userID FROM $user_table_name WHERE userID = $userID;";
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
    fwrite($file,"fluent_summit_review_from_sub\n\n");
    $existing_form_names = get_all_form_names($file);
    //$form_name_str = print_r($existing_form_names, true);
    //fwrite($file, "registered form names:\n".$form_name_str."\n");
    $current_form_name = $form->title; 
    //fwrite($file, $current_form_name."\n");
    $current_form_id = $form->id;

    //$form_p = print_r($form, true);
    //$form_fields = $form->get( "form_fields" );
    $formData_p = print_r($formData, true);
    fwrite($file, "formData 1: \n".$formData_p."\n\n");

    $qs_and_as = fluent_get_fields_array($formData, $file);
    //$qs_and_as_p = print_r($qs_and_as, true);
    //fwrite($file, "questions and answers: \n".$qs_and_as_p."\n\n");
    
    if(in_array($current_form_name, $existing_form_names)){
        fwrite($file, "Starting the Process \n\n");
        //-----------------write to tables-----------------------------
        summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);
        //---------------create custom post-----------------------------------
        $header = summit_form_submission_custom_post_content($current_form_id, $qs_and_as, $file);
        $header_info_read = print_r($header, true);
        fwrite($file, "HEADER: \n".$header_info_read."\n\n");
        insert_into_ski_review($header, $qs, $as, $file);
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
    fwrite($file,"form names: ".$existing_form_names."\n");

    $current_form_name = $record->get_form_settings( 'form_name' ); 
    fwrite($file,"current form name: ".$current_form_name."\n");

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
        summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);

        //---------------create custom post-----------------------------------
        $header = summit_form_submission_custom_post_content($current_form_id, $qs_and_as, $file);
        //$header_info_read = print_r($header, true);
        //fwrite($file, "HEADER: \n".$header_info_read."\n\n");
    	insert_into_ski_review($header, $qs, $as, $file);
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
    $start = "\n\n start summit_form_submission_write_to_tabless \n";
    fwrite($file, $start);
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
    fwrite($file, "\n\nsummit_insert_into_review_answer_table function exited\n\n");
    //$ajax_handler->add_response_data( true, $output );
}

function get_all_form_names($file){
    global $wpdb;
    //$start = "\n\n SUMMIT get all form names \n";
    //fwrite($file, $start);
    $review_forms_table = $wpdb->prefix . "bcr_review_forms";
    //$q = "SELECT reviewFormName FROM $review_forms_table;";
    $q = "SELECT reviewFormName FROM `wp_bcr_review_forms`";
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
    $start = "\n\n SUMMIT INSERT INTO ANSWER TABLE \n";
    fwrite($file, $start);
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
        $start = "\n\nSUMMIT INSERT INTO REVIEW TABLE \n";
        fwrite($file, $start);
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
        $fields_review['knowThyselfID'] = 4;//NOT NESSESARY ANYMORE get this id from bcr_know_thyself using userid HAS TO EXIST BEFORE SUBMISSION

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

function get_knowthyself_id($userID){
    global $wpdb;
    $KTS_table = $wpdb->prefix . "bcr_know_thyself";
    $q = "SELECT * FROM $KTS_table WHERE %s = %d;";
    $res = $wpdb->get_results($q);
    return $res->knowThyselfID;
}

function summit_insert_into_review_answer_table($review_id, $answer_ids,$file){
    //insert answer ids int review answer table
        global $wpdb;
        $start = "\n\nSUMMIT INSERT INTO REVIEW ANSWER TABLE \n";
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

// --------------getters for creating custom post--------------------------

function summit_form_submission_custom_post_content($current_form_id,$record,$file){
    $start = "\n\n summit_form_submission_custom_post_content \n";
    fwrite($file, $start);
    $product_info = get_product_info($current_form_id,$file);
    $p_info_read = print_r($product_info, true);
    fwrite($file, "product info: \n".$p_info_read."\n\n");
    
    $category_info = get_category_info($product_info['categoryID'], $file);
    $c_info_read = print_r($category_info, true);
    fwrite($file, "categroy info: \n".$c_info_read."\n\n");
    
    $sport_info = get_sport_info($category_info['sportID']);
    $s_info_read = print_r($sport_info, true);
    fwrite($file, "sport info: \n".$s_info_read."\n\n");
    
    $q_and_a_content = get_answer_and_question_content($record,$file);
    $qa_info_read = print_r($q_and_a_content, true);
    fwrite($file, "QandA info: \n".$qa_info_read."\n\n");
    
    $user_info = get_user_information($file);
    $u_info_read = print_r($user_info, true);
    fwrite($file, "user info: \n".$u_info_read."\n\n");
   
   $qs = $q_and_a_content['question_content'];
   $as = $q_and_a_content['answer_content'];

    $header = array(
        'reviewID' => $current_form_id,
        'productName' => $product_info['productName'],
        'categoryName' => $category_info['categoryName'],
        'sportName' => $sport_info['sportName'],
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
    $question_ids = array_keys($record);//manually entered into elementor form, until we can make a form dynamically 
    
    //$raw_fields = $record->get( 'fields' );
    //$output = [];
    //sufficient to split array into array keys == question_ids and values  == answer_content
    /*foreach ( $record as $id => $field) {
        if($id != "step"){
            array_push($answer_content, $field);
            array_push($question_ids, $id);
        }
    }*/
    //fwrite($file, implode(", ", $question_ids)." \n");
    $question_content = [];
    $question_table = $wpdb->prefix . "bcr_questions";
    $desired_column = "questionContent";
    $where_column = "questionID";
    foreach($question_ids as $id){
    	$q = "SELECT questionContent FROM $question_table WHERE questionID = $id;";
    	$q_content = $wpdb->get_row($q);
    	//$var = print_r($q_content, true);
    	//fwrite($file,"get redults: \n".$var."\n");
    	$content = $q_content->questionContent;
    	//fwrite($file,"contne from get result: \n".$content."\n");
    	array_push($question_content, $content);
    }
    //fwrite($file, implode(", ", $question_content)." \n");
    //fwrite($file, implode(", ", $answer_content)." \n");
    $return_array['question_content'] = $question_content;
    $return_array['answer_content'] = $answer_content;
   
    return $return_array;
}

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
	return $return_array;
}

function get_category_info($category_id, $file){//may just want to return res !!!!!!
	global $wpdb;
	$start = "\n\n GET CATEGORY INFORMATION \n";
    fwrite($file, $start);
    //fwrite($file, "category id: ".$category_id."\n");
	$category_table = $wpdb->prefix . "bcr_categories";
	$q = "SELECT * FROM $category_table WHERE categoryID = $category_id;";
	$res = $wpdb->get_row($q);
	//$var = print_r($res, true);
    //fwrite($file,"get results: \n".$var."\n");
	$return_array = [];
	$return_array['categoryID'] = $category_id;
	$return_array['categoryName'] = $res->categoryName;
	$return_array['parentID'] = $res->parentID;
	$return_array['sportID'] = $res->sportID;
	return $return_array;
}

function get_sport_info($sport_id){ //may just want to return res !!!!!!
	global $wpdb;
	$sports_table = $wpdb->prefix . "bcr_sports";
	$q = "SELECT * FROM $sports_table WHERE sportID = $sport_id;";
	$res = $wpdb->get_row($q);
	$return_array = [];
	$return_array['sportID'] = $sport_id;
	$return_array['sportName'] = $res->sportName;
	return $return_array;
}

function get_user_information($file){
    global $wpdb;
    $start = "\n\n GET USER INFORMATION \n";
    fwrite($file, $start);
    $userID = get_current_userID($file);
    //fwrite($file, "user id: ".$userID."\n");
    $user_table_name = $wpdb->prefix . "bcr_users";//i do not have this same able 
    $queryString = "SELECT * FROM $user_table_name WHERE userID=$userID";
    $userInformation = $wpdb->get_row($queryString);

    return $userInformation;
}

?>
