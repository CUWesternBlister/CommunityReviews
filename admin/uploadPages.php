<?php
/**
 * Upload pages function
 *
 * @return void
 */

// Define the function that will create a new page
function create_test_page() {
    $post_content = 'Test content';
    $post_title = 'Hello World Testing Page Upload';
    $post_status = 'publish';
    $post_type = 'page';
    
    $test_page = array(
        'post_title' => $post_title,
        'post_content' => $post_content,
        'post_status' => $post_status,
        'post_type' => $post_type
    );
    wp_insert_post( $test_page );
}

/**
 * parsing xml function
 *
 * @return void
 */

function parse_xml() {
    $file = fopen(plugin_dir_path( __FILE__ ) . '/xmlTesting.txt', 'w') or die("Unable to open xmlTesting.txt");
    $filePath = '/Users/jacobvogel/Local Sites/communityreviews-knowthyself/app/public/wp-content/plugins/CommunityReviews/admin/communityreviewprototypes.xml';
    $xml = simplexml_load_file($filePath) or die("Failed to open XML file.");
    foreach($xml->channel->item as $item){
        fwrite($file, $item->title);
        fwrite($file, "\n");
        fwrite($file, $item->)
    }
    
}

parse_xml();

?>
