<?php
add_action('init', 'update_existing_custom_posts');

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
      add_metadata_to_custom_posts($post_id);
    }
    wp_reset_postdata();
  }
}


function add_metadata_to_custom_posts( $post_id ) {
    $post_type = get_post_type( $post_id );
    $height_in_inches = get_post_meta( $post_id, 'height_in_inches', true );
    
    // $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    // $file = fopen($file_path, "a") or die('fopen failed');
   
    // if (empty($height_in_inches)) {
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
        
        // fwrite($file, "\nbrand:\n".$brand.
        //               "\nyear:\n".strval($year).
        //               "\nski_length:\n".strval($ski_length_num).
        //               "\nheight in inches:\n".strval($height_in_inches).
        //               "\n\n------------------------------\n\n");
        
        // add meta data  to post
        update_post_meta( $post_id, 'brand', $brand);
        update_post_meta( $post_id, 'height', $height_in_inches);
        update_post_meta( $post_id, 'year', $year);
        update_post_meta( $post_id, 'ski_length',  $ski_length_num);
    // }
    //fclose($file);
}
//add_action( 'save_post', 'add_metadata_to_custom_posts' );
?>