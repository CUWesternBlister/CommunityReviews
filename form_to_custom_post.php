<?php
require 'table_utils.php';
require 'table_writing_functions.php';
require 'table_reading_functions.php';
require 'community_reviews_custom_post.php';

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

    session_start();

    $prev_url = isset($_SESSION['prev_url']) ? $_SESSION['prev_url'] : '';

    $redirect_to = $record->replace_setting_shortcodes( $prev_url );

	$ajax_handler->add_response_data( 'redirect_url', $redirect_to );
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
        $header_info_read = print_r($header, true);
        fwrite($file, "HEADER: \n".$header_info_read."\n\n");
        insert_into_ski_review($header, $file, $current_form_name);
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
    	insert_into_ski_review($header, $file, $current_form_name);
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

function get_all_form_names($file){
    global $wpdb;
    //$start = "\n\n SUMMIT get all form names \n";
    //fwrite($file, $start);
    $review_forms_table = $wpdb->prefix . "bcr_review_forms";
    $q = $wpdb->prepare("SELECT reviewFormName FROM $review_forms_table;");
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
//---------------------------------------
function summit_form_submission_write_to_tables($current_form_id, $record, $file){
    //$start = "\n\n start summit_form_submission_write_to_tabless \n";
    //fwrite($file, $start);
    $answer_ids = insert_into_answer_table($record, $file);
    //fwrite($file, "write to answer table ids:\n".$answer_ids."\n\n") or die('fwrite 1 failed');
    //$res1 = implode(", ", $answer_ids)."\n";
    //fwrite($file, $res1) or die('fwrite 2 failed');

    //$ajax_handler->add_response_data( true, $output );
    //fwrite($file, "write to review table id:\n") or die('fwrite 3 failed');
    $id = insert_into_review_table($current_form_id,$file);
    //fwrite($file, "last inserted review id: ".strval($id)."\n") or die('fwrite 2 failed');
    //$ajax_handler->add_response_data( true, $output );
    insert_into_review_answer_table($id, $answer_ids, $file);
    //fwrite($file, "\n\nsummit_insert_into_review_answer_table function exited\n\n");
    //$ajax_handler->add_response_data( true, $output );
    return $id;
}

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
    //$brand_info = get_brand_info($brand_name, $file);
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

    $header = array(
        'reviewID' => $current_review_id, 
        'formID' => $current_form_id,
        'productName' => $product_info['productName'],
        'brandName' => $brand_name,
        'categoryName' => $category_info->categoryName,
        'questions_and_answers' => $q_and_a_content,
        'userInfo' => $user_info
    );
    
    return $header;
}

?>
