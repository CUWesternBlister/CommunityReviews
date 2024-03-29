<?php

include 'generate_html.php';

function insert_into_ski_review($header, $file, $formName, $form_id) {
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
    $ordered_title_arr = make_ordered_title_arr($title_arr);
   
    $postTitle = get_post_title($ordered_title_arr);

    $years = "";
    $length = "";
    $ski_boot_size = "";
    foreach($ordered_title_arr as $key => $arr){
        if(strtolower($arr['question'])=='model-year'){$years = $arr['answer'];}
        else if(strtolower($arr['question'])=='length'){$length = intval($arr['answer']);}
        else if(strtolower($arr['question'])=='boot size'){$ski_boot_size = intval($arr['answer']);}
    }

    $years_arr = explode("-", $years);
    $year = $years_arr[0];


    $ski_review = array(
                        'post_title' => wp_strip_all_tags($postTitle), 
                        'post_content' => $html,
                        'meta_input' => array(
                                                'id'            => $header['reviewID'],
                                                'formID'        => $form_id,
                                                'userID'        => $userInfo->userID,
                                                'userName'      => $userName,
                                                'height'        => $userInfo->height,
                                                'weight'        => $userInfo->weight,
                                                'skiAbility'    => $userInfo->skiAbility,
                                                'product_tested'=> $header['productName'],
                                                'brand'         => $header['brandName'],
                                                'category'      => $header['categoryName'],
                                                'sport'         => $header['sportName'],
                                                'FlaggedForReview' => $header['flagForReview'],
                                                'year'          => $year,
                                                'length'        => $length,
                                                'boot_size'     => $ski_boot_size,
                                                'qs_and_as_arr' => $header["questions_and_answers"]
                                            ), 
                        'post_type'   => 'Community Reviews',
                        'post_excerpt' => $user_html,
                        'post_status' => 'publish',
                        );
    wp_insert_post( $ski_review );     
}

function make_ordered_title_arr($originalArray) {
    $indexedArray = array();
    foreach ($originalArray as $key => $value) {
      $indexedArray[$value['title_order']] = $value;
    }
    ksort($indexedArray);
    return $indexedArray;
  }

?>