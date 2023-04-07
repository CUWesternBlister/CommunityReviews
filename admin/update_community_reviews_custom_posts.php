<?php
/**
 * Displays button to update the metadata of all custom posts
 * 
 * @return  void
 */
function bcr_admin_update_custom_post_submenu_page_callback() {
  ?>
    <div class="wrap">
        <h1>BCR Update Custom Posts</h1>
        <form method="post" action="">
            <label for="reviewID">Review ID:</label>
            <input type="text" id="reviewID" name="reviewID"><br>

            <label for="formID">Form ID:</label>
            <input type="text" id="formID" name="formID"><br>

            <label for="userID">User ID:</label>
            <input type="text" id="userID" name="userID"><br>

            <label for="userName">User Name:</label>
            <input type="text" id="userName" name="userName"><br>

            <label for="height">Height:</label>
            <input type="text" id="height" name="height"><br>

            <label for="weight">Weight:</label>
            <input type="text" id="weight" name="weight"><br>

            <label for="skiAbility">Ski Ability:</label>
            <input type="text" id="skiAbility" name="skiAbility"><br>

            <label for="product_tested">Product Tested:</label>
            <input type="text" id="product_tested" name="product_tested"><br>

            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand"><br>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category"><br>

            <label for="sport">Sport:</label>
            <input type="text" id="sport" name="sport"><br>

            <label for="FlaggedForReview">Flagged for Review:</label>
            <input type="text" id="FlaggedForReview" name="FlaggedForReview"><br>

            <label for="year">Year:</label>
            <input type="text" id="year" name="year"><br>

            <label for="length">Length:</label>
            <input type="text" id="length" name="length"><br>

            <label for="boot_size">Boot Size:</label>
            <input type="text" id="boot_size" name="boot_size"><br>

            <input type="submit" value="Submit">
        </form>
    </div>
    <?php
    get_reviews();
    ?>
    <?php
}

function get_reviews(){
  $args = array(
    'post_type' => 'Community Reviews',
    'posts_per_page' => -1,
    'orderby' => 'post_date',
    'order' => 'ASC',
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => 'FlaggedForReview',
        'compare' => 'NOT EXISTS'
      ),
      array(
        'key' => 'FlaggedForReview',
        'value' => '0',
        'compare' => '!='
      )
    )
  );

  $query = new WP_Query( $args );
      ?>
        <div class="community-reviews-display-flagged-reviews-radio">           
            <label for="flagged_reviews">SELECT REVIEW TO UPDATE META DATA:</label><br><br>
                <?php if ( $query->have_posts() ) {
                        while ( $query->have_posts() ) {
                          $query->the_post();
                          $post_id = get_the_ID();
                          $post_title = get_the_title($post_id);
                          $meta_data = get_post_meta( $post_id );
                          $url = get_the_guid($post_id);
                          $str = "Post ID: ".strval($post_id)."<br>Post Title: ".$post_title."<br> Post Meta Data: <br>". var_export($meta_data, true);
                          echo '<input type="radio" name="review" id="FR_'.strval($parent_id).'" value="'.strval($parent_id).'" />';
                          echo '<label for="FR_'.strval($parent_id).'">'.$str.'</label>';
                          echo '<br><a href="'.$url.'">URL: BCR Post '.strval($post_id).'</a>';
                          echo '<br><br>';
                        }
                      } 
                ?>
        </div>
      <?php
 
}

function update_existing_custom_posts() {
  $args = array(
    'post_type' => 'Community Reviews',
    'post_status' => 'any',
    'posts_per_page' => -1,
  );

  $query = new WP_Query($args);

  // $file = fopen("testfile.txt", "a");
  // fwrite($file, "here\n\n");
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_id = get_the_ID();
      $sport = get_post_meta( $post_id, 'category', true );
      //fwrite($file, "Sport: ".$sport."\n\n");
      //echo $sport."<br>";
      if(empty($sport)){
        add_metadata_to_custom_posts($post_id);
      }
      //echo "<br>";
    }
    wp_reset_postdata();
  } 
  //fclose($file);
}


function add_metadata_to_custom_posts( $post_id ) { 
    //fwrite($file, "In add metadata \n\n");   
    //echo "In add metadata<br>";
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