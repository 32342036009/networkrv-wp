<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_About {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_load_about_page', array(__CLASS__, 'redirect_to_amazon_course'));
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

	#region Load

	public static function redirect_to_amazon_course() {
		easyazon_redirect('http://boostwp.com/amazon-affiliate-course/');
	}

	#endregion Load
}

EasyAzonPro_Components_About::init();
