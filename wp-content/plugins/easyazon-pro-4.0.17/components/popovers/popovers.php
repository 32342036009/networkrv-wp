<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Popovers {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
			add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('wp_ajax_easyazon_get_popover_markup', array(__CLASS__, 'get_popover_markup'));
		add_action('wp_ajax_nopriv_easyazon_get_popover_markup', array(__CLASS__, 'get_popover_markup'));
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

	public static function get_popover_markup() {
		$data = stripslashes_deep($_POST);

		$atts = isset($data['atts']) ? $data['atts'] : array();

		$shortcode_atts = apply_filters('easyazon_shortcode_atts', array(), $atts);
		$link_atts      = apply_filters('easyazon_link_atts', array(), $shortcode_atts);

		$identifier = isset($shortcode_atts['identifier']) ? $shortcode_atts['identifier'] : false;
		$locale     = isset($shortcode_atts['locale']) ? $shortcode_atts['locale'] : false;

		$item = easyazon_get_item($identifier, $locale);

		if($item && !is_wp_error($item)) {
			$buttons = easyazon_get_call_to_action_buttons();
			$buttons = isset($buttons[$locale]) && is_array($buttons[$locale]) ? $buttons[$locale] : array();
			$button  = current($buttons);

			ob_start();
			include('views/popover.php');
			$markup = ob_get_clean();
		} else {
			$markup = sprintf('<p>%s</p>', __('Could not find product data.'));
		}

		wp_send_json(array(
			'markup' => $markup,
		));
	}

	#endregion AJAX

	#region Resources

	public static function enqueue_scripts() {
		wp_enqueue_script('easyazonpro-popovers', plugins_url('resources/popovers.js', __FILE__), array(), EASYAZONPRO_VERSION, true);
		wp_enqueue_style('easyazonpro-popovers', plugins_url('resources/popovers.css', __FILE__), array(), EASYAZONPRO_VERSION);

		wp_localize_script('easyazonpro-popovers', 'EasyAzonPro_Components_Popovers', array(
			'ajaxUrl'    => admin_url('admin-ajax.php'),
			'ajaxAction' => 'easyazon_get_popover_markup',
			'loading'    => __('Loading product data.'),
			'placement'  => 'top',
			'template'   => '<div class="popover easyazon-popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content easyazon-popover-content"></div></div>',
			'timeout'    => '750',
		));
	}

	#endregion Resources
}

EasyAzonPro_Components_Popovers::init();
