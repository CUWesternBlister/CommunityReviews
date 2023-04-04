<?php
/**
 * Add javascript to pages to modify fluent forms dropdowns through ajax
 * 
 * @return	null
 */
function bcr_enqueue_fluent_forms_modifier_scripts() {       
	wp_enqueue_script( 'bcr_fluent_forms_modifiers', plugin_dir_url( __FILE__ ) . 'fluent_forms_functions.js', array('jquery'), 1.1, true );

	wp_localize_script( 'bcr_fluent_forms_modifiers', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'bcr_enqueue_fluent_forms_modifier_scripts' );
?>