<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
$db=getenv('OPENSHIFT_GEAR_NAME');
$host=getenv('OPENSHIFT_MYSQL_DB_HOST');
$port=getenv('OPENSHIFT_MYSQL_DB_PORT');
$usr=getenv('OPENSHIFT_MYSQL_DB_USERNAME');
$pass=getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
define('DB_NAME', $db);

/** MySQL database username */
define('DB_USER', $usr);

/** MySQL database password */
define('DB_PASSWORD', $pass);

/** MySQL hostname */
define('DB_HOST', $host);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '+t,noF|=T{2v|: 2zZ#V7yTR-Ad(S0;]SGya@M~UT(Z0}?t]m}|Lk~6BWSYL3OSE');
define('SECURE_AUTH_KEY',  '|I^:GwrgSx!fv.L.K7Z-Xa+N.L#6*Fc7>x-&?n>{-r1t|,RyVn=;x>qb]ed>mkxR');
define('LOGGED_IN_KEY',    'wwtJ;KMIi|`dPBU_SyLT%N^%}?4~pQF7,bKA.z=e`i&K:}nwN-v^zVl31}+2YP_P');
define('NONCE_KEY',        '9K/AY@MDu[5jRlXs+3|Kfpdu*q]6fMomNV4C+N96clM{<_u]ND#+`^>ZhJDS0o1k');
define('AUTH_SALT',        '^-m>JHxqL+$,nY%QrBE>#FIwFL|T/^qIkRS>D|FkSa!gH;<=:yY6-yvrKTE}|Mhi');
define('SECURE_AUTH_SALT', 'idUt)]CF?iE-2<B4A{-ocMcL{!a+E;avS{t-hXk/FYDKaLKKnEqx7o#KzFX<F+{X');
define('LOGGED_IN_SALT',   'w$7~002O;QffK,-<q2iNWo!7ve/MwXh>-.R+Js^ !+67R[~i=BAtYhg@Pz-KQ@NJ');
define('NONCE_SALT',       'B,!JPA#*pMox0w^c|8U:!P?9eOLKefc12*3mhC=bJ7Y|r4vIE24yu+jdWBW,_(qc');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
