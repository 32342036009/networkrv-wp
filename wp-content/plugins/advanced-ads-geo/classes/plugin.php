<?php
/*
* load common and WordPress based resources
*
* @since 1.0.0
*/
class Advanced_Ads_Geo_Plugin {

    /**
     *
     * @var Advanced_Ads_Geo_Plugin
     */
    protected static $instance;

    /**
     * plugin options
     *
     * @var     array (if loaded)
     */
    protected $options = false;

    /**
     * name of options in db
     *
     * @car     string
     */
    public $options_slug;


    private function __construct() {
	add_action( 'plugins_loaded', array( $this, 'wp_plugins_loaded' ) );
    }

    /**
    *
    * @return Advanced_Ads_Geo_Plugin
    */
    public static function get_instance() {
	// If the single instance hasn't been set, set it now.
	if ( null === self::$instance ) {
	    self::$instance = new self;
	}

	return self::$instance;
    }

    /**
    * load actions and filters
    *
    * @todo include more of the hooks used in public and admin class
    */
    public function wp_plugins_loaded() {
	// stop, if main plugin doesn’t exist
	if ( ! class_exists( 'Advanced_Ads', false ) ) {
	    return ;
	}
	
	$this->load_plugin_textdomain();

	// add new visitor condition
	add_filter( 'advanced-ads-visitor-conditions', array( $this, 'visitor_conditions' ) );
	$this->options_slug =  AAGT_SLUG;

	// register plugin for auto updates
	if( is_admin() ){
	    add_filter( 'advanced-ads-add-ons', array( $this, 'register_auto_updater' ), 10 );
	}
    }
    
    /**
    * Load the plugin text domain for translation.
    *
    * @since    1.0.0
    */
    public function load_plugin_textdomain() {
	   // $locale = apply_filters('advanced-ads-plugin-locale', get_locale(), $domain);
	   load_plugin_textdomain( AAGT_SLUG, false, AAGT_BASE_DIR . '/languages' );
    }

    /**
    * load advanced ads settings
    */
    public function options(){
	// don’t initiate if main plugin not loaded
	if(!class_exists('Advanced_Ads')) {
	    return array();
	}

	return Advanced_Ads::get_instance()->options();
    }

    /**
    * add visitor condition
    *
    * @since 1.0.0
    * @param arr $conditions visitor conditions of the main plugin
    * @return arr $conditions new global visitor conditions
    */
    public function visitor_conditions( $conditions ){
	
	add_action( 'wp_ajax_load_visitor_conditions_metabox', array( $this, 'load_visitor_condition' ) );

	$conditions['geo_targeting'] = array(
	    'label' => __( 'geo location', 'advanced-ads-geo' ),
	    'description' => __( 'Display ads based on geo location.', 'advanced-ads-geo' ),
	    'metabox' => array( 'Advanced_Ads_Geo_Admin', 'metabox_geo' ), // callback to generate the visitor condition
	    'check' => array( 'Advanced_Ads_Geo', 'check_geo' ) // callback for frontend check
	);

	return $conditions;
    }

    /**
    * register plugin for the auto updater in the base plugin
    *
    * @param arr $plugins plugin that are already registered for auto updates
    * @return arr $plugins
    */
    public function register_auto_updater( array $plugins = array() ){

	$plugins['geo'] = array(
	    'name' => AAGT_PLUGIN_NAME,
	    'version' => AAGT_VERSION,
	    'path' => AAGT_BASE_PATH . 'advanced-ads-geo.php',
	    'options_slug' => $this->options_slug,
	);
	return $plugins;
    }
}

