<?php
require 'table_utils.php';
function insert_into_ski_review($header, $questions, $answers, $file) {
        global $wpdb;

        //$answersContent = $header['answersContent'];
        fwrite($file, "answer content grabbed\n".implode(', ', $answers)."---------\n\n");
    
        $userInfo = $header['userInfo'];
        fwrite($file, "user info grabbed \n");
        $u_info = print_r($userInfo, true);
        fwrite($file, "post user info: \n".$u_info."\n\n");
        
        if ( NULL === $header || 0 === $header || '0' === $header || empty( $header ) ) {
            return;
        }

        $html = format_questions_answers_post_content($questions, $answers);
        fwrite($file, "HTML STRING: \n".$html."\n\n");

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
                            'post_type'   => 'Ski Reviews',
                            'post_excerpt' => $user_html,
                            'post_status' => 'publish',
                            );
        $custom_post_input = print_r($ski_review, true);
        fwrite($file, "Post array: \n".$custom_post_input."\n\n");
        wp_insert_post( $ski_review );
        
    }

function format_questions_answers_post_content($questions, $answers){
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

    $html .= '<div style = "text-align:justify;">
              <div style = "width:33.3%;padding:0 0 0 0;float:left;font-weight:bold;">Terrain Tested</div>
              <div style = "width:33.3%;padding:0 0 0 0;float:left;font-weight:bold;">Where</div>
              <div style = "width:33.3%;padding:0 0 0 0;float:right;font-weight:bold;">Conditions Tested</div>
              </div>
              <div style="clear:both;"></div>';

    $html .= '<div style = "text-align:left;">
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[4].'</div>
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[3].'</div>
              <div style = "width:33.3%;padding:10px 0 0 0;float:right;column_gap:20px;">'.$answers[5].'</div>
              </div>
              <div style="clear:both;"></div>';       


    $html .= '<div style = "text-align:Justify;">
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold">Stablility?</div>
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold">Maniveurability?</div>
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold">Demand of ski</div>
              <div style="clear:both;"></div>';


    $html .= '<div style = "text-align:left;">
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">  '.$answers[7].$answers[8].'</div>
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[6].'</div>
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">  '.$answers[9].'</div>
              <div style="clear:both;"></div>';


    $html .= '<div style = "text-align:Justify;">
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> Ride Quality</div>
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> Fun Factor</div>
              <div style = "width:33.3%;padding:30px 0 0 0;float:right;font-weight:bold;"> Ski felt good on...</div>
              <div style="clear:both;"></div>';

    $html .= '<div style = "text-align:left;">
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[10].'</div>
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[12].'</div>
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[11].'</div>              
              <div style="clear:both;"></div>';

    $html .= '<div style = "text-align:Justify;">
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> Correct Lenght?</div>
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> Who will like this Ski?</div>
              <div style="clear:both;"></div>';


    $html .= '<div style = "text-align:left;">
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[13].'</div>
              <div style = "width:66.6%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[14].'</div>          
              <div style="clear:both;"></div>';

    $html .= '<div style = "text-align:Justify;">
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> How good 1-10?</div>
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> elaboration </div>
              <div style="clear:both;"></div>';

    $html .= '<div style = "text-align:left;">
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[15].'</div>
              <div style = "width:66.6%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[16].'</div>          
              <div style="clear:both;"></div>';

    $html .= '<div style = "text-align:Justify;">
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> Recommend?</div>
              <div style = "width:33.3%;padding:30px 0 0 0;float:left;font-weight:bold;"> elaboration </div>
              <div style="clear:both;"></div>'; 

    $html .= '<div style = "text-align:left;">
              <div style = "width:33.3%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[17].'</div>
              <div style = "width:66.6%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[18].'</div>          
              <div style="clear:both;"></div>';  

    $html .= '<div style = "text-align:Justify;">
              <div style = "width:100%;padding:30px 0 0 0;float:left;font-weight:bold;"> Prefered Similar Products? and why?</div>
              <div style = "width:100%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[19].'</div>
              <div style="clear:both;"></div>'; 

    $html .= '<div style = "text-align:Justify;">
              <div style = "width:100%;padding:30px 0 0 0;float:left;font-weight:bold;"> Personal Comment</div>
              <div style = "width:100%;padding:10px 0 0 0;float:left;column_gap:20px;">'.$answers[20].'</div>
              <div style="clear:both;"></div>'; 

	return $html;
}

function profile_info_sub( $record, $ajax_handler ){
    global $wpdb;
    $user_table_name = $wpdb->prefix . "bcr_users";
    $form_name = $record->get_form_settings( 'form_name' );
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $myfile = fopen($file_path, "a") or die('fopen failed');
    $fields = [];
    if($form_name == 'Profile Builder') {
        $userID = get_current_userID($myfile);
        fwrite($myfile,$userID);
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
    	//-----------------write to tables-----------------------------
        $answer_ids = summit_insert_into_answer_table($record, $myfile);
        fwrite($myfile, "write to answer table ids:\n") or die('fwrite 1 failed');
        $res1 = implode(", ", $answer_ids)."\n";
        fwrite($myfile, $res1) or die('fwrite 2 failed');


        //$ajax_handler->add_response_data( true, $output );
        fwrite($myfile, "write to review table id:\n") or die('fwrite 3 failed');
        $id = summit_insert_into_review_table($current_form_id,$myfile);
        fwrite($myfile, "last inserted review id: ".strval($id)."\n") or die('fwrite 2 failed');
        //$ajax_handler->add_response_data( true, $output );
        

        summit_insert_into_review_answer_table($id, $answer_ids,$myfile);

        //---------------create custom post-----------------------------------
       
        $product_info = get_product_info($current_form_id,$myfile);
        $p_info_read = print_r($product_info, true);
        fwrite($myfile, "product info: \n".$p_info_read."\n\n");
        
        $category_info = get_category_info($product_info['categoryID'], $myfile);
        $c_info_read = print_r($category_info, true);
        fwrite($myfile, "categroy info: \n".$c_info_read."\n\n");
        
        $sport_info = get_sport_info($category_info['sportID']);
        $s_info_read = print_r($sport_info, true);
        fwrite($myfile, "sport info: \n".$s_info_read."\n\n"); 
        
        $q_and_a_content = get_answer_and_question_content($record,$myfile);
        $qa_info_read = print_r($q_and_a_content, true);
        fwrite($myfile, "QandA info: \n".$qa_info_read."\n\n");
        
        $user_info = get_user_information($myfile);
        $u_info_read = print_r($user_info, true);
        fwrite($myfile, "user info: \n".$u_info_read."\n\n");
       
       $qs = $q_and_a_content['question_content'];
       $as = $q_and_a_content['answer_content'];

        $header = array(
        	'reviewID' => $id,
        	'productName' => $product_info['productName'],
        	'categoryName' => $category_info['categoryName'],
        	'sportName' => $sport_info['sportName'],
        	'questionContent' => $q_and_a_content['question_content'],
        	'answerContent' => $q_and_a_content['answer_content'],
        	'userInfo' => $user_info
        );
        $header_info_read = print_r($header, true);
        fwrite($myfile, "HEADER: \n".$header_info_read."\n\n");
    	insert_into_ski_review($header, $qs, $as, $myfile);
	}
}

//-----------------------------usefull functions------------------------------------

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

//------------------------------tabel writing stuff-----------------------------------

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

function get_answer_and_question_content($record,$file){
    global $wpdb;
    $start = "\n\n GET ANSWERS AND QUESTIONS \n";
    fwrite($file, $start);

    $return_array = [];
    //$answer_ids = []; //used for when inserting into reviews answers
    $answer_content = [];
    $question_ids = [];//manually entered into elementor form, until we can make a form dynamically 
    
    $raw_fields = $record->get( 'fields' );
    $output = [];
    foreach ( $raw_fields as $id => $field ) {
        if($id != "step"){
            array_push($answer_content, $field['value']);
            array_push($question_ids, $id);
        }
    }
    fwrite($file, implode(", ", $question_ids)." \n");
    $question_table = $wpdb->prefix . "bcr_questions";
    $question_content = [];
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

    fwrite($file, implode(", ", $question_content)." \n");
    fwrite($file, implode(", ", $answer_content)." \n");
    $return_array['question_content'] = $question_content;
    $return_array['answer_content'] = $answer_content;
   
    return $return_array;
}

function get_product_info($form_id,$file){
	global $wpdb;
	$start = "\n\n GET PRODUCT INFORMATION \n";
    fwrite($file, $start);
    fwrite($file, "form id: ".$form_id."\n");
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

function get_category_info($category_id, $file){
	global $wpdb;
	$start = "\n\n GET CATEGORY INFORMATION \n";
    fwrite($file, $start);
    fwrite($file, "category id: ".$category_id."\n");
	$category_table = $wpdb->prefix . "bcr_categories";
	$q = "SELECT * FROM $category_table WHERE categoryID = $category_id;";
	$res = $wpdb->get_row($q);
	$var = print_r($res, true);
    fwrite($file,"get results: \n".$var."\n");
	$return_array = [];
	$return_array['categoryID'] = $category_id;
	$return_array['categoryName'] = $res->categoryName;
	$return_array['parentID'] = $res->parentID;
	$return_array['sportID'] = $res->sportID;
	return $return_array;
}

function get_sport_info($sport_id){
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
    fwrite($file, "user id: ".$userID."\n");
    $user_table_name = $wpdb->prefix . "bcr_users";//i do not have this same able 
    $queryString = "SELECT * FROM $user_table_name WHERE userID=$userID";
    $userInformation = $wpdb->get_row($queryString);

    return $userInformation;
}

?>
