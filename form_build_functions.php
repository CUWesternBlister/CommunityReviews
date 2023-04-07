<?php
function get_answers_associated_with_question( $item, $index, $form ) {
	if ( 'my-form' === $form->get_settings_for_display( 'form_name' ) ) {
		if ( '66af2e0' === $item['_id'] ) {
			$item['field_options'] = "Red|red\nGreen|green\nBlue|blue";
			$item['field_value'] = 'colors';
		}
	}
	return $item;
}
?>