<?php

class Advanced_Ads_Pro_Module_Inject_Content_Admin {

	public function __construct() {
		// add "post content (random)" placement type to select box
		add_filter( 'advanced-ads-placement-types', array( $this, 'add_placement_types' ) );
		// options for custom position placement
		add_action( 'advanced-ads-placement-options-after', array( $this, 'custom_position_placement_options' ), 11, 2 );
		// load frontend picker script
		add_action( 'advanced-ads-placements-list-after', array( $this, 'frontend_picker_script' ) );
		// add minimum length setting for content injection placements
		add_action( 'advanced-ads-placement-options-after-advanced', array( $this, 'minimum_content_length_option' ), 10, 2 );
		// Render setting that allow to prevent injection inside `the_content`
		add_action( 'advanced_ads_render_post_meta_box', array( $this, 'render_post_meta_box' ), 10, 2 );
		// Save setting that allow to prevent injection inside `the_content`.
		add_filter( 'advanced_ads_save_post_meta_box', array( $this, 'save_post_meta_box' ) );
	}

	/**
	 * add new placement types
	 *
	 * @since   1.0.0
	 * @param array $types
	 *
	 * @return array $types
	 */
	public function add_placement_types($types) {
		// ad injection on random position
		$types['post_content_random'] = array(
			'title' => __( 'Random Paragraph', 'advanced-ads-pro' ),
			'description' => __( 'After a random paragraph in the main content.', 'advanced-ads-pro' ),
			'image' => AAP_BASE_URL . 'modules/inject-content/assets/img/content-random.png',
			'options' => array( 'show_position' => true )
		);
		// ad injection above the post headline
		$types['post_above_headline'] = array(
			'title' => __( 'Above Headline', 'advanced-ads-pro' ),
			'description' => __( 'Above the main headline on the page (&lt;h1&gt;).', 'advanced-ads-pro' ),
			'image' => AAP_BASE_URL . 'modules/inject-content/assets/img/content-above-headline.png',
			'options' => array( 'show_position' => true )
		);
		// ad injection in the middle of a post
		$types['post_content_middle'] = array(
			'title' => __( 'Content Middle', 'advanced-ads-pro' ),
			'description' => __( 'In the middle of the main content based on the number of paragraphs.', 'advanced-ads-pro' ),
			'image' => AAP_BASE_URL . 'modules/inject-content/assets/img/content-middle.png',
			'options' => array( 'show_position' => true )
		);
		// ad injection at a hand selected element in the frontend
		$types['custom_position'] = array(
			'title' => __( 'Custom Position', 'advanced-ads-pro' ),
			'description' => __( 'Attach the ad to any element in the frontend.', 'advanced-ads-pro' ),
			'image' => AAP_BASE_URL . 'modules/inject-content/assets/img/custom-position.png',
			'options' => array( 'show_position' => true )
		);
		// ad injection at a hand selected element in the frontend
		$types['archive_pages'] = array(
			'title' => __( 'Post Lists', 'advanced-ads-pro' ),
			'description' => __( 'Display the ad between posts on post lists, e.g. home, archives, search etc.', 'advanced-ads-pro' ),
			'image' => AAP_BASE_URL . 'modules/inject-content/assets/img/post-list.png',
			'options' => array( 'show_position' => true )
		);
		return $types;
	}

	/**
	 * render custom position placement options
	 *
	 * @since 1.1.2
	 * @param string $placement_slug id of the placement
	 *
	 */
	public function custom_position_placement_options( $placement_slug, $placement ){
	    switch ( $placement['type'] ){
		    case 'custom_position' :
			$positions = array(
			    'insertBefore' => __( 'above', 'advanced-ads-pro' ),
			    'prependTo' => __( 'inside, before other content', 'advanced-ads-pro' ),
			    'appendTo' => __( 'inside, after other content', 'advanced-ads-pro' ),
			    'insertAfter' => __( 'below', 'advanced-ads-pro' )
			);
			$curr_position = isset($placement['options']['pro_custom_position']) ? $placement['options']['pro_custom_position'] : '';
			$inject_by = isset( $placement['options']['inject_by'] ) ? $placement['options']['inject_by'] : 'pro_custom_element';
			$container_id = ! empty( $placement['options']['container_id'] ) ? $placement['options']['container_id'] :  '#c' . md5( $placement_slug );
			ob_start(); ?>
			<div id="advads-frontend-element-<?php echo $placement_slug; ?>">
				    <fieldset><legend>
					<label><input type="radio" name="advads[placements][<?php echo $placement_slug; ?>][options][inject_by]" value="pro_custom_element" <?php 
					checked( $inject_by, 'pro_custom_element' ); ?>><?php _e( 'by existing element', 'advanced-ads-pro' ); ?></label>
				</legend>
					<p class="description"><?php _e( 'Place ads in relation to an existing element in the frontend.', 'advanced-ads-pro' ); ?></p>
					<button style="display:none; color: red;" type="button" class="advads-deactivate-frontend-picker button"><?php _ex( 'stop selection', 'frontend picker',  'advanced-ads-pro' ); ?></button>
					<button type="button" class="advads-activate-frontend-picker button" data-placementid="<?php echo $placement_slug; ?>"><?php _e( 'select position', 'advanced-ads-pro' ); ?></button>
					<input class="advads-frontend-element" type="text" name="advads[placements][<?php echo $placement_slug; ?>][options][pro_custom_element]" value="<?php
					    echo ( isset( $placement['options']['pro_custom_element'] ) ) ? esc_attr( stripslashes( $placement['options']['pro_custom_element'] ) ) : ''; 
					    ?>" placeholder="<?php _e( 'or enter manually', 'advanced-ads-pro' ); ?>"/>
					<p class="description"><?php _e( 'Uses <a href="https://api.jquery.com/category/selectors/" target="_blank">jQuery selectors</a>, e.g. #container_id, .container_class', 'advanced-ads-pro' ); ?></p>
					<label><?php _e( 'Position', 'advanced-ads-pro' ); ?>
					    <select name="advads[placements][<?php echo $placement_slug; ?>][options][pro_custom_position]">
						<?php foreach( $positions as $_value => $_text ) : ?>
						    <option value="<?php echo $_value; ?>" <?php selected( $_value, $curr_position ); ?>><?php echo $_text; ?></option>
						<?php endforeach; ?>
					    </select>
					</label>
				</fieldset>
				<fieldset>
					<legend><label><input type="radio" name="advads[placements][<?php echo $placement_slug; ?>][options][inject_by]" value="container_id" <?php 
					checked( $inject_by, 'container_id' ); ?>><?php _e( 'by new element', 'advanced-ads-pro' ); ?></label></legend>
					<p class="description"><?php _e( 'Place the following element where the ad should be displayed.', 'advanced-ads-pro' ); ?></p>
					<input type="text" class="advads-wide-input" name="" value="<?php echo esc_attr( sprintf( '<div id="%s"></div>', substr( $container_id, 1 ) ) ); ?>">
					<input type="hidden" name="advads[placements][<?php echo $placement_slug; ?>][options][container_id]" value="<?php 
					echo esc_attr( $container_id ); ?>">
				</fieldset>
			</div><?php
			
			$option_content = ob_get_clean();
			
			if( class_exists( 'Advanced_Ads_Admin_Options' ) ){
				Advanced_Ads_Admin_Options::render_option( 
					'placement-custom-position', 
					__( 'position', 'advanced-ads-pro' ),
					$option_content );
			}
			
			break;
		    case 'archive_pages' :
			$index = (isset($placement['options']['pro_archive_pages_index'])) ? $placement['options']['pro_archive_pages_index'] : 1;
			$index_option = '<input type="number" name="advads[placements][' . $placement_slug . '][options][pro_archive_pages_index]" value="'
			    . $index . '" name="advads-placements-archive-pages-index' . $placement_slug . '"/>';
			$option_content = sprintf(__( 'Inject before %s. post', 'advanced-ads-pro' ), $index_option );
			
			$description = __( 'Before which post to inject the ad on post lists.', 'advanced-ads-pro' );
			if( class_exists( 'Advanced_Ads_Admin_Options' ) ){
				Advanced_Ads_Admin_Options::render_option( 
					'placement-background-color', 
					__( 'position', 'advanced-ads-pro' ),
					$option_content,
					$description );
			}
			
			/*$curr_type = isset($placement['options']['pro_archive_pages_type']) ? $placement['options']['pro_archive_pages_type'] : '';
			$types = array(
			    'container' => __( 'post container', 'advanced-ads-pro' ),
			    'content' => __( 'post content', 'advanced-ads-pro' ),
			); ?><select name="advads[placements][<?php echo $placement_slug; ?>][options][pro_archive_pages_type]">
				<?php foreach( $types as $_value => $_text ) : ?>
				    <option value="<?php echo $_value; ?>" <?php selected( $_value, $curr_type); ?>><?php echo $_text; ?></option>
				<?php endforeach; ?>
			    </select>*/
			break;
	    }
	}

	/**
	 * render minimum content length option for content injection placements
	 *
	 * @since 1.2.3
	 * @param string $placement_slug id of the placement
	 *
	 */
	public function minimum_content_length_option( $placement_slug, $placement ){
	    switch ( $placement['type'] ){
		    case 'post_top' :
		    case 'post_bottom' :
		    case 'post_content' :
		    case 'post_content_random' :
		    case 'post_content_middle' :
			    $options = Advanced_Ads_Pro::get_instance()->get_options();
			    $minimum_length = ( isset( $placement['options']['pro_minimum_length'] ) && $placement['options']['pro_minimum_length'] > 0 ) ? $placement['options']['pro_minimum_length'] : '';
			    $option_content = '<input type="number" name="advads[placements][' . $placement_slug . '][options][pro_minimum_length]" size="4" value="'. $minimum_length . '" name="advads-placement-minimum-content-length-'. $placement_slug .'"/>';
			    $description = __( 'Minimum length of content (in words) before automatically injected ads are allowed in them. Leave empty to use default setting. Default settings will also be used if they are lower.', 'advanced-ads-pro' );
			    
			    if( class_exists( 'Advanced_Ads_Admin_Options' ) ){
				Advanced_Ads_Admin_Options::render_option( 
					'placement-content-minimum-length',
					__( 'minimum content length', 'advanced-ads-pro' ),
					$option_content,
					$description );
			    }			    
		    break;
	    }
	}

	/**
	 * load frontend picker javascript
	 *
	 * @since 1.1.2
	 * @param arr $placements active placements
	 */
	public function frontend_picker_script( $placements ){
		?><script>jQuery( document ).ready( function(){
				// set element from frontend into placement input field
				if( localStorage.getItem( 'advads_frontend_element' )){
					var id = 'advads-frontend-element-' + localStorage.getItem( 'advads_frontend_picker' );
					jQuery( '[id="' + id + '"]' ).find( '.advads-frontend-element' ).val( localStorage.getItem( 'advads_frontend_element' ) );

					localStorage.removeItem( 'advads_frontend_element' );
					localStorage.removeItem( 'advads_frontend_picker' );
					localStorage.removeItem( 'advads_prev_url' );
				}
				jQuery('.advads-activate-frontend-picker').click(function( e ){
					localStorage.setItem( 'advads_frontend_picker', this.getAttribute('data-placementid') );
					localStorage.setItem( 'advads_prev_url', window.location );
					window.location = "<?php echo home_url(); ?>";
				});
				// allow to deactivate frontend picker
				if ( localStorage.getItem( 'advads_frontend_picker' ) ) {
					var id = 'advads-frontend-element-' + localStorage.getItem( 'advads_frontend_picker' );
					jQuery( '[id="' + id + '"]' ).find( '.advads-deactivate-frontend-picker' ).show();
				}
				jQuery( '.advads-deactivate-frontend-picker' ).click( function( e ) {
					localStorage.removeItem( 'advads_frontend_element' );
					localStorage.removeItem( 'advads_frontend_picker' );
					localStorage.removeItem( 'advads_prev_url' );
					jQuery('.advads-deactivate-frontend-picker').hide();
				});
			});
		</script><?php
	}

	/**
	* Render setting that allow to prevent injection inside `the_content`.
	*
	* @param WP_Post $post The post object.
	* @param mixed $values existing values from database
	*/
	public function render_post_meta_box( $post, $values ) {
		require plugin_dir_path(__FILE__) . '/views/setting_post_meta_box.php';
	}

	/**
	* Sanitize and save setting that allow to prevent injection inside `the_content`.
	*
	* @param array $_data data sent by user
	* @return $_data sanitized data
	*/
	public function save_post_meta_box( $_data = array() ) {
		$_data['disable_the_content'] = isset( $_POST['advanced_ads']['disable_the_content'] ) ? absint( $_POST['advanced_ads']['disable_the_content'] ) : 0;

		return $_data;
	}

}
