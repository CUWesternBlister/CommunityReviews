<?php

require 'table_utils.php';
require 'table_writing_functions.php';
require 'table_reading_functions.php';
require 'insert_custom_post.php';


function fluent_summit_review_form_sub($entryId, $formData, $form) {
    global $wpdb;
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt'; 
    $file = fopen($file_path, "w") or die('fopen failed');
    $existing_form_names = get_all_form_names($file);
    
    $current_form_name = $form->title; 
    
    $current_form_id = $form->id;

    //$qs_and_as = fluent_get_fields_array_formData($formData, $file);
    $qs_and_as = fluent_get_fields_array_form($form, $formData);
    
    if(in_array($current_form_name, $existing_form_names)){
        //-----------------write to tables (table_writing_functions)-----------------------------
        $review_id = summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);
        //---------------create custom post (insert_custom_post)-----------------------------------
        $header = summit_form_submission_custom_post_content($review_id, $current_form_id, $qs_and_as, $file);
        insert_into_ski_review($header, $file, $current_form_name, $current_form_id);
    }
    fclose($file);
}

function fluent_get_fields_array_formData($formData, $file){
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

function fluent_get_fields_array_form($form, $formData){
    // echo "Fields: <br>".var_dump($formData)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    // echo "Fields: <br>".var_dump($form)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    $fields = json_decode($form->form_fields)->fields;
    // echo "Fields: <br>".var_dump($fields)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    $return_array = [];
    foreach($fields as $id => $obj){
        $settings = $obj->settings;
        $attributes = $obj->attributes;
        $name =  $attributes->name;
        $id = intval(preg_replace('/\D/', '',$settings->admin_field_label));
        $answer = $formData[$name];
        if(($id == 1 || $id == 2) && (str_contains(strtolower($answer), "other") || str_contains(strtolower($answer), "not listed"))){ $answer = null;}
        if(gettype($answer) == "array"){ $answer = implode(", ", $answer); }
        // echo "Name Attribute: $name<br>";
        // echo "Answer: <br>".$answer."<br>";
        // echo "Admin ID: <br>".strval($id)."<br>";
        // echo "------------------------------------------------------------<br>";
        if(!array_key_exists($id, $return_array) || $return_array[$id] == null){
            $return_array[$id] = $answer;
        }
    }
    foreach ($return_array as $key => $value) {
        if ($value === null) {
            unset($return_array[$key]);
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
    $answer_ids = insert_into_answer_table($record, $file);
    $id = insert_into_review_table($current_form_id, $record[2], $record[1], $file);
    insert_into_review_answer_table($id, $answer_ids, $file);
    return $id;
}

function summit_form_submission_custom_post_content($current_review_id, $current_form_id, $record, $file){
    $product_info = [];
    $product_info['productName'] = $record[2];
    
    $brand_name = $record[1];

    $category_info = get_category_info($current_form_id, $file);

    $sport_info = get_sport_info($category_info->categoryName);

    $q_and_a_content = get_answer_and_question_content($record,$file);
    
    $user_info = get_user_information($file);

    $flagForReview = 0;
    if((!check_for_brand($brand_name)) || (!check_for_product($product_info['productName']))){
        $flagForReview = 1;
        //echo "review flagged<br>";
    }

    $header = array(
        'reviewID' => $current_review_id, 
        'formID' => $current_form_id,
        'productName' => $product_info['productName'],
        'brandName' => $brand_name,
        'categoryName' => $category_info->categoryName,
        'questions_and_answers' => $q_and_a_content,
        'userInfo' => $user_info,
        'sportName' => $sport_info->categoryName,
        'flagForReview' => $flagForReview
    );
    
    return $header;
}

?>