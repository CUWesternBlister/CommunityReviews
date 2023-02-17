<?php
/* 
* all of these functions read form bcr tables
*
*
*
*/

function get_answer_and_question_content($record,$file){
    global $wpdb;
    //$start = "\n\n GET ANSWERS AND QUESTIONS \n";
    //fwrite($file, $start);

    $return_array = array(
        'title' => array(),
        'testingConditions' => array(),
        'multipleChoice' => array(),
        'testimony' => array()
    );

    //$answer_ids = []; //used for when inserting into reviews answers
    $answer_content = array_values($record);
    $question_ids = array_keys($record);//manually entered into 
    
    $question_content = [];
    $question_table = $wpdb->prefix . "bcr_questions";
    $desired_column = "questionContent";
    $where_column = "questionID";

    $i = 0;
    foreach($question_ids as $id){
    	$q = $wpdb->prepare("SELECT questionDisplayContent FROM $question_table WHERE questionID = $id;");
    	$q_content = $wpdb->get_row($q);
        $type = $q_content->questionType;
        $display = $q_content->questionDisplayContent;
        $answer = $answer_content[$i];
        $obj = ["id" => $id, "question" => $display, "answer" => $answer];
        //fwrite($file, "question type: " . $type. "\n");
        //fwrite($file, "question display: " . $display. "\n");
        //fwrite($file, "question answer: " . $answer. "\n");
        //fwrite($file, "-----------------------------\n");
        $return_array[$type][] = $obj;
        $i += 1;
    }
   
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
*/

/* ---- this one will be usefull for when product table is filled out.
function get_product_info($product_name, $file){
    $product_table = $wpdb->prefix . "bcr_products";
    $q = $wpdb->prepare("SELECT * FROM $product_table WHERE productName = '".$product_name."';");
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
    $q = $wpdb->prepare("SELECT * FROM $brand_table WHERE brandName = '".$brand_name."';");
    //fwrite($file, "query: ".$q."\n");
    $res = $wpdb->get_row($q);
    //fwrite($file,"get results type: \n".gettype($res)."\n");
    //$var = print_r($res, true);
    //fwrite($file,"get results: \n".$var."\n");
    return $res;
}

function get_category_info($form_id, $file){//may just want to return res !!!!!!
    global $wpdb;
    //$start = "\n\n GET CATEGORY INFORMATION \n";
    //fwrite($file, $start);
    //fwrite($file, "category id: ".$category_id."\n");

    $form_table = $wpdb->prefix . "bcr_review_forms";
    $q1 = $wpdb->prepare("SELECT * FROM $form_table WHERE reviewFormID = $form_id;");
    $res1 = $wpdb->get_row($q1);
    //$var = print_r($res1, true);
    //fwrite($file,"get results: \n".$var."\n");
    $category_id = $res1->categoryID;

    $category_table = $wpdb->prefix . "bcr_categories";
    $q = $wpdb->prepare("SELECT * FROM $category_table WHERE categoryID = $category_id;");
    $res = $wpdb->get_row($q);
    //$var = print_r($res, true);
    //fwrite($file,"get results: \n".$var."\n");
    return $res;
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