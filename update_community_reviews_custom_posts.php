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
    
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $file = fopen($file_path, "a") or die('fopen failed');
   
    //if (empty($height_in_inches)) { //($post_type == 'Community Reviews') &
        //get length from title
        $post_title = get_the_title( $post_id );
        $ski_length_num = "";
        if (preg_match('/\d+cm/', $post_title, $matches)) {
            $ski_length = $matches[0];
            preg_match('/\d+/', $ski_length, $matches);
            $ski_length_num = $matches[0];
        }
        //get year from title
        $year = "";
        if (preg_match('/\d{4}/', $post_title, $matches)) {
            $year = (int) $matches[0];
        }
        $year = $year;
        //get user height and convert

        $feet_str = get_post_meta( $post_id, 'heightFeet', true );
        $inch_str = get_post_meta( $post_id, 'heightInches', true );
        fwrite($file, "feet str:\n".$feet_str."\ninch str\n".$inch_str);
        $feet = (int) $feet_str;
        $inches = (int) $inch_str;  
        //$form_name_str = print_r($existing_form_n, true);
        fwrite($file, "\nyear1:\n".strval($year).
                    "\nski_length:\n".strval($ski_length_num).
                    "\nheigh in inches:\n".strval(($feet*12)+$inches).
                    "\n\n------------------------------\n\n");
        
        // add meta data  to post
        update_post_meta( $post_id, 'height_in_inches', ($feet*12)+$inches);
        update_post_meta( $post_id, 'year1', $year);
        //update_post_meta( $post_id, 'year2', $year2);
        update_post_meta( $post_id, 'ski_length',  $ski_length_num);
    //}
    fclose($file);
}
//add_action( 'save_post', 'add_metadata_to_custom_posts' );
?>