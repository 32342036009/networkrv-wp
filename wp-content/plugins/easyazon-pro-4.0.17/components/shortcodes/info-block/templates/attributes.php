<?php
/*
 * Template:    Attributes
 * Stylesheets: basic.css
 */
?>

<div class="easyazon-block-information">
	<h3 class="easyazon-block-title"><?php printf('<a %s>%s</a>', easyazon_collapse_attributes($link_atts), esc_html($item['title'])); ?></h3>

	<table class="easyazon-block-attributes">
		<tbody>
			<?php foreach($item['attributes'] as $attribute => $attribute_value) { if(is_array($attribute_value)) { continue; } ?>
			<tr class="easyazon-attribute">
				<th class="easyazon-attribute-name" scope="row"><?php echo esc_html(easyazon_get_attribute($attribute)); ?></th>
				<td class="easyazon-attribute-value"><?php echo easyazon_get_attribute_value($attribute, $attribute_value); ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

	<div class="easyazon-block-information-price-disclaimer">
		<small class="easyazon-price-disclaimer" data-content="<?php printf(__('Prices are accurate as of %1$s. Product prices and availability are subject to change. Any price and availablility information displayed on Amazon at the time of purchase will apply to the purchase of any products.'), date('F j, Y \a\t g:i A', $item['fetched'])); ?>"><?php _e('Price Disclaimer'); ?></small>
	</div>
</div>
