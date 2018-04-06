<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_SettingsSections_Upgrade {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			remove_action('easyazon_display_settings_page', array('EasyAzon_Components_SettingsSections_Upgrade', 'add_settings_section_and_fields'), 1001);
			remove_action('easyazon_load_settings_page', array('EasyAzon_Components_SettingsSections_Upgrade', 'load_settings_page'));
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
}

EasyAzonPro_Components_SettingsSections_Upgrade::init();
