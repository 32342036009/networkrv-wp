<style type="text/css">
#tracking-ads-box .form-group {
    margin: 8px;
    padding: 6px;
}
#tracking-ads-box .form-group label {
    display: block;
    font-weight: bold;
    margin: 6px 0 8px 0;
}
</style>
<?php if( $warnings ) : ?>
<ul id="tracking-ads-box-notices" class="advads-metabox-notices">
<?php foreach( $warnings as $_warning ) :
	$warning_class = isset( $_warning['class'] ) ? $_warning['class'] : '';
	echo '<li class="'. $warning_class . '">';
	echo $_warning['text'];
	echo '</li>';
endforeach;
endif;
// hide options if Google Analytics tracking method is used
if( 'ga' !== $this->plugin->get_tracking_method() ) :
?></ul>
<div class="advads-option-list">
<?php
    global $post;
    $admin_ad_title = $post->post_title;
?>
	<span class="label"><?php _e( 'admin stats', 'advanced-ads-tracking' ); ?></span>
    <div><a target="blank" href="<?php echo Advanced_Ads_Tracking_Admin::admin_30days_stats_url( $post->ID ); ?>"><?php _e( 'Show statistics for this ad', 'advanced-ads-tracking' ); ?></a></div>
    <hr />
	<span class="label"><?php _e( 'limits', 'advanced-ads-tracking' ); ?></span>
	<div>
    <table id="advads-ad-stats" class="table widefat">
	<thead>
	    <tr class="alternate">
		<th></th>
		<th><strong><?php _e( 'current', 'advanced-ads-tracking' ); ?></strong></th>
		<th><strong><?php _e( 'limit', 'advanced-ads-tracking' ); ?></strong></th>
	    </tr>
	</thead>
	<tbody>
	    <tr>
		<th><strong><?php _e( 'impressions', 'advanced-ads-tracking' ); ?></strong></th>
		<td><?php echo isset( $sums['impressions'][ $post->ID ] ) ? $sums['impressions'][ $post->ID ] : 0; ?></td>
		<td><input name="advanced_ad[tracking][impression_limit]" type="number" value="<?php echo $impression_limit; ?>"/></td>
	    </tr>
	    <tr class="advads-tracking-click-limit-row" style="<?php echo $clicks_display; ?>">
		<th><strong><?php _e( 'clicks', 'advanced-ads-tracking' ); ?></strong></th>
		<td><?php echo isset( $sums['clicks'][ $post->ID ] ) ? $sums['clicks'][ $post->ID ] : 0; ?></td>
		<td><input name="advanced_ad[tracking][click_limit]" type="number" value="<?php echo $click_limit; ?>"/></td>
	    </tr>
	</tbody>
    </table>
    <p class="description"><?php _e('Set a limit if you want to expire the ad after a specific amount of impressions or clicks.', 'advanced-ads-tracking'); ?></p>
	</div>
	<hr />
	<span class="label"><?php _e( 'Link to public stats', 'advanced-ads-tracking' ); ?></span>
    <?php if ( $public_id ) : ?>
    <?php $public_link = site_url( '/' . $public_stats_slug .'/' . $public_id . '/' ); ?>
    <div>
		<input type="hidden" name="advanced_ad[tracking][public-id]" value="<?php echo esc_attr( $public_id ); ?>" />
		<a href="<?php echo esc_url( $public_link ); ?>" target="_blank"><?php echo $public_link; ?></a>
	</div>
	<hr />
    <span class="label"><?php _e( 'Public name', 'advanced-ads-tracking' ); ?></span>
	<div>
        <input type="text" name="advanced_ad[tracking][public-name]" value="<?php echo $public_name; ?>" />
        <p class="description"><?php _e( 'Will be used as ad name instead of the internal ad title', 'advanced-ads-tracking' ); 
        ?>&nbsp;<?php echo ( ! empty( $admin_ad_title ) )? '(' . $admin_ad_title .')' : '' ; ?></p>
    </div>
    <?php else : ?>
	<div>
    <div class="update-nag"><p><?php _e( 'The public stats url for this ad will be generated the next time it is saved.', 'advanced-ads-tracking' ); ?></p></div>
	</div>
    <input type="hidden" name="advanced_ad[tracking][public-id]" value="<?php echo wp_generate_password( $hash_length, false ); ?>" />
    <?php endif; ?>
	<hr />
	<span class="label"><?php _e( 'report recipient', 'advanced-ads-tracking' ); ?></span>
	<div>
		<?php if ( $billing_email ) : ?>
		<input type="hidden" name="advanced_ad[tracking][report-recip]" value="" />
		<input type="text" style="width:66%;" disabled value="<?php echo esc_attr( $billing_email ); ?>"/>
		<?php else : ?>
		<input type="text" style="width:66%;" name="advanced_ad[tracking][report-recip]" value="<?php echo esc_attr( $report_recip ); ?>" />
		<?php endif; ?>
		<p class="description"><?php _e( 'Email address to send the performance report for this ad', 'advanced-ads-tracking' ); ?></p>
	</div>
	<hr>
	<span class="label"><?php _e( 'report period', 'advanced-ads-tracking' ); ?></span>
	<div>
		<select name="advanced_ad[tracking][report-period]">
			<option value="last30days" <?php selected( $report_period, 'last30days' ); ?>><?php _e( 'last 30 days', 'advanced-ads-tracking' ); ?></option>
			<option value="lastmonth" <?php selected( $report_period, 'lastmonth' ); ?>><?php _e( 'last month', 'advanced-ads-tracking' ); ?></option>
			<option value="last12months" <?php selected( $report_period, 'last12months' ); ?>><?php _e( 'last 12 months', 'advanced-ads-tracking' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Period used to calculate the stats for the report', 'advanced-ads-tracking' ); ?></p>
	</div>
	<hr>
	<span class="label"><?php _e( 'report frequency', 'advanced-ads-tracking' ); ?></span>
	<div>
		<select name="advanced_ad[tracking][report-frequency]">
			<option value="never" <?php selected( $report_frequency, 'never' ); ?>><?php _e( 'never', 'advanced-ads-tracking' ); ?></option>
			<option value="daily" <?php selected( $report_frequency, 'daily' ); ?>><?php _e( 'daily', 'advanced-ads-tracking' ); ?></option>
			<option value="weekly" <?php selected( $report_frequency, 'weekly' ); ?>><?php _e( 'weekly', 'advanced-ads-tracking' ); ?></option>
			<option value="monthly" <?php selected( $report_frequency, 'monthly' ); ?>><?php _e( 'monthly', 'advanced-ads-tracking' ); ?></option>
		</select>
		<p class="description"><?php _e( 'How often to send email reports', 'advanced-ads-tracking' ); ?></p>
	</div>
	<hr>
</div>
<?php endif;