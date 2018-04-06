<?php
class Advanced_Ads_Pro_Group_Refresh {

	protected $extra_footer_output = '';

	public function __construct() {
		$options = Advanced_Ads_Pro::get_instance()->get_options();

		if ( empty( $options['cache-busting']['enabled'] ) ) {
			return ;
		}

		$this->is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		if ( ! is_admin() ) {
			add_action( 'wp', array( $this, 'init_fronend' ) );
		} elseif ( $this->is_ajax ) {
			$this->init_group_refresh();
		}
	}

	/**
	 * Init frontend after the `parse_query` hook.
	 * Not ajax, not admin.
	 */
	public function init_fronend() {
		if ( apply_filters( 'advanced-ads-pro-cb-frontend-disable', false ) ) { return; }

		$this->init_group_refresh();
		add_filter( 'advanced-ads-pro-passive-cb-group-data', array( $this, 'after_group_output_passive' ), 10, 3 );
		add_action( 'wp_footer', array( $this, 'output_in_footer' ), 21 );
	}

	/**
	 * Init group refresh
	 */
	private function init_group_refresh() {
		add_filter( 'advanced-ads-group-output-array', array( $this, 'output_group_refresh_markup'), 10, 2 );
		add_filter( 'advanced-ads-ad-select-args', array( $this, 'additional_ad_select_args' ), 10, 2 );
		// manipulate number of ads that should be displayed in a group
		add_filter( 'advanced-ads-group-ad-count', array($this, 'adjust_ad_group_number'), 10, 2 );
		add_filter( 'advanced-ads-output-wrapper-options', array( $this, 'add_wrapper_options' ), 10, 2 );
	}

	/**
	 * output group refresh markup for group. Called only for off, ajax cb.
	 * 
	 * @return array
	 */
	public function output_group_refresh_markup( array $ad_content, Advanced_Ads_Group $group ){
		$settings = self::get_group_refresh_settings( $group );

		if ( count( $ad_content ) > 1 && $settings ) {
			array_unshift( $ad_content, '<div style="visibility:hidden;" class="' . $settings['init_class'] . '">' );
			array_push( $ad_content, '</div>' );

			if ( ! $this->is_ajax ) {
				$this->extra_footer_output .= $settings['script'];
			} else {
				array_push( $ad_content, $settings['script'] );
			}
		}

		return $ad_content;
	}

	/**
	 * add ad select arguments: disable tracking when group refresh is used
	 *
	 * @param array $args
	 * @return array $args
	 */
	public function additional_ad_select_args( $args ) {
		// the first ad will be shown right away, so disable tracking starting from second ad
		if ( ! empty( $args['group_info']['refresh_enabled'] ) && ! empty( $args['group_info']['ads_displayed'] ) ) {
			$args['global_output'] = false;
		}
		return $args;
	}

	/**
	 * adjust the ad group number for group refresh
	 *
	 * @param int $ad_count
	 * @param obj $adgroup Advanced_Ads_Group
	 * @return int $ad_count
	 */
	public function adjust_ad_group_number( $ad_count = 0, Advanced_Ads_Group $adgroup ) {
		if ( ! empty( $adgroup->options['refresh']['enabled'] )
			&& in_array( $adgroup->type , array( 'default', 'ordered' ) )
		) {
			return 'all';
		}

		return $ad_count;
	}	

	/**
	 * Add group refresh markup to passive cache-busting.
	 *
	 * @param arr $group_data
	 * @param obj $group Advanced_Ads_Group
	 * @param string $element_id
	 */
	public function after_group_output_passive( $group_data, Advanced_Ads_Group $group, $element_id ) {
		$settings = self::get_group_refresh_settings( $group );

		if ( $element_id && $settings ) {
			$group_data['group_wrap'][] = array(
				'min_ads' => 2,
				'before' => '<div style="visibility:hidden;" class="' . $settings['init_class'] . '">',
				'after' => '</div>' . $settings['script']
			);
		}

		return $group_data;
	}

	/**
	 * get group refresh settings
	 * 
	 * @return null/array
	 */
	public static function get_group_refresh_settings( Advanced_Ads_Group $group ) {
		if ( ! in_array( $group->type , array( 'default', 'ordered' ) )
			|| empty( $group->options['refresh']['enabled'] )
		) { return; }

		$interval = ! empty( $group->options['refresh']['interval'] ) ? absint( $group->options['refresh']['interval'] ) : 2000;
		$prefix = Advanced_Ads_Plugin::get_instance()->get_frontend_prefix();
		$init_class = $prefix . 'refresh-' . mt_rand();

		$weights = $group->get_ad_weights();

		$js_obj = array(
			'interval' => $interval,
			'weights' => $weights,
			'type' => $group->type
		);

		return array(
			'init_class' => $init_class,
			'script' => '<script>jQuery(function() { jQuery( ".' . $init_class . '" ).advads_group_refresh(' . json_encode( $js_obj ) . '); });</script>'
		);
	}	

	/**
	 * Output data in the footer.
	 */
	public function output_in_footer() {
		echo $this->extra_footer_output;
	}

	/**
	 * Add attributes to wrapper.
	 *
	 * @param arr $wrapper Wrapper options.
	 * @param obj $ad Advanced_Ads_Ad.
	 */
	public function add_wrapper_options( $wrapper = array(), Advanced_Ads_Ad $ad ) {
		if ( ! empty( $ad->args['group_info']['refresh_enabled'] ) ) {
			$wrapper['data-id'] = $ad->id;
		}
		return $wrapper;
	}
}