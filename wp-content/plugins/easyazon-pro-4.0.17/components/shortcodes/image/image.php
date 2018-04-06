<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZONPRO_SHORTCODE_IMAGE')) {
	define('EASYAZONPRO_SHORTCODE_IMAGE', 'easyazon_image');
}

if(!defined('EASYAZONPRO_SHORTCODE_IMAGE_LEGACY')) {
	define('EASYAZONPRO_SHORTCODE_IMAGE_LEGACY', 'easyazon-image');
}

if(!defined('EASYAZONPRO_SHORTCODE_IMAGE_LEGACY_ADDITIONAL')) {
	define('EASYAZONPRO_SHORTCODE_IMAGE_LEGACY_ADDITIONAL', 'easyazon-image-link');
}

if(!defined('EASYAZONPRO_SHORTCODE_IMAGE_LEGACY_SIMPLEAZON')) {
	define('EASYAZONPRO_SHORTCODE_IMAGE_LEGACY_SIMPLEAZON', 'simpleazon-image');
}

class EasyAzonPro_Components_Shortcodes_Image {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
		self::_add_shortcodes();
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
		add_filter('easyazon_get_shortcodes', array(__CLASS__, 'get_shortcodes'));
	}

	private static function _add_shortcodes() {
		add_shortcode(EASYAZONPRO_SHORTCODE_IMAGE,                   array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZONPRO_SHORTCODE_IMAGE_LEGACY,            array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZONPRO_SHORTCODE_IMAGE_LEGACY_ADDITIONAL, array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZONPRO_SHORTCODE_IMAGE_LEGACY_SIMPLEAZON, array(__CLASS__, 'shortcode'));
	}

	#region Shortcode

	public static function get_shortcodes($shortcodes) {
		$shortcodes[] = EASYAZONPRO_SHORTCODE_IMAGE;
		$shortcodes[] = EASYAZONPRO_SHORTCODE_IMAGE_LEGACY;
		$shortcodes[] = EASYAZONPRO_SHORTCODE_IMAGE_LEGACY_SIMPLEAZON;

		return $shortcodes;
	}

	public static function shortcode($atts, $content = null) {
		$atts = apply_filters('easyazon_shortcode_atts', array(), $atts);

		$identifier = isset($atts['identifier']) ? $atts['identifier'] : false;
		$locale     = isset($atts['locale']) ? $atts['locale'] : false;

		if((empty($identifier) || empty($locale)) || !($item = easyazon_get_item($identifier, $locale))) {
			return '';
		} else {
			$image_atts = apply_filters('easyazon_image_atts', array(), $atts);
			$link_atts  = apply_filters('easyazon_link_atts', array(), $atts);

			return sprintf('<a %1$s><img %2$s /></a>', easyazon_collapse_attributes($link_atts), easyazon_collapse_attributes($image_atts));
		}
	}

	#endregion Shortcode
}

EasyAzonPro_Components_Shortcodes_Image::init();
