<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Popup {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_popup_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
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

	#region Scripts and Styles

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popup', plugins_url('resources/popup.js', __FILE__), array('easyazon-popup'), EASYAZONPRO_VERSION, true);

		wp_localize_script('easyazonpro-popup', 'EasyAzonPro_Popup', array(
			'ajaxActionShortcodeRaw' => 'easyazonpro_shortcode_raw',
		));
	}

	#endregion Scripts and Styles
}

EasyAzonPro_Components_Popup::init();
