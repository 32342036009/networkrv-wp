<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_PopupStatesAdditions {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			remove_action('easyazon_link_form_after', array('EasyAzon_Components_PopupStatesAdditions', 'link_form_upgrade_prompt'));
			remove_action('easyazon_search_buttons_before', array('EasyAzon_Components_PopupStatesAdditions', 'search_buttons_upgrade_prompt'));;

			add_action('easyazon_link_buttons_after', array(__CLASS__, 'output_markup_warning'));
			add_action('easyazon_image_buttons_after', array(__CLASS__, 'output_markup_warning'));
			add_action('easyazon_info_block_buttons_after', array(__CLASS__, 'output_markup_warning'));
			add_action('easyazon_cta_buttons_after', array(__CLASS__, 'output_markup_warning'));
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
		add_filter('easyazon_popup_localize', array(__CLASS__, 'add_tags'));
	}

	#region Localize

	public static function add_tags($data) {
		$data['tags']['BR'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_br'))));
		$data['tags']['CA'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_ca'))));
		$data['tags']['CN'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_cn'))));
		$data['tags']['DE'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_de'))));
		$data['tags']['ES'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_es'))));
		$data['tags']['FR'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_fr'))));
		$data['tags']['IN'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_in'))));
		$data['tags']['IT'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_it'))));
		$data['tags']['JP'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_jp'))));
		$data['tags']['UK'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_uk'))));
		$data['tags']['US'] = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_us'))));

		return $data;
	}

	#endregion Localize

	#region UI

	public static function output_markup_warning() {
		printf('<p class="description">%s</p>', __('Use "Insert Markup" if you don\'t want to use shortcodes for your EasyAzon links. Any global changes to your link settings will not affect your non shortcode links.'));
	}

	#endregion UI
}

EasyAzonPro_Components_PopupStatesAdditions::init();
