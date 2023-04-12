<?php

use Random\BrokenRandomEngineError;

function removeReview(){
    header('Access-Control-Allow-Origin: *');

    global $wpdb;

    $review_table_name = $wpdb->prefix . "bcr_reviews";

    $reviewID = $_POST['reviewID'];
    $flag = $_POST['flag'];
    $postID = $_POST['postID'];
    $result = [];
    if($reviewID){
        $sql = $wpdb->prepare("UPDATE $review_table_name SET FlaggedForReview = $flag WHERE reviewID = $reviewID;");
        $res = $wpdb->get_results($sql);
        $result['type'] = $res;
        $args = array(
            'post_type' => 'Community Reviews', 
            'p' => intval($postID) 
        );
        $query = new WP_Query($args);
        if ($query->have_posts()){//} && metadata_exists( 'post', $postID, 'FlaggedForReview')) {
            $update_res = update_post_meta(intval($postID), 'FlaggedForReview', $flag);
            $result['metaData update'] = $update_res;
        }    
    }
    echo json_encode($result);
    wp_die();
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
    $postID = $_POST['postID'];

    $args = array(
        'post_type' => 'Community Reviews', 
        'p' => intval($postID) 
    );
    $query = new WP_Query($args);

    $result = [];
    $brand_id = -1;
    if(check_for_brand($brand)){
        $brand_id = get_brand_id($brand);
        $result['brand'] = 'exists';
    }else{
        $brand_id = insert_brand($brand);
        $result['brand'] = 'inserted';
        if ($query->have_posts()){
            //update post with brand
            $update_res = update_post_meta(intval($postID), 'brand', $brand);
            $result['post update brand'] = $update_res;
        }
    }

    $category_id = get_category_id($category);

    $product_id = -1;
    if($brand_id == -1 || $category_id == -1){
        $result['type'] = "FAIL: brand id or category id was not found. brand id = ".strval($brand_id).", category id = ".strval($category_id);
    }else{
        $result['type'] = "Found brand and category";
        if(check_for_product($product)){
            $result['New Product ID'] = 'product already exists';
        }else{
            $product_id = insert_product($product, $brand_id, $category_id);
            if ($query->have_posts()){
                $update_res = update_post_meta(intval($postID), 'product_tested', $product);
                $result['post update product'] = $update_res;
            }
            $result['New Product ID'] = $product_id;
        }
    }
    echo json_encode($result);
    wp_die();
}
add_action( 'wp_ajax_addRow', 'addRow' );
add_action( 'wp_ajax_nopriv_addRow', 'addRow' );

?>