<?php
//require plugin_dir_path( __FILE__ )."table_reading_functions.php";
function bcr_flagged_reviews_callback() {
    echo "bcr_flagged_reviews_callback<br>";
    $flagged_reviews = get_flagged_reviews();
    $file = "";
    foreach ($flagged_reviews as $review) {
        $review_id = $review->reviewID;
        $form_id = $review->reviewFormID;
        
        $category_row = get_category_info($form_id, $file);
        $category_name = $category_row->categoryName;
        
        $sport_row = get_sport_info($category_name); 
        $sport_name = $sport_row->categoryName;

        //$review_post = get_flagged_review_post($review_id);

        echo "Review ID: $review_id, Sport: $sport_name, Category: $category_name, <br>";
        
    }
    
}

function get_flagged_review_post($review_id){
    echo "get_flagged_review_post<br>";
    $args = array(
        'post_type' => 'Community Reviews',
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
        while ( $query->have_posts() ) {
            $query->the_post();
            $custom_meta_value = get_post_meta( get_the_ID(), 'id', true );
            echo strval($custom_meta_value)." review id<br>";
            // Do something with the custom meta value
        }
    }else{
        echo "query was empty no post found with this id<br>";
    }
    
    wp_reset_postdata();
    return $query;
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