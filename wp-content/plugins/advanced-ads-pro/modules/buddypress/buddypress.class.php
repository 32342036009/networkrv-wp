<?php

class Advanced_Ads_Pro_Module_BuddyPress {
    
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'wp_plugins_loaded_ad_actions' ), 20 );
    }
    
    public function wp_plugins_loaded_ad_actions(){
	// stop, if main plugin doesn’t exist
	if ( ! class_exists( 'Advanced_Ads', false ) ) {
            return;
	}
        
        // stop if BuddyPress isn't activated
        if ( ! class_exists( 'BuddyPress', false ) ){
            return;
        }
        
        //dont load new ads on posts added via ajax
        if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX) ){
            return;
        }
    
	// load BuddyPress hooks

	// get placements
	$placements = get_option( 'advads-ads-placements', array() );

	if( is_array( $placements ) ){
	    foreach ( $placements as $_placement_id => $_placement ){
		if ( isset($_placement['type']) && 'buddypress' == $_placement['type'] && isset( $_placement['options']['buddypress_hook'] ) ){
		    $hook = str_replace( ' ', '_', 'bp_' . $_placement['options']['buddypress_hook'] );
		    add_action( $hook, array($this, 'execute_hook') );
		}
	    }
	}
    }
    
    public function execute_hook(){
        // get placements
	$placements = get_option( 'advads-ads-placements', array() );
        // look for the current hook in the placements
	$hook = current_filter();
	if( is_array( $placements ) ){
	    foreach ( $placements as $_placement_id => $_placement ){
		if ( isset($_placement['type'] ) && 'buddypress' == $_placement['type']
		&& isset( $_placement['options']['pro_buddypress_pages_index'] )
		&& isset( $_placement['options']['buddypress_hook'] )
		&& $hook === str_replace( ' ', '_', 'bp_' . $_placement['options']['buddypress_hook'] ) ){
		    if( did_action( $hook ) == $_placement['options']['pro_buddypress_pages_index'] ){
		       the_ad_placement( $_placement_id ); 
		    }
		}
	    }
	}
    }
}

