<?php
/**
 * Plugin Name: Ski Boot Review Custom Post
 * Author: Jacob Vogel
 * Description: Create Ski Boot Review Custom Post from SQL database
 * Version: 0.1.1
 * text-domain: ski_reviews_custom_post
*/

function create_ski_boot_review() {

    $labels = array(
        'name' => _x( 'Ski Boot Reviews', 'Post Type General Name', 'Ski Boot Reviews' ),
        'singular_name' => _x( 'Ski Boot Review', 'Post Type Singular Name', 'Ski Boot Review' ),
        'menu_name' => _x( 'Ski Boot Reviews', 'Admin Menu text', 'Ski Boot Reviews' ),
        'name_admin_bar' => _x( 'Ski Boot Reviews', 'Add New on Toolbar', 'Ski Boot Reviews' ),
        'archives' => __( 'Ski Boot Reviews Archives', 'Ski Boot Reviews' ),
        'attributes' => __( 'Ski Boot Review Attributes', 'Ski Boot Reviews' ),
        'parent_item_colon' => __( 'Parent Ski Boot Reviews:', 'Ski Boot Reviews' ),
        'all_items' => __( 'All Ski Boot Reviews', 'Ski Boot Reviews' ),
        'add_new_item' => __( 'Add New Ski Boot Reviews', 'Ski Boot Reviews' ),
        'add_new' => __( 'New', 'Ski Boot Reviews' ),
        'new_item' => __( 'Create New Ski Boot Review', 'Ski Boot Reviews' ),
        'edit_item' => __( 'Modify Ski Boot Review', 'Ski Boot Reviews' ),
        'update_item' => __( 'Update Ski Boot Review', 'Ski Boot Reviews' ),
        'view_item' => __( 'View Ski Boot Review', 'Ski Boot Reviews' ),
        'view_items' => __( 'View Ski Boot Reviews', 'Ski Boot Reviews' ),
        'search_items' => __( 'Search Ski Boot Reviews', 'Ski Boot Reviews' ),
        'not_found' => __( 'No Ski Boot Reviews found.', 'Ski Boot Reviews' ),
        'not_found_in_trash' => __( 'No Ski Boot Reviews found in the trash.', 'Ski Boot Reviews' ),
        'featured_image' => __( 'Featured Image', 'Ski Boot Reviews' ),
        'set_featured_image' => __( 'Set Featured Image', 'Ski Boot Reviews' ),
        'remove_featured_image' => __( 'Remove Featured Image', 'Ski Boot Reviews' ),
        'use_featured_image' => __( 'Use Featured Image', 'Ski Boot Reviews' ),
        'insert_into_item' => __( 'Insert into Ski Boot Review', 'Ski Boot Reviews' ),
        'uploaded_to_this_item' => __( 'Uploaded to Ski Boot Review', 'Ski Boot Reviews' ),
        'items_list' => __( 'List Ski Boot Reviews', 'Ski Boot Reviews' ),
        'items_list_navigation' => __( 'List Navigation for Ski Boot Reviews', 'Ski Boot Reviews' ),
        'filter_items_list' => __( 'Filter Ski Boot Reviews', 'Ski Boot Reviews' ),
    );
    $args = array(
        'label' => __( 'Ski Boot Reviews', 'Ski Boot Reviews' ),
        'description' => __( 'Ski Boot Reviews', 'Ski Boot Reviews' ),
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
        'query_var' => true,
        'capability_type' => 'post',
    );
    register_post_type( 'Ski Boot Reviews', $args );

}
add_action( 'init', 'create_ski_boot_review', 0 );


add_action( 'admin_init', 'ski_boot_admin' );

function ski_boot_admin() {
    
    add_meta_box(
        'ski_boot_review_meta_box',
        'Ski Boot Reviews Information',
        'display_ski__boot_review_meta_box',
        'Ski Boot Reviews',
        'normal',
        'high'
    );
}

add_action( 'wp_footer', 'display_ski_boot_review_meta_box');

function display_ski_boot_review_meta_box() {
    
    $postmetas = get_post_meta(get_the_ID());

    if ('skibootreviews' == get_post_type(get_the_ID())){

        foreach($postmetas as $meta_key=>$meta_value) {
            echo $meta_key . ' : ' . $meta_value[0] . '<br/>';
        }

    }

}

?>

