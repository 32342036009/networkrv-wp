<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_PopupStates_CTA {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_popup_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
			add_action('easyazon_popup_states', array(__CLASS__, 'output_state'), 15);
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('easyazon_search_result_column_insert_links', array(__CLASS__, 'add_insert_links'), 15);
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region State

	public static function output_state() {
		include('views/state.php');
	}

	#endregion State

	#region Search Result Links

	public static function add_insert_links($links) {
		$links[] = sprintf('<a href="#" data-bind="click: $root.ctaActivate">%s</a>', esc_html(__('CTA Link')));

		return $links;
	}

	#endregion Search Result Links

	#region Scripts and Styles

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popup-states-cta', plugins_url('resources/popup-state.js', __FILE__), array('easyazon-popup'), EASYAZONPRO_VERSION, true);

		wp_localize_script('easyazonpro-popup-states-cta', 'EasyAzonPro_PopupStates_CTA', array(
			'buttons'   => array_map('array_values', easyazon_get_call_to_action_buttons()),
			'shortcode' => EASYAZONPRO_SHORTCODE_CTA,
		));
	}

	#endregion Scripts and Styles
}

EasyAzonPro_Components_PopupStates_CTA::init();
