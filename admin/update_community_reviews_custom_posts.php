<?php

function update_existing_custom_posts() {
  $args = array(
    'post_type' => 'Community Reviews',
    'post_status' => 'any',
    'posts_per_page' => -1,
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_id = get_the_ID();
      $sport = get_post_meta( $post_id, 'category', true );
      if(empty($sport)){
        add_metadata_to_custom_posts($post_id);
      }
    }
    wp_reset_postdata();
  } 
}


function add_metadata_to_custom_posts( $post_id ) { 
    //get length from title
    $post_title = get_the_title( $post_id );

    //get brand
    $post_title_substrs = explode(" ", $post_title);
    $brand = "";
    if(preg_match('/\d{4}-\d{4}/', $post_title_substrs[0]) | preg_match('/\d{4}/', $post_title_substrs[0])){
      $brand = $post_title_substrs[1];
    }else{
      $brand = $post_title_substrs[0];
    } 

    //get ski length
    $lastString = end($post_title_substrs);
    $ski_length_num = "";
    if (preg_match('/\d+cm/', $lastString)) {
      $ski_length_num = intval(preg_replace('/[^0-9]/', '', $lastString));
    }

    //get year from title
    $year = "";
    if (preg_match('/\d{4}/', $post_title, $matches)) {
        $year = (int) $matches[0];
    }
    
    //get user height and convert
    $feet_str = get_post_meta( $post_id, 'heightFeet', true );
    $inch_str = get_post_meta( $post_id, 'heightInches', true );
    $height_in_inches = (intval($feet_str)*12)+intval($inch_str);

    //get category id
    global $wpdb;
    $cate_table_name = $wpdb->prefix . "bcr_categories";
    $category = get_post_meta( $post_id, 'category', true );
    echo "categrory: ".$category."<br>";
    $q = $wpdb->prepare("SELECT * FROM $cate_table_name WHERE categoryName = %s;", $category);
    $res = $wpdb->get_row($q);
    if($res->parentID != 0){
        $parent_id = $res->parentID;
        $q = $wpdb->prepare("SELECT * FROM $cate_table_name WHERE categoryID = %s;", $parent_id);
        $res = $wpdb->get_row($q);
    }
    $sport_name = $res->categoryName;
    // add meta data  to post
    update_post_meta( $post_id, 'brand', $brand);
    update_post_meta( $post_id, 'height', $height_in_inches);
    update_post_meta( $post_id, 'year', $year);
    update_post_meta( $post_id, 'ski_length',  $ski_length_num);
    update_post_meta( $post_id, 'sport',  $sport_name);    
}
?>