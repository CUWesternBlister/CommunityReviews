<?php
/** 
 * Filter posts so that only those written by the current user show
 * 
 * @param Query     $query  The Wordpress Query object to modify
 * 
 * @return Query
 */
function bcr_view_current_user_reviews_only_query( $query ) {
    if(!is_user_logged_in()) {
        // Set the query to return nothing
        $query = new WP_Query(array('post__in' => array(0)));
    } else {
        if ( ! function_exists( 'get_current_user_id' ) ) {
	        return 0;
	    }
	    $uid = get_current_user_id();
        
        $query->set('author', $uid);
    }
}

add_action( 'elementor/query/users_reviews', 'bcr_view_current_user_reviews_only_query' );

// function bcr_filter_test( $query ) {
//     $query = new WP_Query(array('post__in' => array(0)));
// }
// add_action( 'elementor/query/filter_test', 'bcr_filter_test' );
?>