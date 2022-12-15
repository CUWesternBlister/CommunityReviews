<?php
/**
 * Plugin Name: Ski Review Custom Post
 * Author: Jacob Vogel
 * Description: Create Ski Review Custom Post from SQL database
 * Version: 0.1.1
 * text-domain: prefix-plugin-name
*/

$filePath = '/Users/jacobvogel/Local Sites/communityreviews-knowthyself/app/public/wp-content/plugins/CommunityReviews/testfile.txt';
$myfile = fopen($filePath, 'a') or die('fopen failed');

function create_ski_review() {
    
    global $myfile;
    
    fwrite($myfile, "Function create_ski_review starting\n");

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
        'query_var' => true,
        'capability_type' => 'post',
    );
    register_post_type( 'Ski Reviews', $args );
    
    fwrite($myfile, "Function create_ski_review finished\n");

}
add_action( 'init', 'create_ski_review', 0 );



add_action( 'admin_init', 'my_admin' );

function my_admin() {
    
    global $myfile;
    
    fwrite($myfile, "Function my_admin starting\n");
    
    add_meta_box(
        'ski_review_meta_box',
        'Ski Reviews Information',
        'display_ski_review_meta_box',
        'Ski Reviews',
        'normal',
        'high'
    );
    fwrite($myfile, "Function my_admin ending\n");
}

function display_ski_review_meta_box() {
    
    global $myfile;
    
    fwrite($myfile, "display_ski_review_meta_box starting\n");
    
    ?>
    <table>
        <tr>
            <td style="width: 50%">ReviewID</td>
            <td><input type="text" size="40" name="ski_review" value="<?php echo get_post_meta( get_the_ID(), 'reviewID', true ); ?>" readonly /></td>
        </tr>
        <tr>
            <td style="width: 50%">Product Tested</td>
            <td><input type="text" size="40" name="ski_review" value="<?php echo get_post_meta( get_the_ID(), 'product_tested', true ); ?>" readonly /></td>
        </tr>
    /*
    <tr>
        <td style="width: 50%">Questions</td>
        <td><input type="text" size="40" name="ski_review" value="<?php echo get_post_meta( get_the_ID(), 'questions', true ); ?>" readonly /></td>
    </tr>*/
    </table>
    <?php
    fwrite($myfile, "display_ski_review_meta_box ending\n");
}



add_action( 'wp', 'insert_into_ski_review' );



function ski_reviews_check_for_similar_meta_ids() {
    
    global $myfile;
    
    fwrite($myfile, "ski_reviews_check_for_similar_meta_ids starting\n");
    $id_arrays_in_cpt = array();

    $args = array(
        'post_type'      => 'Ski Reviews',
        'posts_per_page' => -1,
    );
    
    

    $loop = new WP_Query($args);
    
    if( $loop->have_posts){
        while( $loop->have_posts() ) {
            $loop->the_post();
            $id_arrays_in_cpt[] = get_post_meta( get_the_ID(), 'id', true );
        }
    }
    
    fwrite($myfile, "ski_reviews_check_for_similar_meta_ids returning\n");

    return $id_arrays_in_cpt;
}

function ski_reviews_query_database_header( $ski_review_available_in_cpt_array ) {
    global $wpdb;
    global $myfile;
    //$filePath = '/Users/jacobvogel/Local Sites/communityreviews-knowthyself/app/public/wp-content/plugins/CommunityReviews/testfile.txt';
    //$myfile = fopen($filePath, 'a') or die('fopen failed');
    
    fwrite($myfile, "ski_reviews_query_database_header starting\n");
    
    if ( NULL === $ski_review_available_in_cpt_array || 0 === $ski_review_available_in_cpt_array || '0' === $ski_review_available_in_cpt_array || empty( $ski_review_available_in_cpt_array ) ) {
        fwrite($myfile, "ski_reviews_query_database_header running header query\n");
        $headerSql = "SELECT reviewID, userID, productName, categoryName, sportName
                            FROM wp_bcr_reviews AS reviews
                            INNER JOIN
                            wp_bcr_review_forms AS reviewForms
                            ON reviews.reviewFormID=reviewForms.reviewFormID
                            INNER JOIN
                            wp_bcr_products AS products
                            ON reviewForms.productID=products.productID
                            INNER JOIN
                            wp_bcr_categories AS categories
                            ON categories.categoryID=products.categoryID
                            INNER JOIN
                            wp_bcr_sports AS sports
                            ON sports.sportID=categories.sportID";
        $header = $wpdb->get_results($headerSql);
        $answerSql = "SELECT answerContent, questionContent
                            FROM wp_bcr_reviews AS reviews
                            INNER JOIN
                            wp_bcr_reviews_answers as reviewAnswers
                            ON reviewAnswers.reviewID=reviews.reviewID
                            INNER JOIN
                            wp_bcr_answers as answers
                            ON answers.answerID=reviewAnswers.answerID
                            INNER JOIN
                            wp_bcr_questions as questions
                            ON questions.questionID=answers.questionID
                            LIMIT 1";
        $answers = $wpdb->get_results($answerSql);
        //$returnArray = array($header,$answers);
        fwrite($myfile, "ski_reviews_query_database_header returning\n");
        return $header;
        /*$productName = $header->productName;
        fwrite($myfile,"productName: ". $productName ."\n");
        fclose($myfile);*/
        //return $header;
    } else {
        $ids = implode( ",", $ski_review_available_in_cpt_array );
        $headerSql = "SELECT reviewID, userID, productName, categoryName, sportName
                            FROM wp_bcr_reviews AS reviews
                            INNER JOIN
                            wp_bcr_review_forms AS reviewForms
                            ON reviews.reviewFormID=reviewForms.reviewFormID
                            INNER JOIN
                            wp_bcr_products AS products
                            ON reviewForms.productID=products.productID
                            INNER JOIN
                            wp_bcr_categories AS categories
                            ON categories.categoryID=products.categoryID
                            INNER JOIN
                            wp_bcr_sports AS sports
                            ON sports.sportID=categories.sportID
                            WHERE id NOT IN ( $ids )";
        $header = $wpdb->get_results($headerSql);
        /*fwrite($myfile,"header2: ".$header."\n");
        fclose($myfile);*/
        fwrite($myfile, "ski_reviews_query_database_header returning\n");
        return $header;
        }
    }

function ski_reviews_query_database_QnA( $ski_review_available_in_cpt_array ) {
    global $wpdb;
    global $myfile;
    //$filePath = '/Users/jacobvogel/Local Sites/communityreviews-knowthyself/app/public/wp-content/plugins/CommunityReviews/testfile.txt';
    //$myfile = fopen($filePath, 'a') or die('fopen failed');
    
    fwrite($myfile, "ski_reviews_query_database_QnA starting\n");
    
    if ( NULL === $ski_review_available_in_cpt_array || 0 === $ski_review_available_in_cpt_array || '0' === $ski_review_available_in_cpt_array || empty( $ski_review_available_in_cpt_array ) ) {
        $answerSql = "SELECT answerContent, questionContent
                            FROM wp_bcr_reviews AS reviews
                            INNER JOIN
                            wp_bcr_reviews_answers as reviewAnswers
                            ON reviewAnswers.reviewID=reviews.reviewID
                            INNER JOIN
                            wp_bcr_answers as answers
                            ON answers.answerID=reviewAnswers.answerID
                            INNER JOIN
                            wp_bcr_questions as questions
                            ON questions.questionID=answers.questionID";
        $answers = $wpdb->get_results($answerSql);
        /*fwrite($myfile,"answers1: ".$answers->answerContent."\n");
        fclose($myfile);*/
        
        fwrite($myfile, "ski_reviews_query_database_header returning\n");
        return $answers;
        
    } else {
        $ids = implode( ",", $ski_review_available_in_cpt_array );
        $answerSql = "SELECT questionContent, answerContent
                            FROM wp_bcr_reviews AS reviews
                            INNER JOIN
                            wp_bcr_reviews_answers as reviewAnswers
                            ON reviewAnswers.reviewID=reviews.reviewID
                            INNER JOIN
                            wp_bcr_answers as answers
                            ON answers.answerID=reviewAnswers.answerID
                            INNER JOIN
                            wp_bcr_questions as questions
                            ON questions.questionID=answers.questionID
                            WHERE id NOT IN ( $ids )";
        $answers = $wpdb->get_results($answerSql);
        
        /*fwrite($myfile,"answers2: ".$answers."\n");
        fclose($myfile);*/
        fwrite($myfile, "ski_reviews_query_database_header returning\n");
        return $answers;
    }
    
}
    
function insert_into_ski_review() {
        global $wpdb;
        global $myfile;
    
        fwrite($myfile, "insert_into_ski_review() starting\n");
        
        $ski_review_available_in_cpt_array_1 = ski_reviews_check_for_similar_meta_ids();
        $ski_review_available_in_cpt_array_2 = $ski_review_available_in_cpt_array_1;
        
        $header = ski_reviews_query_database_header( $ski_review_available_in_cpt_array_1 );
        
        if ( NULL === $header || 0 === $header || '0' === $header || empty( $header ) ) {
            return;
        }
        
        //$header = $results[0];
        //$QnA = $results[1];
        
        //$QnA = ski_reviews_query_database_QnA( $ski_review_available_in_cpt_array_2 );
        
        //$answerContent = $QnA->answerContent;
        
        
        fwrite($myfile,"productName: " . $header->productName . "\n");
        fwrite($myfile,"header: " . $header . "\n");
        fwrite($myfile,"header: " . $header . "\n");
        fwrite($myfile,"\n\n");
        
        //foreach ( $header as $header ) {
        //$meta_input =
        $ski_review = array(
                            'post_title' => wp_strip_all_tags( /*$QnA->answerContent . ' ' .*/ $header->productName /*. ' ' . $QnA->answerContent*/),
                            //'post_content' => wp_strip_all_tags( $QnA->$answerContent),
                            'meta_input' => array(
                                                  'id'        => $header->reviewID,
                                                  'product_tested'        => $header->productName,
                                                  'category'           => $header->categoryName,
                                                  'sport'          => $header->sportName,
                                                  //'questions'          => $QnA->questionContent,
                                                  //'answers'           => $QnA->answerContent
                                                  ),
                            'post_type'   => 'Ski Reviews',
                            'post_status' => 'publish',
                            );
        fwrite($myfile, "insert_into_ski_review() inserting post\n");
        wp_insert_post( $ski_review );
        fwrite($myfile, "post inserted??\n\n");
        //}
        
    }
?>
