<?php

/**
 * load common and WordPress based resources
 *
 * @since 1.2.0
 */
class Advanced_Ads_Tracking_Plugin {

	/**
	 *
	 * @var Advanced_Ads_Tracking_Plugin
	 */
	protected static $instance;

	/**
	 * plugin options
	 *
	 * @var     array (if loaded)
	 */
	protected $options;

	/**
	 * name of options in db
	 *
	 * @var     string
	 */
	public $options_slug;
	
	/**
	 * array with ad types that use click tracking
	 *  AdSense and AMP are not among them
	 * 
	 * @var	    array
	 */
	public static $types_using_click_tracking = array( 'plain', 'dummy', 'content', 'image', 'flash' );
	

	private function __construct() {
		if ( ! defined( 'ADVADS_SLUG' ) ) {
			return ;
		}
		$this->options_slug =  ADVADS_SLUG . '-tracking';

		// register plugin for auto updates
		// -TODO this is true for any AJAX call
		if( is_admin() ){
			add_filter( 'advanced-ads-add-ons', array( $this, 'register_auto_updater' ), 10 );
		}
	}

	/**
	 *
	 * @return Advanced_Ads_Tracking_Plugin
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * load advanced ads settings
	 */
	public function options() {
		// don't initiate if main plugin not loaded
		if ( ! class_exists( 'Advanced_Ads', false ) ) {
			return false;
		}

		// return options if already loaded
		if ( isset($this->options ) ) {
			return $this->options;
		}

		$this->options = get_option( $this->options_slug, array() );

		// get "old" options
		if ( $this->options === array() ) {
			$old_options = Advanced_Ads_Plugin::get_instance()->options();
			if ( isset( $old_options['tracking'] ) ) {
				$this->options = $old_options['tracking'];
				// save as new options
				$this->update_options($this->options);
			}
		}

		return $this->options;
	}
	
	/**
	 * get the tracking method used in the main options
	 * ignores whether Analytics is enabled with the constant in wp-config.php or not
	 */
	public function get_tracking_method(){
		$plugin_options = $this->options();
		return isset( $plugin_options['method'] ) ? $plugin_options['method'] : false;
	}

	/**
	 * load advanced ads settings
	 */
	public function update_options( array $options ){
		// don’t allow to clear options
		if ( $options === array() ) {
			return;
		}

		$this->options = $options;
		update_option( $this->options_slug, $options );
	}

	/**
	 * register plugin for the auto updater in the base plugin
	 *
	 * @param arr $plugins plugin that are already registered for auto updates
	 * @return arr $plugins
	 */
	public function register_auto_updater( array $plugins = array() ){
		$plugins['tracking'] = array(
			'name' => AAT_PLUGIN_NAME,
			'version' => AAT_VERSION,
			'path' => AAT_BASE_PATH . 'tracking.php',
			'options_slug' => $this->options_slug,
		);

		return $plugins;
	}

	/**
	* check, whether to track a specific ad or not
	*
	* @param obj $ad ad object
	*/
	public function check_ad_tracking_enabled( Advanced_Ads_Ad $ad ) {
		$options = $ad->options();
		$tracking = isset( $options['tracking']['enabled'] ) && $options['tracking']['enabled'] ? $options['tracking']['enabled'] : null;

		// check for default settings
		if ( ! isset( $tracking ) || $tracking == 'default' ) {
			// check get global setting
			$global_options = $this->options();
			if ( is_array($global_options) && ( ! isset( $global_options['everything'] ) || $global_options['everything'] == 'true' ) ) {
				return true;
			}
		};

		if ( isset( $tracking ) ) {
			return $options['tracking']['enabled'] == 'enabled';
		}
	}
}
