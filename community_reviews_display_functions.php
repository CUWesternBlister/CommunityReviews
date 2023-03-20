<?php
/**
 * Filter community reviews posts based on settings in community reviews widget
 * 
 * @return  HTML
 */
function bcr_filter_posts() {
    global $wpdb;

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
    } else if ( ! empty( $_POST['sport'] ) ) {
        $categories_table_name = $wpdb->prefix . "bcr_categories";

        $sql = $wpdb->prepare("SELECT categoryID FROM $categories_table_name WHERE (categoryName=%s);", sanitize_text_field( $_POST['sport'] ));

        $selected_sport_id = $wpdb->get_var($sql, 0, 0);

        $sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE (parentID=%s);", $selected_sport_id);

        $results  = $wpdb->get_results($sql);
        
        $categories = array(sanitize_text_field( $_POST['sport']));

        foreach ($results as $id => $category_obj) {
            $category_name = $category_obj->categoryName;
            array_push($categories, $category_name);
        }

        array_push($meta_query, array('key' => 'category', 'value' => $categories));
    }

    if ( ! empty($_POST['min_weight']) And ! empty($_POST['max_weight']) ) {
        array_push($meta_query, array('key' => 'weight', 'value' => array(sanitize_text_field( $_POST['min_weight'] ), sanitize_text_field( $_POST['max_weight'] )), 'compare' => 'BETWEEN', 'type' => 'numeric') );
    }

    if ( ! empty($_POST['min_height']) And ! empty($_POST['max_height']) ) {
        array_push($meta_query, array('key' => 'height', 'value' => array(sanitize_text_field( $_POST['min_height'] ), sanitize_text_field( $_POST['max_height'] )), 'compare' => 'BETWEEN', 'type' => 'numeric') );
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
 * Filter the options for the products dropdown based on the currently selected brand
 * 
 * @return  HTML
 */
function bcr_filter_products() {
    global $wpdb;

    $products_table_name = $wpdb->prefix . "bcr_products";

    if ( ! empty( $_POST['brand_selected'] ) ) {
        $selected_brand = sanitize_text_field( $_POST['brand_selected'] );

        $brands_table_name = $wpdb->prefix . "bcr_brands";

        $sql = $wpdb->prepare("SELECT brandID FROM $brands_table_name WHERE (brandName = %s);", $selected_brand);

        $selected_brand_id = $wpdb->get_var($sql, 0, 0);

        $sql = $wpdb->prepare("SELECT productName FROM $products_table_name WHERE (brandID = %s);", $selected_brand_id);
    } else {
        $sql = $wpdb->prepare("SELECT productName FROM $products_table_name;");
    }

    $results  = $wpdb->get_results($sql);

    echo '<label for="community-reviews-display-product">Product:</label>';
    echo '<select id="community-reviews-display-product">';
    
    echo '<option value="">--No Product Filter--</option>';

    foreach ($results as $id => $product_obj) {
        $product_name = $product_obj->productName;

        echo '<option value="' . esc_html($product_name) . '">' . esc_html($product_name) . '</option>';
    }

    echo '</select>';

    wp_die();
}

add_action( 'wp_ajax_bcr_filter_products', 'bcr_filter_products' );
add_action( 'wp_ajax_nopriv_bcr_filter_products', 'bcr_filter_products' );

/**
 * Filter the available category options based on selected sport
 * 
 * @return  HTML
 */
function bcr_filter_categories() {
    global $wpdb;

    $categories_table_name = $wpdb->prefix . "bcr_categories";

    if ( ! empty( $_POST['sport_selected'] ) ) {
        $selected_sport = sanitize_text_field( $_POST['sport_selected'] );

        $sql = $wpdb->prepare("SELECT categoryID FROM $categories_table_name WHERE (categoryName=%s);", $selected_sport);

        $selected_sport_id = $wpdb->get_var($sql, 0, 0);

        $sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE (parentID=%s);", $selected_sport_id);
    } else {
        $sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE (parentID!=0);");
    }

    $results  = $wpdb->get_results($sql);

    echo '<label for="community-reviews-display-category">Category:</label>';
    echo '<select id="community-reviews-display-category">';
    
    echo '<option value="">--No Category Filter--</option>';

    foreach ($results as $id => $category_obj) {
        $category_name = $category_obj->categoryName;

        echo '<option value="' . esc_html($category_name) . '">' . esc_html($category_name) . '</option>';
    }

    echo '</select>';

    wp_die();
}

add_action( 'wp_ajax_bcr_filter_categories', 'bcr_filter_categories' );
add_action( 'wp_ajax_nopriv_bcr_filter_categories', 'bcr_filter_categories' );

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

/**
 * Load the CSS for the filtering widget
 * 
 * @return  void
 */
function bcr_load_filter_widget_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'filter_widget_desktop_style', $plugin_url . 'widgets/desktop_style.css' );
}
add_action( 'wp_enqueue_scripts', 'bcr_load_filter_widget_css' )
?>