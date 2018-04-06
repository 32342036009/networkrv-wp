<div class="wrap">
	<h2>
		<?php _e('Localized Products'); ?>
		<a class="add-new-h2" href="<?php echo esc_attr(esc_url(add_query_arg('id', '0', $page_url))); ?>"><?php _e('Add New'); ?></a>
	</h2>

	<?php settings_errors(); ?>

	<p><strong><?php _e('Please Note:'); ?></strong> <?php _e('You can hover over any identifier to see the full product name.'); ?></p>

	<form action="<?php echo esc_attr(esc_url($page_url)); ?>" method="get">
		<input type="hidden" name="page" value="<?php echo esc_attr(EASYAZONPRO_LOCALIZATION_PAGE); ?>" />

		<div class="tablenav top">
			<div class="tablenav-pages">
				<span class="displaying-num"><?php printf(_n('%d localization', '%d localizations', count($localizations)), count($localizations)); ?></span>
				<?php if($pages > 1) { ?>
				<span class="pagination-links">
					<a class="first-page <?php if(1 == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the first page'); ?>" href="<?php echo esc_attr(esc_url($page_url)); ?>">&laquo;</a>
					<a class="prev-page <?php if(1 == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the previous page'); ?>" href="<?php echo esc_attr(esc_url(add_query_arg('paged', max(1, $page - 1), $page_url))); ?>">&lsaquo;</a>
					<span class="paging-input">
						<label for="current-page-selector" class="screen-reader-text"><?php _e('Select Page'); ?></label>
						<input class="current-page" id="current-page-selector" title="<?php _e('Current page'); ?>" type="text" name="paged" size="1" value="<?php echo esc_attr($page); ?>" />
						<?php _e('of'); ?>
						<span class="total-pages"><?php echo esc_html($pages); ?></span>
					</span>
					<a class="next-page <?php if($pages == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the next page'); ?>" href="<?php echo esc_attr(esc_url(add_query_arg('paged', $page + 1, $page_url))); ?>">&rsaquo;</a>
					<a class="last-page <?php if($pages == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the last page'); ?>" href="<?php echo esc_attr(esc_url(add_query_arg('paged', $pages, $page_url))); ?>">&raquo;</a>
				</span>
				<?php } ?>
			</div>
		</div>
	</form>

	<table class="widefat">
		<thead>
			<tr>
				<th rowspan="2"><?php _e('Actions'); ?></th>
				<th scope="col" colspan="<?php echo count($locales); ?>"><?php _e('Products'); ?></th>
			</tr>
			<tr>
				<?php foreach($locales_keys as $locale_key) { ?>
				<th scope="col"><?php printf(__('%s'), esc_html($locale_key)); ?></th>
				<?php } ?>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<th scope="col"><?php _e('Actions'); ?></th>
				<?php foreach($locales_keys as $locale_key) { ?>
				<th scope="col"><?php printf(__('%s'), esc_html($locale_key)); ?></th>
				<?php } ?>
			</tr>
		</tfoot>

		<tbody>
			<?php if(empty($localizations)) { ?>
			<tr>
				<td colspan="<?php echo (count($locales) + 1); ?>"><?php _e('There are currently no items localized'); ?></td>
			</tr>
			<?php } else { ?>

			<?php foreach($localizations as $localization) { ?>

			<tr>
				<td>
					<a href="<?php echo esc_attr(esc_url(add_query_arg('id', $localization['ID'], $page_url))); ?>"><?php _e('Edit'); ?></a> |
					<a href="<?php echo esc_attr(esc_url(wp_nonce_url(add_query_arg('id', $localization['ID'], $page_url), 'easyazonpro-localization-delete', 'easyazonpro-localization-delete-nonce'))); ?>"><?php _e('Delete'); ?></a>
				</td>
				<?php foreach($locales_keys as $locale_key) { ?>
				<td>
					<?php
					if(isset($localization[$locale_key]) && ($item = easyazon_get_item($localization[$locale_key], $locale_key))) {
						printf('<a href="%s" target="_blank" title="%s">%s</a>', esc_attr(esc_url($item['url'])), esc_attr($item['title']), esc_html($localization[$locale_key]));
					} else {
						_e('N/A');
					}
					?>
				</td>
				<?php } ?>
			</tr>

			<?php } ?>

			<?php } ?>
		</tbody>
	</table>

	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="displaying-num"><?php printf(_n('%d localization', '%d localizations', count($localizations)), count($localizations)); ?></span>
			<?php if($pages > 1) { ?>
			<span class="pagination-links">
				<a class="first-page <?php if(1 == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the first page'); ?>" href="<?php echo esc_attr(esc_url($page_url)); ?>">«</a>
				<a class="prev-page <?php if(1 == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the previous page'); ?>" href="<?php echo esc_attr(esc_url(add_query_arg('paged', max(1, $page - 1), $page_url))); ?>">‹</a>
				<span class="paging-input">
					<?php echo esc_html($page); ?>
					<?php _e('of'); ?>
					<span class="total-pages"><?php echo esc_html($pages); ?></span>
				</span>
				<a class="next-page <?php if($pages == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the next page'); ?>" href="<?php echo esc_attr(esc_url(add_query_arg('paged', $page + 1, $page_url))); ?>">›</a>
				<a class="last-page <?php if($pages == $page) { echo 'disabled'; } ?>" title="<?php _e('Go to the last page'); ?>" href="<?php echo esc_attr(esc_url(add_query_arg('paged', $pages, $page_url))); ?>">»</a>
			</span>
			<?php } ?>
		</div>
	</div>
</div>
