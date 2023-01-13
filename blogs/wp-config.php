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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u633699830_ehege' );

/** MySQL database username */
define( 'DB_USER', 'u633699830_abema' );

/** MySQL database password */
define( 'DB_PASSWORD', 'yteDeWuZeT' );

/** MySQL hostname */
define( 'DB_HOST', 'mysql' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'dM{nY9X9-TDJCea`MBn$;FtFOt.+,9r1SrRqcI*xD`G.k]_RhfBmz2?xU:iKeD:P');
define('SECURE_AUTH_KEY',  'gqzjaBN;8(!xs[!2]SZC2x-r>8=mJ;a|,/p2mJ+f{7K_fFP{hMq{)d>>-:?V-ez+');
define('LOGGED_IN_KEY',    '}3zQV#b*!!S^H8ho]<Pz9Yr<XkEd `3+>H}<.Wli~b*26sF:,2(^k^|D-*?B1%e2');
define('NONCE_KEY',        '3w=nfax(;QxE^bLFAJPi4:rw:ZWg$N^RH!kb`^k%]io(KVzfzKT-S_:beM+$`5 C');
define('AUTH_SALT',        'H~Z`o!a-x)y+|obE!gh*uche)M{z2~G]SOp!lO%J^4[80Y7..<+0v/Fw;gS(O@@V');
define('SECURE_AUTH_SALT', 'l|!h%]9{yE]-u~?^era#V|O|dl&~S6QGflgFph@SW#>e.>8|VUbip;jlYJ<Je!*Y');
define('LOGGED_IN_SALT',   'a-3/oa.kCg6yyL2y%T(+Fo)Q<qO]U!ZLS2C]z*n~%u-42Oq5zw7CL3Gi|MO|+beL');
define('NONCE_SALT',       'P[Sx+|._cYRrG~z-qfA-@_t;V:k*?,;o%pqna`B*)VoN{AOXS`~E1m|%y^@r-TK5');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
