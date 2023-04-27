jQuery( document ).ready( function( $ ) {
    if (! $( '.communityreviews' ).length ) {
        return;
    }
    var post_and_id = $( '#content' ).attr('class');
    const match = post_and_id.match(/\d+/);
    const post_id = match ? match[0] : null;
    console.log("post id: "+post_id);
    $.ajax( {
        url: ajax_object.ajax_url,
        method: 'POST',
        data: {
            action: 'bcr_change_height_units',
            post_id: post_id
        },
        success: function( data ) {
            /*$( '#userHeight' ).html( data );*/
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
            /*if( $( '#userWeight' ).length ) {
                $( '#userWeight' ).html( data );
            } else {
                $( '#reviewerInfoContainer div:nth-child(3)' ).html( data );
            }*/
        },
        error: function( xhr, status, error ) {
            console.error( xhr, status, error );
        },
    });
});