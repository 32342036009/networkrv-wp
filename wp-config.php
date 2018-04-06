<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
define('WP_DEBUG', true);
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mktcash_networkrv');

/** MySQL database username */
define('DB_USER', 'mktcash_networkr');

/** MySQL database password */
define('DB_PASSWORD', 'bTrkT%WUD(l)');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']O;893f/w#_~mEDsb-ymOa[C|2>9B8v-xzopPrHl-}bx1n-8CHuiV`{O=!u_8-X<');
define('SECURE_AUTH_KEY',  'Fu&ewWC~O]B?d,+6~=2UB.T?E]H~lPnWNifOEsKFt,EQ9/jv[,cP:w$$F{4oWb[3');
define('LOGGED_IN_KEY',    'J*.Aa[q;N<)3Vl4,),&#!`u9}PVIq;iM)rmEK)*;,(jDyZRPl<_CUVPDccL/w/k@');
define('NONCE_KEY',        '} =)78vrF;5xrXDwV#I_#hO{ Sw5@YA#uxJXwJI0yg*~kg7Hb8.<w2DF:|/0[{za');
define('AUTH_SALT',        '`npn3q}V,9O&s8#$mGcfZ%qRr#0VVe^3Vl&lZX;CAzsm5IdhIRB{|PdH=r|0BJ&I');
define('SECURE_AUTH_SALT', 'SVwXo]#hf!9gY&2[03 Mht}dZau`aCZA@{>,?H$ki$YVh)TLqzyc;-i8$46q*e%]');
define('LOGGED_IN_SALT',   '_~`[HQb#7v%F$w$q3!y_npanU$D}$uRK6m?_&iWZy+i^OH_W*Th]cITlN?-}liwP');
define('NONCE_SALT',       'aakv0$<Fn)1AvsJvYzq7Y872{J@yqM-AezTT[%N5CaD6J[g;v=[W%iIa`Y5G;<j9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	


/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define( 'AUTOMATIC_UPDATER_DISABLED', true );

