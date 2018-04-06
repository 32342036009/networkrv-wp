<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_SettingsSections_Links {
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
		add_settings_field('link_cart', __('Add to Cart'), array(__CLASS__, 'display_settings_field_link_cart'), $page, EASYAZON_SETTINGS_SECTION_DEFAULTS, array(
			'label_for' => easyazon_get_setting_field_id('link_cart'),
		));

		add_settings_field('link_cloak', __('Cloak Links'), array(__CLASS__, 'display_settings_field_link_cloak'), $page, EASYAZON_SETTINGS_SECTION_DEFAULTS, array(
			'label_for' => easyazon_get_setting_field_id('link_cloak'),
		));

		add_settings_field('link_cloak_prefix', __('Cloaking Prefix'), array(__CLASS__, 'display_settings_field_link_cloak_prefix'), $page, EASYAZON_SETTINGS_SECTION_DEFAULTS, array(
			'label_for' => easyazon_get_setting_field_id('link_cloak_prefix'),
		));

		add_settings_field('link_popups', __('Product Popups'), array(__CLASS__, 'display_settings_field_link_popups'), $page, EASYAZON_SETTINGS_SECTION_DEFAULTS, array(
			'label_for' => easyazon_get_setting_field_id('link_popups'),
		));

		add_settings_field('infoblock_template', __('Info Block Template'), array(__CLASS__, 'display_settings_field_infoblock_template'), $page, EASYAZON_SETTINGS_SECTION_DEFAULTS, array(
			'label_for' => easyazon_get_setting_field_id('infoblock_template'),
		));
	}

	#endregion Settings Section

	#region Settings Fields

	public static function display_settings_field_link_cart($args) {
		printf('<input type="hidden" name="%s" value="n" />', easyazon_get_setting_field_name('link_cart'));
		printf('<label><input type="checkbox" %s id="%s" name="%s" value="y" /> %s</label>', ('y' === easyazon_get_setting('link_cart') ? 'checked="checked"' : ''), easyazon_get_setting_field_id('link_cart'), easyazon_get_setting_field_name('link_cart'), __('I want EasyAzon to have my links add a product to a visitor\'s Amazon cart'));
		printf('<p class="description"><strong>%s:</strong> %s</p>', __('Potential Extra Money Maker'), __('When a visitor adds an item to their shopping cart after clicking through your link you now have an extra 89 day window to earn a commission if the visitor buys the item they added to their shopping cart instead of the usual cookie length of 24 hours'));
	}

	public static function display_settings_field_link_cloak($args) {
		printf('<input type="hidden" name="%s" value="n" />', easyazon_get_setting_field_name('link_cloak'));
		printf('<label><input type="checkbox" %s id="%s" name="%s" value="y" /> %s</label>', ('y' === easyazon_get_setting('link_cloak') ? 'checked="checked"' : ''), easyazon_get_setting_field_id('link_cloak'), easyazon_get_setting_field_name('link_cloak'), __('I want EasyAzon to cloak my links'));
	}

	public static function display_settings_field_link_cloak_prefix($args) {
		printf('<code>%s</code><input type="text" class="regular-text code" id="%s" name="%s" value="%s" /><code>%s</code>', home_url('/'), easyazon_get_setting_field_id('link_cloak_prefix'), easyazon_get_setting_field_name('link_cloak_prefix'), easyazon_get_setting('link_cloak_prefix'), '/LOCALE/ASIN/TAG/');
	}

	public static function display_settings_field_link_popups($args) {
		printf('<input type="hidden" name="%s" value="n" />', easyazon_get_setting_field_name('link_popups'));
		printf('<label><input type="checkbox" %s id="%s" name="%s" value="y" /> %s</label>', ('y' === easyazon_get_setting('link_popups') ? 'checked="checked"' : ''), easyazon_get_setting_field_id('link_popups'), easyazon_get_setting_field_name('link_popups'), __('I want EasyAzon to display information popups when visitors hover over product text links'));
	}

	public static function display_settings_field_infoblock_template($args) {
		$options   = array();
		$selected  = easyazon_get_setting('infoblock_template');
		$templates = easyazon_get_info_block_templates();

		foreach($templates as $template_key => $template) {
			$options[] = sprintf('<option %s value="%s">%s</option>', ($selected === $template_key ? 'selected="selected"' : ''), esc_attr($template_key), esc_html($template['name']));
		}

		printf('<select id="%s" name="%s">%s</select>', easyazon_get_setting_field_id('infoblock_template'), easyazon_get_setting_field_name('infoblock_template'), implode('', $options));
	}

	#endregion Settings Fields

	#region Settings

	public static function add_settings_defaults($defaults) {
		$settings_old = get_option('_easyazon_settings', array());

		$defaults['link_cart']          = isset($settings_old['links_add_to_cart']) && 'no' == $settings_old['links_add_to_cart'] ? 'n' : 'y';
		$defaults['link_cloak']         = isset($settings_old['links_cloaking']) && 'no' == $settings_old['links_cloaking'] ? 'n' : 'y';
		$defaults['link_cloak_prefix']  = isset($settings_old['links_cloaking_prefix']) ? $settings_old['links_cloaking_prefix'] : 'product';
		$defaults['link_popups']        = isset($settings_old['links_popups']) && 'no' == $settings_old['links_popups'] ? 'n' : 'y';
		$defaults['infoblock_template'] = 'image';

		return $defaults;
	}

	public static function sanitize_settings($settings, $settings_raw, $settings_defaults) {
		if(isset($settings['link_cart'])) {
			$settings['link_cart'] = easyazon_yn($settings['link_cart']);
		}

		if(isset($settings['link_cloak'])) {
			$settings['link_cloak'] = easyazon_yn($settings['link_cloak']);
		}

		if(isset($settings['link_cloak_prefix'])) {
			$settings['link_cloak_prefix'] = trim(preg_replace('#[^A-Za-z0-9.-/]#', '', $settings['link_cloak_prefix']));

			if(empty($settings['link_cloak_prefix'])) {
				unset($settings['link_cloak_prefix']);
			}
		}

		if(isset($settings['link_popups'])) {
			$settings['link_popups'] = easyazon_yn($settings['link_popups']);
		}

		return $settings;
	}

	#endregion Settings
}

EasyAzonPro_Components_SettingsSections_Links::init();
