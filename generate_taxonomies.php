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
    'hierarchical' => false,
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
      $result = wp_insert_term($category->categoryName, 'bcr_categories'/*, array('parent' => $term_ids[$category->parentID])*/);
      if(!is_wp_error($result)) {
          $term_ids[$category->categoryID] = $result['term_id'];
      } else {
        break;
      }
    }
}

add_action( 'init', 'bcr_generate_terms', 0 );


//------------------------------------------------------------------

function render_custom_taxonomy_meta_box( $post ) {
    $image_id = get_term_meta( $post->term_id, 'custom_taxonomy_image_id', true );
    $image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
    ?>
    <div class="form-field">
        <label for="custom_taxonomy_image"><?php _e( 'Image', 'textdomain' ); ?></label>
        <input type="hidden" name="custom_taxonomy_image_id" id="custom_taxonomy_image_id" value="<?php echo esc_attr( $image_id ); ?>">
        <img src="<?php echo esc_url( $image_url ); ?>" width="100" height="100">
        <input type="button" id="custom_taxonomy_image_button" class="button" value="<?php _e( 'Upload', 'textdomain' ); ?>">
    </div>
    <?php
}

add_action( 'category_add_form_fields', 'render_custom_taxonomy_meta_box' );


function custom_taxonomy_meta_box_scripts() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Uploading files
        var file_frame;
        $('#custom_taxonomy_image_button').on('click', function(event) {
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                file_frame.open();
                return;
            }
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {
                    text: $(this).data('uploader_button_text'),
                },
                multiple: false
            });
            // When an image is selected, run a callback.
            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $('#custom_taxonomy_image_id').val(attachment.id);
                $('#custom_taxonomy_image').attr('src', attachment.url);
            });
            // Finally, open the modal.
            file_frame.open();
        });
    });
    </script>
    <?php
}
add_action( 'admin_enqueue_scripts', 'custom_taxonomy_meta_box_scripts' );

function save_custom_taxonomy_meta( $term_id, $taxonomy ) {
    if ( isset( $_POST['custom_taxonomy_image_id'] ) ) {
        update_term_meta( $term_id, 'custom_taxonomy_image_id', absint( $_POST['custom_taxonomy_image_id'] ) );
    }
}
add_action( 'edited_custom_taxonomy', 'save_custom_taxonomy_meta', 10, 2 );

?>