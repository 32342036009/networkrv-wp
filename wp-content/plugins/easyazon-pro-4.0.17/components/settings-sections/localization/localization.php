<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZONPRO_SETTINGS_SECTION_LOCALIZATION')) {
	define('EASYAZONPRO_SETTINGS_SECTION_LOCALIZATION', 'localization');
}

class EasyAzonPro_Components_SettingsSections_Localization {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'), 4);
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
		add_filter('easyazon_pre_get_settings_defaults', array(__CLASS__, 'add_settings_defaults'));
		add_filter('easyazon_sanitize_settings', array(__CLASS__, 'sanitize_settings'), 11, 3);
	}

	#region Settings Section

	public static function add_settings_section_and_fields($page) {
		add_settings_section(EASYAZONPRO_SETTINGS_SECTION_LOCALIZATION, __('Link Localization'), array(__CLASS__, 'display_settings_section'), $page);

		add_settings_field('link_localize', __('Localization'), array(__CLASS__, 'display_settings_field_link_localize'), $page, EASYAZONPRO_SETTINGS_SECTION_LOCALIZATION, array(
			'label_for' => easyazon_get_setting_field_id('link_localize'),
		));

		foreach(easyazon_get_locales() as $locale_key => $locale_name) {
			$locale_key = strtolower($locale_key);

			add_settings_field("link_localize_{$locale_key}", $locale_name, array(__CLASS__, 'display_settings_field_link_localize_locale'), $page, EASYAZONPRO_SETTINGS_SECTION_LOCALIZATION, array(
				'locale_key' => $locale_key,
				'locale_name' => $locale_name
			));
		}
	}

	public static function display_settings_section() {
		do_action('easyazon_settings_section_before_' . EASYAZONPRO_SETTINGS_SECTION_LOCALIZATION);

		include('views/section.php');

		do_action('easyazon_settings_section_after_' . EASYAZONPRO_SETTINGS_SECTION_LOCALIZATION);
	}

	#endregion Settings Section

	#region Settings Fields

	public static function display_settings_field_link_localize($args) {
		printf('<input type="hidden" name="%s" value="no" />', easyazon_get_setting_field_name('link_localize'));
		printf('<label><input type="checkbox" %s id="%s" name="%s" value="y" /> %s</label>', ('y' === easyazon_get_setting('link_localize') ? 'checked="checked"' : ''), easyazon_get_setting_field_id('link_localize'), easyazon_get_setting_field_name('link_localize'), __('I want EasyAzon links to be localized where possible'));
		printf('<p class="description"><strong>%s: </strong> %s</p>', __('Potential Extra Money Maker'), __('Automatically change your Amazon affiliate links to match the country a visitor is viewing your website from, allowing you to earn commissions on traffic that you would otherwise miss out on'));
		printf('<p class="description"><strong>%s: </strong> %s</p>', __('For Example'), __('If you create an affiliate link for the Xbox One product in the United States a visitor from the United Kingdom will be taken to the product search results page for "Xbox One" on Amazon.co.uk'));
	}

	public static function display_settings_field_link_localize_locale($args) {
		$setting_key = "link_localize_{$args['locale_key']}";
		$countries   = easyazon_get_countries();
		$list_items  = array();
		$values      = easyazon_get_setting($setting_key);
		$values_flip = array_flip($values);

		$countries = array_merge(array_intersect_key($countries, $values_flip), array_diff_key($countries, $values_flip));

		foreach($countries as $country_key => $country_name) {
			$list_items[] = sprintf('<li><label><input type="checkbox" %s name="%s[]" value="%s" /> %s</label></li>', (in_array($country_key, $values) ? 'checked="checked"' : ''), easyazon_get_setting_field_name($setting_key), esc_attr($country_key), esc_html($country_name));
		}

		printf('<div class="wp-tab-panel"><ul class="easyazon-localize-countries">%s</ul></div>', implode("\n", $list_items));
	}

	#endregion Settings Fields

	#region Settings Page

	public static function load_settings_page() {
		wp_enqueue_style('easyazonpro-settings-localization', plugins_url('resources/localization.css', __FILE__), array(), EASYAZONPRO_VERSION);
	}

	#endregion Settings Page

	#region Settings

	public static function add_settings_defaults($defaults) {
		$settings_old = get_option('_easyazon_settings', array());

		$defaults['link_localize'] = isset($settings_old['links_localization']) && 'no' == $settings_old['links_localization'] ? 'n' : 'y';

		$defaults['link_localize_us'] = array('US');
		$defaults['link_localize_br'] = array('BR');
		$defaults['link_localize_ca'] = array('CA');
		$defaults['link_localize_cn'] = array('CN');
		$defaults['link_localize_fr'] = array('FR', 'LU', 'BE');
		$defaults['link_localize_de'] = array('DE', 'NL', 'PL', 'CZ', 'AT', 'CH', 'HU', 'SK', 'SI', 'RO', 'BG');
		$defaults['link_localize_it'] = array('IT');
		$defaults['link_localize_in'] = array('IN');
		$defaults['link_localize_jp'] = array('JP');
		$defaults['link_localize_es'] = array('ES', 'PT');
		$defaults['link_localize_uk'] = array('GB', 'SE', 'NO', 'FI', 'DK', 'EE', 'LT', 'IS', 'GR', 'IE');

		return $defaults;
	}

	public static function sanitize_settings($settings, $settings_raw, $settings_defaults) {
		if(isset($settings['link_localize'])) {
			$settings['link_localize'] = easyazon_yn($settings['link_localize']);
		}

		$accumulated  = array();
		$country_keys = array_keys(easyazon_get_countries());

		foreach(easyazon_get_locales() as $locale_key => $locale_name) {
			$locale_key  = strtolower($locale_key);
			$setting_key = "link_localize_{$locale_key}";

			if(isset($settings[$setting_key]) && is_array($settings[$setting_key])) {
				$settings[$setting_key] = is_array($settings[$setting_key]) ? array_unique(array_intersect($settings[$setting_key], $country_keys)) : array();
			} else {
				$settings[$setting_key] = array();
			}

			$settings[$setting_key] = array_diff($settings[$setting_key], $accumulated);

			$accumulated = array_merge($accumulated, $settings[$setting_key]);
		}

		return $settings;
	}

	#endregion Settings
}

EasyAzonPro_Components_SettingsSections_Localization::init();
