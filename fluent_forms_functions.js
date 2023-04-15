

jQuery( document ).ready( function( $ ) {
    $( 'select' ).on( 'change', function() {
        const selector_name = $( this ).attr( 'id' );
        if( !(selector_name.split('_')[2] == 'dropdown' && selector_name.split('_')[3] != 1) ) {
            return;
        }

        const current_form_id = selector_name.split('_')[1];

        const brand_selector_name = '#ff_' + current_form_id + '_dropdown';
        const product_selector_name = '#ff_' + current_form_id + '_dropdown_1';

        var brand_selected = $( brand_selector_name ).val();

        $.ajax( {
            url: ajax_object.ajax_url,
            method: 'POST',
            data: {
                action: 'bcr_filter_fluent_forms_dropdown',
                brand_selected: brand_selected,
                form_id: current_form_id
            },
            success: function( data ) {
                $( product_selector_name ).html( data );
            },
            error: function( xhr, status, error ) {
                console.error( xhr, status, error );
            },
        } );
    } );
} );