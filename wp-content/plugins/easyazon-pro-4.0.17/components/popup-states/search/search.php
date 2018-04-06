<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_PopupStates_Search {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_popup_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
			add_action('easyazon_search_fields_after', array(__CLASS__, 'output_fields'));
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

	#region Fields

	public static function output_fields() {
		include('views/fields.php');
	}

	#endregion Fields

	#region Scripts and Styles

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popup-states-search', plugins_url('resources/popup-state.js', __FILE__), array('easyazon-popup-states-search'), EASYAZONPRO_VERSION, true);

		wp_localize_script('easyazonpro-popup-states-search', 'EasyAzonPro_PopupStates_Search', array(
			'indices' => easyazon_get_locale_search_indices(),
			'sorts'   => easyazon_get_sort_values(),
		));
	}

	#endregion Scripts and Styles
}

EasyAzonPro_Components_PopupStates_Search::init();
