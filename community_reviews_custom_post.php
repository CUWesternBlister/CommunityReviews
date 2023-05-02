<?php

function create_community_review() {

    $labels = array(
        'name' => _x( 'Community Reviews', 'Post Type General Name', 'Community Reviews' ),
        'singular_name' => _x( 'Community Review', 'Post Type Singular Name', 'Community Review' ),
        'menu_name' => _x( 'Community Reviews', 'Admin Menu text', 'Community Reviews' ),
        'name_admin_bar' => _x( 'Community Reviews', 'Add New on Toolbar', 'Community Reviews' ),
        'archives' => __( 'Community Reviews Archives', 'Community Reviews' ),
        'attributes' => __( 'Community Review Attributes', 'Community Reviews' ),
        'parent_item_colon' => __( 'Parent Community Reviews:', 'Community Reviews' ),
        'all_items' => __( 'All Community Reviews', 'Community Reviews' ),
        'add_new_item' => __( 'Add New Community Reviews', 'Community Reviews' ),
        'add_new' => __( 'New', 'Community Reviews' ),
        'new_item' => __( 'Create New Community Review', 'Community Reviews' ),
        'edit_item' => __( 'Modify Community Review', 'Community Reviews' ),
        'update_item' => __( 'Update Community Review', 'Community Reviews' ),
        'view_item' => __( 'View Community Review', 'Community Reviews' ),
        'view_items' => __( 'View Community Reviews', 'Community Reviews' ),
        'search_items' => __( 'Search Community Reviews', 'Community Reviews' ),
        'not_found' => __( 'No Community Reviews found.', 'Community Reviews' ),
        'not_found_in_trash' => __( 'No Community Reviews found in the trash.', 'Community Reviews' ),
        'featured_image' => __( 'Featured Image', 'Community Reviews' ),
        'set_featured_image' => __( 'Set Featured Image', 'Community Reviews' ),
        'remove_featured_image' => __( 'Remove Featured Image', 'Community Reviews' ),
        'use_featured_image' => __( 'Use Featured Image', 'Community Reviews' ),
        'insert_into_item' => __( 'Insert into Community Review', 'Community Reviews' ),
        'uploaded_to_this_item' => __( 'Uploaded to Community Review', 'Community Reviews' ),
        'items_list' => __( 'List Community Reviews', 'Community Reviews' ),
        'items_list_navigation' => __( 'List Navigation for Community Reviews', 'Community Reviews' ),
        'filter_items_list' => __( 'Filter Community Reviews', 'Community Reviews' ),
    );
    $args = array(
        'label' => __( 'Community Reviews', 'Community Reviews' ),
        'description' => __( 'Community Reviews', 'Community Reviews' ),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title','editor','revisions','trackbacks','author','excerpt','custom-fields', 'post-formats'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'query_var' => true,//truzoome
        'capability_type' => 'post',
    );
    register_post_type( 'Community Reviews', $args );

}
add_action( 'init', 'create_community_review', 0 );



add_action( 'admin_init', 'ski_admin' );

function ski_admin() {   
    add_meta_box(
        'community_review_meta_box',
        'Community Reviews Information',
        'display_community_review_meta_box',
        'Community Reviews',
        'normal',
        'high'
    );
}

add_action( 'wp_footer', 'display_community_review_meta_box');

function display_community_review_meta_box() {
    
    $postmetas = get_post_meta(get_the_ID());
   

}

?>
