<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Localization_Links {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
			add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_resources'));
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('wp_ajax_easyazonpro_localize', array(__CLASS__, 'localize'));
		add_action('wp_ajax_nopriv_easyazonpro_localize', array(__CLASS__, 'localize'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region AJAX

	public static function localize() {
		$data = stripslashes_deep($_POST);

		$localized      = array();
		$localizing     = array();
		$localizables   = isset($data['localizables']) ? $data['localizables'] : array();
		$replacements   = array();
		$locale_visitor = easyazon_get_locale_for_country_code(easyazon_get_visitor_country_code());

		if($locale_visitor) {
			$visitor_tags = array_filter(array_map('trim', explode(',', easyazon_get_setting('associates_' . strtolower($locale_visitor)))));
			$visitor_tag  = current($visitor_tags);

			foreach($localizables as $localizable) {
				$identifier = isset($localizable['identifier']) ? $localizable['identifier'] : false;
				$locale     = isset($localizable['locale']) ? $localizable['locale'] : false;
				$keywords   = isset($localizable['keywords']) ? $localizable['keywords'] : false;

				if($locale == $locale_visitor) { continue; }

				if($identifier && $locale) {
					$identifier_localized = easyazon_get_localized_identifier($identifier, $locale, $locale_visitor);

					if($identifier_localized) {
						$localized[$identifier_localized] = array_merge($localizable, array(
							'identifier_orig' => $identifier,
							'identifier'      => $identifier_localized,
							'locale_orig'     => $locale,
							'locale'          => $locale_visitor,
							'tag'             => $visitor_tag,
						));
					} else {
						if(!isset($localizing[$locale]) || !is_array($localizing[$locale])) {
							$localizing[$locale] = array();
						}

						$localizing[$locale][$identifier] = $localizable;
					}

				} else if($keywords && $locale) {
					$selector = sprintf('[data-keywords="%s"][data-locale="%s"]', esc_attr($keywords), esc_attr($locale));
					$url      = easyazon_get_url(array_merge($localizable, array(
						'locale' => $locale_visitor,
						'tag'    => $visitor_tag,
					)));

					$replacements[] = compact('selector', 'url');
				}
			}

			// We are forcing a fetch of all these items at once so they get cached and we can use them
			// DO NOT REMOVE THIS BECAUSE PERFORMANCE WILL SUFFER
			$items = easyazon_get_items(array_unique(array_keys($localized)), $locale_visitor);
			foreach($localized as $localizable) {
				$selector = sprintf('[data-identifier="%s"][data-locale="%s"]', esc_attr($localizable['identifier_orig']), esc_attr($localizable['locale_orig']));
				$url      = easyazon_get_url($localizable);

				$replacements[] = compact('selector', 'url');
			}

			foreach($localizing as $locale => $identifiers) {
				$items = easyazon_get_items(array_unique(array_keys($identifiers)), $locale);

				foreach($items as $identifier => $item) {
					if(!$item || !isset($item['title'])) { continue; }

					$selector = sprintf('[data-identifier="%s"][data-locale="%s"]', esc_attr($identifier), esc_attr($locale));
					$url      = easyazon_get_url(
									array_merge(
										array_diff_key($identifiers[$identifier], array(
											'identifier' => false,
											'locale'     => false,
										)), array(
											'keywords' => $item['title'],
											'locale'   => $locale_visitor,
											'tag'      => $visitor_tag
										)
									)
								);

					$replacements[] = compact('selector', 'url');
				}
			}
		}

		wp_send_json($replacements);
	}

	#endregion AJAX

	#region Enqueue Resources

	public static function enqueue_resources() {
		wp_enqueue_script('easyazonpro-localize-links', plugins_url('resources/links.js', __FILE__), array('jquery'), EASYAZONPRO_VERSION, true);
		wp_localize_script('easyazonpro-localize-links', 'EasyAzonPro_Localize_Links', array(
			'ajaxAction' => 'easyazonpro_localize',
			'ajaxUrl'    => admin_url('admin-ajax.php'),
		));
	}

	#endregion Enqueue Resources
}

EasyAzonPro_Components_Localization_Links::init();
