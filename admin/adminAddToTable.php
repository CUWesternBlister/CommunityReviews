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
            //echo "$review_id";
            //$form_id = $review->reviewFormID;
            
            //$category_row = get_category_info($form_id, $file);
            //$category_name = $category_row->categoryName;
            
            //$sport_row = get_sport_info($category_name); 
            //$sport_name = $sport_row->categoryName;

            $review_meta_data = get_flagged_review_meta_data($review_id);
            //$str = print_r($review_meta_data, true);
            $str = "Review ID: $review_id, Post ID: $review_meta_data[id], Category: $review_meta_data[category], Brand: $review_meta_data[brand], Product: $review_meta_data[product], URL: $review_meta_data[url]";
            $flagged_reviews_arr[$review_id] = $review_meta_data;
            
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
    <script src="
        https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js
        "></script>
        <link href="
        https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css
        " rel="stylesheet">

    <div class="flagged-community-reviews-admin-display">
        <div class="community-reviews-admin-display" id="community-reviews-admin-display">
        <div class="community-reviews-add-remove-dropdown">
        <div class="community-reviews-display-title">Add/Remove Product or Brand:</div>
                        <strong>Category:</strong>
                        <select id="community-reviews-display-category-dropdown" class="select2">
                        
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
                        <select id="community-reviews-display-brand-dropdown" class="select2">
                        
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
                        <select id="community-reviews-display-product-dropdown" class="select2">
                        
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
                        <button type="submit" id="approve-button" onclick="approveButtonClicked()">Approve</button>
                        <button type="submit" id="deny-button" onclick="denyButtonClicked()">Deny</button>
        <br>
        <br>                
        <strong>Flagged Reviews</strong>
                    
        <div class="community-reviews-display-flagged-reviews-radio">
                
                <label for="flagged_reviews">Select flagged review:</label>
                        <?php foreach ($flaggedReviews as $key => $arr) : ?>
                            <?php $str = "Review ID: $key, Post ID: $arr[post_id], Category: $arr[category], Brand: $arr[brand], Product: $arr[product]" ?>
                            <br>
                            <input type="radio" name="flagged_review" id="FR_<?php echo $key ?>" value="<?php echo $key?>" />
                            <label for="FR_<?php echo $key ?>"><?php echo $str.", " ?></label>
                            <a href="<?php echo  $arr['url']?>"><?php echo "URL: BCR Post $arr[post_id]"?></a>
                        <?php endforeach ?>
                        <div>
                            <?php
                                $myArrJson = json_encode($flaggedReviews);
                            ?>
                            <input type="hidden" id="myArr" value='<?php echo strval($myArrJson);?>'>
                            <button type="submit" id="submit-button" onclick="submitButtonClicked()" >Submit</button>
                        </div>
                        
                </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#community-reviews-display-category-dropdown').select2({
                tags: true,
                placeholder: 'Select or create a category'
            });
        });

        jQuery(document).ready(function($) {
            $('#community-reviews-display-brand-dropdown').select2({
                tags: true,
                placeholder: 'Select or create a brand'
            });
        });

        jQuery(document).ready(function($) {
            $('#community-reviews-display-product-dropdown').select2({
                tags: true,
                placeholder: 'Select or create a product'
            });
        });
    
        function approveButtonClicked(){
            const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
            const approveButton = document.getElementById('approve-button');
            const myArrJson = document.getElementById('myArr').value;
            const myArr = JSON.parse(myArrJson);
            const reviewId = Number(selectedRadio.nextElementSibling.textContent.match(/Review ID: (\d+)/)[1]);
            removeValueFromRadio(reviewId);
        }

        function denyButtonClicked(){
            const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
            const denyButton = document.getElementById('deny-button');
            const myArrJson = document.getElementById('myArr').value;
            const myArr = JSON.parse(myArrJson);
            const reviewId = Number(selectedRadio.nextElementSibling.textContent.match(/Review ID: (\d+)/)[1]);
            removeValueFromRadio(reviewId);
        }

        function removeValueFromRadio(reviewId){
            const selectedRadio = document.querySelector('input[name="flagged_review"]:checked');
            <?php
                global $wpdb;

                $review_table_name = $wpdb->prefix . "bcr_reviews";
                echo $reviewId;
            ?>
            location.reload();
        }

        function updateDropdown(dropdownElement, updateValue){
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
                console.log(`Selected "${updateValue}" in the one of the dropdowns`);
            } else{
                console.error(`Could not find dropdown element: "${dropdownElement}"`);
            }
        }
    
        function submitButtonClicked() { 
            const submitButton = document.getElementById('submit-button');
            const myArrJson = document.getElementById('myArr').value;
            console.log(`JSON: ${myArrJson}`);
            const myArr = JSON.parse(myArrJson);
            //submitButton.addEventListener('click', update_dropdowns(myArr));
            update_dropdowns(myArr);
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
                    updateDropdown(categoryElement, flagged_review_arr['category']);
                    const brandElement = document.getElementById("community-reviews-display-brand-dropdown");
                    updateDropdown(brandElement, flagged_review_arr['brand']);
                    const productElement = document.getElementById("community-reviews-display-product-dropdown");
                    updateDropdown(productElement, flagged_review_arr['product']);

                } else {
                    console.log('No radio button selected');
                }
        }
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
            'post_id' => $id
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