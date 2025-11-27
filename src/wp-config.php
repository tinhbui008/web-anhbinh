<?php
ob_start();
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
 
ini_set('max_execution_time', '3000');

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') );

/** Database username */
define( 'DB_USER', getenv('WORDPRESS_DB_USER') );

/** Database password */
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') );

/** Database hostname */
define( 'DB_HOST', getenv('WORDPRESS_DB_HOST') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/** WordPress Site URL - Read from environment */
define( 'WP_HOME', getenv('WP_HOME') ?: 'http://localhost:8081' );
define( 'WP_SITEURL', getenv('WP_SITEURL') ?: 'http://localhost:8081' );

/** WordPress Debug Settings - Read from environment */
define( 'WP_DEBUG', filter_var(getenv('WP_DEBUG'), FILTER_VALIDATE_BOOLEAN) );
define( 'WP_DEBUG_LOG', filter_var(getenv('WP_DEBUG_LOG'), FILTER_VALIDATE_BOOLEAN) );
define( 'WP_DEBUG_DISPLAY', filter_var(getenv('WP_DEBUG_DISPLAY'), FILTER_VALIDATE_BOOLEAN) );

/** WordPress Environment Type */
define( 'WP_ENVIRONMENT_TYPE', getenv('WP_ENVIRONMENT_TYPE') ?: 'production' );

/** Set display_errors based on WP_DEBUG_DISPLAY */
@ini_set('display_errors', WP_DEBUG_DISPLAY ? 1 : 0);

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
define( 'AUTH_KEY',         'AvioS<$Z2=O.Cb_8+)Y3>hd pd.gzD/G{T;r_6>N^s|ZM?n%R1#R#+^RlvBS>=qg' );
define( 'SECURE_AUTH_KEY',  'd}ATAHHM fAywo1f~T1Mj]vI@2A{DBwuu1<QA}rSZlVE(:%u@oNh.kB``J$5gg!|' );
define( 'LOGGED_IN_KEY',    '[#UPDKAc^ZLp!3c4}`/k^Z7^RH}Fx-rHrsfhGvpv5(6j<,<T+,7c`F^E@d#0z,a;' );
define( 'NONCE_KEY',        '{ho,2/Gq;z=mG~w+St6g0%T[6L;3U*[/[H;Kn/ef<bJ+Aq|h<C#p-V5S&`gwnH3r' );
define( 'AUTH_SALT',        '0rn`pdk%M#_^bH%.u9xhd xVe[>pDuYxp,E;pq-rWk(NX5`VB>:>|Q0l>CqyYS92' );
define( 'SECURE_AUTH_SALT', 'aS_qN:7iQttXZZC0kH1Ip|<J;gOss}tF.3[)O7k^P.ts`EjXA(mve]>Zg)$IMD;b' );
define( 'LOGGED_IN_SALT',   'M=JRb/+k&DC|_k8GmsqzWD[kM]sb:DXy^{A]U|~eo)jQH| 53x&H6hqN!?}M7JW[' );
define( 'NONCE_SALT',       '{ {qm3gdR)D).W6^)vwUBR}ee[qD[v_:We.CEn3p?(msF}+5[OG0~^&CI/EXEUg{' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', getenv('WP_DEBUG') );
define( 'WP_MEMORY_LIMIT', '768M' );

/* Add any custom values between this line and the "stop editing" line. */
define( 'DISALLOW_FILE_EDIT', true );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
