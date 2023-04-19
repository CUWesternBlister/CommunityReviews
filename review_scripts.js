jQuery( document ).ready( function( $ ) {
    var post_and_id = $( '.communityreviews' ).attr('id');
    var post_id = post_and_id.split('-')[1];
    $.ajax( {
        url: ajax_object.ajax_url,
        method: 'POST',
        data: {
            action: 'bcr_change_height_units',
            post_id: post_id
        },
        success: function( data ) {
            $( '#userHeight' ).html( data );
        },
        error: function( xhr, status, error ) {
            console.error( xhr, status, error );
        },
    });

    $.ajax( {
        url: ajax_object.ajax_url,
        method: 'POST',
        data: {
            action: 'bcr_change_weight_units',
            post_id: post_id
        },
        success: function( data ) {
            if( $( '#userWeight' ).length ) {
                $( '#userWeight' ).html( data );
            } else {
                $( '#reviewerInfoContainer div:nth-child(3)' ).html( data );
            }
        },
        error: function( xhr, status, error ) {
            console.error( xhr, status, error );
        },
    });
});