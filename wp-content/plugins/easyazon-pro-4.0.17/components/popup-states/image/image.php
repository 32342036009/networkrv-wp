<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_PopupStates_Image {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_popup_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
			add_action('easyazon_popup_states', array(__CLASS__, 'output_state'));
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('wp_ajax_easyazonpro_save_image', array(__CLASS__, 'save_image'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('easyazon_search_result_column_insert_links', array(__CLASS__, 'add_insert_links'));
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Save Image Locally

	public static function save_image() {
		$data = stripslashes_deep($_POST);
		$url = isset($data['url']) ? $data['url'] : false;

		if($url) {
			$response = wp_remote_get($url);

			if(!is_wp_error($response)) {
				$name   = preg_replace('#[^0-9A-Za-z\.]#', '', basename($url));
				$result = wp_upload_bits($name, null, wp_remote_retrieve_body($response));

				if(!isset($result['error']) || empty($result['error']) && isset($result['url'])) {
					$url = $result['url'];
				}
			}
		}


		wp_send_json(array(
			'url' => $url
		));
	}

	#endregion Save Image Locally

	#region State

	public static function output_state() {
		include('views/state.php');
	}

	#endregion State

	#region Search Result Links

	public static function add_insert_links($links) {
		$links[] = sprintf('<a href="#" data-bind="click: $root.imageActivate">%s</a>', esc_html(__('Image Link')));

		return $links;
	}

	#endregion Search Result Links

	#region Scripts and Styles

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popup-states-image', plugins_url('resources/popup-state.js', __FILE__), array('easyazon-popup'), EASYAZONPRO_VERSION, true);
		wp_enqueue_style('easyazonpro-popup-states-image', plugins_url('resources/popup-state.css', __FILE__), array('easyazon-popup'), EASYAZONPRO_VERSION);

		wp_localize_script('easyazonpro-popup-states-image', 'EasyAzonPro_PopupStates_Image', array(
			'ajaxActionImageSave' => 'easyazonpro_save_image',
			'shortcode'           => EASYAZONPRO_SHORTCODE_IMAGE,
		));
	}

	#endregion Scripts and Styles
}

EasyAzonPro_Components_PopupStates_Image::init();
