<?php

if(!defined('ABSPATH')) { exit; }

function easyazon_get_converted_currency($value, $locale) {
	$converted = $value;
	$locale = easyazon_get_locale($locale);

	switch($locale) {
		case 'BR':
		case 'CA':
		case 'CN':
		case 'DE':
		case 'ES':
		case 'FR':
		case 'IN':
		case 'IT':
		case 'UK':
		case 'US':
			$converted = ($value * 100);
			break;
	}

	return $converted;
}

function easyazon_get_countries() {
	static $countries = null;

	if(is_null($countries)) {
		$countries = array (
		    'AF' => 'Afghanistan',
		    'AX' => 'Åland Islands',
		    'AL' => 'Albania',
		    'DZ' => 'Algeria',
		    'AS' => 'American Samoa',
		    'AD' => 'Andorra',
		    'AO' => 'Angola',
		    'AI' => 'Anguilla',
		    'AQ' => 'Antarctica',
		    'AG' => 'Antigua and Barbuda',
		    'AR' => 'Argentina',
		    'AM' => 'Armenia',
		    'AW' => 'Aruba',
		    'AU' => 'Australia',
		    'AT' => 'Austria',
		    'AZ' => 'Azerbaijan',
		    'BS' => 'Bahamas',
		    'BH' => 'Bahrain',
		    'BD' => 'Bangladesh',
		    'BB' => 'Barbados',
		    'BY' => 'Belarus',
		    'BE' => 'Belgium',
		    'BZ' => 'Belize',
		    'BJ' => 'Benin',
		    'BM' => 'Bermuda',
		    'BT' => 'Bhutan',
		    'BO' => 'Bolivia, Plurinational State of',
		    'BA' => 'Bosnia and Herzegovina',
		    'BW' => 'Botswana',
		    'BV' => 'Bouvet Island',
		    'BR' => 'Brazil',
		    'IO' => 'British Indian Ocean Territory',
		    'BN' => 'Brunei Darussalam',
		    'BG' => 'Bulgaria',
		    'BF' => 'Burkina Faso',
		    'BI' => 'Burundi',
		    'KH' => 'Cambodia',
		    'CM' => 'Cameroon',
		    'CA' => 'Canada',
		    'CV' => 'Cape Verde',
		    'KY' => 'Cayman Islands',
		    'CF' => 'Central African Republic',
		    'TD' => 'Chad',
		    'CL' => 'Chile',
		    'CN' => 'China',
		    'CX' => 'Christmas Island',
		    'CC' => 'Cocos (Keeling) Islands',
		    'CO' => 'Colombia',
		    'KM' => 'Comoros',
		    'CG' => 'Congo',
		    'CD' => 'Congo, the Democratic Republic of the',
		    'CK' => 'Cook Islands',
		    'CR' => 'Costa Rica',
		    'CI' => "Côte d'Ivoire",
		    'HR' => 'Croatia',
		    'CU' => 'Cuba',
		    'CY' => 'Cyprus',
		    'CZ' => 'Czech Republic',
		    'DK' => 'Denmark',
		    'DJ' => 'Djibouti',
		    'DM' => 'Dominica',
		    'DO' => 'Dominican Republic',
		    'EC' => 'Ecuador',
		    'EG' => 'Egypt',
		    'SV' => 'El Salvador',
		    'GQ' => 'Equatorial Guinea',
		    'ER' => 'Eritrea',
		    'EE' => 'Estonia',
		    'ET' => 'Ethiopia',
		    'FK' => 'Falkland Islands (Malvinas)',
		    'FO' => 'Faroe Islands',
		    'FJ' => 'Fiji',
		    'FI' => 'Finland',
		    'FR' => 'France',
		    'GF' => 'French Guiana',
		    'PF' => 'French Polynesia',
		    'TF' => 'French Southern Territories',
		    'GA' => 'Gabon',
		    'GM' => 'Gambia',
		    'GE' => 'Georgia',
		    'DE' => 'Germany',
		    'GH' => 'Ghana',
		    'GI' => 'Gibraltar',
		    'GR' => 'Greece',
		    'GL' => 'Greenland',
		    'GD' => 'Grenada',
		    'GP' => 'Guadeloupe',
		    'GU' => 'Guam',
		    'GT' => 'Guatemala',
		    'GG' => 'Guernsey',
		    'GN' => 'Guinea',
		    'GW' => 'Guinea-Bissau',
		    'GY' => 'Guyana',
		    'HT' => 'Haiti',
		    'HM' => 'Heard Island and McDonald Islands',
		    'VA' => 'Holy See (Vatican City State)',
		    'HN' => 'Honduras',
		    'HK' => 'Hong Kong',
		    'HU' => 'Hungary',
		    'IS' => 'Iceland',
		    'IN' => 'India',
		    'ID' => 'Indonesia',
		    'IR' => 'Iran, Islamic Republic of',
		    'IQ' => 'Iraq',
		    'IE' => 'Ireland',
		    'IM' => 'Isle of Man',
		    'IL' => 'Israel',
		    'IT' => 'Italy',
		    'JM' => 'Jamaica',
		    'JP' => 'Japan',
		    'JE' => 'Jersey',
		    'JO' => 'Jordan',
		    'KZ' => 'Kazakhstan',
		    'KE' => 'Kenya',
		    'KI' => 'Kiribati',
		    'KP' => "Korea, Democratic People's Republic of",
		    'KR' => 'Korea, Republic of',
		    'KW' => 'Kuwait',
		    'KG' => 'Kyrgyzstan',
		    'LA' => "Lao People's Democratic Republic",
		    'LV' => 'Latvia',
		    'LB' => 'Lebanon',
		    'LS' => 'Lesotho',
		    'LR' => 'Liberia',
		    'LY' => 'Libyan Arab Jamahiriya',
		    'LI' => 'Liechtenstein',
		    'LT' => 'Lithuania',
		    'LU' => 'Luxembourg',
		    'MO' => 'Macao',
		    'MK' => 'Macedonia, the former Yugoslav Republic of',
		    'MG' => 'Madagascar',
		    'MW' => 'Malawi',
		    'MY' => 'Malaysia',
		    'MV' => 'Maldives',
		    'ML' => 'Mali',
		    'MT' => 'Malta',
		    'MH' => 'Marshall Islands',
		    'MQ' => 'Martinique',
		    'MR' => 'Mauritania',
		    'MU' => 'Mauritius',
		    'YT' => 'Mayotte',
		    'MX' => 'Mexico',
		    'FM' => 'Micronesia, Federated States of',
		    'MD' => 'Moldova, Republic of',
		    'MC' => 'Monaco',
		    'MN' => 'Mongolia',
		    'ME' => 'Montenegro',
		    'MS' => 'Montserrat',
		    'MA' => 'Morocco',
		    'MZ' => 'Mozambique',
		    'MM' => 'Myanmar',
		    'NA' => 'Namibia',
		    'NR' => 'Nauru',
		    'NP' => 'Nepal',
		    'NL' => 'Netherlands',
		    'AN' => 'Netherlands Antilles',
		    'NC' => 'New Caledonia',
		    'NZ' => 'New Zealand',
		    'NI' => 'Nicaragua',
		    'NE' => 'Niger',
		    'NG' => 'Nigeria',
		    'NU' => 'Niue',
		    'NF' => 'Norfolk Island',
		    'MP' => 'Northern Mariana Islands',
		    'NO' => 'Norway',
		    'OM' => 'Oman',
		    'PK' => 'Pakistan',
		    'PW' => 'Palau',
		    'PS' => 'Palestinian Territory, Occupied',
		    'PA' => 'Panama',
		    'PG' => 'Papua New Guinea',
		    'PY' => 'Paraguay',
		    'PE' => 'Peru',
		    'PH' => 'Philippines',
		    'PN' => 'Pitcairn',
		    'PL' => 'Poland',
		    'PT' => 'Portugal',
		    'PR' => 'Puerto Rico',
		    'QA' => 'Qatar',
		    'RE' => 'Réunion',
		    'RO' => 'Romania',
		    'RU' => 'Russian Federation',
		    'RW' => 'Rwanda',
		    'BL' => 'Saint Barthélemy',
		    'SH' => 'Saint Helena',
		    'KN' => 'Saint Kitts and Nevis',
		    'LC' => 'Saint Lucia',
		    'MF' => 'Saint Martin (French part)',
		    'PM' => 'Saint Pierre and Miquelon',
		    'VC' => 'Saint Vincent and the Grenadines',
		    'WS' => 'Samoa',
		    'SM' => 'San Marino',
		    'ST' => 'Sao Tome and Principe',
		    'SA' => 'Saudi Arabia',
		    'SN' => 'Senegal',
		    'RS' => 'Serbia',
		    'SC' => 'Seychelles',
		    'SL' => 'Sierra Leone',
		    'SG' => 'Singapore',
		    'SK' => 'Slovakia',
		    'SI' => 'Slovenia',
		    'SB' => 'Solomon Islands',
		    'SO' => 'Somalia',
		    'ZA' => 'South Africa',
		    'GS' => 'South Georgia and the South Sandwich Islands',
		    'ES' => 'Spain',
		    'LK' => 'Sri Lanka',
		    'SD' => 'Sudan',
		    'SR' => 'Suriname',
		    'SJ' => 'Svalbard and Jan Mayen',
		    'SZ' => 'Swaziland',
		    'SE' => 'Sweden',
		    'CH' => 'Switzerland',
		    'SY' => 'Syrian Arab Republic',
		    'TW' => 'Taiwan, Province of China',
		    'TJ' => 'Tajikistan',
		    'TZ' => 'Tanzania, United Republic of',
		    'TH' => 'Thailand',
		    'TL' => 'Timor-Leste',
		    'TG' => 'Togo',
		    'TK' => 'Tokelau',
		    'TO' => 'Tonga',
		    'TT' => 'Trinidad and Tobago',
		    'TN' => 'Tunisia',
		    'TR' => 'Turkey',
		    'TM' => 'Turkmenistan',
		    'TC' => 'Turks and Caicos Islands',
		    'TV' => 'Tuvalu',
		    'UG' => 'Uganda',
		    'UA' => 'Ukraine',
		    'AE' => 'United Arab Emirates',
		    'GB' => 'United Kingdom',
		    'US' => 'United States',
		    'UM' => 'United States Minor Outlying Islands',
		    'UY' => 'Uruguay',
		    'UZ' => 'Uzbekistan',
		    'VU' => 'Vanuatu',
		    'VE' => 'Venezuela, Bolivarian Republic of',
		    'VN' => 'Viet Nam',
		    'VG' => 'Virgin Islands, British',
		    'VI' => 'Virgin Islands, U.S.',
		    'WF' => 'Wallis and Futuna',
		    'EH' => 'Western Sahara',
		    'YE' => 'Yemen',
		    'ZM' => 'Zambia',
		    'ZW' => 'Zimbabwe'
		);
	}

	return $countries;
}

function easyazon_get_locale_search_indices() {
	static $indices = null;

	if(is_null($indices)) {
		$indices = array();

		foreach(glob(dirname(__FILE__) . '/indices/*.json') as $file) {
			$parts = explode('/', $file);
			$locale = str_replace('.json', '', end($parts));
			$indices[$locale] = json_decode(file_get_contents($file), true);
		}

		foreach($indices as $locale => $indices_locale) {
			foreach($indices_locale as $key => $index) {
				$indices[$locale][$key] = array_merge($index, array(
					'name' => easyazon_split_camel_case($index['name']),
				));
			}
		}
	}

	return $indices;
}

function easyazon_get_search_url($keywords, $locale, $tag) {
	$query_args = array_filter(array(
		'field-keywords' => rawurlencode($keywords),
		'tag' => $tag,
	));

	return add_query_arg($query_args, sprintf('http://www.amazon.%s/s/', easyazon_get_locale_tld($locale)));
}

function easyazon_get_sort_values() {
	static $sorts = null;

	if(is_null($sorts)) {
		$sorts = array(
			array('key' => '-age-min', 'name' => "Age: high to low"),
			array('key' => 'albumrank', 'name' => "Album: A to Z"),
			array('key' => '-albumrank', 'name' => "Album: Z to A"),
			array('key' => 'amzrank', 'name' => "Alphabetical: A to Z"),
			array('key' => 'artistalbumrank', 'name' => "Artist: A to Z"),
			array('key' => '-artistalbumrank', 'name' => "Artist: Z to A"),
			array('key' => 'artistrank', 'name' => "Artist name: A to Z"),
			array('key' => 'availability', 'name' => "Most to least available"),
			array('key' => '-date', 'name' => "Publication date: old to new"),
			array('key' => 'daterank', 'name' => "Publication date: new to old"),
			array('key' => '-daterank', 'name' => "Publication date: old to new"),
			array('key' => 'date-desc-rank', 'name' => "Publication date: new to old"),
			array('key' => '-edition-sales-velocity', 'name' => "Quickest to slowest selling products"),
			array('key' => 'inverseprice', 'name' => "Price: high to low"),
			array('key' => 'inverse-price', 'name' => "Price: high to low"),
			array('key' => 'inverse-pricerank', 'name' => "Price: high to low"),
			array('key' => 'launchdate', 'name' => "Launch date: newer to older"),
			array('key' => 'launch-date', 'name' => "Launch date: newer to older"),
			array('key' => '-launch-date', 'name' => "Launch date: older to newer"),
			array('key' => 'mfg-age-min', 'name' => "Age: low to high"),
			array('key' => '-mfg-age-min', 'name' => "Age: high to low"),
			array('key' => 'orig-rel-date', 'name' => "Original release date: earliest to latest"),
			array('key' => '-orig-rel-date', 'name' => "Original release date: latest to earliest"),
			array('key' => 'paidsalesrank', 'name' => "Bestseller rank: by project sales"),
			array('key' => 'pct-off', 'name' => "Discount:  high to low"),
			array('key' => '-pct-off', 'name' => "Discount:  low to high"),
			array('key' => 'pmrank', 'name' => "Featured items"),
			array('key' => 'popularityrank', 'name' => "Items ranked by popularity"),
			array('key' => 'popularity-rank', 'name' => "Items ranked by popularity"),
			array('key' => 'price', 'name' => "Price: low to high"),
			array('key' => '-price', 'name' => "Price: high to low"),
			array('key' => 'price-asc-rank', 'name' => "Price: low to high"),
			array('key' => 'price-desc-rank', 'name' => "Price: high to low"),
			array('key' => 'price-new-bin', 'name' => "Price: low to high"),
			array('key' => '-price-new-bin', 'name' => "Price: high to low"),
			array('key' => 'pricerank', 'name' => "Price: low to high"),
			array('key' => '-pricerank', 'name' => "Price: high to low"),
			array('key' => 'psrank', 'name' => "Bestseller rank: by projected sales"),
			array('key' => 'pubdate', 'name' => "Publication date: newest to oldest"),
			array('key' => '-pubdate', 'name' => "Publication date: oldest to most recent"),
			array('key' => 'publicationdate', 'name' => "Publication date: newest to oldest"),
			array('key' => 'publication_date', 'name' => "Publication date: newest to oldest"),
			array('key' => '-publicationdate', 'name' => "Publication date: oldest to most recent"),
			array('key' => '-publication_date', 'name' => "Publication date: oldest to most recent"),
			array('key' => 'releasedate', 'name' => "Release date: older to newer"),
			array('key' => 'release-date', 'name' => "Release date: older to newer"),
			array('key' => '-releasedate', 'name' => "Release date: newer to older"),
			array('key' => '-release-date', 'name' => "Release date: newer to older"),
			array('key' => 'relevance', 'name' => "Relevance"),
			array('key' => 'relevance-fs-rank', 'name' => ""),
			array('key' => 'relevancerank', 'name' => "Relevance"),
			array('key' => 'reviewrank', 'name' => "Highest to lowest ratings in customer reviews"),
			array('key' => 'review-rank', 'name' => "Highest to lowest ratings in customer reviews"),
			array('key' => 'reviewrank_authority', 'name' => "Review rank: high to low"),
			array('key' => '-reviewrank_authority', 'name' => "Review rank: low to high"),
			array('key' => 'runtime', 'name' => "Track length: high to low"),
			array('key' => '-runtime', 'name' => "Track length: low to high"),
			array('key' => 'sale-flag', 'name' => "On sale"),
			array('key' => 'salesrank', 'name' => "Bestselling"),
			array('key' => 'songtitlerank', 'name' => "Most popular"),
			array('key' => 'subslot-salesrank', 'name' => "Bestselling"),
			array('key' => 'titlerank', 'name' => "Alphabetical: A to Z"),
			array('key' => '-titlerank', 'name' => "Alphabetical: Z to A"),
			array('key' => '-unit-sales', 'name' => ""),
			array('key' => 'uploaddaterank', 'name' => "Date added"),
			array('key' => '-video-release-date', 'name' => "Release date: newer to older"),
			array('key' => 'xsrelevancerank', 'name' => ""),
		);
	}

	return $sorts;
}
