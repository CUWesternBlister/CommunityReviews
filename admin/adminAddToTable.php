<?php
//require plugin_dir_path( __FILE__ )."table_reading_functions.php";
function bcr_flagged_reviews_callback() {
    //echo "bcr_flagged_reviews_callback<br>";
    $flagged_reviews = get_flagged_reviews();
    $file = "";
    $flagged_reviews_arr = array();
    if ($flagged_reviews) {
        //echo "flagged reviews: $flagged_reviews <br>";
        foreach ($flagged_reviews as $review) {
            $review_id = $review->reviewID;
            //$form_id = $review->reviewFormID;
            
            //$category_row = get_category_info($form_id, $file);
            //$category_name = $category_row->categoryName;
            
            //$sport_row = get_sport_info($category_name); 
            //$sport_name = $sport_row->categoryName;

            $review_meta_data = get_flagged_review_meta_data($review_id);
            //$str = print_r($review_meta_data, true);
            $str = "Review ID: $review_meta_data[id], Category: $review_meta_data[category], Brand: $review_meta_data[brand], Product: $review_meta_data[product], URL: $review_meta_data[url]";
            $flagged_reviews_arr[$review_meta_data['id']] = $review_meta_data;
            
            //echo "$str";
            //$brand = "brand_placeholder";
            //$product = "product_placeholder";

            //echo "Review ID: $review_meta_data[id], Category: $review_meta_data[category], Brand: $review_meta_data[brand], Product: $review_meta_data[product], URL: $review_meta_data[url]<br>";
            
        }
    }else{
        echo "no flagged reviews found";
    }
    display($flagged_reviews_arr);
}



function display($flaggedReviews){
    // $str = print_r($flaggedReviews, true);
    // echo "flagged reviews: ". $str;
    ?>
    <div class="flagged-community-reviews-admin-display">
        <div class="community-reviews-admin-display" id="community-reviews-admin-display">
        <div class="community-reviews-add-remove-dropdown">
        <div class="community-reviews-display-title">Add/Remove Product or Brand:</div>
                        <strong>Category:</strong>
                        <select id="community-reviews-display-category-dropdown">
                        
                        <?$category_selected = "Ski";
                            echo '<option value="' . esc_html($category_selected) . '">' . esc_html($category_selected) . '</option>';
            
                                global $wpdb;

                                $categories_table_name = $wpdb->prefix . "bcr_categories";
                                $zero = 0;
                                $sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE parentID!=0");
                                
                                $results  = $wpdb->get_results($sql);
                                //$str = print_r($results, true);
                                
                                
                                
                                foreach ($results as $id => $category_obj) {
                                    $category_name = $category_obj->categoryName;
                                    if($category_name != $category_selected){
                                        echo '<option value="' . esc_html($category_name) . '">' . esc_html($category_name) . '</option>';
                                    }
                                }
                            ?>
                        </select>
                        <strong>Brand:</strong>
                        <select id="community-reviews-display-brand-dropdown">
                        
                        <?$brand_selected =  "K2";
                            echo '<option value="' . esc_html($brand_selected) . '">' . esc_html($brand_selected) . '</option>';
            
                                //global $wpdb;

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
                        <select id="community-reviews-display-product-dropdown">
                        
                        <?$product_selected = "Brahma 88";
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
        <br>
        <br>                
        <strong>Flagged Reviews</strong>
                    
        <div class="community-reviews-display-flagged-reviews-radio">
                
                <label for="flagged_reviews">Select flagged review:</label>
                        <?php foreach ($flaggedReviews as $key => $arr) : ?>
                            <?php $str = "Review ID: $arr[id], Category: $arr[category], Brand: $arr[brand], Product: $arr[product]" ?>
                            <br>
                            <input type="radio" name="flagged_review" id="FR_<?php echo $key ?>" value="<?php echo $key?>" />
                            <label for="FR_<?php echo $key ?>"><?php echo $str ?></label>
                            <br>
                            <a href="<?php echo  $arr['url']?>"><?php echo "URL: Review $arr[id]"?></a>
                        <?php endforeach ?>
                        <div>
                            <?php
                                $myArrJson = json_encode($flaggedReviews);
                                echo $myArrJson;
                            ?>
                            <input type="hidden" id="myArr" value='<?php echo strval($myArrJson);?>'>
                            <button type="submit" id="submit-button" onclick="submitButtonClicked()" >Submit</button>
                        </div>
                        
                </div>
        </div>
    </div>
    <script>
        
            function submitButtonClicked() { 
                const submitButton = document.getElementById('submit-button');
                const myArrJson = document.getElementById('myArr').value;
                console.log(`JSON: ${myArrJson}`);
                const myArr = JSON.parse(myArrJson);
                submitButton.addEventListener('click', update_dropdowns(myArr));
            }

            function update_dropdowns(myArr){
                    const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
                    if (selectedRadio) {

                        const selectedValue = selectedRadio.value;
                        console.log(`Selected value: ${selectedValue}`);
                        const reviewId = Number(selectedRadio.nextElementSibling.textContent.match(/Review ID: (\d+)/)[1]);
                        const flagged_review_arr = myArr[reviewId];
                        console.log(`flagged review arr category : ${flagged_review_arr['category']}`);
                        //set defualt values for each drop down based off id
                        const categoryElement = document.getElementById("community-reviews-display-category-dropdown");
                        categoryElement.value = flagged_review_arr['category'];
                        const brandElement = document.getElementById("community-reviews-display-brand-dropdown");
                        brandElement.value = flagged_review_arr['brand'];
                        const productElement = document.getElementById("community-reviews-display-product-dropdown");
                        productElement.value = flagged_review_arr['product'];
                    } else {
                        console.log('No radio button selected');
                    }
            }


         //    const submitButton = document.getElementById('submit-button');
        //     submitButton.addEventListener('click', () => {
        //                     const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
        //                     if (selectedRadio) {
        //                         const selectedValue = selectedRadio.value;
        //                         console.log(`Selected value: ${selectedValue}`);
        //                         const reviewId = selectedRadio.nextElementSibling.textContent.match(/Review ID: (\d+)/)[1];
        //                         $flagged_review_arr = $flaggedReviews[reviewId];
        //                         //set defualt values for each drop down based off id
        //                         const categoryElement = document.getElementById("community-reviews-display-category-dropdown");
        //                         categoryElement.value = $flagged_review_arr['category'];
        //                         const brandElement = document.getElementById("community-reviews-display-brand-dropdown");
        //                         brandElement.value = $flagged_review_arr['brand'];
        //                         const productElement = document.getElementById("community-reviews-display-product-dropdown");
        //                         productElement.value = $flagged_review_arr['product'];
        //                     } else {
        //                         console.log('No radio button selected');
        //                     }
        //     });
    </script>
    <?
}

function get_flagged_review_meta_data($review_id){
    //echo "get_flagged_review_post<br>";
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
        //while ( $query->have_posts() ) {
        $query->the_post();
        $brand = get_post_meta( get_the_ID(), 'brand', true );
        //echo "$custom_meta_value brand name<br>";
        $product = get_post_meta( get_the_ID(), 'product_tested', true );
        //echo "$custom_meta_value product name<br>";
        $category = get_post_meta( get_the_ID(), 'category', true );
        $sport = get_post_meta( get_the_ID(), 'sport', true );
        $url = get_the_guid(get_the_ID());
        $title = get_the_title(get_the_ID());
        $id = get_the_ID();
        //echo "$title<br>";

        $retArr = array(
            'brand' => $brand,
            'product' => $product,
            'category' => $category,
            'sport' => $sport,
            'url' => $url,
            'title' => $title,
            'id' => $id
        );


            // Do something with the custom meta value
        //}
    }else{
        //echo "query was empty no post found with this id<br>";
    }
    
    wp_reset_postdata();
    return $retArr;
}

function add_bcr_flagged_reviews_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=communityreviews', // The parent menu slug
        'BCR Flagged Reviews', // The page title
        'BCR Flagged Reviews', // The menu title
        'manage_options', // The required user capability to access the page
        'bcr-flagged-reviews', // The menu slug
        'bcr_flagged_reviews_callback' // The callback function to display the page content
    );
}

add_action( 'admin_menu', 'add_bcr_flagged_reviews_submenu_page' );

?>