<?php

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
        'query_var' => truzoome,
        'capability_type' => 'post',
    );
    register_post_type( 'Ski Reviews', $args );
    
    //fwrite($myfile, "Function create_ski_review finished\n");

}
add_action( 'init', 'create_ski_review', 0 );

function my_admin() {
    
    //global $myfile;
    
    //fwrite($myfile, "Function my_admin starting\n");
    
    add_meta_box(
        'ski_review_meta_box',
        'Ski Reviews Information',
        'display_ski_review_meta_box',
        'Ski Reviews',
        'normal',
        'high'
    );
    //fwrite($myfile, "Function my_admin ending\n");
}
add_action( 'admin_init', 'my_admin' );

function display_ski_review_meta_box() {
    
    //global $myfile;
    
    //fwrite($myfile, "display_ski_review_meta_box starting\n");
    
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
    //fwrite($myfile, "display_ski_review_meta_box ending\n");
}

function ski_reviews_check_for_similar_meta_ids() {
    
    //global $myfile;
    
    //fwrite($myfile, "ski_reviews_check_for_similar_meta_ids starting\n");
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
    
    //fwrite($myfile, "ski_reviews_check_for_similar_meta_ids returning\n");

    return $id_arrays_in_cpt;
}

function insert_into_ski_review() {
        global $wpdb;
        //global $myfile;
    
        //fwrite($myfile, "insert_into_ski_review() starting\n");
        
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
        
        
        //fwrite($myfile,"productName: " . $header->productName . "\n");
        //fwrite($myfile,"header: " . $header . "\n");
        //fwrite($myfile,"header: " . $header . "\n");
        //fwrite($myfile,"\n\n");
        
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
        //fwrite($myfile, "insert_into_ski_review() inserting post\n");
        wp_insert_post( $ski_review );
        //fwrite($myfile, "post inserted??\n\n");
        //}
        
    }

function summit_review_from_sub( $record, $ajax_handler ) {
    global $wpdb;
    $file_path = plugin_dir_path( __FILE__ ) . '/testfile.txt'; 
    $myfile = fopen($file_path, "a") or die('fopen failed');
    $existing_form_names = get_all_form_names($myfile);
    fwrite($myfile,"form names: ".$existing_form_names."\n");


    $current_form_name = $record->get_form_settings( 'form_name' ); // this may be a way to get the form id!!!!!!
    fwrite($myfile,"current form name: ".$current_form_name."\n");


    $current_form_id = $record->get_form_settings('form_id');
    fwrite($myfile,"current form id: ".$current_form_id."\n"); 

    if(in_array($current_form_name, $existing_form_names)){
        
        $answer_ids = summit_insert_into_answer_table($record, $myfile);
        fwrite($myfile, "write to answer table ids:\n") or die('fwrite 1 failed');
        $res1 = implode(", ", $answer_ids)."\n";
        fwrite($myfile, $res1) or die('fwrite 2 failed');


        //$ajax_handler->add_response_data( true, $output );
        fwrite($myfile, "write to review table id:\n") or die('fwrite 3 failed');
        $id = summit_insert_into_review_table($current_form_id,$myfile);
        fwrite($myfile, "last inserted review id".strval($id)."\n") or die('fwrite 2 failed');
        //$ajax_handler->add_response_data( true, $output );
        

        summit_insert_into_review_answer_table($id, $answer_ids,$myfile);
        //$ajax_handler->add_response_data( true, "this is  another test" );
    }
    fwrite($myfile, "\n\n-------------------------------------------\n\n") or die('fwrite 1 failed');
    fclose($myfile);
}

function get_all_form_names($file){
    global $wpdb;
    $start = "\n\n SUMMIT get all form names \n";
    fwrite($file, $start);
    $review_forms_table = $wpdb->prefix . "bcr_review_forms";
    $q = "SELECT reviewFormName FROM $review_forms_table;";
    //$wpdb->prepare("SELECT %s FROM %s;", array("reviewFormName", $review_table));
    $res = $wpdb->get_results($q);
    $ret_arr = [];
    foreach($res as $r){
        fwrite($file,$r->reviewFormName."\n");
        array_push($ret_arr, $r->reviewFormName);
    }
    return $ret_arr;
}

function summit_answer_array($record,$file){
    //global $wpdb;
    $start = "\n\n SUMMIT INSERT INTO ANSWER TABLE \n";
    fwrite($file, $start);

    $return_array = [];
    //$answer_table = $wpdb->prefix . "bcr_answers";
    //$answer_ids = []; //used for when inserting into reviews answers
    $answer_content = [];
    $question_ids = [];//manually entered into elementor form, until we can make a form dynamically 
    

    $raw_fields = $record->get( 'fields' );
    $output = [];
    foreach ( $raw_fields as $id => $field ) {
        if($id != "step"){
            /*
            $fields_answers = [];
            $fields_answers['questionID'] = $id;
            $fields_answers['answerContent'] = $field['value'];
            $output['success'] = $wpdb->insert($answer_table, $fields_answers);
            //if($output == success){

            //}
            //$last_answer_id = $wpdb->insert_id;//answer id generated upon entering into table
                                                //!!!!!!may be susceptible to collisions with mulitple users
            array_push($answer_content, $field['value'];);
            array_push($question_ids, $id)
        }
    }
    //$ajax_handler->add_response_data( true, $output );
    return $answer_ids;
}

function summit_insert_into_review_table($RF_id, $file){
       //insert review 
        //echo "in review table functions!<br>";
        global $wpdb;
        $start = "\n\n SUMMIT INSERT INTO REVIEW TABLE \n";
        fwrite($file, $start);
        $output2 = [];
        $review_table = $wpdb->prefix . "bcr_reviews";
        $fields_review = [];



        $current_userID = get_current_userID($file);
        if(gettype($current_userID)=="string"){
            fwrite($file,"user id grab failed: ".$current_userID."\n");
            die("user not found"); //should be a redirct to another page
        }
        fwrite($file,"userID = ".strval($current_userID)."\n");
        $fields_review['userID'] = $current_userID;
        


        //$KTSid = get_knowthyself_id($current_userID);
        $fields_review['knowThyselfID'] = 4;//get this id from bcr_know_thyself using userid HAS TO EXIST BEFORE SUBMISSION
        



        $fields_review['reviewFormID'] = $RF_id;//some how get review form id upon submission, could first step id in form HAS TO EXIST BEFORE SUBMISSION



        fwrite($file, "fields_review: ".implode(", ",$fields_review)."\n");
        $output2['success'] = $wpdb->insert($review_table, $fields_review);
        //$str = "number of rows inserted: ".strval($output['success'])." ||| did not work if false\n";
        //fwrite($file, $str);
        //echo strval(output['success'])."<br>";
        //$ajax_handler->add_response_data( true, $output );
        $last_review_id = $wpdb->insert_id;
        return $last_review_id;
}

function get_current_userID($file){
    global $wpdb;
    $start = "          SUMMIT get user id \n";
    fwrite($file, $start);
    if ( ! function_exists( 'get_current_user_id' ) ) {
        return 0;
    }
    $cur_userID = get_current_user_id();
    $str = "-------- " . strval($cur_userID) . " ----------\n";
    fwrite($file, $str);
    if($cur_userID == 0){
        //then not logged in
        //we should check this field when they click to start a review form.
        return "userID does not exist, or user is not logged in";
    }
    $user_table = $wpdb->prefix . "bcr_users";
    $q = "SELECT 1 userID FROM $user_table WHERE userID = $cur_userID;";
    $res = $wpdb->query($q);
    if($res == false){
        //should not be allowed to start a form untill they are in bcr users
        return "userID does not exist in bcr user table, has not registerd";
    }
    //check if user in wp bcr users
    return intval($cur_userID);
}

function get_knowthyself_id($userID){
    global $wpdb;
    $KTS_table = $wpdb->prefix . "bcr_know_thyself";
    $q = "SELECT * FROM $KTS_table WHERE %s = %d;";/////////start here with SELECT * FROM wp_bcr_know_thyself WHERE userID = 1;
    $res = $wpdb->get_results($q);
    return $res->knowThyselfID;
}

function summit_insert_into_review_answer_table($review_id, $answer_ids,$file){
    //insert answer ids int review answer table
        global $wpdb;
        $start = "\n\n SUMMIT INSERT INTO REVIEW ANSWER TABLE \n";
        fwrite($file, $start);
        $review_answer_table = $wpdb->prefix . "bcr_reviews_answers";
        $fields_review_answers = [];
        $output=[];
        foreach($answer_ids as $id){
            $fields_review_answers['reviewID'] = $review_id;
            $fields_review_answers['answerID'] = $id;
            $output['success'] = $wpdb->insert($review_answer_table, $fields_review_answers);
            //if($output == success){

            //}
        }
}

?>