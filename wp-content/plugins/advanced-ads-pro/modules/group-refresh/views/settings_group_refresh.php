<fieldset>
    <label><input type="checkbox" name="advads-groups[<?php echo $group->id; ?>][options][refresh][enabled]" value="1" <?php checked( $enabled, 1 ); ?>><?php _e( 'Enabled', 'advanced-ads-pro' ); ?></label>
    <br>
    <label><input type="number" name="advads-groups[<?php echo $group->id; ?>][options][refresh][interval]" value="<?php echo $interval; ?>"> <?php _e( 'milliseconds', 'advanced-ads-pro' ); ?></label>
</fieldset>
<p class="description"><?php _e( 'Refresh ads on the same spot', 'advanced-ads-pro' ); ?></p>