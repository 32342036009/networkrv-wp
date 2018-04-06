=== Advanced Ads – Geo Targeting ===
Contributors: webzunft
Tags: ads, ad, banner, adverts, advertisement, browser, geo, targeting, location, geo targeting
Requires at least: 3.5, Advanced Ads 1.7.16
Tested up to: 4.7.3
Stable tag: 1.1.5

Display ads based on the geo location of the visitor.

== Copyright ==

Copyright 2014-2017, Thomas Maier, webgilde.com

This plugin is not to be distributed after purchase. Arrangements to use it in themes and plugins can be made individually.
The plugin is distributed in the hope that it will be useful,
but without any warrenty, even the implied warranty of
merchantability or fitness for a specific purpose.

== Description ==

Using the Geo Targeting add-on you can display ads based on the location of your visitors.

**Features**

* adds a visitor condition to select the geo location a visitor must come from in order to see / not see an ad
* target visitors by country
* target visitors by region/state
* target visitors by city
* target visitors by continent
* target visitors from European Union

== Installation ==

Geo Targeting is based on the free Advanced Ads plugin, a simple and powerful ad management solution for WordPress. Before using this plugin download, install and activate Advanced Ads for free from http://wordpress.org/plugins/advanced-ads/.
You can use Advanced Ads along any other ad management plugin and don’t need to switch completely.

== Changelog ==

= 1.1.5 =

* fixed minor errors when data is not available for a position

= 1.1.4 ==

* upper/lower case doesn’t matter anymore when checking regions or cities
* add constant `ADVANCED_ADS_GEO_CHECK_DEBUG` to `wp-config.php` in order to log all tests in `wp-content/geo-check.log`
* fixed bug not checking regions

= 1.1.3 =

* don’t throw error message when IP was not found
* made the plugin compatible with Advanced Ads 1.7.16
* updated Spanish translation

= 1.1.2 =

* filter IP address for valid format
* prevent errors when IP address is empty

= 1.1.1 =

* added link to settings when database is missing in visitor conditions
* updated German translation

= 1.1 =

* implemented check for states/regions
* allow state/region and city names in different languages
* added one click installation for Advanced Ads
* updated German translation

= 1.0.6 =

* fixed static var error message

= 1.0.5 =

* request users location only once, even when there are multiple geo checks on a page
* removed code deprecated with Advanced Ads 1.7.1

= 1.0.4 =

* made IP check compatible with CloudFlare

= 1.0.3 =

* moved error logging to debug.log file
* added Spanish translation

= 1.0.2 =

* fixed issue with cache-busting

= 1.0.1 =

* fixed license validation and database update error

= 1.0.0 =

* first plugin version
* added geo targeting by country