<?php
/**
 * Filter community reviews posts based on settings in community reviews widget
 * 
 * @return  HTML
 */
function bcr_filter_posts() {
    global $wpdb;

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $args = array(
        'post_type'      => 'Community Reviews',
        'posts_per_page' => 4,
        'paged'          => $paged,
    );

    $meta_query = array();

    if ( ! empty( $_POST['product'] ) And sanitize_text_field( $_POST['product'] ) != "No Product Filter" ) {
        array_push($meta_query, array('key' => 'product_tested', 'value' => sanitize_text_field( $_POST['product'] )));
    }

    if ( ! empty( $_POST['brand'] ) And sanitize_text_field( $_POST['brand'] ) != "No Brand Filter" ) {
        array_push($meta_query, array('key' => 'brand', 'value' => sanitize_text_field( $_POST['brand'] )));
    }

    if ( ! empty( $_POST['category'] ) And sanitize_text_field( $_POST['category'] ) != "No Category Filter" ) {
        array_push($meta_query, array('key' => 'category', 'value' => sanitize_text_field( $_POST['category'] )));
    } else if ( ! empty( $_POST['sport'] ) And sanitize_text_field( $_POST['sport'] ) != "No Sport Filter" ) {
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

    if ( ! empty( $_POST['ski_ability'] ) And sanitize_text_field( $_POST['ski_ability'] ) != "No Ability Filter" ) {
        array_push($meta_query, array('key' => 'skiAbility', 'value' => sanitize_text_field( $_POST['ski_ability'] )));
    }

    if ( ! empty($_POST['min_weight']) And ! empty($_POST['max_weight']) ) {
        array_push($meta_query, array('key' => 'weight', 'value' => array(sanitize_text_field( $_POST['min_weight'] ), sanitize_text_field( $_POST['max_weight'] )), 'compare' => 'BETWEEN', 'type' => 'numeric') );
    }

    if ( ! empty($_POST['min_height']) And ! empty($_POST['max_height']) ) {
        array_push($meta_query, array('key' => 'height', 'value' => array(sanitize_text_field( $_POST['min_height'] ), sanitize_text_field( $_POST['max_height'] )), 'compare' => 'BETWEEN', 'type' => 'numeric') );
    }

    if ( ! empty($_POST['min_length']) And ! empty($_POST['max_length']) And ! empty($_POST['category']) ) {
        if(sanitize_text_field( $_POST['category'] ) == 'Skis') {
            array_push($meta_query, array('key' => 'length', 'value' => array(sanitize_text_field( $_POST['min_length'] ), sanitize_text_field( $_POST['max_length'] )), 'compare' => 'BETWEEN', 'type' => 'numeric') );
        }
    }

    if ( ! empty($_POST['min_year']) And ! empty($_POST['max_year']) ) {
        $year_query = array('relation' => 'OR', array('key' => 'year', 'value' => ''));
        array_push($year_query, array('key' => 'year', 'value' => array(sanitize_text_field( $_POST['min_year'] ), sanitize_text_field( $_POST['max_year'] )), 'compare' => 'BETWEEN', 'type' => 'numeric') );
        array_push($meta_query, $year_query);
    }

    $args['meta_query'] = $meta_query;

    $query = new \WP_Query( $args );

    bcr_display_posts( $query );
    
    wp_die();
}

add_action( 'wp_ajax_bcr_filter_posts', 'bcr_filter_posts' );
add_action( 'wp_ajax_nopriv_bcr_filter_posts', 'bcr_filter_posts' );

/**
 * Display the pagination controls for the filtering widget
 * 
 * @return  HTML
 */
function bcr_community_reviews_display_pagination($pages) {
    global $paged;
    if(empty($paged)) $paged = 1;

    echo '<div class="community-reviews-display-pagination">';

    echo '<div class="community-reviews-display-pagination-prev">';
    if($paged > 1) {
        echo '<a href="' . get_pagenum_link($paged - 1) . '" id="community-reviews-display-pagination-prev-button"><< Prev</a>';
    }
    echo '</div>';

    echo '<div class="community-reviews-display-pagination-next">';
    if($paged < $pages) {
        echo '<a href="' . get_pagenum_link($paged + 1) . '" id="community-reviews-display-pagination-next-button">Next >></a>';
    }
    echo '</div>';

    echo '</div>';
}

/**
 * Use a query to display the posts for the filtering widget
 * 
 * @param   WP_Query    $query
 * 
 * @return  void
 */
function bcr_display_posts( $query ) {
    if ( $query->have_posts() ) {
        echo '<div class="community-reviews-all-excerpts">';
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<div class="community_review_excerpt">';
            echo '<a href=' . get_the_permalink() . '>';
            echo '<div class="excerpt_title">' . get_the_title() . '</div>';
            echo '<div class="community_review_postdate">' . get_the_date() . '</div>';
            echo '<div class="excerpt_content">' . get_the_excerpt() . '</div>';
            echo '<div>' . the_meta() . '</div>';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';

        bcr_community_reviews_display_pagination($query->max_num_pages);

        wp_reset_postdata();
    }
}

/**
 * Filter the options for the products dropdown based on the currently selected brand
 * 
 * @return  HTML
 */
function bcr_filter_products() {
    global $wpdb;

    $products_table_name = $wpdb->prefix . "bcr_products";

    if ( ! empty( $_POST['brand_selected'] ) And sanitize_text_field( $_POST['brand_selected'] ) != "No Brand Filter" ) {
        $selected_brand = sanitize_text_field( $_POST['brand_selected'] );

        $brands_table_name = $wpdb->prefix . "bcr_brands";

        $sql = $wpdb->prepare("SELECT brandID FROM $brands_table_name WHERE (brandName = %s);", $selected_brand);

        $selected_brand_id = $wpdb->get_var($sql, 0, 0);

        $sql = $wpdb->prepare("SELECT productName FROM $products_table_name WHERE (brandID = %s);", $selected_brand_id);
    } else {
        $sql = $wpdb->prepare("SELECT productName FROM $products_table_name;");
    }

    $results  = $wpdb->get_results($sql);

    echo '<div class="community-reviews-display-title">Product</div>';
    echo '<select id="community-reviews-display-product">';
    
    echo '<option value="No Product Filter">--No Product Filter--</option>';

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

    if ( ! empty( $_POST['sport_selected'] ) And sanitize_text_field( $_POST['sport_selected'] ) != "No Sport Filter" ) {
        $selected_sport = sanitize_text_field( $_POST['sport_selected'] );

        $sql = $wpdb->prepare("SELECT categoryID FROM $categories_table_name WHERE (categoryName=%s);", $selected_sport);

        $selected_sport_id = $wpdb->get_var($sql, 0, 0);

        $sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE (parentID=%s);", $selected_sport_id);
    } else {
        $sql = $wpdb->prepare("SELECT categoryName FROM $categories_table_name WHERE (parentID!=0);");
    }

    $results  = $wpdb->get_results($sql);

    echo '<div class="community-reviews-display-title">Category</div>';
    echo '<select id="community-reviews-display-category">';
    
    echo '<option value="No Category Filter">--No Category Filter--</option>';

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
    wp_enqueue_style( 'filter_widget_style', $plugin_url . 'widgets/community_reviews_display_widget_style.css' );
}
add_action( 'wp_enqueue_scripts', 'bcr_load_filter_widget_css' );

/**
 * Load the JS for the filtering widget
 * 
 * @return  void
 */
function bcr_load_filter_widget_js() {
    wp_enqueue_script( 'filter_widget_js', plugin_dir_url( __FILE__ ) . '/widgets/scripts.js', array( 'jquery' ), 1.1, true);
}

add_action( 'wp_enqueue_scripts', 'bcr_load_filter_widget_js' );
?>