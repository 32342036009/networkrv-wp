<?php if($item['images'] && $item['images'][1]) { ?>
<div class="easyazon-popover-image-container">
	<img alt="<?php echo esc_attr($item['title']); ?>" class="easyazon-popover-image" height="<?php echo esc_attr($item['images'][1]['height']); ?>" src="<?php echo esc_attr($item['images'][1]['url']); ?>" width="<?php echo esc_attr($item['images'][1]['width']); ?>" />
</div>
<?php } ?>

<?php
$price_l = isset($item['attributes']['ListPrice']) ? $item['attributes']['ListPrice'] : false;
$price_c = isset($item['offer']) && isset($item['offer']['price']) ? $item['offer']['price'] : false;
$price_s = isset($item['offer']) && isset($item['offer']['saved']) ? $item['offer']['saved'] : false;
$price_l_numeric = floatval(preg_replace('#[^\d.]#', '', $price_l));
$price_c_numeric = floatval(preg_replace('#[^\d.]#', '', $price_s));
$price_s_numeric = floatval(preg_replace('#[^\d.]#', '', $price_s));
?>

<div class="easyazon-popover-information">
	<div class="easyazon-popover-information-title">
		<?php printf('<a %s>%s</a>', easyazon_collapse_attributes($link_atts), esc_html($item['title'])); ?>
	</div>

	<?php if($price_l && (false === $price_c || $price_l_numeric >= $price_c_numeric)) { ?>
	<div class="easyazon-popover-information-price">
		<?php printf('<strong>%s:</strong> <a %s>%s</a>', __('List Price'), easyazon_collapse_attributes($link_atts), esc_html($price_l)); ?>
	</div>
	<?php } ?>

	<?php if($price_c && is_numeric($price_c_numeric)) { ?>
	<div class="easyazon-popover-information-price">
		<?php printf('<strong>%s:</strong> <a %s>%s</a>', __('Current Price'), easyazon_collapse_attributes($link_atts), esc_html($price_c)); ?>
	</div>
	<?php } ?>

	<?php if($price_s && is_numeric($price_s_numeric)) { ?>
	<div class="easyazon-popover-information-price">
		<?php printf('<strong>%s:</strong> <a %s>%s</a>', __('Saved'), easyazon_collapse_attributes($link_atts), esc_html($price_s)); ?>
	</div>
	<?php } ?>

	<?php if($button) { ?>
	<div class="easyazon-popover-information-button">
		<?php printf('<a %s><img alt="%s" height="%s" src="%s" width="%s" /></a>', easyazon_collapse_attributes($link_atts), esc_attr($item['title']), esc_attr($button['height']), esc_attr($button['url']), esc_attr($button['width'])); ?>
	</div>
	<?php } ?>

	<?php if(isset($item['fetched'])) { ?>
	<div class="easyazon-popover-information-disclaimer">
		<?php printf(__('Prices are accurate as of %s'), get_date_from_gmt(gmdate('Y-m-d H:i:s', $item['fetched']), get_option('date_format') . ' ' . get_option('time_format'))); ?>
	</div>
	<?php } ?>
</div>