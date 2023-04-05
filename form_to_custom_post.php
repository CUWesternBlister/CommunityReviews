<?php

require 'table_utils.php';
require 'table_writing_functions.php';
require 'table_reading_functions.php';
require 'insert_custom_post.php';

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
    
    $current_form_name = $form->title; 
    
    $current_form_id = $form->id;

    $qs_and_as = fluent_get_fields_array($formData, $file);
    
    if(in_array($current_form_name, $existing_form_names)){
        //-----------------write to tables (table_writing_functions)-----------------------------
        $review_id = summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);
        //---------------create custom post (insert_custom_post)-----------------------------------
        $header = summit_form_submission_custom_post_content($review_id, $current_form_id, $qs_and_as, $file);
        //$header_info_read = print_r($header, true);
        //fwrite($file, "HEADER: \n".$header_info_read."\n\n");
        insert_into_ski_review($header, $file, $current_form_name, $current_form_id);
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

    $current_form_name = $record->get_form_settings( 'form_name' ); 

    $current_form_id = $record->get_form_settings('form_id');

    $raw_fields = $record->get( 'fields' );

    $qs_and_as = elementor_get_fields_array($raw_fields, $file);

    if(in_array($current_form_name, $existing_form_names)){
    	//-----------------write to tables----------------------------
        $review_id = summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);

        //---------------create custom post-----------------------------------
        $header = summit_form_submission_custom_post_content($review_id, $current_form_id, $qs_and_as, $file);
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

//---------------------------------------
function summit_form_submission_write_to_tables($current_form_id, $record, $file){
    //$start = "\n\n start summit_form_submission_write_to_tabless \n";
    //fwrite($file, $start);
    $answer_ids = insert_into_answer_table($record, $file);
    $id = insert_into_review_table($current_form_id, $record[2], $record[1], $file);
    insert_into_review_answer_table($id, $answer_ids, $file);
    return $id;
}

function summit_form_submission_custom_post_content($current_review_id, $current_form_id, $record, $file){
    //$start = "\n\n summit_form_submission_custom_post_content \n";
    //fwrite($file, $start);
    $product_info = [];
    $product_info['productName'] = $record[2];
    
    $brand_name = $record[1];
    //$brand_info = get_brand_info($brand_name, $file);

    $category_info = get_category_info($current_form_id, $file);

    $sport_info = get_sport_info($category_info->categoryName);

    $q_and_a_content = get_answer_and_question_content($record,$file);
    
    $user_info = get_user_information($file);

    $header = array(
        'reviewID' => $current_review_id, 
        'formID' => $current_form_id,
        'productName' => $product_info['productName'],
        'brandName' => $brand_name,
        'categoryName' => $category_info->categoryName,
        'questions_and_answers' => $q_and_a_content,
        'userInfo' => $user_info,
        'sportName' => $sport_info->categoryName
    );
    
    return $header;
}


