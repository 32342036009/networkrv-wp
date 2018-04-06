<?php
/**
 * Advanced Ads Dummy Ad Type
 *
 * @package   Advanced_Ads
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2014-2017 Thomas Maier, webgilde GmbH
 * @since     1.8
 *
 * Class containing information about the dummy ad type
 *
 */
class Advanced_Ads_Ad_Type_Dummy extends Advanced_Ads_Ad_Type_Abstract{

	/**
	 * ID - internal type of the ad type
	 */
	public $ID = 'dummy';

	/**
	 * set basic attributes
	 */
	public function __construct() {
		$this->title = __( 'Dummy', 'advanced-ads' );
		$this->description = __( 'Uses a simple placeholder ad for quick testing.', 'advanced-ads' );
		
	}

	/**
	 * output for the ad parameters metabox
	 *
	 * this will be loaded using ajax when changing the ad type radio buttons
	 * echo the output right away here
	 * name parameters must be in the "advanced_ads" array
	 *
	 * @param obj $ad ad object
	 */
	public function render_parameters( $ad ){
	    
		?><img src="<?php echo ADVADS_BASE_URL ?>/public/assets/img/dummy.png" width="300" height="250"/><?php
		
		// don’t show url field if tracking plugin enabled
		if( ! defined( 'AAT_VERSION' )) :
		    $url = ( ! empty( $ad->url ) ) ? esc_url( $ad->url ) : ADVADS_URL;
		    ?><span class="label"><?php _e( 'url', 'advanced-ads' ); ?></span>
		    <div><input type="url" name="advanced_ad[url]" id="advads-url" value="<?php echo $url; ?>"/></div>
		<?php endif;
		
		?><input type="hidden" name="advanced_ad[width]" value="300"/>
		<input type="hidden" name="advanced_ad[height]" value="250"/><?php
	}
	
	/**
	 * prepare the ads frontend output
	 *
	 * @param obj $ad ad object
	 * @return str static image content
	 */
	public function prepare_output($ad){
	    
		$url =	    ( isset( $ad->url ) ) ? esc_url( $ad->url ) : '';

		ob_start();
		if( ! defined( 'AAT_VERSION' ) && $url ){ echo '<a href="'. $url .'">'; }
		echo '<img src="' . ADVADS_BASE_URL . '/public/assets/img/dummy.png" width="300" height="250"/>';
		if( ! defined( 'AAT_VERSION' ) && $url ){ echo '</a>'; }

		return ob_get_clean();

	}

}
