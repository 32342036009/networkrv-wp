<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_PopupStates_Link {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_link_buttons', array(__CLASS__, 'add_insert_markup_button'));
			add_action('easyazon_link_fields_after', array(__CLASS__, 'output_fields'));
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

	#region Buttons

	public static function add_insert_markup_button() {
		printf('<button class="button button-secondary" data-bind="click: insertRaw">%s</button>', __('Insert Markup'));
	}

	#endregion Buttons

	#region State

	public static function output_fields() {
		include('views/fields.php');
	}

	#endregion State

	#region Scripts and Styles

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popup-states-link', plugins_url('resources/popup-state.js', __FILE__), array('easyazon-popup-states-link'), EASYAZONPRO_VERSION, true);

		wp_localize_script('easyazonpro-popup-states-link', 'EasyAzonPro_PopupStates_Link', array(

		));
	}

	#endregion Scripts and Styles
}

EasyAzonPro_Components_PopupStates_Link::init();
