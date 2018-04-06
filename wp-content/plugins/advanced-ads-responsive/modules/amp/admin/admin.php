<?php
defined( 'WPINC' ) || exit;

class Advanced_Ads_Responsive_Amp_Admin {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'wp_admin_plugins_loaded' ) );
	}

	/**
	 * Load actions and filters.
	 */
	public function wp_admin_plugins_loaded() {
		if ( ! class_exists( 'Advanced_Ads', false ) ) { return; }

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 9 );
		add_filter( 'advanced-ads-save-options', array( $this, 'save_ad_options' ) );
		add_filter( 'advanced-ads-ad-notices', array($this, 'ad_notices'), 10, 3 );
	}

	/**
	 * Enqueue admin-specific JavaScript.
	 */
	public function enqueue_admin_scripts() {
		if ( Advanced_Ads_Admin::screen_belongs_to_advanced_ads() ) {
			$uriRelPath = plugin_dir_url( __FILE__ );
		    wp_enqueue_script( ADVADS_SLUG . '-amp-admin', $uriRelPath . 'assets/admin.js', array( 'jquery' ), AAR_VERSION );
		}
	}

	/**
	 * Show warning if a non-AMP compatible option is selected.
	 *
	 * @param array $extra_params, array of extra parameters
	 * @param $content, the ad content object
	 */
	public function ad_notices( $notices, $box, $post ) {
		if ( function_exists( 'is_amp_endpoint' ) || function_exists( 'is_wp_amp' ) ) {
			switch ($box['id']){
				case 'ad-parameters-box' :
					// add warning if this is an non-AMP compatible AdSense ad
					// hidden by default and made visible with JS
					$types = array( __( 'Normal', 'advanced-ads-responsive' ), __( 'Responsive', 'advanced-ads-responsive' ) . ': ' . __( 'advanced', 'advanced-ads-responsive') );
					$notices[] = array(
						'text' => sprintf(__( '%s types will be automatically converted into the correct format on AMP pages. Other types will be removed from AMP pages.', 'advanced-ads-responsive' ), '&#8220;' . implode( '&#8221;, &#8220;', $types ) . '&#8221;' ),
						'class' => 'advanced-ads-adsense-amp-warning hidden',
					);
				    break;
			}
		}
	    
		return $notices;
			
	}

	/**
	 * Sanitize and save ad options.
	 *
	 * @param arr $options
	 * @return arr $options
	 */
	public function save_ad_options( array $options ) {
		$attributes = isset( $_POST['advanced_ad']['amp']['attributes'] ) ? array_values( $_POST['advanced_ad']['amp']['attributes']  ) : array();
		$data = isset( $_POST['advanced_ad']['amp']['data'] ) ? array_values( $_POST['advanced_ad']['amp']['data'] ) : array();

		unset( $options['amp']['attributes'], $options['amp']['data'] );

		if ( is_array( $attributes ) && is_array( $data ) && count( $attributes ) === count( $data ) ) {
			foreach ( $attributes as $_i => $_attribute ) {
				$clear_attribute = sanitize_key( $_attribute );
				$clear_data = isset( $data[ $_i ] ) ? $data[ $_i ] : '';

				if ( $clear_attribute && $clear_data ) {
					$options['amp']['attributes'][ $clear_attribute ] = $clear_data;
				}
			}
		}

		if ( ! empty( $_POST['advanced_ad']['amp']['fallback'] ) ) {
			$options['amp']['fallback'] = wp_kses_post( $_POST['advanced_ad']['amp']['fallback'] );
		}

		return $options;
	}

	/**
	 * callback to display the AMP display condition
	 *
	 * @param arr $options options of the condition
	 * @param int $index index of the condition
	 */
	public static function metabox_amp( $options, $index = 0 ) {
		if ( ! isset ( $options['type'] ) || '' === $options['type'] ) { return; }

		$type_options = Advanced_Ads_Display_Conditions::get_instance()->conditions;

		if ( ! isset( $type_options[ $options['type'] ] ) ) {
			return;
		}

		// form name basis
		$name = Advanced_Ads_Display_Conditions::FORM_NAME . '[' . $index . ']';

		// options
		$operator = isset( $options['operator'] ) ? $options['operator'] : 'is';

		?><input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $options['type']; ?>"/>
		<select name="<?php echo $name; ?>[operator]">
			<option value="is" <?php selected( 'is', $operator ); ?>><?php _e( 'is', 'advanced-ads-responsive' ); ?></option>
			<option value="is_not" <?php selected( 'is_not', $operator ); ?>><?php _e( 'is not', 'advanced-ads-responsive' ); ?></option>
		</select>
		<p class="description"><?php echo $type_options[ $options['type'] ]['description']; ?></p><?php
	}

}



