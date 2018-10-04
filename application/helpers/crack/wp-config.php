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
define('DB_NAME', 'starfishgit');

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
define('AUTH_KEY',         '::~Q6t}`u&sH{o}s//(:pNS7hfDj+^Qqi27RO1R$UlCIFD@0Qr,-9wR3[ufObgv`');
define('SECURE_AUTH_KEY',  'q#vO][tDm d^138hbA8zCh43##g>rB#(]i-Rt -hVEok+}kt)!wPHGXOh`vy$h|Y');
define('LOGGED_IN_KEY',    'V(=W682,nFF9t)V(DAtgd.WJ@i^KE}G IER|ti,RoC`;ivi~oP#O<e8a6Xo~]j@C');
define('NONCE_KEY',        'o!<O.WWWFMF,b4;3;L5t%OFw&t#liw6h4wL5Nsq~c%h7C{~={L85Q<~&C}zb3N5J');
define('AUTH_SALT',        '[V*PA%waj!/8h0V[vE,+0>I{plt Sk`P4(m0PaOL+xlhqJqWGW3!sf;E!k%;cL 4');
define('SECURE_AUTH_SALT', 'b$7flZzpJ< S#1{{k(9#,<U<rqp.T^?@>^$$Okam)dhM]p/,Hqw%e$orU4DW#{yY');
define('LOGGED_IN_SALT',   'q3_SgO_Z3bg42$P}L`Wa[e3q07S{x7}OB0S[J&4WIE&w/!_5n9/DDbTN)|W{P~,5');
define('NONCE_SALT',       '~tIw@jv:h?q/_Ar%7WqH}]38Hc1v)Oq[$ZcaaSmx:>%<>j^w R4rzSbT9#2b!KWi');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
