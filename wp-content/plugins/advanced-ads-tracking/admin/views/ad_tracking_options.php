<div class="advads-option-list" style="<?php echo $params_display; ?>">
    <span class="label"><?php _e('tracking', 'advanced-ads-tracking'); ?></span>
    <div>
	<label><input name="advanced_ad[tracking][enabled]" type="radio" value="default" <?php checked($enabled, 'default'); ?>/><?php _e('default', 'advanced-ads-tracking'); ?></label>
	<label><input name="advanced_ad[tracking][enabled]" type="radio" value="enabled" <?php checked($enabled, 'enabled'); ?>/><?php _e('enabled', 'advanced-ads-tracking'); ?></label>
	<label><input name="advanced_ad[tracking][enabled]" type="radio" value="disabled" <?php checked($enabled, 'disabled'); ?>/><?php _e('disabled', 'advanced-ads-tracking'); ?></label>
	<p class="description"><?php printf(__('Please visit the <a href="%s" target="_blank">manual</a> to learn more about click tracking.', 'advanced-ads-tracking'), Advanced_Ads_Tracking_Admin::PLUGIN_LINK); ?></p>
    </div>
    <hr/>
    <span class="label"><?php _e('url', 'advanced-ads-tracking'); ?></span>
    <div>
	<input name="advanced_ad[tracking][link]" style="width:60%;" id="advads-tracking-url" type="text" value="<?php echo $link; ?>"/>
	<p class="description"><?php _e( 'Don’t use this field on JavaScript ad tags (like from Google AdSense). If you are using your own <code>&lt;a&gt;</code> tag, use <code>href="%link%"</code> to insert the tracking link.', 'advanced-ads-tracking' ); ?></p>
	<p class="description"><?php _e( 'You can use <code>[POST_ID]</code>, <code>[POST_SLUG]</code>, <code>[CAT_SLUG]</code> in the url to insert the post ID, post slug or a comma separated list of category slugs into the url.', 'advanced-ads-tracking' ); ?></p>
    </div>
    <hr/>
	<span class="label"><?php _e( 'target window', 'advanced-ads-tracking' ); ?></span>
	<div>
		<label><input name="advanced_ad[tracking][target]" type="radio" value="default" <?php checked($target, 'default'); ?>/><?php _e('default', 'advanced-ads-tracking'); ?></label>
		<label><input name="advanced_ad[tracking][target]" type="radio" value="same" <?php checked($target, 'same'); ?>/><?php _e('same window', 'advanced-ads-tracking'); ?></label>
		<label><input name="advanced_ad[tracking][target]" type="radio" value="new" <?php checked($target, 'new'); ?>/><?php _e('new window', 'advanced-ads-tracking'); ?></label>
		<p class="description"><?php _e( 'Where to open the link (if present).', 'advanced-ads-tracking' ); ?></p>
    </div>
	<hr />
	<span class="label"><?php _e( 'Add “nofollow”', 'advanced-ads-tracking' ); ?></span>
	<div>
		<label><input name="advanced_ad[tracking][nofollow]" type="radio" value="default" <?php checked($nofollow, 'default'); ?>/><?php _e( 'default', 'advanced-ads-tracking' ); ?></label>
		<label><input name="advanced_ad[tracking][nofollow]" type="radio" value="1" <?php checked($nofollow, 1); ?>/><?php _e( 'yes', 'advanced-ads-tracking' ); ?></label>
		<label><input name="advanced_ad[tracking][nofollow]" type="radio" value="0" <?php checked($nofollow, 0); ?>/><?php _e( 'no', 'advanced-ads-tracking' ); ?></label>
		<p class="description"><?php printf( __( 'Add %s to tracking links.', 'advanced-ads-tracking' ), '<code>rel="nofollow"</code>'); ?></p>
    </div>
	<hr />
</div>
<?php $link_error_show = ( $link && strpos( $ad->content, 'href=' ) && ! strpos( $ad->content, '%link%' ) ); ?>
<?php $exchange_link_show = ( $link && ( strpos( $ad->content, '"' . $link . '"' ) || strpos( $ad->content, "'" . $link . "'" ) ) && ! strpos( $ad->content, '%link%' ) ); ?>
<div class="advads-error-message" id="advads-tracking-link-error" <?php if( ! $link_error_show ) { echo 'style="display: none;"'; } ?>>
	<?php _e('Replace the <code>href</code> attribute of your link with <code>%link%</code> in order to track it. E.g. <code>&lt;a href="%link%"&gt;</code>', 'advanced-ads-tracking'); ?>
	<?php if ( $exchange_link_show ) : // show the exchange link only if $link is actually found in the ad content ?>
	.&nbsp;<?php printf( __( 'Click <a href="#" id="%s">here</a> to replace it', 'advanced-ads-tracking' ), 'advads-tracking-link-exchange' ); ?>
	<?php endif; ?>
</div>
