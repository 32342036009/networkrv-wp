<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Cloaking {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_load_settings_page', 'flush_rewrite_rules');
		} else {
			// Actions that only affect the frontend interface or operation
			add_action('parse_request', array(__CLASS__, 'redirect'));
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('generate_rewrite_rules', array(__CLASS__, 'add_rewrite_rules'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
			add_filter('query_vars', array(__CLASS__, 'add_query_vars'));
		}

		// Filters that affect both the administrative and frontend interface or operation
		add_filter('easyazon_get_url', array(__CLASS__, 'get_url'), 12, 2);
	}

	#region Redirection

	public static function redirect($wp) {
		$query_vars = $wp->query_vars;
		$identifier = isset($query_vars['easyazon-cloaking-identifier']) ? urldecode($query_vars['easyazon-cloaking-identifier']) : false;
		$locale     = isset($query_vars['easyazon-cloaking-locale']) ? $query_vars['easyazon-cloaking-locale'] : false;
		$tag        = isset($query_vars['easyazon-cloaking-tag']) ? $query_vars['easyazon-cloaking-tag'] : false;

		if($identifier && $locale) {
			$keywords = (0 === strpos($identifier, 'k-')) ? substr($identifier, 2) : false;
			$cart     = isset($_GET['cart']) && 'y' === $_GET['cart'] ? 'y' : 'n';

			$url = easyazon_get_url(compact('cart', 'identifier', 'keywords', 'locale', 'tag'));

			if($url) {
				easyazon_redirect($url);
			} else {
				wp_die(__('The item in question could not be found. Please contact the site administrator with the following information:') . '<br /><br />' . sprintf(__('Identifier: <code>%s</code>'), $identifier) . '<br />' . sprintf(__('Locale: <code>%s</code>'), $locale));
			}
		}
	}

	#endregion Redirection

	#region Rewrite Rules

	public static function add_rewrite_rules($wp_rewrite) {
		$prefix = easyazon_get_setting('link_cloak_prefix');

		$wp_rewrite->rules = array(
			"{$prefix}/([^/]+)/([^/]+)(/([^/]+))?/?" => sprintf('index.php?easyazon-cloaking-identifier=%s&easyazon-cloaking-locale=%s&easyazon-cloaking-tag=%s', $wp_rewrite->preg_index(1), $wp_rewrite->preg_index(2), $wp_rewrite->preg_index(4)),
		) + $wp_rewrite->rules;
	}

	#endregion Rewrite Rules

	#region Query Vars

	public static function add_query_vars($vars) {
		$vars[] = 'easyazon-cloaking-identifier';
		$vars[] = 'easyazon-cloaking-locale';
		$vars[] = 'easyazon-cloaking-tag';

		return $vars;
	}

	#endregion Query Vars

	#region URLs

	public static function get_url($url, $shortcode_atts) {
		if(isset($shortcode_atts['cloak']) && 'y' === $shortcode_atts['cloak']) {
			$permalink = get_option('permalink_structure');

			$locale = isset($shortcode_atts['locale']) ? $shortcode_atts['locale'] : '';
			$prefix = easyazon_get_setting('link_cloak_prefix');
			$tag    = isset($shortcode_atts['tag']) ? $shortcode_atts['tag'] : '';

			if(isset($shortcode_atts['keywords']) && $shortcode_atts['keywords']) {
				$keywords = 'k-' . urlencode(trim($shortcode_atts['keywords']));

				if(empty($permalink)) {
					$query_args = array(
						'easyazon-cloaking-identifier' => $keywords,
						'easyazon-cloaking-locale'     => $locale,
						'easyazon-cloaking-tag'        => $tag,
					);

					$url = add_query_arg($query_args, home_url('/'));
				} else {
					$url = sprintf(home_url('/%s/%s/%s/%s/'), $prefix, $keywords, $locale, $tag);
					$url = trailingslashit(rtrim($url, '/'));
				}

			} else if(isset($shortcode_atts['identifier']) && isset($shortcode_atts['locale'])) {
				$identifier = isset($shortcode_atts['identifier']) ? $shortcode_atts['identifier'] : '';

				if(empty($permalink)) {
					$query_args = array(
						'easyazon-cloaking-identifier' => $identifier,
						'easyazon-cloaking-locale'     => $locale,
						'easyazon-cloaking-tag'        => $tag,
					);

					if(isset($shortcode_atts['cart']) && 'y' === $shortcode_atts['cart']) {
						$query_args['cart'] = 'y';
					}					

					$url = add_query_arg($query_args, home_url('/'));
				} else {
					$url = sprintf(home_url('/%s/%s/%s/%s/'), $prefix, $identifier, $locale, $tag);
					$url = trailingslashit(rtrim($url, '/'));

					if(isset($shortcode_atts['cart']) && 'y' === $shortcode_atts['cart']) {
						$url = add_query_arg(array('cart' => 'y'), $url);
					}
				}
			}
		}

		return $url;
	}

	#endregion URLs
}

EasyAzonPro_Components_Cloaking::init();
