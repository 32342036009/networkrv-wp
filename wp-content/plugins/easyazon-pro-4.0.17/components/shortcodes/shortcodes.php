<?php

if(!defined('ABSPATH')) { exit; }

class EasyAzonPro_Components_Shortcodes {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
		add_filter('easyazon_get_url', array(__CLASS__, 'get_url'), 11, 2);
		add_filter('easyazon_block_atts', array(__CLASS__, 'block_atts'), 10, 2);
		add_filter('easyazon_image_atts', array(__CLASS__, 'image_atts'), 10, 2);
		add_filter('easyazon_link_atts', array(__CLASS__, 'link_atts'), 10, 2);
		add_filter('easyazon_shortcode_atts', array(__CLASS__, 'shortcode_atts'), 10, 2);
	}

	#region Shortcode

	public static function shortcode_atts($sanitized, $atts) {
		// Need to turn "add to cart" on / off
		$sanitized['cart'] = isset($atts['cart']) ? $atts['cart'] : false;
		$sanitized['cart'] = ((false === $sanitized['cart']) && isset($atts['add_to_cart'])) ? $atts['add_to_cart'] : $sanitized['cart'];
		$sanitized['cart'] = str_replace(array('yes', 'no'), array('y', 'n'), $sanitized['cart']);
		$sanitized['cart'] = in_array($sanitized['cart'], array('y', 'n')) ? $sanitized['cart'] : easyazon_get_setting('link_cart');

		// Need to turn cloaking on / off
		$sanitized['cloak'] = isset($atts['cloak']) ? $atts['cloak'] : false;
		$sanitized['cloak'] = ((false === $sanitized['cloak']) && isset($atts['cloaking'])) ? $atts['cloaking'] : $sanitized['cloak'];
		$sanitized['cloak'] = str_replace(array('yes', 'no'), array('y', 'n'), $sanitized['cloak']);
		$sanitized['cloak'] = in_array($sanitized['cloak'], array('y', 'n')) ? $sanitized['cloak'] : easyazon_get_setting('link_cloak');

		// Need to turn localization on / off
		$sanitized['localize'] = isset($atts['localize']) ? $atts['localize'] : false;
		$sanitized['localize'] = ((false === $sanitized['localize']) && isset($atts['localization'])) ? $atts['localization'] : $sanitized['localize'];
		$sanitized['localize'] = str_replace(array('yes', 'no'), array('y', 'n'), $sanitized['localize']);
		$sanitized['localize'] = in_array($sanitized['localize'], array('y', 'n')) ? $sanitized['localize'] : easyazon_get_setting('link_localize');

		// Need to turn popups on / off
		$sanitized['popups'] = isset($atts['popups']) ? $atts['popups'] : false;
		$sanitized['popups'] = str_replace(array('yes', 'no'), array('y', 'n'), $sanitized['popups']);
		$sanitized['popups'] = in_array($sanitized['popups'], array('y', 'n')) ? $sanitized['popups'] : easyazon_get_setting('link_popups');

		// Need to allow image attributes
		$sanitized['height'] = isset($atts['height']) ? $atts['height'] : false;
		$sanitized['src']    = isset($atts['src']) ? $atts['src'] : false;
		$sanitized['width']  = isset($atts['width']) ? $atts['width'] : false;

		// Alignment for images, info blocks, and call to action items
		$sanitized['align'] = isset($atts['align']) && in_array(strtolower($atts['align']), array('center', 'left', 'none', 'right')) ? strtolower($atts['align']) : false;

		// Allow keywords attributes
		$sanitized['keywords'] = isset($atts['keywords']) ? trim($atts['keywords']) : false;

		// Allow key for template file for info blocks
		$sanitized['key'] = isset($atts['key']) ? trim($atts['key']) : easyazon_get_setting('infoblock_template');

		// Allow for specifying layout
		$sanitized['layout'] = isset($atts['layout']) ? trim($atts['layout']) : '';

		return $sanitized;
	}

	#endregion Shortcode

	#region Attributes

	public static function block_atts($block_atts, $shortcode_atts) {
		$block_atts['class'] = isset($block_atts['class']) ? (is_array($block_atts['class']) ? $block_atts['class'] : array_filter(array($block_atts['class']))) : array();
		$block_atts['class'] = array_merge($block_atts['class'], array('easyazon-block'));

		if(isset($shortcode_atts['align']) && $shortcode_atts['align']) {
			$block_atts['class'] = array_merge($block_atts['class'], array("align{$shortcode_atts['align']}"));
		}

		if(isset($shortcode_atts['key']) && $shortcode_atts['key']) {
			$block_atts['class'] = array_merge($block_atts['class'], array("easyazon-block-{$shortcode_atts['key']}"));
		}

		if(isset($shortcode_atts['layout']) && $shortcode_atts['layout']) {
			$block_atts['class'] = array_merge($block_atts['class'], array("easyazon-block-layout-{$shortcode_atts['layout']}"));
		}

		$block_atts['data-block-identifier'] = isset($shortcode_atts['identifier']) ? $shortcode_atts['identifier'] : false;
		$block_atts['data-block-locale']     = isset($shortcode_atts['locale']) ? $shortcode_atts['locale'] : false;

		return $block_atts;
	}

	public static function image_atts($image_atts, $shortcode_atts) {
		$item = easyazon_get_item($shortcode_atts['identifier'], $shortcode_atts['locale']);

		if($item && isset($item['name'])) {
			$image_atts['alt'] = $item['name'];
		}

		$image_atts['class'] = isset($image_atts['class']) ? (is_array($image_atts['class']) ? $image_atts['class'] : array_filter(array($image_atts['class']))) : array();
		$image_atts['class'] = array_merge($image_atts['class'], array('easyazon-image'));

		if(isset($shortcode_atts['align']) && $shortcode_atts['align']) {
			$image_atts['class'] = array_merge($image_atts['class'], array("align{$shortcode_atts['align']}"));
		}

		if(isset($shortcode_atts['height']) && $shortcode_atts['height']) {
			$image_atts['height'] = $shortcode_atts['height'];
		}

		if(isset($shortcode_atts['src']) && $shortcode_atts['src']) {
			$image_atts['src'] = $shortcode_atts['src'];
		}

		if(isset($shortcode_atts['width']) && $shortcode_atts['width']) {
			$image_atts['width'] = $shortcode_atts['width'];
		}

		return $image_atts;
	}

	public static function link_atts($link_atts, $shortcode_atts) {
		$link_atts['data-cart']     = isset($shortcode_atts['cart']) && $shortcode_atts['cart'] ? $shortcode_atts['cart'] : '';
		$link_atts['data-cloak']    = isset($shortcode_atts['cloak']) && $shortcode_atts['cloak'] ? $shortcode_atts['cloak'] : '';
		$link_atts['data-keywords'] = isset($shortcode_atts['keywords']) && $shortcode_atts['keywords'] ? $shortcode_atts['keywords'] : '';
		$link_atts['data-localize'] = isset($shortcode_atts['localize']) && $shortcode_atts['localize'] ? $shortcode_atts['localize'] : '';
		$link_atts['data-popups']   = isset($shortcode_atts['popups']) && $shortcode_atts['popups'] ? $shortcode_atts['popups'] : '';

		return $link_atts;
	}

	#endregion Attributes

	#region URLs

	public static function get_url($url, $shortcode_atts) {
		if(isset($shortcode_atts['keywords']) && $shortcode_atts['keywords']) {
			$url = easyazon_get_search_url($shortcode_atts['keywords'], $shortcode_atts['locale'], $shortcode_atts['tag']);
		} else if(isset($shortcode_atts['cart']) && 'y' === $shortcode_atts['cart'] && isset($shortcode_atts['identifier']) && isset($shortcode_atts['locale'])) {
			$access_key = easyazon_get_setting('access_key');
			$identifier = $shortcode_atts['identifier'];
			$locale     = $shortcode_atts['locale'];
			$tag        = isset($shortcode_atts['tag']) ? $shortcode_atts['tag'] : '';

			$url = sprintf('http://www.amazon.%s/gp/aws/cart/add.html?ASIN.1=%s&Quantity.1=1&AWSAccessKeyId=%s&AssociateTag=%s', easyazon_get_locale_tld($locale), $identifier, $access_key, $tag);
		}

		return $url;
	}

	#endregion URLs
}

EasyAzonPro_Components_Shortcodes::init();
