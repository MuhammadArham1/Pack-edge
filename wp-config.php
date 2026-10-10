<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pack-edge' );

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
define( 'AUTH_KEY',         '%:3&fBZb[,r[!EN8sjryN1d5-oR}i;V 4/;5=WXQn,lJvZjg;>U)Ep%8YJ0~8Ee{' );
define( 'SECURE_AUTH_KEY',  '}?2crtV_cd>sQ)C%?M%g+6M0+3MtRhUQb@A+x?H#~=?NuO:7z/q30BIA#.jU0xb,' );
define( 'LOGGED_IN_KEY',    '2WIP/A${_Amu>E5n>|ciyL0UgDAvbge%m@hpxH?@52.j2Bn3.|6KTm+ME}h%8C=^' );
define( 'NONCE_KEY',        'lPI8T6HB^+F2B9+&x)kBa`C8E!|Gt&M}2ZC?g)y`SyTHrMKlkk?4MuZ*@6Y_Kt[I' );
define( 'AUTH_SALT',        'fJ< kU!9J}RJW`3hFqS`rM=HAjdq8ZHfC^Jk9*ii$>HlqrbURkBb_r|pk6=ZK1[o' );
define( 'SECURE_AUTH_SALT', 'u*:%}R:<kBJ?J]JA,31_[;@K&3&DW#aiB1!ZqmNxg 9{?mZ N&Cov-myI*f%ttY(' );
define( 'LOGGED_IN_SALT',   '#Tta&6j Ma$NEaTm_-b7XqR>q{x1z{ubQYyCj|@c.,AW_ET$@^$wR*J$i^,#|P%O' );
define( 'NONCE_SALT',       'd9(%s&UW>EoB`Q!0[L?aQfCn<!s)(FKYIE4d05Mx^~_AIu.fl3Msg~pQSKAQ#xI7' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
