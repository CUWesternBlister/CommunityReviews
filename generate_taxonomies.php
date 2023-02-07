<?php
/**
 * Registers the review categories as a taxonomy for the reviews
 * 
 * @return void
 */
function bcr_create_review_category_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Category', 'taxonomy general name' ),
    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Categories' ),
    'parent_item' => __( 'Parent Categories' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Category' ), 
    'update_item' => __( 'Update Category' ),
    'add_new_item' => __( 'Add New Category' ),
    'new_item_name' => __( 'New Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    
  
// Now register the taxonomy
  register_taxonomy('bcr_categories','community_reviews', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'category' ),
  ));
}

add_action( 'init', 'bcr_create_review_category_hierarchical_taxonomy', 0 );

/**
 * Reads the categories table and generates terms based on categories
 * 
 * @return null
 */
function bcr_generate_terms() {
    global $wpdb;

    $categories_table_name = $wpdb->prefix . "bcr_categories";

    $sql = $wpdb->prepare("SELECT * FROM $categories_table_name;");
    $existing_categories = $wpdb->get_results($sql);

    $term_ids = array(0 => 0);
    foreach($existing_categories as $id=>$category) {
    $result = wp_insert_term($category->categoryName, 'bcr_categories', array('parent' => $term_ids[$category->parentID]));
    if(!is_wp_error($result)) {
        $term_ids[$category->categoryID] = $result['term_id'];
    }
    }
}

add_action( 'init', 'bcr_generate_terms', 0 );
?>