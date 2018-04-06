<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZONPRO_SHORTCODE_CTA')) {
	define('EASYAZONPRO_SHORTCODE_CTA', 'easyazon_cta');
}

if(!defined('EASYAZONPRO_SHORTCODE_CTA_LEGACY')) {
	define('EASYAZONPRO_SHORTCODE_CTA_LEGACY', 'easyazon-cta');
}

class EasyAzonPro_Components_Shortcodes_CTA {
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
		add_filter('easyazon_cta_atts', array(__CLASS__, 'cta_atts'), 10, 2);
		add_filter('easyazon_get_shortcodes', array(__CLASS__, 'get_shortcodes'));
	}

	private static function _add_shortcodes() {
		add_shortcode(EASYAZONPRO_SHORTCODE_CTA,        array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZONPRO_SHORTCODE_CTA_LEGACY, array(__CLASS__, 'shortcode'));
	}

	#region Attributes

	public static function cta_atts($cta_atts, $shortcode_atts) {
		$item = easyazon_get_item($shortcode_atts['identifier'], $shortcode_atts['locale']);

		if($item && isset($item['name'])) {
			$cta_atts['alt'] = $item['name'];
		}

		$cta_atts['class'] = isset($cta_atts['class']) ? (is_array($cta_atts['class']) ? $cta_atts['class'] : array_filter(array($cta_atts['class']))) : array();
		$cta_atts['class'] = array_merge($cta_atts['class'], array('easyazon-image'));

		if(isset($shortcode_atts['align']) && $shortcode_atts['align']) {
			$cta_atts['class'] = array_merge($cta_atts['class'], array("align{$shortcode_atts['align']}"));
		}

		if(isset($shortcode_atts['key'])) {
			if(0 === strpos($shortcode_atts['key'], 'amazon-us-')) {
				$shortcode_atts['locale'] = strtoupper(substr($shortcode_atts['key'], 7, 2));
				$shortcode_atts['key']    = substr($shortcode_atts['key'], 10);
			}

			$buttons = easyazon_get_call_to_action_buttons();
			$buttons = isset($buttons[$shortcode_atts['locale']]) ? $buttons[$shortcode_atts['locale']] : current($buttons);
			$button  = isset($buttons[$shortcode_atts['key']]) ? $buttons[$shortcode_atts['key']] : current($buttons);

			$cta_atts['height'] = $button['height'];
			$cta_atts['src']    = $button['url'];
			$cta_atts['width']  = $button['width'];
		}

		return $cta_atts;
	}

	#endregion Attributes

	#region Shortcode

	public static function get_shortcodes($shortcodes) {
		$shortcodes[] = EASYAZONPRO_SHORTCODE_CTA;
		$shortcodes[] = EASYAZONPRO_SHORTCODE_CTA_LEGACY;

		return $shortcodes;
	}

	public static function shortcode($atts, $content = null) {
		$atts = apply_filters('easyazon_shortcode_atts', array(), $atts);

		$identifier = isset($atts['identifier']) ? $atts['identifier'] : false;
		$locale     = isset($atts['locale']) ? $atts['locale'] : false;

		if((empty($identifier) || empty($locale)) || !($item = easyazon_get_item($identifier, $locale))) {
			return '';
		} else {
			$cta_atts  = apply_filters('easyazon_cta_atts', array(), $atts);
			$link_atts = apply_filters('easyazon_link_atts', array(), $atts);

			return sprintf('<a %1$s><img %2$s /></a>', easyazon_collapse_attributes($link_atts), easyazon_collapse_attributes($cta_atts));
		}
	}

	#endregion Shortcode

	#region Public API

	private static $buttons = null;

	public static function get_call_to_action_buttons() {
		if(is_null(self::$buttons)) {
			$buttons = array();

			$base_path = path_join(dirname(__FILE__), 'resources/');
			$base_url  = plugins_url('resources/', __FILE__);

			$pattern = path_join($base_path, '*/*.gif');
			$files   = glob($pattern);

			if(is_array($files)) {
				foreach($files as $file) {
					$image_dimensions = getimagesize($file);
					if($image_dimensions) {
						$image_parts  = explode('/', str_replace($base_path, '', $file));
						$image_locale = $image_parts[0];
						$image_name   = $image_parts[1];
						$image_key    = str_replace('.gif', '', $image_name);
						$image_url    = path_join($base_url, "{$image_locale}/{$image_name}");

						$buttons[$image_locale]   = isset($buttons[$image_locale]) && is_array($buttons[$image_locale]) ? $buttons[$image_locale] : array();
						$buttons[$image_locale][$image_key] = array(
							'key'    => $image_key,
							'name'   => ucwords(str_replace('-', ' ', $image_key)),
							'height' => $image_dimensions[1],
							'url'    => $image_url,
							'width'  => $image_dimensions[0],
						);
					}
				}
			}

			self::$buttons = $buttons;
		}

		return self::$buttons;
	}

	#endregion Public API
}

require_once('lib/call-to-action-functions.php');

EasyAzonPro_Components_Shortcodes_CTA::init();
