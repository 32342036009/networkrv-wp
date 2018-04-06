<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Settings {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
			add_action('easyazon_settings_before_sections', array(__CLASS__, 'link_to_free_course'));
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

	#region Administrative Interface

	public static function link_to_free_course() {
		printf(__('<p><strong>Do you want to make the most of EasyAzon Pro? Take our <a href="%s" target="_blank">free Amazon course</a> to increase your site\'s earnings today!</strong></p>'), 'http://boostwp.com/amazon-affiliate-course/');
	}

	#endregion Administrative Interface
}

EasyAzonPro_Components_Settings::init();
