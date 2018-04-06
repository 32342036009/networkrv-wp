<?php
class Advanced_Ads_Slider_Admin {

	/**
	 * holds base class
	 *
	 * @var Advanced_Ads_Slider_Plugin
	 * @since 1.0.0
	 */
	protected $plugin;

	const PLUGIN_LINK = 'http://wpadvancedads.com/add-ons/slider/';

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$this->plugin = Advanced_Ads_Slider_Plugin::get_instance();

		add_action( 'plugins_loaded', array( $this, 'wp_admin_plugins_loaded' ) );

	}

	/**
	 * load actions and filters
	 */
	public function wp_admin_plugins_loaded(){

		if( ! class_exists( 'Advanced_Ads_Admin', false ) ) {
			// show admin notice
			add_action( 'admin_notices', array( $this, 'missing_plugin_notice' ) );

			return;
		}

		// admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// add group options
		add_action( 'advanced-ads-group-form-options', array( $this, 'group_options' ) );

		// add snippet to overview page
		add_action( 'advanced-ads-admin-overview-before', array($this, 'register_overview_page_widget' ), 10, 2);
	}

	/**
	 * show warning if Advanced Ads js is not activated
	 */
	public function missing_plugin_notice(){
		echo '<div class="error"><p>' . sprintf( __( '<strong>Advanced Ads – Slider</strong> is an extension for the Advanced Ads plugin. Please visit <a href="%s" target="_blank" >wpadvancedads.com</a> to download it for free.', 'slider-ads' ), 'https://wpadvancedads.com' ) . '</p></div>';
	}

	/**
	 * enqueue plugin admin script
	 */
	public function enqueue_admin_scripts() {
		$screen = get_current_screen();

		if( 'advanced-ads_page_advanced-ads-groups' === $screen->id ){
		    wp_enqueue_script( 'advads-slider' . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array('jquery'), AAS_VERSION );
		}
	}

	/**
	 * render group options for slider
	 *
	 * @param obj $group Advanced_Ads_Group
	 */
	public function group_options( Advanced_Ads_Group $group ){

		$delay = isset( $group->options['slider']['delay'] ) ? absint( $group->options['slider']['delay'] ) : 2000;
		$random = isset( $group->options['slider']['random'] ) ? $group->options['slider']['random'] : false;

		include AAS_BASE_PATH . 'admin/views/group-options.php';
	}

	/**
	* update the widget on the overview page
	*
	* @since 1.0.3
	*/
       public function register_overview_page_widget(){
	   global $wp_meta_boxes;

	   // change the callback of the widget
	   $wp_meta_boxes['toplevel_page_advanced-ads']['side']['high']['advads_overview_addon_slider']['callback'][0] = 'Advanced_Ads_Slider_Admin';
	   $wp_meta_boxes['toplevel_page_advanced-ads']['side']['high']['advads_overview_addon_slider']['callback'][1] = 'render_overview_widget';
       }

       /**
	* render infos on overview page
	*
	* @since 1.1.3
	*/
       public static function render_overview_widget(){
	   require_once( 'views/overview.php' );
       }
}
