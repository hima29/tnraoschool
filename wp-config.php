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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('FS_METHOD', 'direct');
define('FTP_USER', 'username'); // Your FTP username
define('FTP_PASS', 'password'); // Your FTP password
define('FTP_HOST', 'ftp.example.org:21'); // Your FTP URL:Your FTP port
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '6SnV9qE8J0S4nz$AER8U@z09VTjp~2j.8~:l<a^`;s|BItb4OE;JrZ6sOM! ]S~`' );
define( 'SECURE_AUTH_KEY',  'cytSksCP ?7K`U$:E(4.L.13,2^{*jO6F7,jC;6h9RJKkQVJbHPA+p[*k4LMe8ni' );
define( 'LOGGED_IN_KEY',    '%rYM&V*O/=X{u`hbX0[^.qR_C5&7hA>f/kDB$=0?v{ok<e;RxL5Sw2/ipY/b12H$' );
define( 'NONCE_KEY',        '){=/,|_$9>NOlE=y.>C|Ve`:DKa8CYi;(g^-Xml[x}[oK<E.pIZlPS85R4OF^qPE' );
define( 'AUTH_SALT',        'S5;33U2Re.x~=YrAMayC5JnPGP<6*ncl^k[M?KOU^O--amK[8pZ<1!=lxt@oy1#t' );
define( 'SECURE_AUTH_SALT', 'cq|4%6Q(K{Sdtq ,@v,:5jBj:>j&:j&,z-+tjr{x/o2CT0cVcmh]ij*O/OBe[!<.' );
define( 'LOGGED_IN_SALT',   'GG6k220x$[zQS buzVYn`u>YfI,rn^?I|Kmtmlqvs^@!ltho0@[O}B[0<<Maaygj' );
define( 'NONCE_SALT',       '+ezLJ)zD|E>;%*YH(xJ<G5MNKjvij!]&lWC%.*a#$z:Cx?OQqJK!TJ,;+>nX+Ab]' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_test';

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
define( 'WP_DEBUG', false );
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
