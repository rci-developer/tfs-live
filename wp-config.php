<?php

define('WP_HOME','https://tfs.direct');
define('WP_SITEURL','https://tfs.direct');

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
define('DB_NAME', 'tfs_live');

/** MySQL database username */
define('DB_USER', 'tfs_user');

/** MySQL database password */
define('DB_PASSWORD', 'Solaris1');

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
define('AUTH_KEY',         'm]slh56,h]x>-B.fe$M&Bs;Q(6xq+7v{M6@IxWW@d7I72%lVAwP.2j$r|/PVMcP_');
define('SECURE_AUTH_KEY',  '9=$PN8PU0hf;Fe= {Y?__Nm.k!9mW=u4(|L^Vg2`9Z~v.wiZXz4VRww&_X~pzATu');
define('LOGGED_IN_KEY',    'K}R1|Ft#w>*cw!E~ _AooCW_8OHH@&%T;wq63L6##K$~@QQ6V>Kf%99*`)(*n-n_');
define('NONCE_KEY',        'NOc$T]pjC.k|Q+XUYmBJ@WjHak.L;zU[Db9Q!]o!:B-+h5h_h(39+ ]x|604;E{1');
define('AUTH_SALT',        'Ih~(yzxg~N7P{71=wk$w?^G(gJ}_%F}jJZc>cX 3uRw@K+~xVPuTDLCC-1g !;8k');
define('SECURE_AUTH_SALT', 'O(rVrN=s!dV^jcg+3{N;c$?_>Kuu{{9e<[36BSW7dE#S/<<dT;Ke4C,kf{pmZXI(');
define('LOGGED_IN_SALT',   'X.> mk& }urUf4tYuP]EkSf55dq(][9A8o)!<Y`InolEyi|z]Nzg3!~317c-(+zC');
define('NONCE_SALT',       'P1*RVS!rB6TmuIWd99O5/rOm=Ud(ZmQY%hYRn:@~AA1br+6.(T?t4vJ 8+RNyb<3');

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
