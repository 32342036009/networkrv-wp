<p class="description"><?php _e('Choose here whether to track all or no ads by default. You can change this setting individually for each ad on the ad edit page.', 'advanced-ads-tracking'); ?></p>
<label><input name="<?php echo $this->plugin->options_slug; ?>[everything]" type="radio" value="true" <?php
    checked( 'true', $method); ?>/><?php _e('track every ad by default', 'advanced-ads-tracking'); ?></label><br/>
<label><input name="<?php echo $this->plugin->options_slug; ?>[everything]" type="radio" value="false" <?php
    checked( 'false', $method ); ?>/><?php _e('don’t track every ad by default', 'advanced-ads-tracking'); ?></label>