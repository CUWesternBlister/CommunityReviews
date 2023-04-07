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
          <p id="postID">Post ID: </p>
          <p id="reviewID">Review ID: </p>
          <p id="formID">Form ID: </p>
        <form method="post" action="">

            <label for="height">Height:</label>
            <input type="text" id="height" name="height"><br>

            <label for="weight">Weight:</label>
            <input type="text" id="weight" name="weight"><br>

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

            <input type="submit" value="Update Meta Data">
        </form>
        <input type="hidden" id="loadedPostMeta" name="load-post-meta" value="">
    </div><br><br>
        <?php
          $q = get_reviews();
          makeRadioHTML($q);
        ?>
    <script>
            const btn = document.querySelector('#btn');
            const postRadios = document.querySelectorAll('input[name="reviewRadio"]');

            const postIdInput = document.getElementById('postID');

            btn.addEventListener("click", () => {
              let selectedPostID;
              for (const postRadio of postRadios) {
                  if (postRadio.checked) {
                      selectedPostID = postRadio.value;
                      break;
                  }
              }
              console.log(typeof selectedPostID);
              
              // show the output:
               $metaData = getPostMetaData(Number(selectedPostID), function(metaData) {
                    if('id' in metaData){
                      document.getElementById('reviewID').innerHTML = "Review ID: "+metaData['id'];
                    }
                    if('formID' in metaData){
                      document.getElementById('formID').innerHTML = "Form ID: "+metaData['formID'];
                    }
                    if('height' in metaData){
                      document.getElementById('height').value = metaData['height'];
                    }
                    if('weight' in metaData){
                      document.getElementById('weight').value = metaData['weight'];
                    }
                    if('product_tested' in metaData){
                      document.getElementById('product_tested').value = metaData['product_tested'];
                    }
                    if('brand' in metaData){
                      document.getElementById('brand').value = metaData['brand'];
                    }
                    if('category' in metaData){
                      document.getElementById('category').value = metaData['category'];
                    }
                    if('sport' in metaData){
                      document.getElementById('sport').value = metaData['sport'];
                    }
                    if('FlaggedForReview' in metaData){
                      document.getElementById('FlaggedForReview').value = metaData['FlaggedForReview'];
                    }else{
                      document.getElementById('FlaggedForReview').value = 0;
                    }
                    if('year' in metaData){
                      document.getElementById('year').value = metaData['year'];
                    }
                    if('length' in metaData){
                      document.getElementById('length').value = metaData['length'];
                    }
                    if('boot_size' in metaData){
                      document.getElementById('boot_size').value = metaData['boot_size'];
                    }
                    document.getElementById('loadedPostMeta').value = "1";
              });
              postIdInput.innerHTML = "Post ID: " + selectedPostID;
              console.log(typeof metaData);

              var hiddenInput = document.getElementById('loadedPostMeta');
              var submitButton = document.querySelector('input[type="submit"]');

              hiddenInput.addEventListener('input', function() {
                if (hiddenInput.value !== '') {
                  submitButton.removeAttribute('disabled');
                  console.log("submit button enabled");
                } else {
                  submitButton.setAttribute('disabled', 'disabled');
                  console.log("submit button disabled");
                }
              });

              submitButton.addEventListener('click', function(event) {
                event.preventDefault();
                console.log("submit button working");
                //create metaData arr
                //call updatePostMetaData(selectedPostID)
                //print success res
              });
              

            });

            function makeMetaDataArr(){
              meta_data = {};
              meta_data.height = document.getElementById('height').value;
              meta_data.weight = document.getElementById('weight').value;
              meta_data.product_tested = document.getElementById('product_tested').value;
              meta_data.brand = document.getElementById('brand').value;
              meta_data.category = document.getElementById('category').value;
            }

            function getPostMetaData(postID, callback){
              jQuery.ajax({
                  url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                  method: 'POST',
                  data: {
                      action: 'getPostMetaDataCall',
                      postID: postID
                  },
                  dataType:"json",
                  success: function(result) {
                    console.log(result);
                    callback(result);
                  }
              });
            }

            function updatePostMetaData(postID, metaData){
              jQuery.ajax({
                  url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                  method: 'POST',
                  data: {
                      action: 'updatePostMetaDataCall',
                      postID: postID,
                      metaData: metaData
                  },
                  //dataType:"json",
                  success: function(result) {
                    console.log(result);
                    //callback(result);
                  }
              });
            }

        </script>
    <?php
}

function getPostMetaDataCall(){
  header('Access-Control-Allow-Origin: *');
   
  $postID = intval($_POST['postID']);

  // echo '<script>console.log("FUCKKKKKKKKKKKKKKKKKKKKK!"); </script>';

  if($postID){
    $args = array(
      'post_type' => 'Community Reviews', 
      'p' => intval($postID) 
    );
    $query = new WP_Query($args);
    // $file = fopen('testfile.txt', "w");
    // fwrite($file, "hello world\n");
    // fclose($file);
    $result = [];
    if ($query->have_posts()) {
      $meta_data = get_post_meta( $postID, '', false);
      //if($meta_data){
        $result['type'] = "success";
        $result['metaData'] = $meta_data;
      // }else{
      //   $result['type'] = "fail";
      //   $result['metaData'] = -1;
      // }
      $result = json_encode($meta_data);
      echo $result;
    }
  }
  wp_die();
}

 add_action( 'wp_ajax_getPostMetaDataCall', 'getPostMetaDataCall' );
 add_action( 'wp_ajax_nopriv_getPostMetaDataCall', 'getPostMetaDataCall' );

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
  return $query;
}

function makeRadioHTML($query){
    ?>
        <div class="community-reviews-display-flagged-reviews-radio">           
            <label for="flagged_reviews">SELECT REVIEW TO UPDATE META DATA:</label><br>
                <p>
                  <button id="btn">Load Selected Post</button>
              </p>
                <?php if ( $query->have_posts() ) {
                        while ( $query->have_posts() ) {
                          $query->the_post();
                          $post_id = get_the_ID();
                          $post_title = get_the_title($post_id);
                          $meta_data = get_post_meta( $post_id );
                          $url = get_the_guid($post_id);
                          $str = "Post ID: ".strval($post_id)."<br>Post Title: ".$post_title."<br> Post Meta Data: <br>". var_export($meta_data, true);
                          echo '<input type="radio" name="reviewRadio" id="FR_'.strval($post_id).'" value="'.strval($post_id).'" />';
                          echo '<label for="FR_'.strval($post_id).'">'.$str.'</label>';
                          echo '<br><a href="'.$url.'">URL: BCR Post '.strval($post_id).'</a>';
                          echo '<br><br>';
                        }
                      } 
                ?>
        </div>
      <?php
}

// function makeHiddenMetaDataArr($query){
//   $arr = [];
//   if ( $query->have_posts() ) {
//     while ( $query->have_posts() ) {
//       $query->the_post();
//       $post_id = get_the_ID();
//       $meta_data = get_post_meta( $post_id );
//       $arr[$post_id] = $meta_data;
//     }
//     $jsonarr = json_decode() 
//   }else{echo "<br><br>FAILED TO MAKE POST<br><br>";}
// }











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