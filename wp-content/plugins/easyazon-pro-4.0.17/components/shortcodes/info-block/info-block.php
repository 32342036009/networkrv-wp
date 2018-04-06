<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZONPRO_SHORTCODE_INFO_BLOCK')) {
	define('EASYAZONPRO_SHORTCODE_INFO_BLOCK', 'easyazon_infoblock');
}

if(!defined('EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY')) {
	define('EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY', 'easyazon_block');
}

if(!defined('EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY_ADDITIONAL')) {
	define('EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY_ADDITIONAL', 'easyazon-block');
}

class EasyAzonPro_Components_Shortcodes_InfoBlock {
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
			add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_resources'));
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
		add_shortcode(EASYAZONPRO_SHORTCODE_INFO_BLOCK,                   array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY,            array(__CLASS__, 'shortcode'));
		add_shortcode(EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY_ADDITIONAL, array(__CLASS__, 'shortcode'));
	}

	#region Shortcode

	public static function get_shortcodes($shortcodes) {
		$shortcodes[] = EASYAZONPRO_SHORTCODE_INFO_BLOCK;
		$shortcodes[] = EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY;

		return $shortcodes;
	}

	public static function shortcode($atts, $content = null) {
		$atts = apply_filters('easyazon_shortcode_atts', array(), $atts);

		$identifier = isset($atts['identifier']) ? $atts['identifier'] : false;
		$locale     = isset($atts['locale']) ? $atts['locale'] : false;

		if((empty($identifier) || empty($locale)) || !($item = easyazon_get_item($identifier, $locale))) {
			return '';
		} else {
			$block_atts = apply_filters('easyazon_block_atts', array(), $atts);
			$link_atts  = apply_filters('easyazon_link_atts', array(), $atts);

			if(isset($item['images']) && is_array($item['images'])) {
				$image_index = 3;
				while($image_index >= 0) {
					if(isset($item['images'][$image_index])) {
						$image_atts = array(
							'alt'    => $item['title'] ? $item['title'] : '',
							'class'  => 'easyazon-block-information-image',
							'height' => $item['images'][$image_index]['height'],
							'src'    => $item['images'][$image_index]['url'],
							'width'  => $item['images'][$image_index]['width'],
						);

						break;
					}

					$image_index--;
				}
			} else {
				$image_atts = false;
			}

			$buttons = easyazon_get_call_to_action_buttons();
			if(isset($buttons[$locale])) {
				$cta_key  = key($buttons[$locale]);
				$cta_atts = apply_filters('easyazon_cta_atts', array(), array('align' => 'center', 'key' => $cta_key, 'identifier' => $identifier, 'locale' => $locale));
			} else {
				$cta_atts = false;
			}

			$key      = isset($atts['key']) ? $atts['key'] : false;
			$template = easyazon_get_info_block_template_file($key);

			if(file_exists($template)) {
				ob_start();

				include($template);

				return sprintf('<div %s>%s</div>', easyazon_collapse_attributes($block_atts), ob_get_clean());
			} else {
				return '';
			}
		}
	}

	#endregion Shortcode

	#region Resources

	public static function enqueue_resources() {
		$preparsed = easyazon_get_preparsed_shortcodes();

		$attribute_sets = array();

		if(isset($preparsed[EASYAZONPRO_SHORTCODE_INFO_BLOCK])) {
			$attribute_sets = array_merge($attribute_sets, $preparsed[EASYAZONPRO_SHORTCODE_INFO_BLOCK]);
		}

		if(isset($preparsed[EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY])) {
			$attribute_sets = array_merge($attribute_sets, $preparsed[EASYAZONPRO_SHORTCODE_INFO_BLOCK_LEGACY]);
		}

		if(!empty($attribute_sets)) {

		}


		$keys = array();
		foreach($attribute_sets as $atts) {
			$atts = apply_filters('easyazon_shortcode_atts', array(), $atts);

			if(isset($atts['key']) && $atts['key']) {
				$keys[] = $atts['key'];
			} else {
				$keys[] = '';
			}
		}


		$keys  = array_unique($keys);

		$scripts     = array();
		$stylesheets = array();

		foreach($keys as $key) {
			$scripts     = array_merge($scripts, easyazon_get_info_block_template_scripts($key));
			$stylesheets = array_merge($stylesheets, easyazon_get_info_block_template_stylesheets($key));
		}

		$scripts     = array_unique($scripts);
		$stylesheets = array_unique($stylesheets);

		foreach($scripts as $script) {
			wp_enqueue_script(sprintf('easyazon-pro-info-block-%s', $script), plugins_url("resources/{$script}", __FILE__), array('jquery', 'easyazonpro-bootstrap-popovers'), EASYAZONPRO_VERSION, true);
		}

		foreach($stylesheets as $stylesheet) {
			wp_enqueue_style(sprintf('easyazon-pro-info-block-%s', $stylesheet), plugins_url("resources/{$stylesheet}", __FILE__), array(), EASYAZONPRO_VERSION);
		}
	}

	#endregion Resources

	#region Templates

	public static function get_info_block_template_file($key) {
		$template = easyazon_get_info_block_template($key);

		return $template && isset($template['file']) ? $template['file'] : false;
	}

	public static function get_info_block_template_name($key) {
		$template = easyazon_get_info_block_template($key);

		return $template && isset($template['name']) ? $template['name'] : false;
	}

	public static function get_info_block_template_scripts($key) {
		$template = easyazon_get_info_block_template($key);

		return $template && isset($template['scripts']) ? $template['scripts'] : array();
	}

	public static function get_info_block_template_stylesheets($key) {
		$template = easyazon_get_info_block_template($key);

		return $template && isset($template['stylesheets']) ? $template['stylesheets'] : array();
	}

	public static function get_info_block_template($key) {
		$template  = false;
		$templates = easyazon_get_info_block_templates();

		if(isset($templates[$key])) {
			$template = $templates[$key];
		} else if(count($templates)) {
			$default = easyazon_get_setting('default_info_block_template');

			if(isset($templates[$default])) {
				$template = $templates[$default];
			} else {
				$template = current($templates);
			}
		}

		return $template;
	}

	public static function get_info_block_templates() {
		$pattern   = path_join(dirname(__FILE__), 'templates/*.php');
		$files     = glob($pattern);
		$templates = array();

		foreach($files as $file) {
			$basename = preg_replace('#\.php$#', '', basename($file));
			if(isset($templates[$basename])) { continue; }

			$template_contents   = file_get_contents($file);
			$template_matches    = array();
			$scripts_matches     = array();
			$stylesheets_matches = array();

			if(preg_match('#Template:\s*?(.+)$#mi', $template_contents, $template_matches)) {
				$name = trim($template_matches[1]);
			} else {
				continue;
			}

			if(preg_match('#Scripts:\s?(.+)$#mi', $template_contents, $scripts_matches)) {
				$scripts = array_map('trim', explode(',', $scripts_matches[1]));
			} else {
				$scripts = array();
			}

			if(preg_match('#Stylesheets:\s?(.+)$#mi', $template_contents, $stylesheets_matches)) {
				$stylesheets = array_map('trim', explode(',', $stylesheets_matches[1]));
			} else {
				$stylesheets = array();
			}


			$templates[$basename] = compact('file', 'name', 'scripts', 'stylesheets');
		}

		return $templates;
	}

	#endregion Templates
}

require_once('lib/info-block-functions.php');

EasyAzonPro_Components_Shortcodes_InfoBlock::init();
