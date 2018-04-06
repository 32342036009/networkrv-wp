<?php
/*
Plugin Name: EasyAzon Pro
Plugin URI: http://boostwp.com/products/easyazon-pro/
Description: Quickly and easily insert Amazon affiliate links into your site's content. By installing this plugin, you agree to the <a href="http://easyazon.com/terms/" target="_blank">EasyAzon terms of service</a>.
Version: 4.0.17
Author: BoostWP
Author URI: http://boostwp.com/
*/

if(!defined('EASYAZONPRO_VERSION')) {
	define('EASYAZONPRO_VERSION', '4.0.17');
}

if(!defined('EASYAZONPRO_EASYAZON_VERSION_REQUIRED')) {
	define('EASYAZONPRO_EASYAZON_VERSION_REQUIRED', '4.0.9');
}

function easyazonpro_initialization() {
	if(defined('EASYAZON_LOADED') && EASYAZON_LOADED && defined('EASYAZON_VERSION') && version_compare(EASYAZON_VERSION, EASYAZONPRO_EASYAZON_VERSION_REQUIRED, 'ge')) {
		if(!defined('EASYAZONPRO_PLUGIN_BASENAME')) {
			define('EASYAZONPRO_PLUGIN_BASENAME', plugin_basename(__FILE__));
		}

		if(!defined('EASYAZONPRO_PLUGIN_DIRECTORY')) {
			define('EASYAZONPRO_PLUGIN_DIRECTORY', dirname(__FILE__));
		}

		if(!defined('EASYAZONPRO_PLUGIN_FILE')) {
			define('EASYAZONPRO_PLUGIN_FILE', __FILE__);
		}

		// Amazon library for making requests to the Amazon Product Advertising API
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'lib/amazon.php'));

		// Override the generic about page
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/about/about.php'));

		// Some additions to the generic popup functionality
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup/popup.php'));

		// Some additions to the generic settings page
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/settings/settings.php'));

		// Settings sections are separate components for each section that will be on the settings page, done
		// this way to allow for easy expandability in the future
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/settings-sections/associates/associates.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/settings-sections/links/links.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/settings-sections/localization/localization.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/settings-sections/upgrade/upgrade.php'));

		// Some additions to the generic popup functionality
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup/popup.php'));

		// Interface for popup states
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup-states/call-to-action/call-to-action.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup-states/image/image.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup-states/info-block/info-block.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup-states/link/link.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup-states/search/search.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup-states/search-results/search-results.php'));

		// Add stuff to popup states (across popup states)
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popup-states-additions/popup-states-additions.php'));

		// Answer queries from the popup editor
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/ajax/ajax.php'));

		// Shortcodes for replacement content
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/shortcodes/shortcodes.php'));

		// Specific shortcodes
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/shortcodes/call-to-action/call-to-action.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/shortcodes/image/image.php'));
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/shortcodes/info-block/info-block.php'));

		// Cloaking parse and redirection
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/cloaking/cloaking.php'));

		// Localization functions
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/localization/localization.php'));

		// Localization link transformation
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/localization/links/links.php'));

		// Localization specs
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/localization/specs/specs.php'));

		// Popover awesomeness
		require_once(path_join(EASYAZONPRO_PLUGIN_DIRECTORY, 'components/popovers/popovers.php'));
	} else if(defined('EASYAZON_LOADED') && EASYAZON_LOADED) {
		function easyazonpro_easyazon_version_warning() {
			printf('<div id="easyazonpro-easyazon-version-warning" class="error"><p>%s</p></div>', sprintf(__('EasyAzon Pro %s requires at least version %s of EasyAzon. Please upgrade EasyAzon.'), EASYAZONPRO_VERSION, EASYAZONPRO_EASYAZON_VERSION_REQUIRED));
		}
		add_action('admin_notices', 'easyazonpro_easyazon_version_warning');
	} else {
		function easyazonpro_easyazon_installed_warning() {
			printf('<div id="easyazonpro-easyazon-installed-warning" class="error"><p>%s</p></div>', sprintf(__('EasyAzon Pro %s requires EasyAzon. Please install and activate EasyAzon %s.'), EASYAZONPRO_VERSION, EASYAZONPRO_EASYAZON_VERSION_REQUIRED));
		}
		add_action('admin_notices', 'easyazonpro_easyazon_installed_warning');
	}
}
add_action('plugins_loaded', 'easyazonpro_initialization', 11);
