<?php
//require plugin_dir_path( __FILE__ )."table_reading_functions.php";
function bcr_flagged_reviews_callback() {
    $query = get_flagged_reviews_cp();
    $flagged_reviews_arr_2 = array();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $brand = get_post_meta( get_the_ID(), 'brand', true );
            $product = get_post_meta( get_the_ID(), 'product_tested', true );
            $category = get_post_meta( get_the_ID(), 'category', true );
            $sport = get_post_meta( get_the_ID(), 'sport', true );
            $url = get_the_guid(get_the_ID());
            $title = get_the_title(get_the_ID());
            $id = get_the_ID();

            $postArr = array(
                'brand' => $brand,
                'product' => $product,
                'category' => $category,
                'sport' => $sport,
                'url' => $url,
                'title' => $title,
                'post_id' => $id
            );
            $flagged_reviews_arr_2[$id] = $postArr;
        }
    }else{
        echo "<b>No Flagged Reviews Found</b> <br><br>";
    }    
    display($flagged_reviews_arr_2);
}



function display($flaggedReviews) {
    // $str = print_r($flaggedReviews, true);
    ?>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    </head>

    <div class="flagged-community-reviews-admin-display">
        <div class="community-reviews-admin-display" id="community-reviews-admin-display">
        <div class="community-reviews-add-remove-dropdown">
        <div class="community-reviews-display-title">Add/Remove Product or Brand:</div>
                        <strong>Brand:</strong>
                        <select id="community-reviews-display-brand-dropdown" class="select2">
                        
                        <?php 
                        $brand_selected =  "K2";
                            echo '<option value="' . esc_html($brand_selected) . '">' . esc_html($brand_selected) . '</option>';
            
                                global $wpdb;

                                $brands_table_name = $wpdb->prefix . "bcr_brands";

                                $sql = $wpdb->prepare("SELECT brandName FROM $brands_table_name;");
                        
                                $results  = $wpdb->get_results($sql);
                                
                                foreach ($results as $id => $brand_obj) {
                                    $brand_name = $brand_obj->brandName;
                                    if($brand_name != $brand_selected){
                                        echo '<option value="' . esc_html($brand_name) . '">' . esc_html($brand_name) . '</option>';
                                    }
                                }
                        ?>
                        </select>
                        <!--<div class="community-reviews-display-title">Product:</div>-->
                        <strong>Product:</strong>
                        <select id="community-reviews-display-product-dropdown" class="select2">
                        
                        <?php
                            $product_selected = "Brahma 88";
                            echo '<option value="' . esc_html($product_selected) . '">' . esc_html($product_selected) . '</option>';
            
                                //global $wpdb;

                                $products_table_name = $wpdb->prefix . "bcr_products";

                                $sql = $wpdb->prepare("SELECT productName FROM $products_table_name;");
                        
                                $results  = $wpdb->get_results($sql);
                                
                                foreach ($results as $id => $product_obj) {
                                    $product_name = $product_obj->productName;
                                    if($product_name != $product_selected){
                                        echo '<option value="' . esc_html($product_name) . '">' . esc_html($product_name) . '</option>';
                                    }
                                }
                            ?>
                        </select>
                        <button type="submit" id="approve-button" onclick="approveButtonClicked()">Approve</button>
                        <button type="submit" id="deny-button" onclick="denyButtonClicked()">Deny</button>
        <br>
        <br>                
        <strong>Flagged Reviews</strong>
                    
        <div class="community-reviews-display-flagged-reviews-radio">
                
                <label for="flagged_reviews">Select flagged review:</label>
                        <?php foreach ($flaggedReviews as $key => $arr) : ?>
                            <?php 
                            if($arr['sport']){
                                $str = "Review Post ID: $arr[post_id], Sport: $arr[sport], Category: $arr[category], Brand: $arr[brand], Product: $arr[product]";
                            } else{
                                $str = "Review Post ID: $arr[post_id], Sport: $arr[category], Category: $arr[category], Brand: $arr[brand], Product: $arr[product]";
                            }
                            ?>
                            <br>
                            <input type="radio" name="flagged_review" id="FR_<?php echo $key ?>" value="<?php echo $key?>" />
                            <label for="FR_<?php echo $key ?>"><?php echo $str.", " ?></label>
                            <a href="<?php echo  $arr['url']?>"><?php echo "URL: BCR Post $arr[post_id]"?></a>
                        <?php endforeach ?>
                        <div>
                            
                            <button type="submit" id="submit-button" onclick="submitButtonClicked()" >Load Flagged Review</button>
                        </div>
                        
                </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function(jQuery) {
            jQuery('#community-reviews-display-sport-dropdown').select2({
                tags: true,
                placeholder: 'Select or create a sport'
            });
        });

        jQuery(document).ready(function(jQuery) {
            jQuery('#community-reviews-display-category-dropdown').select2({
                tags: true,
                placeholder: 'Select or create a category'
            });
        });

        jQuery(document).ready(function(jQuery) {
            jQuery('#community-reviews-display-brand-dropdown').select2({
                tags: true,
                placeholder: 'Select or create a brand'
            });
        });

        jQuery(document).ready(function(jQuery) {
            jQuery('#community-reviews-display-product-dropdown').select2({
                tags: true,
                placeholder: 'Select or create a product'
            });
        });

        
    
        function approveButtonClicked(){
            const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
            const approveButton = document.getElementById('approve-button');
            const reviewId = Number(selectedRadio.nextElementSibling.textContent.match(/Review Post ID: (\d+)/)[1]);
            const radio_id = selectedRadio.id;
            var selector = 'label[for=' + radio_id + ']';
            var label = document.querySelector(selector);
            var text = label.innerHTML;
            
            const match1 = text.match(/Category:\s([\w\s]+)/);
            
            const category = match1 ? match1[1] : null;
            
            const productElement = document.getElementById("community-reviews-display-product-dropdown");
            const brandElement = document.getElementById("community-reviews-display-brand-dropdown");
            //console.log(productElement.value, brandElement.value, prodArr['category'], prodArr['sport'], prodArr['post_id']);
            addRow(productElement.value, brandElement.value, category, reviewId);
            //console.log(`These are the values in the dropdown: product-"${productElement.value}" brand-"${brandElement.value}"`);
            removeValueFromRadio(reviewId, 0, reviewId);
        }

        function addRow(product, brand, category, postID){
            jQuery.ajax({
                url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                method: 'POST',
                data: {
                    action: 'addRow',
                    product: product,
                    brand: brand,
                    category: category,
                    postID: postID
                },
                success: function(result) {
                    console.log(result);
                    //location.reload();
                }
            });
        }

        function denyButtonClicked(){
            const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
            const reviewId = Number(selectedRadio.nextElementSibling.textContent.match(/Review Post ID: (\d+)/)[1]);
            //const postID = Number(selectedRadio.nextElementSibling.textContent.match(/Review Post ID: (\d+)/)[1]);
            removeValueFromRadio(reviewId, 2, reviewId);
        }

        
        function removeValueFromRadio(reviewId, flag, postID){
            jQuery.ajax({
                url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                method: 'POST',
                data: {
                    action: 'removeReview',
                    reviewID: reviewId,
                    flag: flag,
                    postID: postID
                },
                success: function(result) {
                        //console.log(`Successfully changed the flag on the approved/denied review`);
                        console.log(result);
                       location.reload();
                }
            });
        }

        function updateDropdown(dropdownElement, $dropdownText, updateValue){
            //const dropdownElement = document.getElementById(id);
            if(dropdownElement){
                let exists = false;
                for (let i = 0; i < dropdownElement.options.length; i++) {
                    if (dropdownElement.options[i].value === updateValue) {
                        exists = true;
                        //break;
                    }
                }
                if(!exists){
                    const newOption = document.createElement("option");
                    newOption.value = updateValue;
                    newOption.text = updateValue;
                    dropdownElement.appendChild(newOption);
                    console.log(`Added option with value "${updateValue}" to one of the dropdowns.`);
                }
                dropdownElement.value = updateValue;
                $dropdownText.title = updateValue;
                $dropdownText.innerText = updateValue;
                //var option = dropdownElement.find(`option[value="${updateValue}"]`);

                // Set the text of the first option to "New Option"
                //option.text('New Option');

                console.log(`Selected "${updateValue}" in the one of the dropdowns`);
            } else{
                console.error(`Could not find dropdown element: "${dropdownElement}"`);
            }
        }
    
        function submitButtonClicked() { 
            const submitButton = document.getElementById('submit-button');
            const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
            const radio_id = selectedRadio.id;
            
            var selector = 'label[for=' + radio_id + ']';
            var label = document.querySelector(selector);
            var text = label.innerHTML;
            
            const match1 = text.match(/Brand:\s(.+?),/);///Brand:\s([\w\s]+)/);
            
            const brand = match1 ? match1[1] : null;
            
            
            const match2 = text.match(/Product:\s(.+?),/);///Product:\s([\w\s]+)/);
            
            const product = match2 ? match2[1] : null;
            
            update_dropdowns(brand, product);
        }

        function update_dropdowns(brand, product){
                const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
                if (selectedRadio) {

                    const selectedValue = selectedRadio.value;
                    console.log(`Selected value: ${selectedValue}`);
                    const reviewId = Number(selectedRadio.nextElementSibling.textContent.match(/Review Post ID: (\d+)/)[1]);
                    
                    const brandElement = document.getElementById("community-reviews-display-brand-dropdown");
                    var $brandText = document.getElementById("select2-community-reviews-display-brand-dropdown-container");
                    updateDropdown(brandElement, $brandText, brand);
                    const productElement = document.getElementById("community-reviews-display-product-dropdown");
                    var $productText = document.getElementById("select2-community-reviews-display-product-dropdown-container");
                    updateDropdown(productElement, $productText,product);
                    

                } else {
                    console.log('No radio button selected');
                }
        }
    </script>
    <?php
}

function get_flagged_review_meta_data($review_id){
  
    $args = array(
        'post_type' => 'Community Reviews',
        'posts_per_page' => 1,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'id',
                'value' => $review_id,
                'compare' => '='
            )
        )
    );
    
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $query->the_post();
        $brand = get_post_meta( get_the_ID(), 'brand', true );
        $product = get_post_meta( get_the_ID(), 'product_tested', true );
        $category = get_post_meta( get_the_ID(), 'category', true );
        $sport = get_post_meta( get_the_ID(), 'sport', true );
        $url = get_the_guid(get_the_ID());
        $title = get_the_title(get_the_ID());
        $id = get_the_ID();

        $retArr = array(
            'brand' => $brand,
            'product' => $product,
            'category' => $category,
            'sport' => $sport,
            'url' => $url,
            'title' => $title,
            'post_id' => $id
        );
    }else{
        //echo "query was empty no post found with this id<br>";
    }
    
    wp_reset_postdata();
    return $retArr;
}


function get_flagged_reviews_cp(){
    $args = array(
      'post_type' => 'Community Reviews',
      'posts_per_page' => -1,
      'orderby' => 'post_date',
      'order' => 'DESC',
      'meta_query' => array(
        array(
          'key' => 'FlaggedForReview',
          'value' => '1',
          'compare' => '=='
        )
      )
    );
  
    $query = new WP_Query( $args );
    return $query;
  }

function add_bcr_flagged_reviews_submenu_page() {
    $args = array(
        'post_type' => 'Community Reviews',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'flaggedForReview',
                'value' => 1,
                'compare' => '='
            )
        )
    );
    $query = new WP_Query( $args );
    $notification_count = 0;
    if ( $query->have_posts() ) {
        $notification_count = $query->found_posts;
    }    
    add_submenu_page(
        'edit.php?post_type=communityreviews', // The parent menu slug
        'BCR Flagged Reviews', // The page title
        $notification_count ? sprintf('BCR Flagged   Reviews <span class="awaiting-mod">%d</span>', $notification_count) : 'BCR Flagged Reviews',
        'manage_options', // The required user capability to access the page
        'bcr-flagged-reviews', // The menu slug
        'bcr_flagged_reviews_callback' // The callback function to display the page content
    );
}

add_action( 'admin_menu', 'add_bcr_flagged_reviews_submenu_page' );

?>