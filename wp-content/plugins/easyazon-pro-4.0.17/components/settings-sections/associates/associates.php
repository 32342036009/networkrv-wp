<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_SettingsSections_Associates {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_settings_section_after_' . EASYAZON_SETTINGS_SECTION_ASSOCIATES, array(__CLASS__, 'display_settings_section_after'), 4);
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_filter('easyazon_sanitize_settings', array(__CLASS__, 'sanitize_settings'), 11, 3);
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Settings Section

	public static function display_settings_section_after() {
		include('views/section-after.php');
	}

	#endregion Settings Section

	#region Settings

	public static function sanitize_settings($settings, $settings_raw, $settings_defaults) {
		$settings['associates_br'] = implode(', ', array_map('trim', explode(',', $settings['associates_br'])));
		$settings['associates_ca'] = implode(', ', array_map('trim', explode(',', $settings['associates_ca'])));
		$settings['associates_cn'] = implode(', ', array_map('trim', explode(',', $settings['associates_cn'])));
		$settings['associates_de'] = implode(', ', array_map('trim', explode(',', $settings['associates_de'])));
		$settings['associates_es'] = implode(', ', array_map('trim', explode(',', $settings['associates_es'])));
		$settings['associates_fr'] = implode(', ', array_map('trim', explode(',', $settings['associates_fr'])));
		$settings['associates_in'] = implode(', ', array_map('trim', explode(',', $settings['associates_in'])));
		$settings['associates_it'] = implode(', ', array_map('trim', explode(',', $settings['associates_it'])));
		$settings['associates_jp'] = implode(', ', array_map('trim', explode(',', $settings['associates_jp'])));
		$settings['associates_uk'] = implode(', ', array_map('trim', explode(',', $settings['associates_uk'])));
		$settings['associates_us'] = implode(', ', array_map('trim', explode(',', $settings['associates_us'])));

		return $settings;
	}

	#endregion Settings
}

EasyAzonPro_Components_SettingsSections_Associates::init();
