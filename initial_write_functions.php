<?php
function summit_review_from_sub( $record, $ajax_handler ) {

    $form_name = $record->get_form_settings( 'form_name' );
    if($form_name == 'Summit Review Form'){
        $raw_fields = $record->get( 'fields' );
        $fields = [];
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = $field['value'];
        }
        global $wpdb;
        $table_name ='review_form_submissions';
        $output['success'] = $wpdb->insert($table_name, $fields);
        $ajax_handler->add_response_data( true, $output );
    }
    
}
add_action( 'elementor_pro/forms/new_record', 'summit_review_from_sub', 10, 2);
?>