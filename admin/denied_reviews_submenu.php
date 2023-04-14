<?php
function bcr_denied_reviews_callback(){
    echo 'hello world';
}

function add_bcr_denied_reviews_submenu_page() {
    $args = array(
        'post_type' => 'Community Reviews',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'flaggedForReview',
                'value' => 2,
                'compare' => '='
            )
        )
    );
    $query = new WP_Query( $args );
    $notification_count = 0;
    if ( $query->have_posts() ) {
        $notification_count = $query->found_posts;
    }    
    //echo "num found posts: ".strval($query->found_posts)."<br>";
    add_submenu_page(
        'edit.php?post_type=communityreviews', // The parent menu slug
        'BCR Denied Reviews', // The page title
        $notification_count ? sprintf('BCR Denied Reviews <span class="awaiting-mod">%d</span>', $notification_count) : 'BCR Denied Reviews',
        'manage_options', // The required user capability to access the page
        'bcr-denied-reviews', // The menu slug
        'bcr_denied_reviews_callback', // The callback function to display the page content
        'edit.php?post_type=Community Reviews'//
    );
}

add_action( 'admin_menu', 'add_bcr_denied_reviews_submenu_page' );


?>