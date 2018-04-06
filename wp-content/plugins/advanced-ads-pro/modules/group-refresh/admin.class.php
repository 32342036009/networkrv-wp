<?php
class Advanced_Ads_Pro_Group_Refresh_Admin {

	public function __construct() {
		$options = Advanced_Ads_Pro::get_instance()->get_options();

		if ( empty( $options['cache-busting']['enabled'] ) ) {
			return ;
		}
		
		add_action( 'advanced-ads-group-form-options', array( $this, 'add_group_refresh_options' ) );
	}

	/**
	 * Render group refresh options
	 *
	 * @param obj $group Advanced_Ads_Group
	 */
	public function add_group_refresh_options( Advanced_Ads_Group $group ) {
		//$show = in_array( $group->type, array( 'default', 'ordered' ) );
		$enabled = ! empty( $group->options['refresh']['enabled'] ) ? 1 : 0;
		$interval = ! empty( $group->options['refresh']['interval'] ) ? absint( $group->options['refresh']['interval'] ) : 2000;
		
		ob_start();
		include dirname( __FILE__ ) . '/views/settings_group_refresh.php';
		$option_content = ob_get_clean();
		
		if( class_exists( 'Advanced_Ads_Admin_Options' ) ){
			Advanced_Ads_Admin_Options::render_option( 
			    'group-pro-refresh advads-group-type-default advads-group-type-ordered', 
			    __( 'Refresh interval', 'advanced-ads-pro' ),
			    $option_content );
		}
	}
}