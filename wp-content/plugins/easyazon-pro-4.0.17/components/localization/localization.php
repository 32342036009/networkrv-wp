<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Localization {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Public API

	public static function get_locale_for_country_code($country_code) {
		foreach(easyazon_get_locales() as $locale_key => $locale_name) {
			$locale_key_lower = strtolower($locale_key);
			$country_codes    = easyazon_get_setting("link_localize_{$locale_key_lower}");
			if(in_array($country_code, $country_codes)) {
				return $locale_key;
			}
		}

		return false;
	}

	#endregion Public API
}

require_once('lib/localization-functions.php');

EasyAzonPro_Components_Localization::init();
