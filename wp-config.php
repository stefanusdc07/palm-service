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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'palm' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         'rXb5EHghmfOM5z8MVCltAC9eXBjHVf4cPtiDu2v3lA0hmHaBrFDd8jcBF9aWqk4k' );
define( 'SECURE_AUTH_KEY',  'Gwnva45lcdZ9tVc9Fg8GIpH53QLwJjl1SNKUHnM9iWi5TZSl8VytHJLMnLN89iKm' );
define( 'LOGGED_IN_KEY',    'FO5Cc7cxtVkwMw1ByAlqCj4SLT28oieoYD1uN2ZFZ8gr5JtChR3lsqH8yqY7s4g4' );
define( 'NONCE_KEY',        'aECGgrGzXNLZwPjHFPhDj8AQZTz3ff6UTcclWAioEXqgZZU6n4M6yHj95BRftYYj' );
define( 'AUTH_SALT',        'o277JYAHXLG5lbvbJoevH4O8HE7K2mXT6ozyxHS2izSVantu2AHyrHIYmTbhjodV' );
define( 'SECURE_AUTH_SALT', 'ZmGnYgAmBBijo0v0cQPOPt7Eq5ZofUPAepoXS5L8YtBsmWxjVkoIg4o4BmWNot65' );
define( 'LOGGED_IN_SALT',   'JPY1eMO6vITxjdFfVkcYHgltZ0NWJt3UsQeWc0BOE0VPCk1KwjkgDdwU51MgUbTm' );
define( 'NONCE_SALT',       'kXCLQs4x4LFpeSegS27ZA5bedIKZ9rswJQB5EFkXvAsbKHeLapPaaGHJHoaXFXrW' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
