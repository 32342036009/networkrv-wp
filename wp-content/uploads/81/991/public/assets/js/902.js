( function( $ ) {
    /**
     * If cache-busting module is enabled.
     * With 'document.ready' this function will be called after 'document.ready'
     * from cache-busting when 'defer' attribute is added to scripts.
     * It is too late, hence do not use 'document.ready'.
     */
    if ( typeof advanced_ads_pro !== 'undefined' ) {
        advanced_ads_pro.observers.add( function( event ) {
            // waiting for the moment when all passive cache-busting ads will be inserted into html
            if ( event.event === 'inject_passive_ads' && $.isArray( event.ad_ids ) ) {
                var advads_ad_ids;
                if ( advadsTracking.method === 'frontend' ) {
                    // cache-busting: off + cache-busting: passive
                    advads_ad_ids = advads_tracking_ads.concat( event.ad_ids );
                    // clean cache-busting: off
                    advads_tracking_ads = [];
                } else {
                    // select only passive cache-busting ads
                    advads_ad_ids = event.ad_ids;
                }
				advads_track_ads( advads_ad_ids );
            }
            if ( event.event === 'inject_ajax_ads' && $.isArray( event.ad_ids ) ) {
				// ajax ads
				advads_track_ads( event.ad_ids, 'analytics' );
			}
        } );
    }
}( jQuery ) );

jQuery(document).ready(function($){
    if ( typeof advanced_ads_pro === 'undefined' && advadsTracking.method === 'frontend' ) {
        // cache-busting: off
        advads_track_ads( advads_tracking_ads );
        // clean cache-busting: off
        advads_tracking_ads = [];
    }
});

jQuery( document ).on( 'advads_track_ads', function( e, ad_ids ) {
    advads_track_ads( ad_ids );
});

/**
 * track ads
 *
 * @param {arr} advads_ad_ids
 * @param {str} server, to which server the tracking request should be sent all|local|analytics
 */
function advads_track_ads( advads_ad_ids, server ) {
    if ( ! advads_ad_ids.length ) return; // do not send empty array
	if ( 'undefined' == typeof server ) server = 'all';
    var data = {
        ads: advads_ad_ids
    };
	if ( 'undefined' !== typeof advadsGATracking ) {
		// send tracking data to Google
		if ( 'undefined' === typeof advadsGATracking.deferedAds ) {
			advadsGATracking.deferedAds = [];
		}

		if ( 'local' != server ) {
			// ads ID-s already collected and will be sent automattically once the Analytics tracker is ready
			advadsGATracking.deferedAds = advadsGATracking.deferedAds.concat( advads_ad_ids );
			jQuery( document.body ).trigger( 'advadsGADeferedTrack' );
		}
		
		if ( advadsGATracking.isParallel && 'analytics' != server ) {
			// if concurent tracking, also send data to the server
			jQuery.post( advadsTracking.ajaxurl, data, function(response) {} );
		}
	} else {
		if ( 'analytics' != server ) {
			// just send tracking data to the server
			jQuery.post( advadsTracking.ajaxurl, data, function(response) {} );
		}
	}
}