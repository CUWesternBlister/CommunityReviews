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
        $review_id = -1;
        //summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);
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
    echo "Formdata: <br>";
    echo var_dump($formData)."<br><br>";
    echo "------------------------------------------------------------<br>";
    // echo "Form: <br>";
    // echo var_dump($form)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    $fields = json_decode($form->form_fields)->fields;
    // echo "Fields: <br>".var_dump($fields)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    $return_array = array(
        'title' => array(),
        'testingConditions' => array(),
        'multipleChoice' => array(),
        'testimony' => array()
    );
    foreach($fields as $id => $obj){
        echo "Field:".strval($id)."<br>";
        echo var_dump($obj)."<br><br>";
        echo "------------------------------------------------------------<br>";
        $ignore_fields = ['section_break', 'form_step', 'custom_submit_button'];
        if(!in_array($obj->element, $ignore_fields)){
            $settings = $obj->settings;
            $questionDisplayContent = $settings->admin_field_label;
            $type = $settings->container_class;
            $title_arr = explode("_", $type);
            $title_order = "";
            if(!($title_arr[0] == "")){
                $type = $title_arr[0];
                $title_order = $title_arr[1];
                //echo "title arr: ".print_r($title_arr,true)."<br>";
            }
            
            $attributes = $obj->attributes;
            $name =  $attributes->name;
            $id = intval(preg_replace('/\D/', '',$type));
            $answer = null;
            if(array_key_exists($name, $formData)){
                $answer = $formData[$name];
            }
            

            if((strtolower($questionDisplayContent) == 'brand' || strtolower($questionDisplayContent) == 'product') && (str_contains(strtolower($answer), "other") || str_contains(strtolower($answer), "not listed"))){ $answer = null;}
            if(gettype($answer) == "array"){ $answer = implode(", ", $answer); }
            
            if(!is_null($answer)){ 
                // echo "Name: ".$name."<br>";
                // echo "Type: ".$type."<br>";
                // echo "questionContent: ".$questionContent."<br>";
                // echo "answer: ".$answer."<br>";
                // echo "------------------------------------------------------------<br>";
                if($title_order !== ""){
                    $return_array[$type][$name] = ["question" => $questionDisplayContent, "answer" => $answer, "title_order" => $title_order, 'name_attribute' => $name];
                }else{
                    $return_array[$type][$name] = ["question" => $questionDisplayContent, "answer" => $answer, 'name_attribute' => $name];
                }  
            }
        }
    }
    // echo "return array: <br>";
    // echo var_dump($return_array)."<br><br>";
    // echo "------------------------------------------------------------<br>";
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
        //$review_id = summit_form_submission_write_to_tables($current_form_id, $qs_and_as, $file);
        $review_id = -1;
        //---------------create custom post-----------------------------------
        $header = summit_form_submission_custom_post_content($review_id, $current_form_id, $qs_and_as, $file);
    	//insert_into_ski_review($header, $file, $current_form_name);
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
    $first_two_elements = array_slice($record['title'], 0, 2);
    $new_keys = array('0', '1');
    $first_two_elements = array_combine($new_keys, $first_two_elements);
    // echo "first two elements: <br>";
    // echo var_dump($first_two_elements)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    $product_name = $first_two_elements[1]['answer'];
    // echo "product: ".$product_name."<br><br>";
    $brand_name = $first_two_elements[0]['answer'];
    // echo "brand: ".$brand_name."<br>";
    $category_info = get_category_info($current_form_id, $file);

    $sport_info = get_sport_info($category_info->categoryName);

    // echo "record: <br>";
    // echo var_dump($record)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    $q_and_a_content = $record;#get_answer_and_question_content($record,$file);
    
    $user_info = get_user_information($file);

    $flagForReview = 0;
    if((!check_for_brand($brand_name)) || (!check_for_product($product_name))){
        $flagForReview = 1;
        //echo "review flagged<br>";
    }

    $header = array(
        'reviewID' => $current_review_id, 
        'formID' => $current_form_id,
        'productName' => $product_name,
        'brandName' => $brand_name,
        'categoryName' => $category_info->categoryName,
        'questions_and_answers' => $q_and_a_content,
        'userInfo' => $user_info,
        'sportName' => $sport_info->categoryName,
        'flagForReview' => $flagForReview
    );
    // echo "Header: <br>";
    // echo var_dump($header)."<br>";
    // echo "------------------------------------------------------------<br><br>";
    // echo "record: <br>";
    // echo var_dump($record)."<br><br>";
    // echo "------------------------------------------------------------<br>";
    return $header;
}

?>