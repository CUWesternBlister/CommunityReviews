<?php
/**
 * Plugin Name: Ski Review Custom Post
 * Author: Jacob Vogel and Jayden Omi
 * Description: Create Ski Review Custom Post from SQL database
 * Version: 0.1.0
 * text-domain: prefix-plugin-name
*/
function create_ski_review() {

    $labels = array(
        'name' => _x( 'Ski Reviews', 'Post Type General Name', 'Ski Reviews' ),
        'singular_name' => _x( 'Ski Review', 'Post Type Singular Name', 'Ski Review' ),
        'menu_name' => _x( 'Ski Reviews', 'Admin Menu text', 'Ski Reviews' ),
        'name_admin_bar' => _x( 'Ski Reviews', 'Add New on Toolbar', 'Ski Reviews' ),
        'archives' => __( 'Archivi Ski Reviews', 'Ski Reviews' ),
        'attributes' => __( 'Attributi delle Ski Reviews', 'Ski Reviews' ),
        'parent_item_colon' => __( 'Genitori Ski Reviews:', 'Ski Reviews' ),
        'all_items' => __( 'Tutti le Ski Reviews', 'Ski Reviews' ),
        'add_new_item' => __( 'Aggiungi nuova Ski Reviews', 'Ski Reviews' ),
        'add_new' => __( 'Nuovo', 'Ski Reviews' ),
        'new_item' => __( 'Ski Reviews redigere', 'Ski Reviews' ),
        'edit_item' => __( 'Modifica Ski Reviews', 'Ski Reviews' ),
        'update_item' => __( 'Aggiorna Ski Reviews', 'Ski Reviews' ),
        'view_item' => __( 'Visualizza Ski Reviews', 'Ski Reviews' ),
        'view_items' => __( 'Visualizza le Ski Reviews', 'Ski Reviews' ),
        'search_items' => __( 'Cerca Ski Reviews', 'Ski Reviews' ),
        'not_found' => __( 'Nessun Ski Reviews trovato.', 'Ski Reviews' ),
        'not_found_in_trash' => __( 'Nessun Ski Reviews trovato nel cestino.', 'Ski Reviews' ),
        'featured_image' => __( 'Immagine in evidenza', 'Ski Reviews' ),
        'set_featured_image' => __( 'Imposta immagine in evidenza', 'Ski Reviews' ),
        'remove_featured_image' => __( 'Rimuovi immagine in evidenza', 'Ski Reviews' ),
        'use_featured_image' => __( 'Usa come immagine in evidenza', 'Ski Reviews' ),
        'insert_into_item' => __( 'Inserisci nelle Ski Reviews', 'Ski Reviews' ),
        'uploaded_to_this_item' => __( 'Caricato in questo Ski Reviews', 'Ski Reviews' ),
        'items_list' => __( 'Elenco degli Ski Reviews', 'Ski Reviews' ),
        'items_list_navigation' => __( 'Navigazione elenco Ski Reviews', 'Ski Reviews' ),
        'filter_items_list' => __( 'Filtra elenco Ski Reviews', 'Ski Reviews' ),
    );
    $args = array(
        'label' => __( 'Ski Reviews', 'Ski Reviews' ),
        'description' => __( 'Ski Reviews', 'Ski Reviews' ),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array(),
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
        'capability_type' => 'post',
    );
    register_post_type( 'Ski Reviews', $args );

}
add_action( 'init', 'create_ski_review', 0 );

add_action( 'admin_init', 'my_admin' );

function my_admin() {
    add_meta_box(
        'ski_review_meta_box',
        'Ski Reviews Information',
        'display_ski_review_meta_box',
        'Ski Reviews',
        'normal',
        'high'
    );
}

function display_ski_review_meta_box() {
    ?>
    <table>
        <tr>
            <td style="width: 50%">ReviewID</td>
            <td><input type="text" size="40" name="garage" value="<?php echo get_post_meta( get_the_ID(), 'reviewID', true ); ?>" readonly /></td>
        </tr>
        <tr>
            <td style="width: 50%">Product Tested</td>
            <td><input type="text" size="40" name="garage" value="<?php echo get_post_meta( get_the_ID(), 'product_tested', true ); ?>" readonly /></td>
        </tr>
        <tr>
            <td style="width: 50%">Brand Tested</td>
            <td><input type="text" size="40" name="garage" value="<?php echo get_post_meta( get_the_ID(), 'brand_tested', true ); ?>" readonly /></td>
        </tr>
        <tr>
            <td style="width: 50%">Length Tested</td>
            <td><input type="text" size="40" name="garage" value="<?php echo get_post_meta( get_the_ID(), 'length_tested', true ); ?>" readonly /></td>
        </tr>
    </table>
    <?php
}

add_action( 'wp', 'insert_into_ski_review' );

// NEED TO EDIT BELOW HERE

function ski_reviews_check_for_similar_meta_ids() {
    $id_arrays_in_cpt = array();

    $args = array(
        'post_type'      => 'Ski Reviews',
        'posts_per_page' => -1,
    );

    $loop = new WP_Query($args);
    while( $loop->have_posts() ) {
        $loop->the_post();
        $id_arrays_in_cpt[] = get_post_meta( get_the_ID(), 'id', true );
    }

    return $id_arrays_in_cpt;
}

function ski_reviews_query_garage_table( $car_available_in_cpt_array ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'garage';

    if ( NULL === $car_available_in_cpt_array || 0 === $car_available_in_cpt_array || '0' === $car_available_in_cpt_array || empty( $car_available_in_cpt_array ) ) {
        $results = $wpdb->get_results("SELECT * FROM $table_name");
        return $results;
    } else {
        $ids = implode( ",", $car_available_in_cpt_array );
        $sql = "SELECT * FROM $table_name WHERE id NOT IN ( $ids )";
        $results = $wpdb->get_results( $sql );
        return $results;
    }
}

function ski_reviews_insert_into_auto_cpt() {

    $car_available_in_cpt_array = ski_reviews_check_for_similar_meta_ids();
    $database_results = ski_reviews_query_garage_table( $car_available_in_cpt_array );

    if ( NULL === $database_results || 0 === $database_results || '0' === $database_results || empty( $database_results ) ) {
        return;
    }

    foreach ( $database_results as $result ) {
        $car_model = array(
            'post_title' => wp_strip_all_tags( $result->Brand . ' ' . $result->Model . ' ' . $result->Km ),
            'meta_input' => array(
                'id'        => $result->id,
                'brand'        => $result->Brand,
                'model'        => $result->Model,
                'color'        => $result->Color,
                'km'           => $result->Km,
            ),
            'post_type'   => 'auto',
            'post_status' => 'publish',
        );
        wp_insert_post( $car_model );
    }
}
