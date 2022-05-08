<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wpbp' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '%AHw?Z5]{N^a%~F6V]_K4t<Te6Vc:,B>grB1]OMn&7 Ai#<9?x7&_ry:z0f;Ots:' );
define( 'SECURE_AUTH_KEY',  ';03:YxV<?wA6C:`DTkQoXc$u>sL=]*~p>a=:bk6Kh>;`^tCBX]!T5v`.QAiKGXXg' );
define( 'LOGGED_IN_KEY',    'J|7P|VXbn62[|BV{<?:6 .7BO| Hmx0XW6p!S#z]7q!}k*e*gnZ6~[5f!zN>qPES' );
define( 'NONCE_KEY',        'Xq ^BE|a}v37B:/rQic B%]{!Omtx4[E}Or_4>l|UHlJynm>m8vL*0:#EO]QB^6.' );
define( 'AUTH_SALT',        'Ic/ZTjS_#m8+d2vrtP%v`%4qH47XXa:>DPh%tu;[>|Iw`|rvZJfk:%2fymQj!0^u' );
define( 'SECURE_AUTH_SALT', 'P;>)B_2VzZ7=zIeVMDO8Q(w-%L)qwYEEp`STBN0J ,xptl ++zaMHLKU#pyc,]q9' );
define( 'LOGGED_IN_SALT',   '*9HKat>MSo{fGgim)p!LevB9!)J/rc.o:8Wg*3vPv#u)(Z[Vy69o$(i$b<hvDiO:' );
define( 'NONCE_SALT',       'Q#<<%}Jn,Kad}]m<bpijXIx0T{i)#z5H.xHB*G[@/[cx|`!XM)FE~{b$-G#j7rzk' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
