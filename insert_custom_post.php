<?php

include 'generate_html.php';

function insert_into_ski_review($header, $file, $formName) {
    global $wpdb;

    $userInfo = $header['userInfo'];
    $u_info = print_r($userInfo, true);
    //fwrite($file, "post user info: \n".$u_info."\n\n");
    
    $current_userData = get_userdata($userInfo->userID);
    $userName = $current_userData->display_name;
    //$userName = get_userName_by_userID($userInfo->userID,$file);
    fwrite($file, "user name: ".$userName." \n");
    
    if ( NULL === $header || 0 === $header || '0' === $header || empty( $header ) ) {
        return;
    }
    
    $html = format_questions_answers_post_content($header["questions_and_answers"],$formName,$file);
    
    $user_html.='<div id= "userName" class = "userInfo">Reviewer: '.esc_html($userName). '</div>
                <div id= "userHeight" class = "userInfo">Height: '.esc_html($userInfo->heightFeet).' ft, '.esc_html($userInfo->heightInches).' in</div>
                <div id "userWeight" class = "userInfo">Weight: '.esc_html($userInfo->weight).' lbs</div>
                <div id= "userAbility" class = "userInfo">Reviewer Ability: '.esc_html($userInfo->skiAbility).'</div>';
    $html = $user_html . $html;

    $title_arr = $header["questions_and_answers"]["title"];
    //$custom_post_input = print_r($title_arr, true);
    //fwrite($file, "tile array: \n".$custom_post_input."\n\n");
    
    $postTitle = get_post_title($title_arr);

    //fwrite($file, "\n".$html."\n");
    //fetch user name to insert
    ////wp_strip_all_tags( $header['brandName'] . ' ' . $header['productName']),
    $ski_review = array(
                        'post_title' => wp_strip_all_tags($postTitle), 
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

function get_post_title($title_arr){
$str = "";
$year = "";
$html = "";
foreach($title_arr as $arr){
    if($arr['id'] == 9){
            $year .= $arr["answer"] . ' ';
    }
    else{
        if($arr['id'] == 3){
            $str.=$arr["answer"] . 'cm ';
        }else{
            $str.=$arr["answer"] . ' ';  
        }  
    }
}
$html = $year . $str; 
return $html;
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