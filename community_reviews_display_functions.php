<?php
/**
 * Filter community reviews posts based on settings in community reviews widget
 * 
 * @return  HTML
 */
function bcr_filter_posts() {
    $args = array(
        'post_type'      => 'Community Reviews',
        'posts_per_page' => -1,
    );

    if ( ! empty( $_POST['author'] ) ) {
        $args['author_name'] = sanitize_text_field( $_POST['author'] );
    }

    $meta_query = array();

    if ( ! empty( $_POST['product'] ) ) {
        array_push($meta_query, array('key' => 'product_tested', 'value' => sanitize_text_field( $_POST['product'] )));
    }

    if ( ! empty( $_POST['brand'] ) ) {
        array_push($meta_query, array('key' => 'brand', 'value' => sanitize_text_field( $_POST['brand'] )));
    }

    if ( ! empty( $_POST['category'] ) ) {
        array_push($meta_query, array('key' => 'category', 'value' => sanitize_text_field( $_POST['category'] )));
    }

    if ( ! empty($_POST['min_weight']) And ! empty($_POST['max_weight']) ) {
        echo '<p>' . esc_html(sanitize_text_field( $_POST['min_weight'] )) . ' - ' . esc_html(sanitize_text_field( $_POST['max_weight'] )) . '</p>';
        // array_push($meta_query, array('key' => 'weight', 'value' => array(intval(sanitize_text_field( $_POST['min_weight'] )), intval(sanitize_text_field( $_POST['max_weight'] ))), 'compare' => 'BETWEEN', 'value' => 'numeric') );
    }

    if ( ! empty( $_POST['ski_ability'] ) ) {
        array_push($meta_query, array('key' => 'skiAbility', 'value' => sanitize_text_field( $_POST['ski_ability'] )));
    }

    $args['meta_query'] = $meta_query;

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
    wp_die();
}

add_action( 'wp_ajax_bcr_filter_posts', 'bcr_filter_posts' );
add_action( 'wp_ajax_nopriv_bcr_filter_posts', 'bcr_filter_posts' );

/**
 * Register a category called 'Community Reviews' on the elementor editor panel
 * 
 * @param   Object  elements_manager
 * 
 * @return  void
 */
function bcr_register_widget_category( $elements_manager ) {
    $elements_manager->add_category(
		'community_reviews',
		[
			'title' => esc_html__( 'Community Reviews', 'textdomain' ),
			'icon' => 'fa fa-plug',
		]
	);
}

add_action( 'elementor/elements/categories_registered', 'bcr_register_widget_category' );
?>