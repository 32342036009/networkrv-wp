=== Advanced Ads – Ad Tracking ===
Contributors: webzunft
Tags: ads, ad, banner, adverts, advertisement, browser, ad stats, ad tracking, ad tracker
Requires at least: WP 4.4, Advanced Ads 1.8
Tested up to: 4.8
Stable tag: 1.7.2

Track ad impressions and clicks.

== Copyright ==

Copyright 2015 – 2017, Thomas Maier, webgilde.com

This plugin is not to be distributed after purchase. Arrangements to use it in themes and plugins can be made individually.
The plugin is distributed in the hope that it will be useful,
but without any warrenty, even the implied warranty of
merchantability or fitness for a specific purpose.

== Description ==

This add-on for the Advanced Ads plugin provides tracking ad impressions and clicks.

**Tracking:**

* count impressions either on load or after the ad was displayed
* choose between 4 tracking methods
* track impressions or clicks locally or with Google Analytics
* enable or disable tracking for all ads by default
* enable or disable tracking for each ad individually
* track clicks of an ad with automatically or manually placed links
* choose to open ad link in a new window
* add rel="nofollow" attribute to links

**Ad Planning**

* limit ad views to a certain amount of impressions or clicks

**Stats**

* see stats of all or individual ads in your dashboard based on predefined and custom periods, grouped by day, week or, month
* display stats in a table and graph
* compare stats for ads
* compare stats with the previous or next period
* remove stats for all or single ads
* filter stats by ad groups
* public stats for a single ad – e.g. to show clients
* send email reports for all or individual ads to different emails
* combine impressions and clicks with any other metrics in Google Analytics

**Stats Management**

* export stats as csv
* import stats from csv
* compress old stats
* remove old stats

**on load**

track impressions when the ad is prepared for output

**after frontend is loaded**

track impressions after the frontend is completely loaded

software included:

* [jqPlot](http://www.jqplot.com), GPL 2

== Installation ==

The Tracking add-on is based on the free Advanced Ads plugin, a simple and powerful ad management solution for WordPress.
Before using this plugin download, install and activate Advanced Ads for free from http://wordpress.org/plugins/advanced-ads/.
You can use Advanced Ads along any other ad management plugin and don’t need to switch completely.

== Changelog ==

= 1.7.2 =

* enabled tracking debug log using `ADVANCED_ADS_TRACKING_DEBUG` in your wp-config.php
* moved debug output to db management page
* removed Memcached support since it seems to never have worked properly
* removed automatic data compression feature 3 months after we switched from hourly to daily tracking
* fixed cloned ads using the same stats url as their parent
* fixed warning when using buffer for tracking

= 1.7.1 =

* fixed issue when cache-busting module (Pro add-on) is disabled

= 1.7 =

* never track WP Rocket cache bot
* added `advanced-ads-tracking-after-writing-into-db` action hook to perform something after tracking an event
* load JavaScript after JavaScript from cache-busting
* allow to track 'Footer Code' placement

= 1.6.2 =

* moved ad specific warnings to new ad notices logic
* added db management page to list of AA related pages to show issues there
* added compatibility with WooCommerce 3.0 and Sellings Ads
* fixed for Analytics tracking not recording all impressions on some sites
* fixed custom period for csv export not working

= 1.6.1 =

* added compatibility with reload of ads when screen resizes and Responsive and cache-busting in Pro are installed

= 1.6 =

* switched from hourly to daily tracking, no need for (auto) compression anymore
* renamed "track bots" option to prevent misunderstandings
* fixed WPML breaking linkout urls

= 1.5.3 =

* removed restriction on the db engine when creating the tracking table
* hotfix: disabled auto compression of stats because some users reported too high numbers after an update

= 1.5.2 =

* fixed stats in email reports being empty on some PHP versions
* fixed time error message on some PHP versions

= 1.5.1 =

* fixed ad options

= 1.5 =

* send individual ad stats reports
* filter ads by group on stats page
* updated license handling on uninstall
* updated Spanish and German translation
* fixed link to stats on overview page
* fixed missing script issue
* fixed Dublicate entry database error

= 1.4.4 =

* display click tracking limit option for image ads

= 1.4.3 =

* added a check if tables exist on the stats page and option to create them
* updated jQplot library for graphs
* fixed occasional errors for duplicate table entries
* fixed wrong class name for case sensitive systems

= 1.4.2 =

* changed default stats view on public stats pages from last month to last 30 days
* rewrite of link building function to access it statically
* fix for broken var check for external links
* fix for daylight saving time change on stats pages
* fix for Analytics tracking and cache-busting

= 1.4.1 =

* removed query strings from tracking urls to fix url changes made by WPML and similar plugins
* fix for Analytics tracking

= 1.4 =

* track impressions and clicks in Google Analytics
* moved reset stats function to database management page
* updated text domain and German translation
* added ad based setting for nofollow option
* added warning for AdSense ads and click tracking
* added daily cron job to compress data automatically
* trim redirect link option
* display ad stats for expired, pending, and drafted ads
* fix for one day gap error for some time zones
* fix for error message when stats tables are empty
* fix for unusual target link structures
* fix for scripts not loaded for editor role
* fix for PHP 7 compatibility

= 1.3 =

* export stats as csv
* import stats from csv
* compress old stats
* remove old stats
* adjusted ad settings layout
* reversed order on public and email reports to show recent dates first
* show last 7 days report by default
* added option to set link target individually per ad
* fixed warning on activation
* fixed missing index issue
* decreased number of checks for timezone changes 
* prevent browser crash with corrupted data when showing "today"

= 1.2.15 =

* track external (affiliate) activities
* fix javascript code syntax

= 1.2.14 =

* fixed missing of ad-specific stats table and totals

= 1.2.13 =

* compare stats with the previous or following period
* added French translation

= 1.2.12.1 =

* changed redirect hook for more compatibility with redirect plugins
* fixed issue with unsupported jquery languages

= 1.2.12 =

* added shortcode to display number of impressions in the frontend
* removed links to Advanced Ads for public ad stats
* fixed invalid argument error for memcached ads
* fixed image ads not being linked when tracking is disabled
* added Spanish translation

= 1.2.11 =

* added shutdown tracking method
* deactivate license when deleting the plugin
* move stat table arrows to left 
* fixed language conflict in stats graph script
* fixed empty ads being counted with impressions
* fixed stats for week overlapping into new year

= 1.2.10 =

* added stats column to ad list
* allow placeholders for post id, slug and categories in the target url
* localized stats
* added link from ad edit page to statistics
* possible fix for missing ad stats on public page
* added uninstall routine to remove tables
* option to disable tracking for bots
* removed click tracking from AdSense ads
* fixed missing stats for January 1st to 3rd 2016

= 1.2.9 =

* added email reports feature
* sanitized linkbase setting
* added capability check to allow editors to see stats, if they can edit ads

= 1.2.8 =

* added public stats feature
* fixed empty stats for missing dates
* fixed issue with script not loading on ad edit page

= 1.2.7 =

* compare ad stats on the stats page
* slight layout changes
* fixed compatibility with image ads
* fixed sum timeout not being removed if empty
* prepared and added German translation
* reset stats option hidden to prevent accidental clicks

= 1.2.6.1 =

* hotfix for wrong jQuery selector

= 1.2.6 =

* added option to limit ads for specific number of impressions and clicks
* warn if tracking link is not set correctly within the ad content
* made sure that options get loaded correctly

= 1.2.5.3 =

* changed id prefix of ads to prevent ad blockers from tracking them
* changed stats page headline to align with new WP standards
* fix issue where tracking link didn’t work because of plugin conflict

= 1.2.5.2 =

* hotfix to prevent data loss.

Please contact me when updating the add-on since version 1.2.3.1 or prior.

= 1.2.5.1 =

* fix issues introduced with 1.2.5 where tracking was never flushed when memcached is available
* rollback on MySQL errors during tracking
* security: avoid injection through corrupted memcached

= 1.2.5 =

* fix coding style
* reliably load options
* fix ajax compatibility (track dynamic ads on-load regardless of selected method), for use with Advanced Ads Pro
* fix `frontend` tracking method
* added experimental memcached write buffering to improve AJAX performance
* avoid regression introduced by 1.2.4

= 1.2.4 =

* show warning if Advanced Ads is not installed
* only create tables if not existing yet
* updated plugin link
* added plugin link to license page
* added stats page to array of pages that belong to Advanced Ads

= 1.2.3.3 =

* adjusted decimal points
* moved clicks to right y axis

= 1.2.3.2 =

* reverted change that was expecting php 5.4 and higher

= 1.2.3.1 =

* fixed deprecated root path check

= 1.2.3 =

* fixed tracking link when WordPress is installed in a subdirectory
* updated all class names from "Advads_" to "Advanced_Ads_"

= 1.2.2 =

* fixed a bug with click tracking
* added debug information for time zone

= 1.2.1 =

* optimise db format
* properly use wp local time
* fixed missing datepicker file
* fix stat frames
* fix ajax handler
* modularise code

= 1.2.0 =

* added autoloading
* added composer definitions

= 1.1.0 =

* added option to wrap the whole ad in a link
* track clicks
* display clicks in stats
* calculate click through rate
* custom placement of the tracking url
* changed plugin url
* changed add-on widget on dashboard if plugin is installed
* moved jquery style to Advanced Ads plugin

= 1.0.0 =

* first plugin version
