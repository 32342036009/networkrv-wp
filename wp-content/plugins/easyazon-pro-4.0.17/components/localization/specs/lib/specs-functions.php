<?php

if(!defined('ABSPATH')) { exit; }

function easyazon_get_localized_identifier($current_identifier, $current_locale, $requested_locale) {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Localization_Specs::get_localized_identifier($current_identifier, $current_locale, $requested_locale), $current_identifier, $current_locale, $requested_locale);
}

function easyazon_get_localizations($limit = 20, $page = 1) {
	return apply_filters(__FUNCTION__, EasyAzonPro_Components_Localization_Specs::get_localizations($limit, $page), $limit, $page);
}

function easyazon_get_localization_by_id($id) {
	return apply_filters(__FUNCITON__, EasyAzonPro_Components_Localization_Specs::get_localization_by_id($id), $id);
}
