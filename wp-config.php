<?php

// Configuration common to all environments
include_once __DIR__ . '/wp-config.common.php';


// Configuration common to all environments
include_once __DIR__ . '/wp-config.common.php';

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
//define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . ':8080/wp');
//define('WP_HOME', 'http://' . $_SERVER['SERVER_NAME'] . ':8080/wp');
define('WP_SITEURL',"http://pyr.dev:8888");
define('WP_HOME', 'http://pyr.dev:8888');


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'avda');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         ']vN,x~l=w?2fC~r#uLE<gr$*Xa*F4TsEV|{E{Zwy_@q`>e7].pJ4KvepKNVT-C;]');
define('SECURE_AUTH_KEY',  'C*3U3{/I5CCFs8h4LI4J3,_OlC$0`l]?d`{mmNV,iR&gN27.|FX};:afLx>lq}x:');
define('LOGGED_IN_KEY',    'NvD(x1:5^KK(`(:y/?$?L(gr+=r^8kO/VbU&}k,_&_qJ3K&zSGow-vl:2G!15.O&');
define('NONCE_KEY',        'Yjg&t6@-Jp.y?,R5^`}.VKe}+xY}%u.Zi(FTJ!,B&a5xJkJ}d6< j@+nKp1RAE0y');
define('AUTH_SALT',        'BGdB`T!$@Li4,LB7yd,XMC<8Nh0~1nNKnh:;Wnz=*icX]`9g#wE.H%26@#WqeEX8');
define('SECURE_AUTH_SALT', 'CI|)wx&0}t:n?|5[I3v?lvGw1tIcm5h0a}Byjj%Xfh?(tJ})Rd&QbwqNv4`*@7G&');
define('LOGGED_IN_SALT',   'FGr55}~<?EhjF+M@j4*T75UwC.w9ARP[IUT|)IoAQAm7o$t?1*d&9&)_C3M.XI>~');
define('NONCE_SALT',       'd/m_`H(vVQ:i}| *[_L<H5nFl0^LYxvlbU4^>3/1sbX:?WmL#Y&h4O3)~0XW|6k7');

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

define('VP_ENVIRONMENT', 'default');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
