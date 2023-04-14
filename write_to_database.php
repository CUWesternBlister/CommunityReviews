<?php

add_action( 'elementor_pro/forms/new_record', function( $record, $ajax_handler ) {
    
    $raw_fields = $record->get( 'fields' );
    $fields = [];
    foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = $field['value'];
    }
    
    global $wpdb;
    $output['success'] = $wpdb->insert('form_submissions', $fields);
    $ajax_handler->add_response_data( true, $output );
    
}, 10, 2);


function get_record_from_form_submissions($atts) {
	$atts = shortcode_atts(

		array(
			'name'=>''
		),
		$atts,
		'form_submissions'
	);
	global $wpdb;
	$name = $atts['name'];
	$nameget = $wpdb->prepare('SELECT * FROM form_submissions WHERE name = %s limit 1', $name);
	$nameresults = $wpdb->get_results($nameget);
	if ( $nameresults ) {
        $name_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->name;
            },
            $nameresults
        );
        $email_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->email;
                
            },
            $nameresults
        );
        $message_subs = array_map(
            function( $form_sub_object ) {
                return $form_sub_object->message;
            },
            $nameresults
        );
        return "Name: ".esc_html(implode( ', ', $name_subs))."<br>Email: ".esc_html(implode(', ', $email_subs))."<br>Message: ".esc_html(implode(', ', $message_subs));
    }
    return '';
}
add_shortcode( 'form_submissions', 'get_record_from_form_submissions' );

?>