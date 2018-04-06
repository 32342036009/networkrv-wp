<?php
/*
 * Template:    Image and Prices
 * Stylesheets: basic.css, image.css
 * Scripts:     popover.js
 */
?>

<?php
$price_l = isset($item['attributes']['ListPrice']) ? $item['attributes']['ListPrice'] : false;
$price_c = isset($item['offer']) && isset($item['offer']['price']) ? $item['offer']['price'] : false;
$price_l_numeric = floatval(preg_replace('#[^\d.]#', '', $price_l));
$price_c_numeric = floatval(preg_replace('#[^\d.]#', '', $price_c));
?>

<div class="easyazon-block-information">
	<?php if($image_atts) { ?>
	<div class="easyazon-block-image-container">
		<?php printf('<a %s><img %s /></a>', easyazon_collapse_attributes($link_atts), easyazon_collapse_attributes($image_atts)); ?>
	</div>
	<?php } ?>

	<div class="easyazon-block-information-title"><?php printf('<a %s>%s</a>', easyazon_collapse_attributes($link_atts), esc_html($item['title'])); ?></div>

	<div class="easyazon-block-information-prices">
		<?php if($price_l && (false === $price_c || $price_l_numeric >= $price_c_numeric)) { ?>
		<div class="easyazon-block-information-price"><?php printf('<td class="easyazon-block-information-price-label">%s:</td> <td class="easyazon-block-information-price-value"><a %s>%s</a></td>', __('List Price'), easyazon_collapse_attributes($link_atts), esc_html($price_l)); ?></div>
		<?php } ?>

		<?php if($price_c && 'N/A' !== $price_c) { ?>
		<div class="easyazon-block-information-price"><?php printf('<td class="easyazon-block-information-price-label">%s:</td> <td class="easyazon-block-information-price-value"><a %s>%s</a></td>', __('Price'), easyazon_collapse_attributes($link_atts), esc_html($price_c)); ?></div>
		<?php } ?>

		<?php if($item['offer'] && $item['offer']['saved'] && 'N/A' !== $item['offer']['saved']) { ?>
		<div class="easyazon-block-information-price"><?php printf('<td class="easyazon-block-information-price-label">%s:</td> <td class="easyazon-block-information-price-value"><a %s>%s</a></td>', __('You Save'), easyazon_collapse_attributes($link_atts), esc_html($item['offer']['saved'])); ?></div>
		<?php } ?>
	</div>

	<?php if($cta_atts) { ?>
	<div class="easyazon-block-information-cta">
		<?php $cta_atts['class'][] = 'easyazon-block-cta'; ?>
		<?php printf('<a %s><img %s /></a>', easyazon_collapse_attributes($link_atts), easyazon_collapse_attributes($cta_atts)); ?>
	</div>
	<?php } ?>

	<div class="easyazon-block-information-price-disclaimer">
		<small class="easyazon-price-disclaimer" data-content="<?php printf(__('Prices are accurate as of %1$s. Product prices and availability are subject to change. Any price and availablility information displayed on Amazon at the time of purchase will apply to the purchase of any products.'), date('F j, Y \a\t g:i A', $item['fetched'] + get_option('gmt_offset') * HOUR_IN_SECONDS)); ?>"><?php _e('Price Disclaimer'); ?></small>
	</div>
</div>
