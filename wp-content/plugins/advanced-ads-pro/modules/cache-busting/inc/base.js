/**
 * global advanced_ads_pro_ajax_object
 *
 * Advanced ads loader.
 *
 * - TODO review http://www.jspatterns.com/the-ridiculous-case-of-adding-a-script-element/
 *
 * Limitations:
 * - external scripts in header must be given one-by-one
 * - no support for ads between wp_head() and </head>
 * - async flag detection for external header scripts is primitive and might report false positives in extreme cases
 * - hard coded callback URI
 */
var advanced_ads_pro, advads_pro_utils;

if (!advanced_ads_pro) {
    advanced_ads_pro = {
        ads: [],
        ajax_ads: [], // ajax ads that should be tracked
        passive_ads: [], // passive ads that should be tracked
        deferedAds: [], // ad requests that will be sent to the server using AJAX
        blockme: false,
        blockmeQueue: [],
        observers: jQuery.Callbacks(),
        postscribeObservers : jQuery.Callbacks(),
        random_placements: false,
        busy: false, // whether cache-busting is being working
        iterations: 0,

        options: {
            action: "advads_ad_select", // service action
        },

        /**
         * Prepare ajax requests.
         *
         * @param {obj} args
         */
        load: function (args) {
            // read arguments
            "use strict";
            var id, method, params, elementId, placement_id;
            id = args.hasOwnProperty("id") ? args.id : null;
            method = args.hasOwnProperty("method") ? args.method : null;
            params = args.hasOwnProperty("params") ? args.params : null;
            elementId = args.hasOwnProperty("elementid") ? args.elementid : null;

            if ( elementId && this.iterations > 1 ) {
                jQuery( '#' + elementId ).empty();
            }

            if ( params && typeof params === 'object' ) {
                // do not show `Custom Position` placement ad if selector doesn't exist
                var cp_target = ( ! params.inject_by || params.inject_by === 'pro_custom_element' ) ? 'pro_custom_element' : 'container_id';
                if ( params[ cp_target ] && ! jQuery( params[ cp_target ] ).length ) {
                    return;
                }

                // do not deliver placement, that belongs to a test, and was not randomly selected by weight
                if ( params.test_id ) {
                    if ( params.previous_method === 'placement' ) {
                        placement_id = params.previous_id;
                    } else {
                        placement_id = id;
                    }

                    if ( jQuery.inArray( placement_id, this.get_random_placements() ) < 0 ) {
                        return;
                    }
                }

                params = JSON.stringify( params );
            }

            this.deferedAds[ this.deferedAds.length ] = { ad_id: id, ad_method: method, ad_args: params, elementId: elementId };
        },

        hasAd: function (id, method, title, cb_type) {
            "use strict";
            var ad = {id: id, type: method, title: title, cb_type: cb_type};
            this.ads.push(ad);
            this.observers.fire({ event: "hasAd", ad: ad });
        },

        // inject blocked ads that should not be defered any further
        injectBlocked: function () {
            "use strict";
            var queue = this.blockmeQueue, ad, i, l = queue.length;
            this.blockmeQueue = [];
            for (i = 0; i < l; i += 1) {
                ad = queue[i];
                this.inject(ad[0], ad[1] );
            }
        },

        /**
        * Inject ad content, block if needed.
        *
        * @param {str} elementId id of the wrapper
        * @param {str} ad content
        */
        inject: function ( elementId, ad  ) {
            "use strict";
            var that = this, async, ref;

            if (this.blockme) {
                this.blockmeQueue.push( [ elementId, ad ] );
                return;
            }

            try {
                async = (ad.match(/<script[^>]+src/) && ad.indexOf(" async") === -1);

                if ( elementId === null ) {
                     ref = jQuery( 'head ');
                } else {
                    ref = jQuery( "#" + elementId );
                    if ( ! ref.length ) { return; }
                }

                if (async) {
                    this.blockme = true;
                    postscribe(ref, ad, {
                        afterAsync: function () {
                            that.blockme = false;
                            that.injectBlocked();
                        },
                        done: function() {
                            that.postscribeObservers.fire( { event: "postscribe_done", ref: ref, ad: ad } );
                        },
                        error: function( e ) {
                            console.log( e );
                        }
                    });
                } else {
                    postscribe(ref, ad, {
                        done: function() {
                            that.postscribeObservers.fire( { event: "postscribe_done", ref: ref, ad: ad } );
                        },
                        error: function( e ) {
                            console.log( e );
                        }
                    });
                }
            } catch (err) {
                console.log(err);
            }
        },

        loadAjaxAds: function() {
            "use strict";

            var j, i, that = this, data = { action: "advads_ad_select", ad_ids: this.ads, deferedAds: this.deferedAds };
            this.deferedAds = [];

            jQuery.ajax({ url: advanced_ads_pro_ajax_object.ajax_url, method: "POST", data: data, dataType: "json"})
            .done(function( msg_bunch ) {
                if ( Array.isArray( msg_bunch ) ) {
                    for ( var j =0; j < msg_bunch.length; j++ ) {
                        var msg = msg_bunch[j];
                        if ( msg.hasOwnProperty("status") && msg.status === "success" && msg.hasOwnProperty("item") && msg.item ) {
                            that.inject(msg.elementId, msg.item, true); // inject if item is not empty
                            if (msg.hasOwnProperty("ads") && Array.isArray(msg.ads)) {
                                for (var i = 0; i < msg.ads.length; i += 1) {
                                    that.hasAd( msg.ads[i].id, msg.ads[i].type, msg.ads[i].title, 'ajax' );

                                    if ( msg.ads[ i ].type === 'ad' && msg.ads[ i ].tracking_enabled ) {
	                                    advanced_ads_pro.ajax_ads.push( msg.ads[ i ].id );
                                    }
                                }
                            }
                        }
                    }
                    advanced_ads_pro.observers.fire({ event: "inject_ajax_ads", ad_ids: advanced_ads_pro.ajax_ads });
                    advanced_ads_pro.ajax_ads = [];
                    advanced_ads_pro.busy = false;
                }
            });
        },

		/**
		 * select random placements based on weight from placement tests
		 *
		 * @param {obj} placement_tests
		 * @return {array}
		 */
		get_random_placements: function( placement_tests ) {
			if ( this.random_placements !== false ) {
				return this.random_placements;
			}

			this.random_placements = [];

			advads_pro_utils.each_key( placement_tests, function( placement_id, item ) {
				if ( typeof item === 'object' ) {
					if ( random_placement = advads_pro_utils.get_random_el_by_weight( item.placements ) ) {
						this.random_placements.push( random_placement );
					}
				}
			}, this);

			return this.random_placements;
		},
        // caled after document is ready
		process_passive_cb: function() {
			this.busy = true;
			this.iterations++;

			advads_pro_utils.each( advads_has_ads, function( query ) {
				advanced_ads_pro.hasAd.apply( advanced_ads_pro, query );
			});

			this.get_random_placements( advads_placement_tests );

			// inject all passive ads
			advads_pro_utils.each_key( advads_passive_ads, function( key, item ) {

				advads_pro_utils.each( item.elementid, function( element_id ) {
					if ( advanced_ads_pro.iterations > 1 ) jQuery( '#' + element_id ).empty();
					var ad = new Advads_passive_cb_Ad( item.ads[ key ], element_id ); // only one ad, pass it as argument

					if ( ad.can_display() ) {
						ad.output( { track: true, inject: true } );
					}
				});
			});

			advads_pro_utils.each_key( advads_passive_groups, function( key, item ) {
				advads_pro_utils.each( item.elementid, function( element_id ) {
					if ( advanced_ads_pro.iterations > 1 ) jQuery( '#' + element_id ).empty();
					var group = new Advads_passive_cb_Group( item, element_id );
					group.output();
				});
			});

			advads_pro_utils.each_key( advads_passive_placements, function( key, item ) {

				advads_pro_utils.each( item.elementid, function( element_id ) {
					if ( advanced_ads_pro.iterations > 1 ) jQuery( '#' + element_id ).empty();
					var placement = new Advads_passive_cb_Placement( item, element_id );
					placement.output();
				});


			});

			this.observers.fire({ event: "inject_passive_ads", ad_ids: this.passive_ads });
			this.passive_ads = [];


			// then, load and inject all ajax ads with a single request
			this.process_ajax_ads( advads_ajax_queries );
		},

		/**
		 * Process ajax ads.
		 *
		 * @param {array} ajax_queries
		 */
		process_ajax_ads: function( ajax_queries ) {
			if ( jQuery.isArray( ajax_queries ) ) {
				advads_pro_utils.each( ajax_queries, function( query ) {
					advanced_ads_pro.load( query );
				});
			}

				if ( this.deferedAds.length ) {
					this.loadAjaxAds();
				} else {
					advanced_ads_pro.observers.fire({ event: "inject_ajax_ads", ad_ids: [] });
					advanced_ads_pro.busy = false;
				}
		}
	};

	// Reload ads when screen resizes.
	jQuery( document ).on( 'advanced-ads-resize-window', function( e ) {
		if ( advanced_ads_pro.busy ) {
			return;
		}

		// Remove ajax and passive ads.
		var cb_count = advanced_ads_pro.ads.length;
		while ( cb_count-- ) {
			if ( 'off' !== advanced_ads_pro.ads.cb_method ) {
				advanced_ads_pro.ads.splice( cb_count, 1 );
			}
		}

		advanced_ads_pro.process_passive_cb();
	});


var Advads_passive_cb_Conditions = {
	// Note: hard-coded in JS
	REFERRER_COOKIE_NAME: 'advanced_ads_pro_visitor_referrer',
	// page impression counter
	PAGE_IMPRESSIONS_COOKIE_NAME: 'advanced_ads_page_impressions',
	// ad impression cookie name basis
	AD_IMPRESSIONS_COOKIE_NAME: 'advanced_ads_ad_impressions',

	conditions : {
		// Advanced Ads plugin
		'mobile': 'check_mobile',
		// Advanced Ads Pro plugin
		'referrer_url': 'check_referrer_url',
		'user_agent': 'check_user_agent',
		'request_uri': 'check_request_uri',
		'browser_lang': 'check_browser_lang',
		'cookie': 'check_cookie',
		'page_impressions': 'check_page_impressions',
		'ad_impressions': 'check_ad_impressions',
		'new_visitor': 'check_new_visitor',
		// Responsive Ads plugin
		'device_width': 'check_browser_width',
		'tablet': 'check_tablet',
	},

	/**
	 * controls frontend checks for conditions
	 *
	 * @param {arr} _condition options of the condition
	 * @param {obj} ad Advads_passive_cb_Ad
	 * @return {bool} false, if ad can’t be delivered
	 */
	frontend_check: function( _condition, ad ) {
		var check_function = this.conditions[_condition.type];

		if ( check_function ) {
			if ( this[check_function]( _condition, ad ) ) {
				advads_pro_utils.log( check_function + ' true' );
				return true;
			} else {
				advads_pro_utils.log( check_function + ' false' );
				return false;
			}
		}
		return true;
	},

	/**
	 * check mobile visitor condition in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_mobile: function( options ) {
		// https://github.com/kaimallea/isMobile
		!function(a){var b=/iPhone/i,c=/iPod/i,d=/iPad/i,e=/(?=.*\bAndroid\b)(?=.*\bMobile\b)/i,f=/Android/i,g=/(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)/i,h=/(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)/i,i=/IEMobile/i,j=/(?=.*\bWindows\b)(?=.*\bARM\b)/i,k=/BlackBerry/i,l=/BB10/i,m=/Opera Mini/i,n=/(CriOS|Chrome)(?=.*\bMobile\b)/i,o=/(?=.*\bFirefox\b)(?=.*\bMobile\b)/i,p=new RegExp("(?:Nexus 7|BNTV250|Kindle Fire|Silk|GT-P1000)","i"),q=function(a,b){return a.test(b)},r=function(a){var r=a||navigator.userAgent,s=r.split("[FBAN");return"undefined"!=typeof s[1]&&(r=s[0]),this.apple={phone:q(b,r),ipod:q(c,r),tablet:!q(b,r)&&q(d,r),device:q(b,r)||q(c,r)||q(d,r)},this.amazon={phone:q(g,r),tablet:!q(g,r)&&q(h,r),device:q(g,r)||q(h,r)},this.android={phone:q(g,r)||q(e,r),tablet:!q(g,r)&&!q(e,r)&&(q(h,r)||q(f,r)),device:q(g,r)||q(h,r)||q(e,r)||q(f,r)},this.windows={phone:q(i,r),tablet:q(j,r),device:q(i,r)||q(j,r)},this.other={blackberry:q(k,r),blackberry10:q(l,r),opera:q(m,r),firefox:q(o,r),chrome:q(n,r),device:q(k,r)||q(l,r)||q(m,r)||q(o,r)||q(n,r)},this.seven_inch=q(p,r),this.any=this.apple.device||this.android.device||this.windows.device||this.other.device||this.seven_inch,this.phone=this.apple.phone||this.android.phone||this.windows.phone,this.tablet=this.apple.tablet||this.android.tablet||this.windows.tablet,"undefined"==typeof window?this:void 0},s=function(){var a=new r;return a.Class=r,a};"undefined"!=typeof module&&module.exports&&"undefined"==typeof window?module.exports=r:"undefined"!=typeof module&&module.exports&&"undefined"!=typeof window?module.exports=s():"function"==typeof define&&define.amd?define("isMobile",[],a.isMobile=s()):a.isMobile=s()}(this);

		if ( ! advads_pro_utils.isset( options.operator ) ) {
			return true;
		}

		switch ( options.operator ) {
			case 'is':
				return this.isMobile.any;
				break;
			case 'is_not':
				return ! this.isMobile.any
				break;
		}

		return true;
	},

	/**
	 * check referrer url in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_referrer_url: function( options ) {
		var referer = advads.get_cookie( this.REFERRER_COOKIE_NAME ) || '';

		return this.helper_check_string( referer, options );
	},

	/**
	 * check user agent in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_user_agent: function( options ) {
		var user_agent = typeof navigator === 'object' ? navigator.userAgent : '';  

		return this.helper_check_string( user_agent, options );
	},

	/**
	 * check browser language
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_browser_lang: function( options ) {
		var lang = options.value;
		if ( ! lang ) {
			return true;
		}

		var browser_lang = typeof navigator === 'object' ? ( navigator.languages ? navigator.languages.join(',') : ( navigator.language || navigator.userLanguage ) ) : '';  
		if ( ! browser_lang ) {
			return true;
		}

		try {
			var regexp = new RegExp( '\\b' + lang + '\\b', 'i' );
			var result = browser_lang.search( regexp ) !== -1;
		} catch ( e ) {
			return true;
		}

		if ( options.operator === 'is_not' ) {
			return ! result;
		} else {
			return result;
		}
	},

	/**
	 * check request_uri in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_request_uri: function( options ) {
		var uri_string = typeof location === 'object' ? location.href : '';  

		return this.helper_check_string( uri_string, options );
	},

	/**
	 * check cookie value in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_cookie: function( options ) {
		var must_be_set = ! advads_pro_utils.isset( options.operator ) || 'hide' !== options.operator;

		if ( ! advads_pro_utils.isset( options.cookie ) || '' === options.cookie ) {
			return must_be_set;
		}

		if ( ! advads_pro_utils.isset( advads.get_cookie( options.cookie ) ) ) {
			return ! must_be_set;
		}

		// return true if value is empty or equals the value
		if ( ! advads_pro_utils.isset( options.value ) || '' === options.value ||
			options.value === advads.get_cookie( options.cookie ) ) {
			return must_be_set;
		}

		return ! must_be_set;
	},

	/**
	 * check page_impressions in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_page_impressions: function( options ) {
		if ( ! advads_pro_utils.isset( options.operator ) || ! advads_pro_utils.isset( options.value ) ) {
			return true
		}

		var impressions = 0;
		if ( advads_pro_utils.isset( advads.get_cookie( this.PAGE_IMPRESSIONS_COOKIE_NAME ) ) ) {
			impressions = parseInt( advads.get_cookie( this.PAGE_IMPRESSIONS_COOKIE_NAME ) ) || 0;
		} else {
			return true;
		}

		var value = parseInt( options.value ) || 0;

		switch ( options.operator ) {
			case 'is_equal':
				if ( value !== impressions ) { return false; }
				break;
			case 'is_higher':
				if ( value > impressions ) { return false; }
				break;
			case 'is_lower':
				if ( value < impressions ) { return false; }
				break;
		}

		return true;
	},

	/**
	 * check ad impressions limit for the ad in frontend
	 *
	 * @param {arr} options options of the condition
	 * @param {obj} ad
	 * @return {bool} true if can be displayed
	 */
	check_ad_impressions: function( options, ad ) {
		if ( ! advads_pro_utils.isset( options.value ) || ! advads_pro_utils.isset( options.timeout ) || ! advads_pro_utils.isset( ad.id ) ) {
			return true
		}

		var value = parseInt( options.value ) || 0,
			impressions = 0,
			cookie_name = this.AD_IMPRESSIONS_COOKIE_NAME + '_' + ad.id,
			cookie_timeout_name = cookie_name + '_timeout';

		if ( advads_pro_utils.isset( advads.get_cookie( cookie_name ) ) && advads_pro_utils.isset( advads.get_cookie( cookie_timeout_name ) ) ) {

			impressions = parseInt( advads.get_cookie( cookie_name ) ) || 0;
			if ( value <= impressions ) {
				return false;
			}
		}

		return true;
	},

	/**
	 * check new_visitor in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_new_visitor: function( options ) {
		if ( ! advads_pro_utils.isset( options.operator ) ) {
			return true
		}

		var impressions = 0;
		if ( advads_pro_utils.isset( advads.get_cookie( this.PAGE_IMPRESSIONS_COOKIE_NAME ) ) ) {
			impressions = parseInt( advads.get_cookie( this.PAGE_IMPRESSIONS_COOKIE_NAME ) ) || 0;
		}

		switch ( options.operator ) {
			case 'is':
				return 1 === impressions;
				break;
			case 'is_not':
				return 1 < impressions;
				break;
		}

		return true;
	},

	/**
	 * check browser width in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_browser_width: function( options ) {
		if ( ! advads_pro_utils.isset( options.operator ) || ! advads_pro_utils.isset( options.value ) ) {
			return true
		}
		var browser_width = jQuery( window ).width(),
			value = parseInt( options.value ) || 0;

		switch ( options.operator ) {
			case 'is_equal':
				if ( value !== browser_width ) { return false; }
				break;
			case 'is_higher':
				if ( value > browser_width ) { return false; }
				break;
			case 'is_lower':
				if ( value < browser_width ) { return false; }
				break;
		}

		return true;
	},
	
	/**
	 * check tablet visitor condition in frontend
	 *
	 * @param {arr} options options of the condition
	 * @return {bool} true if can be displayed
	 */
	check_tablet: function( options ) {
	    
		// derived from https://github.com/serbanghita/Mobile-Detect and https://github.com/hgoebl/mobile-detect.js
		if ( ! advads_pro_utils.isset( options.operator ) ) {
			return true;
		}
		
		rules = {iPad:"iPad|iPad.*Mobile",NexusTablet:"Android.*Nexus[\\s]+(7|9|10)",SamsungTablet:"SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|GT-P1000|GT-P1003|GT-P1010|GT-P3105|GT-P6210|GT-P6800|GT-P6810|GT-P7100|GT-P7300|GT-P7310|GT-P7500|GT-P7510|SCH-I800|SCH-I815|SCH-I905|SGH-I957|SGH-I987|SGH-T849|SGH-T859|SGH-T869|SPH-P100|GT-P3100|GT-P3108|GT-P3110|GT-P5100|GT-P5110|GT-P6200|GT-P7320|GT-P7511|GT-N8000|GT-P8510|SGH-I497|SPH-P500|SGH-T779|SCH-I705|SCH-I915|GT-N8013|GT-P3113|GT-P5113|GT-P8110|GT-N8010|GT-N8005|GT-N8020|GT-P1013|GT-P6201|GT-P7501|GT-N5100|GT-N5105|GT-N5110|SHV-E140K|SHV-E140L|SHV-E140S|SHV-E150S|SHV-E230K|SHV-E230L|SHV-E230S|SHW-M180K|SHW-M180L|SHW-M180S|SHW-M180W|SHW-M300W|SHW-M305W|SHW-M380K|SHW-M380S|SHW-M380W|SHW-M430W|SHW-M480K|SHW-M480S|SHW-M480W|SHW-M485W|SHW-M486W|SHW-M500W|GT-I9228|SCH-P739|SCH-I925|GT-I9200|GT-P5200|GT-P5210|GT-P5210X|SM-T311|SM-T310|SM-T310X|SM-T210|SM-T210R|SM-T211|SM-P600|SM-P601|SM-P605|SM-P900|SM-P901|SM-T217|SM-T217A|SM-T217S|SM-P6000|SM-T3100|SGH-I467|XE500|SM-T110|GT-P5220|GT-I9200X|GT-N5110X|GT-N5120|SM-P905|SM-T111|SM-T2105|SM-T315|SM-T320|SM-T320X|SM-T321|SM-T520|SM-T525|SM-T530NU|SM-T230NU|SM-T330NU|SM-T900|XE500T1C|SM-P605V|SM-P905V|SM-T337V|SM-T537V|SM-T707V|SM-T807V|SM-P600X|SM-P900X|SM-T210X|SM-T230|SM-T230X|SM-T325|GT-P7503|SM-T531|SM-T330|SM-T530|SM-T705|SM-T705C|SM-T535|SM-T331|SM-T800|SM-T700|SM-T537|SM-T807|SM-P907A|SM-T337A|SM-T537A|SM-T707A|SM-T807A|SM-T237|SM-T807P|SM-P607T|SM-T217T|SM-T337T|SM-T807T|SM-T116NQ|SM-P550|SM-T350|SM-T550|SM-T9000|SM-P9000|SM-T705Y|SM-T805|GT-P3113|SM-T710|SM-T810|SM-T815|SM-T360|SM-T533|SM-T113|SM-T335|SM-T715|SM-T560|SM-T670|SM-T677|SM-T377|SM-T567|SM-T357T|SM-T555|SM-T561",Kindle:"Kindle|Silk.*Accelerated|Android.*\\b(KFOT|KFTT|KFJWI|KFJWA|KFOTE|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|WFJWAE|KFSAWA|KFSAWI|KFASWI|KFARWI)\\b",SurfaceTablet:"Windows NT [0-9.]+; ARM;.*(Tablet|ARMBJS)",HPTablet:"HP Slate (7|8|10)|HP ElitePad 900|hp-tablet|EliteBook.*Touch|HP 8|Slate 21|HP SlateBook 10",AsusTablet:"^.*PadFone((?!Mobile).)*$|Transformer|TF101|TF101G|TF300T|TF300TG|TF300TL|TF700T|TF700KL|TF701T|TF810C|ME171|ME301T|ME302C|ME371MG|ME370T|ME372MG|ME172V|ME173X|ME400C|Slider SL101|\\bK00F\\b|\\bK00C\\b|\\bK00E\\b|\\bK00L\\b|TX201LA|ME176C|ME102A|\\bM80TA\\b|ME372CL|ME560CG|ME372CG|ME302KL| K010 | K017 |ME572C|ME103K|ME170C|ME171C|\\bME70C\\b|ME581C|ME581CL|ME8510C|ME181C|P01Y|PO1MA",BlackBerryTablet:"PlayBook|RIM Tablet",HTCtablet:"HTC_Flyer_P512|HTC Flyer|HTC Jetstream|HTC-P715a|HTC EVO View 4G|PG41200|PG09410",MotorolaTablet:"xoom|sholest|MZ615|MZ605|MZ505|MZ601|MZ602|MZ603|MZ604|MZ606|MZ607|MZ608|MZ609|MZ615|MZ616|MZ617",NookTablet:"Android.*Nook|NookColor|nook browser|BNRV200|BNRV200A|BNTV250|BNTV250A|BNTV400|BNTV600|LogicPD Zoom2",AcerTablet:"Android.*; \\b(A100|A101|A110|A200|A210|A211|A500|A501|A510|A511|A700|A701|W500|W500P|W501|W501P|W510|W511|W700|G100|G100W|B1-A71|B1-710|B1-711|A1-810|A1-811|A1-830)\\b|W3-810|\\bA3-A10\\b|\\bA3-A11\\b|\\bA3-A20",ToshibaTablet:"Android.*(AT100|AT105|AT200|AT205|AT270|AT275|AT300|AT305|AT1S5|AT500|AT570|AT700|AT830)|TOSHIBA.*FOLIO",LGTablet:"\\bL-06C|LG-V909|LG-V900|LG-V700|LG-V510|LG-V500|LG-V410|LG-V400|LG-VK810\\b",FujitsuTablet:"Android.*\\b(F-01D|F-02F|F-05E|F-10D|M532|Q572)\\b",PrestigioTablet:"PMP3170B|PMP3270B|PMP3470B|PMP7170B|PMP3370B|PMP3570C|PMP5870C|PMP3670B|PMP5570C|PMP5770D|PMP3970B|PMP3870C|PMP5580C|PMP5880D|PMP5780D|PMP5588C|PMP7280C|PMP7280C3G|PMP7280|PMP7880D|PMP5597D|PMP5597|PMP7100D|PER3464|PER3274|PER3574|PER3884|PER5274|PER5474|PMP5097CPRO|PMP5097|PMP7380D|PMP5297C|PMP5297C_QUAD|PMP812E|PMP812E3G|PMP812F|PMP810E|PMP880TD|PMT3017|PMT3037|PMT3047|PMT3057|PMT7008|PMT5887|PMT5001|PMT5002",LenovoTablet:"Lenovo TAB|Idea(Tab|Pad)( A1|A10| K1|)|ThinkPad([ ]+)?Tablet|YT3-X90L|YT3-X90F|YT3-X90X|Lenovo.*(S2109|S2110|S5000|S6000|K3011|A3000|A3500|A1000|A2107|A2109|A1107|A5500|A7600|B6000|B8000|B8080)(-|)(FL|F|HV|H|)",DellTablet:"Venue 11|Venue 8|Venue 7|Dell Streak 10|Dell Streak 7",YarvikTablet:"Android.*\\b(TAB210|TAB211|TAB224|TAB250|TAB260|TAB264|TAB310|TAB360|TAB364|TAB410|TAB411|TAB420|TAB424|TAB450|TAB460|TAB461|TAB464|TAB465|TAB467|TAB468|TAB07-100|TAB07-101|TAB07-150|TAB07-151|TAB07-152|TAB07-200|TAB07-201-3G|TAB07-210|TAB07-211|TAB07-212|TAB07-214|TAB07-220|TAB07-400|TAB07-485|TAB08-150|TAB08-200|TAB08-201-3G|TAB08-201-30|TAB09-100|TAB09-211|TAB09-410|TAB10-150|TAB10-201|TAB10-211|TAB10-400|TAB10-410|TAB13-201|TAB274EUK|TAB275EUK|TAB374EUK|TAB462EUK|TAB474EUK|TAB9-200)\\b",MedionTablet:"Android.*\\bOYO\\b|LIFE.*(P9212|P9514|P9516|S9512)|LIFETAB",ArnovaTablet:"AN10G2|AN7bG3|AN7fG3|AN8G3|AN8cG3|AN7G3|AN9G3|AN7dG3|AN7dG3ST|AN7dG3ChildPad|AN10bG3|AN10bG3DT|AN9G2",IntensoTablet:"INM8002KP|INM1010FP|INM805ND|Intenso Tab|TAB1004",IRUTablet:"M702pro",MegafonTablet:"MegaFon V9|\\bZTE V9\\b|Android.*\\bMT7A\\b",EbodaTablet:"E-Boda (Supreme|Impresspeed|Izzycomm|Essential)",AllViewTablet:"Allview.*(Viva|Alldro|City|Speed|All TV|Frenzy|Quasar|Shine|TX1|AX1|AX2)",ArchosTablet:"\\b(101G9|80G9|A101IT)\\b|Qilive 97R|Archos5|\\bARCHOS (70|79|80|90|97|101|FAMILYPAD|)(b|)(G10| Cobalt| TITANIUM(HD|)| Xenon| Neon|XSK| 2| XS 2| PLATINUM| CARBON|GAMEPAD)\\b",AinolTablet:"NOVO7|NOVO8|NOVO10|Novo7Aurora|Novo7Basic|NOVO7PALADIN|novo9-Spark",NokiaLumiaTablet:"Lumia 2520",SonyTablet:"Sony.*Tablet|Xperia Tablet|Sony Tablet S|SO-03E|SGPT12|SGPT13|SGPT114|SGPT121|SGPT122|SGPT123|SGPT111|SGPT112|SGPT113|SGPT131|SGPT132|SGPT133|SGPT211|SGPT212|SGPT213|SGP311|SGP312|SGP321|EBRD1101|EBRD1102|EBRD1201|SGP351|SGP341|SGP511|SGP512|SGP521|SGP541|SGP551|SGP621|SGP612|SOT31",PhilipsTablet:"\\b(PI2010|PI3000|PI3100|PI3105|PI3110|PI3205|PI3210|PI3900|PI4010|PI7000|PI7100)\\b",CubeTablet:"Android.*(K8GT|U9GT|U10GT|U16GT|U17GT|U18GT|U19GT|U20GT|U23GT|U30GT)|CUBE U8GT",CobyTablet:"MID1042|MID1045|MID1125|MID1126|MID7012|MID7014|MID7015|MID7034|MID7035|MID7036|MID7042|MID7048|MID7127|MID8042|MID8048|MID8127|MID9042|MID9740|MID9742|MID7022|MID7010",MIDTablet:"M9701|M9000|M9100|M806|M1052|M806|T703|MID701|MID713|MID710|MID727|MID760|MID830|MID728|MID933|MID125|MID810|MID732|MID120|MID930|MID800|MID731|MID900|MID100|MID820|MID735|MID980|MID130|MID833|MID737|MID960|MID135|MID860|MID736|MID140|MID930|MID835|MID733|MID4X10",MSITablet:"MSI \\b(Primo 73K|Primo 73L|Primo 81L|Primo 77|Primo 93|Primo 75|Primo 76|Primo 73|Primo 81|Primo 91|Primo 90|Enjoy 71|Enjoy 7|Enjoy 10)\\b",SMiTTablet:"Android.*(\\bMID\\b|MID-560|MTV-T1200|MTV-PND531|MTV-P1101|MTV-PND530)",RockChipTablet:"Android.*(RK2818|RK2808A|RK2918|RK3066)|RK2738|RK2808A",FlyTablet:"IQ310|Fly Vision",bqTablet:"Android.*(bq)?.*(Elcano|Curie|Edison|Maxwell|Kepler|Pascal|Tesla|Hypatia|Platon|Newton|Livingstone|Cervantes|Avant|Aquaris E10)|Maxwell.*Lite|Maxwell.*Plus",HuaweiTablet:"MediaPad|MediaPad 7 Youth|IDEOS S7|S7-201c|S7-202u|S7-101|S7-103|S7-104|S7-105|S7-106|S7-201|S7-Slim",NecTablet:"\\bN-06D|\\bN-08D",PantechTablet:"Pantech.*P4100",BronchoTablet:"Broncho.*(N701|N708|N802|a710)",VersusTablet:"TOUCHPAD.*[78910]|\\bTOUCHTAB\\b",ZyncTablet:"z1000|Z99 2G|z99|z930|z999|z990|z909|Z919|z900",PositivoTablet:"TB07STA|TB10STA|TB07FTA|TB10FTA",NabiTablet:"Android.*\\bNabi",KoboTablet:"Kobo Touch|\\bK080\\b|\\bVox\\b Build|\\bArc\\b Build",DanewTablet:"DSlide.*\\b(700|701R|702|703R|704|802|970|971|972|973|974|1010|1012)\\b",TexetTablet:"NaviPad|TB-772A|TM-7045|TM-7055|TM-9750|TM-7016|TM-7024|TM-7026|TM-7041|TM-7043|TM-7047|TM-8041|TM-9741|TM-9747|TM-9748|TM-9751|TM-7022|TM-7021|TM-7020|TM-7011|TM-7010|TM-7023|TM-7025|TM-7037W|TM-7038W|TM-7027W|TM-9720|TM-9725|TM-9737W|TM-1020|TM-9738W|TM-9740|TM-9743W|TB-807A|TB-771A|TB-727A|TB-725A|TB-719A|TB-823A|TB-805A|TB-723A|TB-715A|TB-707A|TB-705A|TB-709A|TB-711A|TB-890HD|TB-880HD|TB-790HD|TB-780HD|TB-770HD|TB-721HD|TB-710HD|TB-434HD|TB-860HD|TB-840HD|TB-760HD|TB-750HD|TB-740HD|TB-730HD|TB-722HD|TB-720HD|TB-700HD|TB-500HD|TB-470HD|TB-431HD|TB-430HD|TB-506|TB-504|TB-446|TB-436|TB-416|TB-146SE|TB-126SE",PlaystationTablet:"Playstation.*(Portable|Vita)",TrekstorTablet:"ST10416-1|VT10416-1|ST70408-1|ST702xx-1|ST702xx-2|ST80208|ST97216|ST70104-2|VT10416-2|ST10216-2A|SurfTab",PyleAudioTablet:"\\b(PTBL10CEU|PTBL10C|PTBL72BC|PTBL72BCEU|PTBL7CEU|PTBL7C|PTBL92BC|PTBL92BCEU|PTBL9CEU|PTBL9CUK|PTBL9C)\\b",AdvanTablet:"Android.* \\b(E3A|T3X|T5C|T5B|T3E|T3C|T3B|T1J|T1F|T2A|T1H|T1i|E1C|T1-E|T5-A|T4|E1-B|T2Ci|T1-B|T1-D|O1-A|E1-A|T1-A|T3A|T4i)\\b ",DanyTechTablet:"Genius Tab G3|Genius Tab S2|Genius Tab Q3|Genius Tab G4|Genius Tab Q4|Genius Tab G-II|Genius TAB GII|Genius TAB GIII|Genius Tab S1",GalapadTablet:"Android.*\\bG1\\b",MicromaxTablet:"Funbook|Micromax.*\\b(P250|P560|P360|P362|P600|P300|P350|P500|P275)\\b",KarbonnTablet:"Android.*\\b(A39|A37|A34|ST8|ST10|ST7|Smart Tab3|Smart Tab2)\\b",AllFineTablet:"Fine7 Genius|Fine7 Shine|Fine7 Air|Fine8 Style|Fine9 More|Fine10 Joy|Fine11 Wide",PROSCANTablet:"\\b(PEM63|PLT1023G|PLT1041|PLT1044|PLT1044G|PLT1091|PLT4311|PLT4311PL|PLT4315|PLT7030|PLT7033|PLT7033D|PLT7035|PLT7035D|PLT7044K|PLT7045K|PLT7045KB|PLT7071KG|PLT7072|PLT7223G|PLT7225G|PLT7777G|PLT7810K|PLT7849G|PLT7851G|PLT7852G|PLT8015|PLT8031|PLT8034|PLT8036|PLT8080K|PLT8082|PLT8088|PLT8223G|PLT8234G|PLT8235G|PLT8816K|PLT9011|PLT9045K|PLT9233G|PLT9735|PLT9760G|PLT9770G)\\b",YONESTablet:"BQ1078|BC1003|BC1077|RK9702|BC9730|BC9001|IT9001|BC7008|BC7010|BC708|BC728|BC7012|BC7030|BC7027|BC7026",ChangJiaTablet:"TPC7102|TPC7103|TPC7105|TPC7106|TPC7107|TPC7201|TPC7203|TPC7205|TPC7210|TPC7708|TPC7709|TPC7712|TPC7110|TPC8101|TPC8103|TPC8105|TPC8106|TPC8203|TPC8205|TPC8503|TPC9106|TPC9701|TPC97101|TPC97103|TPC97105|TPC97106|TPC97111|TPC97113|TPC97203|TPC97603|TPC97809|TPC97205|TPC10101|TPC10103|TPC10106|TPC10111|TPC10203|TPC10205|TPC10503",GUTablet:"TX-A1301|TX-M9002|Q702|kf026",PointOfViewTablet:"TAB-P506|TAB-navi-7-3G-M|TAB-P517|TAB-P-527|TAB-P701|TAB-P703|TAB-P721|TAB-P731N|TAB-P741|TAB-P825|TAB-P905|TAB-P925|TAB-PR945|TAB-PL1015|TAB-P1025|TAB-PI1045|TAB-P1325|TAB-PROTAB[0-9]+|TAB-PROTAB25|TAB-PROTAB26|TAB-PROTAB27|TAB-PROTAB26XL|TAB-PROTAB2-IPS9|TAB-PROTAB30-IPS9|TAB-PROTAB25XXL|TAB-PROTAB26-IPS10|TAB-PROTAB30-IPS10",OvermaxTablet:"OV-(SteelCore|NewBase|Basecore|Baseone|Exellen|Quattor|EduTab|Solution|ACTION|BasicTab|TeddyTab|MagicTab|Stream|TB-08|TB-09)",HCLTablet:"HCL.*Tablet|Connect-3G-2.0|Connect-2G-2.0|ME Tablet U1|ME Tablet U2|ME Tablet G1|ME Tablet X1|ME Tablet Y2|ME Tablet Sync",DPSTablet:"DPS Dream 9|DPS Dual 7",VistureTablet:"V97 HD|i75 3G|Visture V4( HD)?|Visture V5( HD)?|Visture V10",CrestaTablet:"CTP(-)?810|CTP(-)?818|CTP(-)?828|CTP(-)?838|CTP(-)?888|CTP(-)?978|CTP(-)?980|CTP(-)?987|CTP(-)?988|CTP(-)?989",MediatekTablet:"\\bMT8125|MT8389|MT8135|MT8377\\b",ConcordeTablet:"Concorde([ ]+)?Tab|ConCorde ReadMan",GoCleverTablet:"GOCLEVER TAB|A7GOCLEVER|M1042|M7841|M742|R1042BK|R1041|TAB A975|TAB A7842|TAB A741|TAB A741L|TAB M723G|TAB M721|TAB A1021|TAB I921|TAB R721|TAB I720|TAB T76|TAB R70|TAB R76.2|TAB R106|TAB R83.2|TAB M813G|TAB I721|GCTA722|TAB I70|TAB I71|TAB S73|TAB R73|TAB R74|TAB R93|TAB R75|TAB R76.1|TAB A73|TAB A93|TAB A93.2|TAB T72|TAB R83|TAB R974|TAB R973|TAB A101|TAB A103|TAB A104|TAB A104.2|R105BK|M713G|A972BK|TAB A971|TAB R974.2|TAB R104|TAB R83.3|TAB A1042",ModecomTablet:"FreeTAB 9000|FreeTAB 7.4|FreeTAB 7004|FreeTAB 7800|FreeTAB 2096|FreeTAB 7.5|FreeTAB 1014|FreeTAB 1001 |FreeTAB 8001|FreeTAB 9706|FreeTAB 9702|FreeTAB 7003|FreeTAB 7002|FreeTAB 1002|FreeTAB 7801|FreeTAB 1331|FreeTAB 1004|FreeTAB 8002|FreeTAB 8014|FreeTAB 9704|FreeTAB 1003",VoninoTablet:"\\b(Argus[ _]?S|Diamond[ _]?79HD|Emerald[ _]?78E|Luna[ _]?70C|Onyx[ _]?S|Onyx[ _]?Z|Orin[ _]?HD|Orin[ _]?S|Otis[ _]?S|SpeedStar[ _]?S|Magnet[ _]?M9|Primus[ _]?94[ _]?3G|Primus[ _]?94HD|Primus[ _]?QS|Android.*\\bQ8\\b|Sirius[ _]?EVO[ _]?QS|Sirius[ _]?QS|Spirit[ _]?S)\\b",ECSTablet:"V07OT2|TM105A|S10OT1|TR10CS1",StorexTablet:"eZee[_']?(Tab|Go)[0-9]+|TabLC7|Looney Tunes Tab",VodafoneTablet:"SmartTab([ ]+)?[0-9]+|SmartTabII10|SmartTabII7",EssentielBTablet:"Smart[ ']?TAB[ ]+?[0-9]+|Family[ ']?TAB2",RossMoorTablet:"RM-790|RM-997|RMD-878G|RMD-974R|RMT-705A|RMT-701|RME-601|RMT-501|RMT-711",iMobileTablet:"i-mobile i-note",TolinoTablet:"tolino tab [0-9.]+|tolino shine",AudioSonicTablet:"\\bC-22Q|T7-QC|T-17B|T-17P\\b",AMPETablet:"Android.* A78 ",SkkTablet:"Android.* (SKYPAD|PHOENIX|CYCLOPS)",TecnoTablet:"TECNO P9",JXDTablet:"Android.* \\b(F3000|A3300|JXD5000|JXD3000|JXD2000|JXD300B|JXD300|S5800|S7800|S602b|S5110b|S7300|S5300|S602|S603|S5100|S5110|S601|S7100a|P3000F|P3000s|P101|P200s|P1000m|P200m|P9100|P1000s|S6600b|S908|P1000|P300|S18|S6600|S9100)\\b",iJoyTablet:"Tablet (Spirit 7|Essentia|Galatea|Fusion|Onix 7|Landa|Titan|Scooby|Deox|Stella|Themis|Argon|Unique 7|Sygnus|Hexen|Finity 7|Cream|Cream X2|Jade|Neon 7|Neron 7|Kandy|Scape|Saphyr 7|Rebel|Biox|Rebel|Rebel 8GB|Myst|Draco 7|Myst|Tab7-004|Myst|Tadeo Jones|Tablet Boing|Arrow|Draco Dual Cam|Aurix|Mint|Amity|Revolution|Finity 9|Neon 9|T9w|Amity 4GB Dual Cam|Stone 4GB|Stone 8GB|Andromeda|Silken|X2|Andromeda II|Halley|Flame|Saphyr 9,7|Touch 8|Planet|Triton|Unique 10|Hexen 10|Memphis 4GB|Memphis 8GB|Onix 10)",FX2Tablet:"FX2 PAD7|FX2 PAD10",XoroTablet:"KidsPAD 701|PAD[ ]?712|PAD[ ]?714|PAD[ ]?716|PAD[ ]?717|PAD[ ]?718|PAD[ ]?720|PAD[ ]?721|PAD[ ]?722|PAD[ ]?790|PAD[ ]?792|PAD[ ]?900|PAD[ ]?9715D|PAD[ ]?9716DR|PAD[ ]?9718DR|PAD[ ]?9719QR|PAD[ ]?9720QR|TelePAD1030|Telepad1032|TelePAD730|TelePAD731|TelePAD732|TelePAD735Q|TelePAD830|TelePAD9730|TelePAD795|MegaPAD 1331|MegaPAD 1851|MegaPAD 2151",ViewsonicTablet:"ViewPad 10pi|ViewPad 10e|ViewPad 10s|ViewPad E72|ViewPad7|ViewPad E100|ViewPad 7e|ViewSonic VB733|VB100a",OdysTablet:"LOOX|XENO10|ODYS[ -](Space|EVO|Xpress|NOON)|\\bXELIO\\b|Xelio10Pro|XELIO7PHONETAB|XELIO10EXTREME|XELIOPT2|NEO_QUAD10",CaptivaTablet:"CAPTIVA PAD",IconbitTablet:"NetTAB|NT-3702|NT-3702S|NT-3702S|NT-3603P|NT-3603P|NT-0704S|NT-0704S|NT-3805C|NT-3805C|NT-0806C|NT-0806C|NT-0909T|NT-0909T|NT-0907S|NT-0907S|NT-0902S|NT-0902S",TeclastTablet:"T98 4G|\\bP80\\b|\\bX90HD\\b|X98 Air|X98 Air 3G|\\bX89\\b|P80 3G|\\bX80h\\b|P98 Air|\\bX89HD\\b|P98 3G|\\bP90HD\\b|P89 3G|X98 3G|\\bP70h\\b|P79HD 3G|G18d 3G|\\bP79HD\\b|\\bP89s\\b|\\bA88\\b|\\bP10HD\\b|\\bP19HD\\b|G18 3G|\\bP78HD\\b|\\bA78\\b|\\bP75\\b|G17s 3G|G17h 3G|\\bP85t\\b|\\bP90\\b|\\bP11\\b|\\bP98t\\b|\\bP98HD\\b|\\bG18d\\b|\\bP85s\\b|\\bP11HD\\b|\\bP88s\\b|\\bA80HD\\b|\\bA80se\\b|\\bA10h\\b|\\bP89\\b|\\bP78s\\b|\\bG18\\b|\\bP85\\b|\\bA70h\\b|\\bA70\\b|\\bG17\\b|\\bP18\\b|\\bA80s\\b|\\bA11s\\b|\\bP88HD\\b|\\bA80h\\b|\\bP76s\\b|\\bP76h\\b|\\bP98\\b|\\bA10HD\\b|\\bP78\\b|\\bP88\\b|\\bA11\\b|\\bA10t\\b|\\bP76a\\b|\\bP76t\\b|\\bP76e\\b|\\bP85HD\\b|\\bP85a\\b|\\bP86\\b|\\bP75HD\\b|\\bP76v\\b|\\bA12\\b|\\bP75a\\b|\\bA15\\b|\\bP76Ti\\b|\\bP81HD\\b|\\bA10\\b|\\bT760VE\\b|\\bT720HD\\b|\\bP76\\b|\\bP73\\b|\\bP71\\b|\\bP72\\b|\\bT720SE\\b|\\bC520Ti\\b|\\bT760\\b|\\bT720VE\\b|T720-3GE|T720-WiFi",OndaTablet:"\\b(V975i|Vi30|VX530|V701|Vi60|V701s|Vi50|V801s|V719|Vx610w|VX610W|V819i|Vi10|VX580W|Vi10|V711s|V813|V811|V820w|V820|Vi20|V711|VI30W|V712|V891w|V972|V819w|V820w|Vi60|V820w|V711|V813s|V801|V819|V975s|V801|V819|V819|V818|V811|V712|V975m|V101w|V961w|V812|V818|V971|V971s|V919|V989|V116w|V102w|V973|Vi40)\\b[\\s]+",JaytechTablet:"TPC-PA762",BlaupunktTablet:"Endeavour 800NG|Endeavour 1010",DigmaTablet:"\\b(iDx10|iDx9|iDx8|iDx7|iDxD7|iDxD8|iDsQ8|iDsQ7|iDsQ8|iDsD10|iDnD7|3TS804H|iDsQ11|iDj7|iDs10)\\b",EvolioTablet:"ARIA_Mini_wifi|Aria[ _]Mini|Evolio X10|Evolio X7|Evolio X8|\\bEvotab\\b|\\bNeura\\b",LavaTablet:"QPAD E704|\\bIvoryS\\b|E-TAB IVORY|\\bE-TAB\\b",AocTablet:"MW0811|MW0812|MW0922|MTK8382|MW1031|MW0831|MW0821|MW0931|MW0712",MpmanTablet:"MP11 OCTA|MP10 OCTA|MPQC1114|MPQC1004|MPQC994|MPQC974|MPQC973|MPQC804|MPQC784|MPQC780|\\bMPG7\\b|MPDCG75|MPDCG71|MPDC1006|MP101DC|MPDC9000|MPDC905|MPDC706HD|MPDC706|MPDC705|MPDC110|MPDC100|MPDC99|MPDC97|MPDC88|MPDC8|MPDC77|MP709|MID701|MID711|MID170|MPDC703|MPQC1010",CelkonTablet:"CT695|CT888|CT[\\s]?910|CT7 Tab|CT9 Tab|CT3 Tab|CT2 Tab|CT1 Tab|C820|C720|\\bCT-1\\b",WolderTablet:"miTab \\b(DIAMOND|SPACE|BROOKLYN|NEO|FLY|MANHATTAN|FUNK|EVOLUTION|SKY|GOCAR|IRON|GENIUS|POP|MINT|EPSILON|BROADWAY|JUMP|HOP|LEGEND|NEW AGE|LINE|ADVANCE|FEEL|FOLLOW|LIKE|LINK|LIVE|THINK|FREEDOM|CHICAGO|CLEVELAND|BALTIMORE-GH|IOWA|BOSTON|SEATTLE|PHOENIX|DALLAS|IN 101|MasterChef)\\b",MiTablet:"\\bMI PAD\\b|\\bHM NOTE 1W\\b",NibiruTablet:"Nibiru M1|Nibiru Jupiter One",NexoTablet:"NEXO NOVA|NEXO 10|NEXO AVIO|NEXO FREE|NEXO GO|NEXO EVO|NEXO 3G|NEXO SMART|NEXO KIDDO|NEXO MOBI",LeaderTablet:"TBLT10Q|TBLT10I|TBL-10WDKB|TBL-10WDKBO2013|TBL-W230V2|TBL-W450|TBL-W500|SV572|TBLT7I|TBA-AC7-8G|TBLT79|TBL-8W16|TBL-10W32|TBL-10WKB|TBL-W100",UbislateTablet:"UbiSlate[\\s]?7C",PocketBookTablet:"Pocketbook",KocasoTablet:"\\b(TB-1207)\\b",Hudl:"Hudl HT7S3|Hudl 2",TelstraTablet:"T-Hub2",GenericTablet:"Android.*\\b97D\\b|Tablet(?!.*PC)|BNTV250A|MID-WCDMA|LogicPD Zoom2|\\bA7EB\\b|CatNova8|A1_07|CT704|CT1002|\\bM721\\b|rk30sdk|\\bEVOTAB\\b|M758A|ET904|ALUMIUM10|Smartfren Tab|Endeavour 1010|Tablet-PC-4|Tagi Tab|\\bM6pro\\b|CT1020W|arc 10HD|\\bJolla\\b|\\bTP750\\b"};
		
		var user_agent = typeof navigator === 'object' ? navigator.userAgent : '';
		var device = '';
		
		for (var key in rules) {
		    var reg = new RegExp(rules[key], 'i'); // convert to regEx
		    if (reg.test(user_agent)) {
			device = reg;
			break;
		    }
		}

		switch ( options.operator ) {
			case 'is':
				return device !== '';
				break;
			case 'is_not':
				return device === '';
				break;
		}

		return true;
	},	

	/**
	 * helper for check with strings
	 *
	 * @param {str} string string that is going to be checked
	 * @param {arr} options options of the condition
	 * @return {bool} true if ad can be displayed
	 */
	helper_check_string: function( string, options ) {
		var operator = options.operator;
		var value = options.value;

		if ( typeof value === 'string' && value !== ''  ) {
			string = string.toUpperCase();
			value = value.toUpperCase();
		} else {
			return true;
		}

		var condition = true;
		switch ( operator ) {
			case 'contain':
				condition = string.indexOf( value ) !== -1;
				break;
			case 'contain_not':
				condition = string.indexOf( value ) === -1;
				break;
			case 'start':
				condition = string.lastIndexOf( value, 0 ) === 0
				break;
			case 'start_not':
				condition = string.lastIndexOf( value, 0 ) !== 0
				break;
			case 'end':
				condition = string.slice( - value.length ) === value;
				break;
			case 'end_not':
				condition = string.slice( - value.length ) !== value;
				break;
			case 'match':
				condition = string === value;
				break;
			case 'match_not':
				condition = string !== value;
				break;
			case "regex":
				try {
					var regexp = new RegExp( value, 'i' );
					condition = string.search( regexp ) !== -1;
				} catch ( e ) {
					advads_pro_utils.log( 'regular expression"' + value + '" in visitor condition is broken' );
				}
				break;
			case 'regex_not':
				try {
					var regexp = new RegExp( value, 'i' );
					condition = string.search( regexp ) === -1;
				} catch ( e ) {
					advads_pro_utils.log( 'regular expression"' + value + '" in visitor condition is broken' );
				}
				break;
		}
		return condition;
	}
};

/**
 * constructor
 *
 * @param {obj} placement_info object which contains info about the placement
 */
function Advads_passive_cb_Placement( placement, element_id ) {
	if ( typeof placement !== 'object' ||
		! placement.hasOwnProperty('id') ||
		! placement.hasOwnProperty('type') ||
		! placement.hasOwnProperty('ads') ||
		! placement.hasOwnProperty('placement_info') ||
		typeof placement.ads !== 'object'
	) {
		throw new SyntaxError( "Can not create Advads_passive_cb_Placement obj" );
	}

	this.id = placement.id;
	this.type = placement.type;
	this.element_id = element_id;
	this.ads = placement.ads;
	this.placement_info = placement.placement_info;
	this.placement_id = advads_pro_utils.isset_nested( this.placement_info, 'id' ) ? this.placement_info.id : null;

	this.group_info = placement.group_info;
	this.group_wrap = placement.group_wrap;
};

/**
 * check if the placement can be displayed in frontend due to its own conditions
 *
 * @return {bool} true if can be displayed in frontend, false otherwise
 */
Advads_passive_cb_Placement.prototype.can_display = function() {
	if ( advads_pro_utils.isset_nested( this.placement_info, 'options', 'test_id' ) &&
		jQuery.inArray( this.placement_id, advanced_ads_pro.get_random_placements() ) < 0
	) {
		// do not deliver placement, that belongs to a test, and was not randomly selected by weight
		return false;
	}

	//check if placement was closed with a cookie before (Advads layer plugin)
	if ( advads_pro_utils.isset_nested( this.placement_info, 'options', 'layer_placement', 'close', 'enabled' ) &&
		this.placement_info.options.layer_placement.close.enabled ) {

		if ( advads_pro_utils.isset_nested( this.placement_info, 'options', 'layer_placement', 'close', 'timeout_enabled' ) &&
			this.placement_info.options.layer_placement.close.timeout_enabled &&
			advads_pro_utils.isset( advads.get_cookie( 'timeout_placement_' + this.placement_info.id ) )
		) {
			return false;
		}
	}
	//check if placement was closed with a cookie before (Sticky Ads plugin)
	if ( advads_pro_utils.isset_nested( this.placement_info, 'options', 'close', 'enabled' ) &&
		this.placement_info.options.close.enabled ) {

		if ( advads_pro_utils.isset_nested( this.placement_info, 'options', 'close', 'timeout_enabled' ) &&
			this.placement_info.options.close.timeout_enabled &&
			advads_pro_utils.isset( advads.get_cookie( 'timeout_placement_' + this.placement_info.id ) )
		) {
			return false;
		}
	}

	// don’t show `Custom Position` placement ad if selector doesn’t exist
	if ( advads_pro_utils.isset_nested( this.placement_info, 'options' )
		&& typeof this.placement_info.options === 'object' ) {
		var params = this.placement_info.options;
        var cp_target = ( ! params.inject_by || params.inject_by === 'pro_custom_element' ) ? 'pro_custom_element' : 'container_id';

        if ( params[ cp_target ] && ! jQuery( params[ cp_target ] ).length ) {
            return false;
        }
	}

	return true;
};

/**
 * write the placement to html
 *
 */
Advads_passive_cb_Placement.prototype.output = function() {
	switch ( this.type ) {
		case 'ad':
				if ( ! this.can_display() ) break;
				var ad = new Advads_passive_cb_Ad( this.ads[this.id], this.element_id ); // only one ad, pass it as argument
				if ( ad.can_display() ) {
					ad.output( { track: true, inject: true } );
				}
			break;
		case 'group':
			if ( typeof this.group_info === 'object' ) {
					if ( ! this.can_display() ) break;
					var group = new Advads_passive_cb_Group( this, this.element_id );
					group.output();
			}
			break;
	}
}

/**
 * constructor
 *
 * @param {obj} ad_info object which contains info about the ad
 * @param {str} elementid id of wrapper div
 */
function Advads_passive_cb_Ad( ad_info, elementid ) {
	if ( typeof ad_info !== 'object' ||
		! advads_pro_utils.isset( ad_info.id ) ||
		! advads_pro_utils.isset( ad_info.title ) ||
		! advads_pro_utils.isset( ad_info.content )
	) {
		throw new SyntaxError( "Can not create Advads_passive_cb_Ad obj" );
	}

	this.id = ad_info.id;
	this.title = ad_info.title;
	this.content = ad_info.content;
	this.expiry_date = parseInt( ad_info.expiry_date ) || 0;
	this.visitors = ad_info.visitors;
	this.once_per_page = ad_info.once_per_page;
	this.elementid = elementid ? elementid : null;
	this.day_indexes = ad_info.day_indexes ? ad_info.day_indexes : null;
	this.debugmode = ad_info.debugmode;
	this.tracking_enabled = ( ad_info.tracking_enabled === undefined || ad_info.tracking_enabled == true  ) ? true : false;
};

/**
 * write the ad to html
 *
 * @param {obj} options
 *     track - track if true, do not track if false
 *     inject - inject the ad if true, return ad content if false
 * @return {str} ad content if inject = false
 */
Advads_passive_cb_Ad.prototype.output = function( options ) {
	options = options || {};

	if ( this.debugmode ) {
		var is_displayed = this.can_display( { ignore_debugmode: true } ) ? 'displayed' : 'hidden';
		var debug_message = jQuery( this.content ).find( '.advads-passive-cb-debug' ).data( is_displayed );
		// inject debug info
		this.content  = this.content.replace( '##advanced_ads_passive_cb_debug##', debug_message );
	}

	advanced_ads_pro.hasAd( this.id, 'ad', this.title, 'passive' );

	if ( options.track && this.tracking_enabled ) {
		advanced_ads_pro.passive_ads.push( this.id );
	}

	if ( ! options.inject ) {
		return this.content;
	}

	advanced_ads_pro.inject( this.elementid, this.content );
	advads_pro_utils.log( 'inject ', this.elementid,  this.content );
},

/**
 * check if the ad can be displayed in frontend due to its own conditions
 *
 * @return {bool} true if can be displayed in frontend, false otherwise
 */
Advads_passive_cb_Ad.prototype.can_display = function( check_options ) {
	check_options = check_options || {};

	if ( this.debugmode && ! check_options.ignore_debugmode ) {
		return true;
	}

	if ( '' === jQuery.trim( this.content ) ||
		! this.can_display_by_visitor() ||
		! this.can_display_by_expiry_date() ||
		! this.can_display_by_timeout() ||
		! this.can_display_by_display_limit() ||
		! this.can_display_by_weekday()
		) {
		return false;
	}
	return true;
};

/**
 * check visitor conditions
 *
 * @return {bool} true if can be displayed in frontend based on visitor settings, false otherwise
 */
Advads_passive_cb_Ad.prototype.can_display_by_visitor = function() {
	var expr = '';

	if ( ! jQuery.isArray( this.visitors ) || this.visitors.length === 0 ) {
		return true
	}

	/**
	 * Allow adding of visitor conditions. Usage example
	 * jQuery( document ).on( 'advads-passive-cb-conditions', function( e, Advads_passive_cb_Conditions ) {
	 *    Advads_passive_cb_Conditions.conditions['condition_key'] = 'condition_function';
	 *    Advads_passive_cb_Conditions['condition_function'] = function ( options, ad ) {};
	 * });
	*/
	jQuery( document ).trigger( 'advads-passive-cb-conditions', [ Advads_passive_cb_Conditions ] );

	for ( var i = 0; i < this.visitors.length; i++ ) {
		var _condition = this.visitors[i],
			operator = '';

        if ( typeof this.visitors[i+1] === 'object'  ) {
            operator = ( this.visitors[i+1].connector === 'or' ) ? ' || ' : ' && ';
        }
        expr += Advads_passive_cb_Conditions.frontend_check( _condition, this ) + operator;
	}

	return eval( expr );
};

/**
 * check expiry date
 *
 * @return {bool} true if can be displayed in frontend based on expiry date, false otherwise
 */
Advads_passive_cb_Ad.prototype.can_display_by_expiry_date = function() {
	if ( this.expiry_date <= 0 ) {
		return true;
	}
	// check against current time (universal time)
	return this.expiry_date > ~~ ( new Date().getTime() / 1000 );
}

/**
 * check if ad can be displayed today
 *
 * @return {bool} true if can be displayed, false otherwise
 */
Advads_passive_cb_Ad.prototype.can_display_by_weekday = function() {
	if ( this.day_indexes ) {
		var today = new Date().getUTCDay(); // universal time
		return jQuery.inArray( today, this.day_indexes ) >= 0
	}
	return true;
}

/**
 * check close and timeout feature implemented by Advads Layer
 *
 * @return {bool} true if can be displayed in frontend based on expiry date, false otherwise
 */
Advads_passive_cb_Ad.prototype.can_display_by_timeout = function() {
	//check if ad was closed with a cookie before (Advads layer plugin)
	if ( advads_pro_utils.isset( advads.get_cookie( 'timeout_' + this.id ) ) ) {
		return false;
	}

	return true;
}

/**
 * check if the ad can be displayed based on display limit
 *
 * @return {bool} true if limit is not reached, false otherwise
 */
Advads_passive_cb_Ad.prototype.can_display_by_display_limit = function() {
    if ( this.once_per_page ) {
        for ( var i = 0; i < advanced_ads_pro.ads.length; i++ ) {
            if ( advanced_ads_pro.ads[ i ].type === 'ad' && parseInt( advanced_ads_pro.ads[ i ].id, 10 ) === this.id  ) {
                return false;
            }
        };
    }
    return true;
}

/**
 * constructor
 *
 * @param {obj} placement object which contains info about the placement
 * @param {string} elementid
 */
function Advads_passive_cb_Group( placement, elementid ) {

	if ( ! advads_pro_utils.isset( placement.group_info.id ) ||
		! advads_pro_utils.isset( placement.group_info.type ) ||
		! advads_pro_utils.isset( placement.group_info.weights ) ||
		! advads_pro_utils.isset( placement.group_info.ordered_ad_ids ) ||
		! advads_pro_utils.isset( placement.group_info.ad_count ) ||
		! advads_pro_utils.isset( placement.ads ) ) {
		throw new SyntaxError( "Can not create Advads_passive_cb_Group obj" );
	}

	this.id = placement.group_info.id;
	this.type = placement.group_info.type;
	this.weights = placement.group_info.weights;
	this.ordered_ad_ids = placement.group_info.ordered_ad_ids;
	this.ad_count = placement.group_info.ad_count;
	this.elementid = elementid ? elementid : null;
	this.slider_options = advads_pro_utils.isset( placement.group_info.slider_options ) ? placement.group_info.slider_options: false;
	this.refresh_enabled = advads_pro_utils.isset( placement.group_info.refresh_enabled ) ? true : false;
	this.placement = placement;
	this.random = placement.group_info.random;

	this.ads = placement.ads;
	this.group_wrap = placement.group_wrap;
}

/**
 * write the group to html
 *
 */
Advads_passive_cb_Group.prototype.output = function() {
	var ordered_ad_ids,
		ads_displayed = 0,
		output_buffer = [];

	switch ( this.type ) {
		case 'ordered':
		case 'slider':
			ordered_ad_ids = this.ordered_ad_ids; // already ordered in backend
			break;
		case 'grid':
			ordered_ad_ids = this.random ? this.shuffle_ads() : this.ordered_ad_ids;
			break;
		default:
			ordered_ad_ids = this.refresh_enabled ? this.ordered_ad_ids : this.shuffle_ads();
	}

	if ( ! jQuery.isArray( ordered_ad_ids ) || ! jQuery.isPlainObject( this.ads ) ) {
		return;
	}



	for ( var i = 0; i < ordered_ad_ids.length; i++ ) {
		if ( ! this.ads.hasOwnProperty( ordered_ad_ids[i] ) ) continue;
		var ad_info = this.ads[ordered_ad_ids[i]];

		if ( typeof ad_info === 'object' ) {
			var ad = new Advads_passive_cb_Ad( ad_info, this.elementid );
			if ( ad.can_display() ) {
				if ( this.refresh_enabled ) {
					// the first ad will be shown right away, so disable tracking starting from second ad
					if ( ads_displayed === 0 ) {
						output_buffer.push( ad.output( { track: true, inject: false } ) );
					} else {
						output_buffer.push( ad.output( { track: false, inject: false } ) );
					}
				} else if ( ( this.type === 'slider' && this.slider_options ) 
					|| this.group_wrap 
				) {
					output_buffer.push( ad.output( { track: true, inject: false } ) );
				} else {
					ad.output( { track: true, inject: true } );
				}
				ads_displayed++;
			}
		}
		// break the loop when maximum ads are reached
		if ( ads_displayed === this.ad_count ) {
			break;
		}
	}

	if ( output_buffer.length ) {
		if ( this.type === 'slider' && this.slider_options ) {
			output_buffer = this.output_slider( output_buffer );
		}

		advanced_ads_pro.inject( this.elementid, this.add_group_wrap( output_buffer, ads_displayed ) );
	}
}

/**
 * get markup to inject around each ad and around entire set of ads (if needed)
 *
 * @param arr output_buffer
 * @return string
 */
Advads_passive_cb_Group.prototype.add_group_wrap = function( output_buffer, ads_displayed ) {
    if ( ! output_buffer.length ) { return ''; }

	var before = '', after = '';

	if ( this.group_wrap ) {
		for ( var i = 0; i < this.group_wrap.length; i++ ) {
			var wrap = this.group_wrap[ i ];
			wrap.min_ads = wrap.min_ads || 1;

			if ( typeof( wrap ) !== 'object' || wrap.min_ads > ads_displayed ) { continue; }
			if ( wrap.before ) { before = wrap.before + before }
			if ( wrap.after ) { after = after + wrap.after }
			if ( typeof wrap.each === 'string' ) {
				for ( var j = 0; j < output_buffer.length; j++ ) {
					output_buffer[ j ] = wrap.each.replace( '%s', output_buffer[ j ] );
				}
			} else if ( typeof wrap.each === 'object' ) {
				var each_obj = wrap.each;

				for ( var j = 0; j < output_buffer.length; j++ ) {
					for ( var format_index in each_obj ) {
						var ad_wrapped = false;

						if ( each_obj.hasOwnProperty( format_index )
							&& format_index !== 'all'
							&& ( ( 1 + j ) % parseInt( format_index, 10 ) === 0 )
						) {
							output_buffer[ j ] = each_obj[ format_index ].replace( '%s', output_buffer[ j ] );
							ad_wrapped  = true;
							break;
						}
					}

					if ( ! ad_wrapped && each_obj.all ) {
						// applied here since JavaScript does not guarantee object key order
						output_buffer[ j ] = each_obj.all.replace( '%s', output_buffer[ j ] );
					}
				}
			}
		}
	}

	return before + output_buffer.join( '' ) + after;
}

/**
 * Output slider markup around slides
 *
 * @deprecated since AAS_VERSION > 1.3.1
 * @param arr output_buffer
 * @return string
 */
Advads_passive_cb_Group.prototype.output_slider = function( output_buffer ) {
	var output_html, ads_output;

	if ( output_buffer.length > 1 && typeof jQuery.fn.unslider === 'function' ) {
		ads_output = output_buffer.join('</li><li>');
		output_buffer = [];

		output_buffer.push( '<div id="' + this.slider_options.slider_id + '" class="' + this.slider_options.init_class + ' ' + this.slider_options.prefix + 'slider"><ul><li>' );
		output_buffer.push( ads_output );
		output_buffer.push( '</li></ul></div>' );
		/* custom css file was added with version 1.1 of Advads Slider. Deactivate the following lines if there are issues with your layout
		output_buffer.push( "<style>.advads-slider { position: relative; width: 100% !important; overflow: hidden; } " );
		output_buffer.push( ".advads-slider ul, .advads-slider li { list-style: none; margin: 0 !important; padding: 0 !important; } " );
		output_buffer.push( ".advads-slider ul li { width: 100%; float: left; }</style>" );
		*/
		output_buffer.push( "<script>jQuery(function() { jQuery('." + this.slider_options.init_class + "').unslider({ " + this.slider_options.settings +" }); });</script>" );
	}

	return output_buffer;
}

/**
 * shuffle ads based on ad weight
 *
 * @return {arr} shuffled array with ad ids
 */
Advads_passive_cb_Group.prototype.shuffle_ads = function() {
	var shuffled_ads = [],
		ad_weights = jQuery.extend({}, this.weights );

	// while non-zero weights are set select random next
	while ( null !== ( random_ad_id = advads_pro_utils.get_random_el_by_weight( ad_weights ) ) ) {
		// remove chosen ad from weights array
		delete ad_weights[random_ad_id];
		// put random ad into shuffled array
		shuffled_ads.push( random_ad_id );
	}
	return shuffled_ads;
}
}

if ( ! advads_pro_utils ) {
    var advads_pro_utils = {
        debug: false,
        // Loop over each item in an array-like value.
        each: function( arr, fn, _this ) {
            var i, len = ( arr && arr.length ) || 0;
            for ( i = 0; i < len; i++ ) {
                fn.call( _this, arr[ i ], i );
            }
        },
        // Loop over each key/value pair in a hash.
        each_key: function(obj, fn, _this) {
            if ( 'object' === typeof obj ) {
                var key;
                for( key in obj ) {
                    if ( obj.hasOwnProperty( key ) ) {
                        fn.call( _this, key, obj[ key ] );
                    }
                }
            }

        },
        log: function() {
            if ( this.debug && this.isset( window.console ) ) {
                window.console.log.apply( window.console, arguments );
            }
        },
        isset: function( str ) {
            return typeof str !== 'undefined';
        },

        /**
         * check if nested object key exists
         *
         * @param {obj}
         * @params {str} level1, .. levelN
         * @return {bool} true on success false on failure
         */
        isset_nested: function ( obj ) {
            for ( var i = 1; i < arguments.length; i++ ) {
                if ( ! obj || ! obj.hasOwnProperty( arguments[i] ) ) {
                    return false;
                }
                obj = obj[arguments[i]];
            }
            return true;
        },
        // generate a random number between min and max (inclide min and max)
        get_random_number: function( min, max ) {
            var rand = min - 0.5 + Math.random() * (max - min + 1)
            return Math.round( rand );
        },

        /**
         * get random element by weight
         *
         * @param {object} weights e.g. {'A' => 2, 'B' => 3, 'C' => 5}
         * @param {string} key to skip, e.g. 'A'
         * @source applied with fix for order http://stackoverflow.com/a/11872928/904614
         */
        get_random_el_by_weight: function( weights, skip ) {
            var max = 0, rand;
            skip = typeof skip !== 'undefined' ? skip : false;

            if ( typeof weights === 'object' )  {
                for ( var el in weights ) {
                    if ( el !== skip && weights.hasOwnProperty( el ) ) {
                        max += parseInt( weights[el] ) || 0;
                    }
                }

                if ( max < 1 ) {
                    return null;
                }

                rand = advads_pro_utils.get_random_number( 1, max );

                for ( var el in weights ) {
                    if ( el !== skip && weights.hasOwnProperty( el ) ) {
                        rand -= weights[ el ];
                        if ( rand <= 0 ) {
                            return el;
                        }
                    }
                }
            }
        }
    };
}

if ( ! jQuery.fn.advads_group_refresh ) {
    /**
     * refresh ads based on interval
     *
     * @param {arr} options: interval - time in ms, type - group type, weights - weights of all ads of the group
     */
    jQuery.fn.advads_group_refresh = function( options ) {
        var options = jQuery.extend( {}, { interval: 4000 }, options || {} );

        return this.each(function () {
            if ( ! jQuery.data( this, 'advads_group_refresh' ) ) {
                jQuery.data( this, 'advads_group_refresh', true );

                var i = 0, ad_id, prev_ad_id = false, $ad, $ads = jQuery( this ).children( 'div' ), ad_length = $ads.length, first = true, weights = {}, tracked_ads = [];

                // get weights of injected ads
                if ( options.type !== 'ordered' ) {
                    $ads.each(function() {
                        ad_id = jQuery( this ).data( 'id' );
                        weights[ ad_id ] = options.weights[ ad_id ];
                    });
                }

                (function tick() {
                    // first ad olready ordered and tracked
                    if ( first ) {
                        first = false;
                        $ads.hide();
                        $ad = $ads.eq( 0 ).show();
                        ad_id = parseInt( $ad.data( 'id' ), 10 );
                        tracked_ads.push( ad_id );
                    } else {
                        if ( options.type !== 'ordered' ) {
                            ad_id = advads_pro_utils.get_random_el_by_weight( weights, prev_ad_id );
                            prev_ad_id = ad_id;
                            $ad.hide();
                            $ad = $ads.filter( "[data-id='" + ad_id + "']" ).show();
                        } else {
                            i++;
                            if ( ad_length === i ) { i = 0; }

                            $ad.hide();
                            $ad = $ads.eq( i ).show();
                            ad_id = parseInt( $ad.data( 'id' ), 10 );
                        }

                        // track if was not tracked
                        ad_id = parseInt( ad_id, 10 );
                        if ( $ad.length && ad_id && jQuery.inArray( ad_id, tracked_ads ) < 0 ) {
                            tracked_ads.push( ad_id );
                            jQuery( document ).triggerHandler('advads_track_ads', [ [ad_id] ] );
                        }

                    }
                    setTimeout( tick, options.interval );
                })();

                jQuery( this ).css('visibility', 'visible');
            }
        });
    };
}
