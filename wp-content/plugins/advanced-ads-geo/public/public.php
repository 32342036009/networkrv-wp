<?php

class Advanced_Ads_Geo {

    /**
     * holds plugin base class
     *
     * @var Advanced_Ads_Geo_Plugin
     * @since 1.0.0
     */
    protected $plugin;

    /**
     * Initialize the plugin
     * and styles.
     *
     * @since     1.0.0
     */
    public function __construct() {

	$this->plugin = Advanced_Ads_Geo_Plugin::get_instance();
	
	// register events when all plugins are loaded
	add_action('plugins_loaded', array($this, 'wp_admin_plugins_loaded'));
    }

    /**
     * load actions and filters
     */
    public function wp_admin_plugins_loaded() {
	
    }

    /**
     * check geo visitor condition
     *
     * @since 1.0.0
     */
    static function check_geo( $options = array() ) {

	if ( ( !isset( $options['country'] ) && !isset( $options['region'] ) && !isset( $options['city'] ) ) 
		|| ( '' === $options['country'] && '' === $options['region'] && '' === $options['city'] ) ) {
	    return true;
	}
	
	$operator = isset( $options["operator"] ) ? $options["operator"] : 'is';
	$country = isset( $options["country"] ) ? trim( $options["country"] ) : '';
	$region = isset( $options["region"] ) ? trim( $options["region"] ) : '';
	$city = isset( $options["city"] ) ? trim( $options["city"] ) : '';
	
	$api = Advanced_Ads_Geo_Api::get_instance();
	$ip = $api->get_real_IP_address();
	$country_code = '';
	$visitor_city = '';
	$visitor_region = '';
	
	// get locale
	$options = Advanced_Ads_Geo_Plugin::get_instance()->options();
	$locale = isset( $options[ AAGT_SLUG ]['locale'] ) ? $options[ AAGT_SLUG ]['locale'] : 'en';
	
	// reuse already existing location information to save db requests on the same page impression
	if( ! $ip ){
	    if( 'is_not' === $operator ){
		return true;
	    } else {
		return false;
	    }
	} elseif( $api->used_city_reader && $city && $api->current_city ){
	    $continent_code = $api->current_continent;
	    $country_code = $api->current_country;
	    $visitor_city = $api->current_city;
	} elseif( $api->used_city_reader && $region && $api->current_region ){
	    $continent_code = $api->current_continent;
	    $country_code = $api->current_country;
	    $visitor_region = $api->current_region;
	} elseif( ! $city && ! $region && $api->current_country ){
	    $continent_code = $api->current_continent;
	    $country_code = $api->current_country;
	} else {
	    try {
		// get correct reader
		if( $city || $region ){
		    $reader = $api->get_GeoIP2_city_reader();
		    $api->used_city_reader = true;
		} else {
		    $reader = $api->get_GeoIP2_country_reader();
		}

		if( $reader ){
			// Look up the IP address
			if( $city || $region ){
			    try {
				$record = $reader->city($ip);
			    } catch (Exception $e ){
				// error_log( 'Advanced Ads Geo: ' . $e );
			    }
			} else {
			    try {
				$record = $reader->country($ip);
			    } catch (Exception $e ){
				// error_log( 'Advanced Ads Geo: ' . $e );
			    }
			}

			if ( ! empty( $record ) ) {
			    $api->current_country = $country_code = $record->country->isoCode;
			    $api->current_continent = $continent_code = $record->continent->code;
			    if( $city ){
				
				$api->current_city = $visitor_city = isset( $record->city->name ) ? $record->city->name : __( '(unknown city)', 'advanced-ads-geo' );
				if( isset( $record->city->names[ $locale ] ) && $record->city->names[ $locale ] ) {
				    $api->current_city = $visitor_city = $record->city->names[ $locale ];
				}
			    }
			    if( $region ){
				$api->current_region = $visitor_region = isset( $record->subdivisions[0]->name ) ? $record->subdivisions[0]->name : __( '(unknown region)', 'advanced-ads-geo' );
				if( isset( $record->subdivisions[0]->names[ $locale ] ) && $record->subdivisions[0]->names[ $locale ] ) {
				    $api->current_region = $visitor_region = $record->subdivisions[0]->names[ $locale ];
				}
			    }
			}
		} else {
			error_log( 'Advanced Ads Geo: ' . __( 'Geo Database not found', 'advanced-ads-geo' ) );
		}
		
	    } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
		if( defined( 'ADVANCED_ADS_GEO_CHECK_DEBUG') ){
		    $log_content = sprintf(__( "Address not found: %s", 'advanced-ads-geo' ), $e->getMessage() ) . "\n";
		    error_log( $log_content, 3, WP_CONTENT_DIR . '/geo-check.log' );
		}
		return false;
	    }
	}
	
	// convert to lower case
	if( function_exists('mb_strtolower') ){
	    $city = mb_strtolower( $city, 'utf-8');
	    $region = mb_strtolower( $region, 'utf-8');
	    $visitor_city = mb_strtolower( $visitor_city, 'UTF-8');
	    $visitor_region = mb_strtolower( $visitor_region, 'UTF-8');
	}
	
	if( defined( 'ADVANCED_ADS_GEO_CHECK_DEBUG') ){
		$log_content = "GEO CHECK (setting|visitor): COUNTRY {$country}|{$country_code} – REGION {$region}|{$visitor_region} – CITY {$city}|{$visitor_city}"  . "\n";
		error_log( $log_content, 3, WP_CONTENT_DIR . '/geo-check.log' );
	}
	
	// set up data for continent search
	if( 0 === strpos( $country, 'CONT_' )){
		$country_code = 'CONT_' . $continent_code;
	}
	
	if ( 'is_not' === $operator ) {
	    // check city
	    if( $city ){
		return $city !== $visitor_city;
	    } elseif( $region ) { // check region
		return $region !== $visitor_region;
	    }
	    // check EU
	    if( 'EU' === $country ){
		return !$api->is_eu_state( $country_code );
	    }
	    // check country
	    return $country !== $country_code;
	} else {
	    // check city
	    if( $city ){
		return $city === $visitor_city;
	    } elseif ( $region ){
		return $region === $visitor_region;
	    }
	    // check EU
	    if( 'EU' === $country ){
		return $api->is_eu_state( $country_code );
	    }
	    // check country
	    return $country === $country_code;
	}
	
	return false;
    }

}
