<?php

function removeReview(){
    header('Access-Control-Allow-Origin: *');

    global $wpdb;

    $review_table_name = $wpdb->prefix . "bcr_reviews";

    $reviewID = $_POST['reviewID'];
    $flag = $_POST['flag'];

    if($reviewID){
        $sql = $wpdb->prepare("UPDATE $review_table_name SET FlaggedForReview = $flag WHERE reviewID = $reviewID;");
        $result = $wpdb->get_results($sql);
        return $result;
    } else{
        return -1;
    }
}

add_action( 'wp_ajax_removeReview', 'removeReview' );
add_action( 'wp_ajax_nopriv_removeReview', 'removeReview' );

function addRow(){
    header('Access-Control-Allow-Origin: *');

    global $wpdb;

    $product = $_POST['product'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $sport = $_POST['sport'];

    $brand_id = -1;
    if(check_for_brand($brand)){
        $brand_id = get_brand_id($brand);
    }else{
        $brand_id = insert_brand($brand);
    }

    $category_id = get_category_id($category);

    $product_id = -1;
    if($brand_id == -1 || !$brand_id || $category_id == -1){
        //something
    }else{
        $product_id = insert_product($product, $brand_id, $category_id);
    }

    

    


    return -1;
}
add_action( 'wp_ajax_addRow', 'addRow' );
add_action( 'wp_ajax_nopriv_addRow', 'addRow' );

?>