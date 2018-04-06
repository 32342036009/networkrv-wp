<div id="advads-cache-busting-check-wrap">
    <span id="advads-cache-busting-error-result" class="advads-error-message" style="display:none;">
        <?php printf(__( 'The code of this ad might not work properly with activated cache-busting. <a href="%s" target="_blank">Manual</a>', 'advanced-ads-pro' ), ADVADS_URL . 'manual/cache-busting/#advads-passive-compatibility-warning' ); ?>
    </span>
	<input type="hidden" id="advads-cache-busting-possibility" name="advanced_ad[cache-busting][possible]" value="true" />
	<!-- this frame should be visible for Google Adsense -->
	<iframe src="about:blank" id="advads-cache-busting-test" width="600" height="100"></iframe>
</div>

<?php $type = ( isset( $types[$ad->type] ) ) ? $types[$ad->type] : current( $types ); ?>
<script>
jQuery( document ).ready(function() {
    var ad_content = <?php echo json_encode( $type->prepare_output( $ad ) ); ?>;
    advads_cb_check_ad_markup( ad_content );
});
</script>