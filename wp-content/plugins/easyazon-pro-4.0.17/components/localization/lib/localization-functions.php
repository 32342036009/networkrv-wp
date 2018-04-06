<?php

if(!defined('ABSPATH')) { exit; }

require_once('geoip/phar-stub.php');

use GeoIp2\Database\Reader;

function easyazon_get_geoip_reader() {
	return new Reader(path_join(dirname(__FILE__), 'geoip.mmdb'));
}

function easyazon_get_locale_for_country_code($country_code = 'US') {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Localization::get_locale_for_country_code($country_code), $country_code);
}

function easyazon_get_visitor_country_code($remote_address = null) {
	$geoip_reader   = easyazon_get_geoip_reader();
	$remote_address = is_null($remote_address) && isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $remote_address;

	try {
		$record = $geoip_reader->country($remote_address);

		return $record->country->isoCode;
	} catch(Exception $e) {
		return easyazon_get_setting('default_search_locale');
	}
}

