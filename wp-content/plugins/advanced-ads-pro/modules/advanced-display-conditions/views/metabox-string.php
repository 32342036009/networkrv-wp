<div class="advads-condition-line-wrap">
    <input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $options['type']; ?>"/>
    <select name="<?php echo $name; ?>[operator]">
        <option value="contain" <?php selected( 'contain', $operator ); ?>><?php _e( 'contains', 'advanced-ads' ); ?></option>
        <option value="start" <?php selected( 'start', $operator ); ?>><?php _e( 'starts with', 'advanced-ads' ); ?></option>
        <option value="end" <?php selected( 'end', $operator ); ?>><?php _e( 'ends with', 'advanced-ads' ); ?></option>
        <option value="match" <?php selected( 'match', $operator ); ?>><?php _e( 'matches', 'advanced-ads' ); ?></option>
        <option value="regex" <?php selected( 'regex', $operator ); ?>><?php _e( 'matches regex', 'advanced-ads' ); ?></option>
        <option value="contain_not" <?php selected( 'contain_not', $operator ); ?>><?php _e( 'does not contain', 'advanced-ads' ); ?></option>
        <option value="start_not" <?php selected( 'start_not', $operator ); ?>><?php _e( 'does not start with', 'advanced-ads' ); ?></option>
        <option value="end_not" <?php selected( 'end_not', $operator ); ?>><?php _e( 'does not end with', 'advanced-ads' ); ?></option>
        <option value="match_not" <?php selected( 'match_not', $operator ); ?>><?php _e( 'does not match', 'advanced-ads' ); ?></option>
        <option value="regex_not" <?php selected( 'regex_not', $operator ); ?>><?php _e( 'does not match regex', 'advanced-ads' ); ?></option>
    </select><input type="text" name="<?php echo $name; ?>[value]" value="<?php echo $value; ?>"/>
    <p class="description"><?php echo $type_options[ $options['type'] ]['description']; ?></p>
</div>