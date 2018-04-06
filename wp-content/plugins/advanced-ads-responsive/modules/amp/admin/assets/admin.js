jQuery( document ).ready( function( $ ) {
	$( document ).on( 'click', '#advads-amp-add-prop', function( e ) {
		var copy = $( '.advads-amp-prop-row' ).last().clone();
		copy.find( 'input, textarea' ).val( '' ).end().insertAfter( $( '.advads-amp-prop-row' ).last() );
	});

	$( document ).on( 'click', '.advads-amp-delete-prop', function( e ) {
		if ( $( '.advads-amp-prop-row' ).length > 1 ) {
			$( this ).parents( 'tr' ).remove()
		} else {
			$( this ).parents( 'tr' ).find( 'input, textarea' ).val( '' );
		}
	});

	/**
	 * Show warning if a non-AMP compatible option is selected.
	 */
	function show_warning() {
		if ( $( '#unit-type' ).val() !== 'responsive' || jQuery( '#ad-resize-type' ).val() === 'manual' ) {
			$( '.advanced-ads-adsense-amp-warning' ).hide();
		} else {
			$( '.advanced-ads-adsense-amp-warning' ).show();
		}
	}

	$( document ).on( 'change', '#unit-type, #ad-resize-type', show_warning );
	$( '#advanced-ads-ad-parameters' ).on( 'paramloaded', show_warning );

	show_warning();
} );