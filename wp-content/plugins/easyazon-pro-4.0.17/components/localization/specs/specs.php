<?php

if(!defined('ABSPATH')) { exit; }

if(!defined('EASYAZONPRO_LOCALIZATION_PAGE')) {
	define('EASYAZONPRO_LOCALIZATION_PAGE', 'easyazon-localization');
}

class EasyAzonPro_Components_Localization_Specs {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_add_admin_pages', array(__CLASS__, 'add_localization_page'), 11);
			add_action('easyazon_popup_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('easyazon_search_result_column_insert', array(__CLASS__, 'search_result_column_insert'), 101);
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region About Page

	public static function add_localization_page($parent) {
		$page = add_submenu_page($parent, __('EasyAzon - Localization'), __('Localization'), 'manage_options', EASYAZONPRO_LOCALIZATION_PAGE, array(__CLASS__, 'display_localization_page'));

		add_action("load-{$page}", array(__CLASS__, 'load'));
	}

	public static function display_localization_page() {
		$data = stripslashes_deep($_GET);

		$locales      = easyazon_get_locales();
		$locales_keys = array_keys($locales);
		$page_url     = self::_get_page_url();

		if(isset($data['id'])) {
			$localization = easyazon_get_localization_by_id($data['id']);

			if(isset($data['identifier']) && isset($data['locale'])) {
				$localization[$data['locale']] = $data['identifier'];
			}

			include('views/specs-edit.php');
		} else {
			$limit         = 20;
			$page          = isset($data['paged']) && is_numeric($data['paged']) ? absint($data['paged']) : 1;
			$localizations = easyazon_get_localizations($limit, $page);

			extract($localizations);

			// We need to do a bulk fetch so that we're not killing the API / response time
			$locale_identifiers = array_fill_keys($locales_keys, array());
			foreach($localizations as $localization) {
				foreach($locales_keys as $locale_key) {
					if($localization[$locale_key]) {
						$locale_identifiers[$locale_key][] = $localization[$locale_key];
					}
				}
			}

			// Now for the fetch after the sort
			foreach($locale_identifiers as $locale_key => $identifiers) {
				$items = easyazon_get_items($identifiers, $locale_key);
			}

			include('views/specs-manage.php');
		}
	}

	public static function load() {
		$data = stripslashes_deep($_REQUEST);

		if(current_user_can('manage_options') && isset($data['easyazonpro-localization-save-nonce']) && wp_verify_nonce($data['easyazonpro-localization-save-nonce'], 'easyazonpro-localization-save')) {
			$localization = self::_sanitize_localization($data['easyazonpro-localization']);
			$setting      = self::_save_localization($localization);

			if($setting) {
				add_settings_error('general', 'settings_updated', __('Localization saved.'), 'updated');
				set_transient('settings_errors', get_settings_errors(), 30);

				easyazon_redirect(self::_get_page_url(array('id' => $setting, 'settings-updated' => 'true')));
			}
		} else if(current_user_can('manage_options') && isset($data['easyazonpro-localization-delete-nonce']) && wp_verify_nonce($data['easyazonpro-localization-delete-nonce'], 'easyazonpro-localization-delete') && isset($data['id']) && preg_match('#^easyazon_localization_\d+_\d{4}$#', $data['id'])) {
			delete_option($data['id']);

			add_settings_error('general', 'settings_updated', __('Localization deleted.'), 'updated');
			set_transient('settings_errors', get_settings_errors(), 30);

			easyazon_redirect(self::_get_page_url(array('settings-updated' => 'true')));
		}

		wp_enqueue_media();
		wp_enqueue_script('easyazonpro-localization-spects', plugins_url('resources/specs.js', __FILE__), array('jquery'), EASYAZON_VERSION, true);

		wp_localize_script('easyazonpro-localization-spects', 'EasyAzonPro_Localization_Specs', array(
			'stateName' => 'iframe:easyazon',
			'stateTitle' => __('EasyAzon'),
		));

		do_action('easyazon_load_localization_page');
	}

	private static function _get_page_url($query_args = array()) {
		return add_query_arg(array_merge(array(
			'page' => EASYAZONPRO_LOCALIZATION_PAGE
		), $query_args), admin_url('admin.php'));
	}

	#endregion About Page

	#region Popup States

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popup-states-localization-specs', plugins_url('resources/popup-state.js', __FILE__), array('easyazon-popup-states-search'), EASYAZONPRO_VERSION, true);

		wp_localize_script('easyazonpro-popup-states-localization-specs', 'EasyAzonPro_PopupStates_Localization_Specs', array(

		));
	}

	public static function search_result_column_insert($markup) {
		$markup = sprintf('<span data-bind="visible: $parent.localizationSpec"><a href="#" data-bind="click: $root.selectProductForLocalization">%s</a></span><span data-bind="visible: $parent.localizationSpecNot">%s</span>', __('Select Product'), $markup);

		return $markup;
	}

	#endregion Popup States

	#region Value Parsing

	private static function _build_value($localization) {
		$locales = easyazon_get_locales();

		$parts = array();
		foreach($locales as $locale_key => $locale_name) {
			if(!empty($localization[$locale_key])) {
				$parts[] = "{$locale_key}_{$localization[$locale_key]}";
			}
		}

		return implode('|', array_filter($parts));
	}

	private static function _split_value($value) {
		$locales            = easyazon_get_locales();
		$locale_identifiers = explode('|', $value);


		foreach($locale_identifiers as $locale_identifier) {
			list($locale, $identifier) = array_map('trim', explode('_', $locale_identifier));

			if($locale && $identifier && isset($locales[$locale])) {
				$localization[$locale] = $identifier;
			}
		}

		foreach($locales as $locale_key => $locale_name) {
			if(!isset($localization[$locale_key])) {
				$localization[$locale_key] = '';
			}
		}

		return $localization;
	}

	#endregion Value Parsing

	#region Data Management

	private static function _sanitize_localization($localization) {
		$locale_keys = array_keys(easyazon_get_locales());

		foreach($locale_keys as $locale_key) {
			$matches = array();

			if(preg_match('#dp/([^/]+)#', $localization[$locale_key], $matches)) {
				$localization[$locale_key] = $matches[1];
			}
		}

		return $localization;
	}

	private static function _save_localization($localization) {
		$value = self::_build_value($localization);

		if(isset($localization['ID']) && !empty($localization['ID'])) {
			$setting = $localization['ID'];
			update_option($setting, $value);
		} else {
			do {
				$setting = sprintf('easyazon_localization_%d_%04d', time(), rand(1, 1000));
			} while(!add_option($setting, $value, null, 'no'));
		}

		return $setting;
	}

	#endregion Data Management

	#region Public API

	public static function get_localized_identifier($current_identifier, $current_locale, $requested_locale) {
		if($current_locale === $requested_locale) {
			return $current_identifier;
		} else {
			global $wpdb;
			$value = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_value LIKE %s ORDER BY option_name ASC LIMIT 1", "%{$current_locale}_{$current_identifier}%"));

			if($value) {
				$localization = self::_split_value($value);

				if(isset($localization[$requested_locale]) && $localization[$requested_locale]) {
					return $localization[$requested_locale];
				}
			}

			return false;
		}
	}

	public static function get_localizations($limit, $page) {
		$limit = max(absint($limit), 1);
		$page  = max(absint($page), 1);

		global $wpdb;
		$localizations = array();
		$results       = $wpdb->get_results($wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_name DESC LIMIT %d, %d", 'easyazon_localization_%', $limit * ($page - 1), $limit));
		$total         = $wpdb->get_var('SELECT FOUND_ROWS()');
		$pages         = ceil($total / $limit);

		foreach($results as $result) {
			if(!empty($result->option_value)) {
				$localizations[] = array_merge(array('ID' => $result->option_name), self::_split_value($result->option_value));
			}
		}

		return compact('localizations', 'page', 'pages', 'total');
	}

	public static function get_localization_by_id($id) {
		$localization_value = empty($id) ? '' : get_option($id);

		return array_merge(array('ID' => $id), self::_split_value($localization_value));
	}

	#endregion Public API
}

require_once('lib/specs-functions.php');

EasyAzonPro_Components_Localization_Specs::init();
