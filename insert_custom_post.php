<?php

include 'generate_html.php';

function insert_into_ski_review($header, $file, $formName) {
    //$start = "\n\n INSERT INTO SKI REVIEW \n";
    //fwrite($file, $start);
    global $wpdb;
    $userInfo = $header['userInfo'];
    
    $current_userData = get_userdata($userInfo->userID);
    $userName = $current_userData->display_name;
    
    if ( NULL === $header || 0 === $header || '0' === $header || empty( $header ) ) {
        return;
    }
    
    $html = format_questions_answers_post_content($header["questions_and_answers"],$formName,$file);
    
    $user_html = format_review_excerpt($userInfo, $userName, $header["questions_and_answers"]);
    $user_info_html = format_reviewerInfo($userInfo, $userName);
    $html = $user_info_html . $html;

    $title_arr = $header["questions_and_answers"]["title"];
    
    $postTitle = get_post_title($title_arr);

    $str = print_r($title_arr, true);
    echo $str."<br>";

    $year = "";
    $length = "";
    $ski_boot_size = "";
    foreach($title_arr as $key => $arr){
        if($arr['id']==9){$year = $arr['answer'];}
        else if($arr['id']==3){$length = intval($arr['answer']);}
        else if($arr['id']==22){$ski_boot_size = intval($arr['answer']);}
    }

    preg_match('/\d{4}/', $year, $matches);
    $year = intval($matches[0]);

    echo "year: ".strval($year)."<br>";
    echo "Length: ".strval($length)."<br>";
    echo "Bootsize: ".strval($ski_boot_size)."<br>";

    $height = intval($userInfo->heightFeet) * 12 + intval($userInfo->heightInches);

    $ski_review = array(
                        'post_title' => wp_strip_all_tags($postTitle), 
                        'post_content' => $html,
                        'meta_input' => array(
                            'id'            => $header['reviewID'],
                            'userID'        => $userInfo->userID,
                            'userName'      => $userName,
                            'height'        => $height,
                            'weight'        => $userInfo->weight,
                            'skiAbility'    => $userInfo->skiAbility,
                            'product_tested'=> $header['productName'],
                            'brand'         => $header['brandName'],
                            'category'      => $header['categoryName'],
                            'sport'         => $header['sportName'],
                            'length'        => $length,
                            'year'          => $year,
                            'ski_boot_size' => $ski_boot_size
                            ),
                        'post_type'   => 'Community Reviews',
                        'post_excerpt' => $user_html,
                        'post_status' => 'publish',
                        );
    wp_insert_post( $ski_review );     
}



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

    $answer_content = array_values($record);
    $question_ids = array_keys($record);   
    $question_table = $wpdb->prefix . "bcr_questions";

    $answer_arr_i = 0;
    foreach($question_ids as $id){
        $q_content = get_question_read_content($id);
        $type = $q_content->questionType;
        $display = $q_content->questionDisplayContent;
        $answer = $answer_content[$answer_arr_i];
        $obj = ["id" => $id, "question" => $display, "answer" => $answer];
        $return_array[$type][] = $obj;
        $answer_arr_i += 1;
    }
   
    return $return_array;
}

/*
function get_userName_by_userID($userID, $file){
global $wpdb;
//fwrite($file, "userID to get userName: ".$userID."\n");
$wp_user_table = $wpdb->prefix."users";
$q = $wpdb->prepare("SELECT display_name FROM $wp_user_table WHERE ID = %s;", $userID);
$res = $wpdb->get_row($q);
return $res->display_name;
}
*/
?>