=== Advanced Ads – Responsive Ads ===
Contributors: webzunft
Tags: ads, ad, banner, adverts, advertisement, browser
Requires at least: 3.5, Advanced Ads 1.8.3
Tested up to: 4.8
Stable tag: 1.5

Display ads based on the browser size of the visitor’s device.

== Copyright ==

Copyright 2014-2017, Thomas Maier, webgilde.com

This plugin is not to be distributed after purchase. Arrangements to use it in themes and plugins can be made individually.
The plugin is distributed in the hope that it will be useful,
but without any warrenty, even the implied warranty of
merchantability or fitness for a specific purpose.

== Description ==

With responsive layouts and a wide range of devices serving banners with a fixed size can become a huge pain.

Using Responsive Ads you can define a minimal and maximal width a browser must have in order to see an ad.

**Features**

* set minimal and maximal browser width to display ads for
* set custom sizes for AdSense responsive ads
* set default AdSense sizes to rectangle, vertical or horizontal
* list all ads by their responsive settings on the dashboard
* frontend assistant to display ad, container, and window size for admins
* detect tablets
* force responsive image ads if not supported by the theme
* automatically reload ads when screen resizes and cache-busting in Pro is enabled
* supports AdSense on AMP pages
* support and AMP format from ad networks

Tested with the following AMP plugins

* AMP (by Automattic)
* WP AMP

**external libraries**

* Mobile Detect Library v2.8.22, https://github.com/serbanghita/Mobile-Detect/blob/master/Mobile_Detect.php

== Installation ==

Responsive Ads is based on the free Advanced Ads plugin, a simple and powerful ad management solution for WordPress. Before using this plugin download, install and activate Advanced Ads for free from http://wordpress.org/plugins/advanced-ads/.
You can use Advanced Ads along any other ad management plugin and don’t need to switch completely.

== Changelog ==

= 1.5 =

* moved AMP check function to basic plugin
* moved AMP warning for AdSense to new ad notices section
* minor fixes to textdomains and labels

= 1.4.5 =

* reload ads on screen resize is now optional and off by default, since it caused issues with some kinds of ads
* updated pot file and German translation

= 1.4.4 =

* use cookie functions from basic plugin
* trigger event when screen is resized to reload ads when cache-busting is enabled
* fixed dependency with basic plugin

= 1.4.3 =

* made compatible with AdSense Matched Content
* fixed possible issue when jQuery is not yet loaded

= 1.4.2 =

* show warning on AdSense types that don’t support AMP
* fixed positioning and label size of AMP ads

= 1.4.1 =

* added support for WP AMP plugin

= 1.4 =

* added automatic AMP support for AdSense
* added AMP Display Condition
* added AMP ad type to allow any ad network’s AMP format

= 1.3.2 =

* fix to keep additional sizes for advanced responsive AdSense ads

= 1.3.1 =

* fix to run Responsive add-on along with WP Mobile Detect plugin

= 1.3 =

* added option to force responsive image ads if not supported by the theme
* added tablet detection
* set default AdSense sizes to rectangle, vertical or horizontal

= 1.2.9 =

* fixed link to responsive overview page on blogs in subdirectories
* added Spanish and German localization

= 1.2.8 =

* updated user rights check
* fix for displaying AdSense ads as non-superadmin
* fix frontend helper showing an empty width in Chrome browser
* fix frontend helper working with cache busting in Pro
* fix for saving current browser width if tooltip is enabled

= 1.2.7 =

* fix for manually sized responsive ads

= 1.2.6 =

* fixed manual sizing for cache-busted ads
* fixed saved width on iPhone

= 1.2.5 =

* prevent ads from covering the frontend assistant

= 1.2.4 =

* show warning if Advanced Ads is not installed
* changed class of active buttons to align with main plugin css
* updated plugin link
* added plugin link to license page

= 1.2.3 =

* moved licensing code to main plugin
* added adsbygoogle library reference

= 1.2.2 =

* the list of responsive ads now only includes the new visitor conditions type

= 1.2.1 =

* moved browser width conditions to new visitor conditions api
* updated all class names from "Advads_" to "Advanced_Ads_"
* fixed minor error message in dashboard

= 1.2.0 =

* added frontend assistant to display ad, container, and window size for admins
* added license input and auto updates

= 1.1.4 =

* fix installation error
* changed widget on overview page

= 1.1.3 =

* change of color in responsive ad list
* minor bugfix in ad list
* link to plugin page changed

= 1.1.2 =

* minor fixes to the responsive ads list layout
* added legend below responsive ads list

= 1.1.1 =

* added list of ads by their responsive settings on the dashboard

= 1.1.0 =

* added support for AdSense manual responsive ad sizes
* hide ad settings, if option not enabled
* fixed issue when base plugin is not loaded before the add-on

= 1.0.2 =

* renaming the plugin

= 1.0.1 =

* bugfix - desktop fallback was not saved

= 1.0 =
* first plugin version