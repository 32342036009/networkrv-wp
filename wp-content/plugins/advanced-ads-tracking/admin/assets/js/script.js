/**
 * check if there is a link in the content field and a tracking url given
 */
jQuery(document).ready(function(){
	jQuery('#advanced-ads-ad-parameters textarea#advads-ad-content-plain').keyup(function(){
		advads_tracking_check_link( jQuery( this ) );
	});
	advads_tracking_display_click_limit_field( '' );
});

/**
 * exchange link in ad code with the %link% placeholder.
 */
jQuery( document ).on( 'click', '#advads-tracking-link-exchange', function( ev ){
	ev.preventDefault();
	var url = jQuery( 'input[name="advanced_ad[tracking][link]"]' ).val();
	var tval = jQuery( '#advanced-ads-ad-parameters textarea#advads-ad-content-plain' ).val();
	if ( ! tval || ! url ) return;
	var $tval = jQuery( '<p />' ).html( tval );
	if ( $tval.find( 'a[href="' + url + '"]' ).length ) {
		$tval.find( 'a[href="' + url + '"]' ).attr( 'href', '%link%' );
		jQuery( '#advanced-ads-ad-parameters textarea#advads-ad-content-plain' ).val( $tval.html() );
		jQuery('#advads-tracking-link-error').hide();
	}
} );

/**
 * display click tracking limitation fields based on ad type
 * 
 * @param {string} ad_type
 */
function advads_tracking_display_click_limit_field( ad_type ){
	// get current ad type if not given
	if( ! ad_type ){
		ad_type = jQuery('#advanced-ad-type input:checked').val();
	}
	// display / hide click tracking row
	if( 0 <= advads_tracking_clickable_ad_types.indexOf( ad_type ) ){
		jQuery( '.advads-tracking-click-limit-row' ).show();
	} else {
		jQuery( '.advads-tracking-click-limit-row' ).hide();
	}
}
jQuery( document ).on('change', '#advanced-ad-type input', function () {
	var ad_type = jQuery( this ).val()
	advads_tracking_display_click_limit_field( ad_type );
});

/**
 * check if there is a link attribute in the content field that is not %link%
 * @param {obj} contentfield field selector
 * @returns {undefined}
 */
function advads_tracking_check_link( contentfield ){
    // check if url is given and not empty
    if( ! jQuery('input[name="advanced_ad[tracking][link]').length || '' === jQuery('input[name="advanced_ad[tracking][link]').val() ){
	return;
    }
    // search for href attribute
    var errormessage = jQuery('#advads-tracking-link-error');
    if( contentfield.val().search(' href=') > 0 && contentfield.val().search('%link%') < 0 ){
	    if( errormessage.is(':hidden') ){
		    errormessage.show();
		    errormessage.insertAfter('#advanced-ads-ad-parameters textarea[name="advanced_ad[content]');
	    }
    } else {
	    // hide error message
	    errormessage.hide();
    }
}
