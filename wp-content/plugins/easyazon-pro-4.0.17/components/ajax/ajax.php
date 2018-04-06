<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Ajax {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('wp_ajax_easyazonpro_shortcode_raw', array(__CLASS__, 'shortcode_raw'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
		add_filter('easyazon_query_products_args', array(__CLASS__, 'query_products_args'), 10, 2);
		add_filter('easyazon_query_products_response', array(__CLASS__, 'query_products_response'), 10, 2);
	}

	#region Shortcode Raw

	public static function shortcode_raw() {
		$data = stripslashes_deep($_POST);

		$shortcode = isset($data['shortcode']) ? $data['shortcode'] : '';

		wp_send_json(array(
			'markup' => do_shortcode($shortcode),
		));
	}

	#endregion Shortcode Raw

	#region Query Products Args

	public static function query_products_args($args, $data) {
		if(isset($data['index']) && !empty($data['index'])) {
			$args['SearchIndex'] = $data['index'];
		}

		if(isset($data['priceMin']) && !empty($data['priceMin']) && is_numeric($data['priceMin']) && $data['priceMin'] > 0) {
			$args['MinimumPrice'] = easyazon_get_converted_currency($data['priceMin'], $data['locale']);
		}

		if(isset($data['priceMax']) && !empty($data['priceMax']) && is_numeric($data['priceMax']) && $data['priceMax'] > 0) {
			$args['MaximumPrice'] = easyazon_get_converted_currency($data['priceMax'], $data['locale']);
		}

		if(isset($data['sort']) && !empty($data['sort'])) {
			$args['Sort'] = $data['sort'];
		}

		return $args;
	}

	#endregion Query Products Args

	#region Query Products Response

	public static function query_products_response($response, $data) {
		if(isset($data['index'])) {
			$response['index'] = $data['index'];
		}

		if(isset($data['priceMin'])) {
			$response['priceMin'] = $data['priceMin'];
		}

		if(isset($data['priceMin'])) {
			$response['priceMin'] = $data['priceMin'];
		}

		if(isset($data['sort'])) {
			$response['sort'] = $data['sort'];
		}

		return $response;
	}

	#endregion Query Products Response
}

EasyAzonPro_Components_Ajax::init();
