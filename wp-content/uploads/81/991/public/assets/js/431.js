;(function($){
	"use strict";
	var HOST = 'https://www.google-analytics.com';
	var BATCH_PATH = '/batch';
	var COLLECT_PATH = '/collect';
	var CLICK_TIMEOUT = 1000; // timeout before aborting click post request to Google
	var CLICK_TIMER = null;

	var clickReqObj = null;

	function abortAndRedirect( url ) {
		if ( null !== CLICK_TIMER ) {
			clearTimeout( CLICK_TIMER );
			CLICK_TIMER = null;
		}
		if ( null !== clickReqObj ) {
			clickReqObj.abort();
			clickReqObj == null;
		}
		window.location = url;
	}

	var advadsTracker = function( name ) {
		this.name = name;
		this.cid = false;
		this.analyticsObject = null;
		var that = this;
		this.normalTrackingDone = false;

		/**
		 * check if someone has already requested the analytics.js and created a GoogleAnalyticsObject
		 */
		this.analyticsObject = ( 'string' == typeof( GoogleAnalyticsObject ) && 'function' == typeof( window[GoogleAnalyticsObject] ) )? window[GoogleAnalyticsObject] : false;

		if ( false === this.analyticsObject ) {
			// No one has requested analytics.js at this point. Require it
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','_advads_ga');

			_advads_ga( 'create', advadsGATracking.UID, 'auto', this.name );
			_advads_ga(function(){
				var tracker = _advads_ga.getByName( that.name );
				that.readyCB( tracker );
				$( document ).on( 'advadsGADeferedTrack', function(){
					that.trackImpressions();
				} );
			});
			
		} else {
			// someone has already created a variable, use it to avoid conflicts.
			console.log( "Advanced Ads Analytics >> using other's variable named `" + GoogleAnalyticsObject + "`" );
			window[GoogleAnalyticsObject]( 'create', advadsGATracking.UID, 'auto', this.name );
			window[GoogleAnalyticsObject](function(){
				var tracker = window[GoogleAnalyticsObject].getByName( that.name );
				that.readyCB( tracker );
				$( document ).on( 'advadsGADeferedTrack', function(){
					that.trackImpressions();
				} );
			});
		}
		
		return this;
	}

	advadsTracker.prototype = {
		contructor: advadsTracker,

		hasCid: function(){
			return ( this.cid && '' !== this.cid );
		},

		readyCB: function( tracker ){
			this.cid = tracker.get('clientId');
			this.trackImpressions();
		},

		trackImpressions: function(){
			var trackedAds = [];
			if ( !this.normalTrackingDone && $.isArray( advads_tracking_ads ) ) {
				trackedAds = trackedAds.concat( advads_tracking_ads );
			}
			if ( advadsTracking.method === 'frontend' ) {
				// means parallel tracking. ads ID-s will be sent at the same time as the normal ajax tracking call
				trackedAds = [];
			}
			if ( undefined !== advadsGATracking.deferedAds && 0 < advadsGATracking.deferedAds.length ) {
				// append defered ads
				trackedAds = trackedAds.concat( advadsGATracking.deferedAds );
				// set deferedAds to an empty array
				advadsGATracking.deferedAds = [];
			}
			if ( !trackedAds.length ) {
				// no ads to track
				return;
			}
			if ( ! this.hasCid() ) {
				console.log( ' Advads Tracking >> no clientID. aborting ...' );
				return;
			}
			var trackBaseData = {
				v: 1,
				tid: advadsGATracking.UID,
				cid: this.cid,
				t: 'event',
				ni: 1,
				ec: 'Advanced Ads',
				ea: advadsGALocale.Impressions,
				dl: document.location.origin + document.location.pathname,
				dp: document.location.pathname,
			};
			var payload = "";
			for ( var i in trackedAds ) {
				if ( undefined !== advadsGATracking.ads[trackedAds[i]] ) {
					var adInfo = {
						el: '[' + trackedAds[i] + '] ' + advadsGATracking.ads[trackedAds[i]]['title'],
					};
					var adParam = $.extend( {}, trackBaseData, adInfo );
					payload += $.param( adParam ) + "\n";
				}
			}
			if ( payload.length ) {
				$.post(
					HOST + BATCH_PATH,
					payload
				);
			}
			// set the normaltrackingDone flag if not set yet
			if ( !this.normalTrackingDone ) this.normalTrackingDone = true;

		},

		trackClick: function( id, serverSide, ev, el ){
			if ( ! this.hasCid() ) {
				console.log( ' Advads Tracking >> no clientID. aborting ...' );
				return;
			}
			if ( undefined === serverSide ) serverSide = true;

				var trackData = {
					v: 1,
					tid: advadsGATracking.UID,
					cid: this.cid,
					t: 'event',
					ni: 1,
					ec: 'Advanced Ads',
					ea: advadsGALocale.Clicks,
					el: '[' + id + '] ' + advadsGATracking.ads[id]['title'],
				};
				var payload = $.param( trackData );
				var url = advadsGATracking.ads[id]['target'];
				if ( serverSide ) {
					url = $( el ).attr( 'href' );
				}
				var newTab = ( $( el ).attr( 'target' ) )? true : false;
				if ( newTab ) {
					// the url is opened in a new tab/window
					$.post( HOST + COLLECT_PATH, payload );
					if ( !serverSide ) {
						// no server side tracking, change the link to the real target before the brwoser opens a new tab
						$( el ).attr( 'href', url );
					}
				} else {
					// intercept the default click event behavior
					ev.preventDefault();
					if ( null === CLICK_TIMER && null === clickReqObj ) {
						CLICK_TIMER = setTimeout( function(){
							abortAndRedirect( url, newTab );
						}, CLICK_TIMEOUT );
						clickReqObj = $.post(
							HOST + COLLECT_PATH,
							payload,
							function(){
								clearTimeout( CLICK_TIMER );
								CLICK_TIMER = null;
								clickReqObj = null;
								abortAndRedirect( url );
							}
						);
					}

				}
		},

	}

	$(function(){
		if ( undefined !== advadsGATracking.UID && '' != advadsGATracking.UID ) {

			var tracker = new advadsTracker( 'advadsTracker' );
			$( document ).on( 'click', 'a[href^="' + advadsGATracking.linkBase + '"]', function( ev ){

				// send click event to Google
				var id = $( this ).attr( 'href' ).split( advadsGATracking.linkBase );
				id = parseInt( id[1] );
				if ( undefined !== advadsGATracking.ads[id] && advadsGATracking.ads[id]['target'] ) {
					// clicks on this ad should be tracked
					var serverSide = true;
					if ( !advadsGATracking.isParallel ) {
						// not parallel tracking, i.e. analytics only
						serverSide = false;
					}
					tracker.trackClick( id, serverSide, ev, this );

				}

			} );

		}
	});

})(jQuery);
