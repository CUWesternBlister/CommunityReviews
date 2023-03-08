<?php
add_action( 'wp_ajax_bcr_filter_posts', 'bcr_filter_posts' );
add_action( 'wp_ajax_nopriv_bcr_filter_posts', 'bcr_filter_posts' );

function bcr_filter_posts() {
    $args = array(
        'post_type'      => 'Community Reviews',
        'posts_per_page' => -1,
    );

    if ( ! empty( $_POST['author'] ) ) {
        $args['author_name'] = sanitize_text_field( $_POST['author'] );
    }

    $query = new \WP_Query( $args );

    if ( $query->have_posts() ) {
        echo '<ul>';
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<li>' . get_the_title() . get_the_excerpt() . '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    }
}
?>