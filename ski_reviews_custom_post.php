<?php
/**
 * Plugin Name: Ski Review Custom Post
 * Author: Jacob Vogel
 * Description: Create Ski Review Custom Post from SQL database
 * Version: 0.1.1
 * text-domain: prefix-plugin-name
*/

//$filePath = 'C:/Users/user/Local Sites/blister-capstone-project/app/public/wp-content/plugins/blister-community-reviews/testfile.txt';
//$myfile = fopen($filePath, 'a') or die('fopen failed');

function create_ski_review() {
    
    //global $myfile;
    
    //fwrite($myfile, "Function create_ski_review starting\n");

    $labels = array(
        'name' => _x( 'Ski Reviews', 'Post Type General Name', 'Ski Reviews' ),
        'singular_name' => _x( 'Ski Review', 'Post Type Singular Name', 'Ski Review' ),
        'menu_name' => _x( 'Ski Reviews', 'Admin Menu text', 'Ski Reviews' ),
        'name_admin_bar' => _x( 'Ski Reviews', 'Add New on Toolbar', 'Ski Reviews' ),
        'archives' => __( 'Ski Reviews Archives', 'Ski Reviews' ),
        'attributes' => __( 'Ski Review Attributes', 'Ski Reviews' ),
        'parent_item_colon' => __( 'Parent Ski Reviews:', 'Ski Reviews' ),
        'all_items' => __( 'All Ski Reviews', 'Ski Reviews' ),
        'add_new_item' => __( 'Add New Ski Reviews', 'Ski Reviews' ),
        'add_new' => __( 'New', 'Ski Reviews' ),
        'new_item' => __( 'Create New Ski Review', 'Ski Reviews' ),
        'edit_item' => __( 'Modify Ski Review', 'Ski Reviews' ),
        'update_item' => __( 'Update Ski Review', 'Ski Reviews' ),
        'view_item' => __( 'View Ski Review', 'Ski Reviews' ),
        'view_items' => __( 'View Ski Reviews', 'Ski Reviews' ),
        'search_items' => __( 'Search Ski Reviews', 'Ski Reviews' ),
        'not_found' => __( 'No Ski Reviews found.', 'Ski Reviews' ),
        'not_found_in_trash' => __( 'No Ski Reviews found in the trash.', 'Ski Reviews' ),
        'featured_image' => __( 'Featured Image', 'Ski Reviews' ),
        'set_featured_image' => __( 'Set Featured Image', 'Ski Reviews' ),
        'remove_featured_image' => __( 'Remove Featured Image', 'Ski Reviews' ),
        'use_featured_image' => __( 'Use Featured Image', 'Ski Reviews' ),
        'insert_into_item' => __( 'Insert into Ski Review', 'Ski Reviews' ),
        'uploaded_to_this_item' => __( 'Uploaded to Ski Review', 'Ski Reviews' ),
        'items_list' => __( 'List Ski Reviews', 'Ski Reviews' ),
        'items_list_navigation' => __( 'List Navigation for Ski Reviews', 'Ski Reviews' ),
        'filter_items_list' => __( 'Filter Ski Reviews', 'Ski Reviews' ),
    );
    $args = array(
        'label' => __( 'Ski Reviews', 'Ski Reviews' ),
        'description' => __( 'Ski Reviews', 'Ski Reviews' ),
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
    register_post_type( 'Ski Reviews', $args );
    
    //fwrite($myfile, "Function create_ski_review finished\n");

}
add_action( 'init', 'create_ski_review', 0 );



add_action( 'admin_init', 'my_admin' );

function my_admin() {
    
    //global $myfile;
    
    //fwrite($myfile, "Function my_admin starting\n");
    
    add_meta_box(
        'ski_review_meta_box',
        'Ski Reviews Information',
        //'display_ski_review_meta_box',
        'Ski Reviews',
        'normal',
        'high'
    );
    //fwrite($myfile, "Function my_admin ending\n");
}

//add_action( 'wp_footer', 'display_ski_review_meta_box');
//add_filter('the_content', 'display_ski_review_meta_box');

function display_ski_review_meta_box($content) {
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt';
    $myfile = fopen($file_path, "a") or die('fopen failed');
    $postmetas = get_post_meta(get_the_ID());
    $questions = $postmetas['questions'];
    $answers = $postmetas['answers'];
    $a_metas = print_r($answers, true);
    fwrite($myfile, " info: \n".$a_metas."\n\n");

    $html = "";

    
    $post_metas = print_r($postmetas, true);
    fwrite($myfile, " info: \n".$post_metas."\n\n");

    if ('skireviews' == get_post_type(get_the_ID())){

        //foreach($postmetas as $meta_key=>$meta_value) {
            $html .= "Reviewers height: ".$postmetas['heightFeet'][0]." feet, ".$postmetas['heightInches'][0]." inches<br/>";
            $html .= "Reviewers weight: ".$postmetas['weight'][0]." lbs<br/>";
            $html .= "Reviewers ski ability: ".$postmetas['skiAbility'][0]."<br/>";
            $html .= "Reviewers product tested: ".$postmetas['product_tested'][0]."<br/>";
            $html .= "Reviewers product tested: ".$postmetas['product_tested'][0]."<br/>";
            $html .= "<br/>";

            //if($meta_key == "sports"){break;}
        //}

        for ($i = 0; $i < count($questions); $i++) {
            $html .= 'QQQQQQQQQQ '.$questions[$i].'<br/>';
            $html .= 'AAAAAAAAAA '.$answers[$i].'<br/>';
            $html .= '<br/>';


            //$html .= $questions[$i].":\n";
            //$html .= $answers[$i]."\n\n";

        }

    }

    return $content.$html;

}

?>
