<label>
	<p class="description"><?php _e( 'Choose whether to open programatically created links in the same window (no <code>target="_blank"</code>) or not by default', 'advanced-ads-tracking' ); ?></p>
	<input name="<?php echo $this->plugin->options_slug; ?>[target]" type="radio" value="0" <?php
    checked( 0, $target); ?>/><?php _e( 'open in the same window', 'advanced-ads-tracking' ); ?></label>
	<br/>
	<input name="<?php echo $this->plugin->options_slug; ?>[target]" type="radio" value="1" <?php
    checked( 1, $target); ?>/><?php _e( 'open in a new window', 'advanced-ads-tracking' ); ?></label>
	<br/>