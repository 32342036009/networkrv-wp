<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_PopupStates_SearchResults {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_popup_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
			add_action('easyazon_popup_states', array(__CLASS__, 'output_state'));
			add_action('easyazon_search_buttons_after', array(__CLASS__, 'add_link_to_search_results_action'));
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

	#region State

	public static function output_state() {
		include('views/state.php');
	}

	#endregion State

	#region Search Result Callout

	public static function add_link_to_search_results_action() {
		printf('<p class="description"><a href="#" data-bind="click: $root.searchResultsActivate, visible: keywordsExist">%s "<span data-bind="text: keywords"></span>"</a></p>', __('Link to the search results page for'));
	}

	#endregion Search Result Callout

	#region Scripts and Styles

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popup-states-search-results', plugins_url('resources/popup-state.js', __FILE__), array('easyazon-popup'), EASYAZONPRO_VERSION, true);

		wp_localize_script('easyazonpro-popup-states-search-results', 'EasyAzonPro_PopupStates_SearchResults', array(

		));
	}

	#endregion Scripts and Styles
}

EasyAzonPro_Components_PopupStates_SearchResults::init();
