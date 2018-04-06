<?php

if(!defined('ABSPATH')) { exit; }

function easyazon_get_info_block_template_file($key) {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Shortcodes_InfoBlock::get_info_block_template_file($key), $key);
}

function easyazon_get_info_block_template_name($key) {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Shortcodes_InfoBlock::get_info_block_template_name($key), $key);
}

function easyazon_get_info_block_template_scripts($key) {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Shortcodes_InfoBlock::get_info_block_template_scripts($key), $key);
}

function easyazon_get_info_block_template_stylesheets($key) {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Shortcodes_InfoBlock::get_info_block_template_stylesheets($key), $key);
}

function easyazon_get_info_block_template($key) {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Shortcodes_InfoBlock::get_info_block_template($key), $key);
}

function easyazon_get_info_block_templates() {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Shortcodes_InfoBlock::get_info_block_templates());
}
