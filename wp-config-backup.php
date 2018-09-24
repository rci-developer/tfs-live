<?php

define('WP_HOME','https://tfsuk.roswellit.solutions');
define('WP_SITEURL','https://tfsuk.roswellit.solutions');


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
define('DB_NAME', 'roswell_wp331');

/** MySQL database username */
define('DB_USER', 'roswell_wp331');

/** MySQL database password */
define('DB_PASSWORD', '52]!GuppS8');

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
define('AUTH_KEY',         'fmnsqiab3nxnqq261izxvp0bsyq8npj4bohysclvjoruxttorqjvjayzleww860v');
define('SECURE_AUTH_KEY',  'zt0fu0wydhbsj4qtawqykz8jns3aaxry0bnnoreirqookkhfm2ugfjtuqzkzlxoz');
define('LOGGED_IN_KEY',    'fk16k43sbsxfsvevon8ozgaoous6bappkzsr2tlarlwsp0dfmvhwwnhsn4fxeffg');
define('NONCE_KEY',        'wmrzmc1jowzhu966dzqqhgbuzdhh1l3otrw0swvwryvqcuyb2avv8qlqfr5qblfl');
define('AUTH_SALT',        'iosyw4neoeair3itqgk79osjqjyvdlktyy6l3bxp7hsupks1u9zawcsy0xh7wuuc');
define('SECURE_AUTH_SALT', 'oj9aqiug7ch2wk0spmbdp0n8ww0rin9ble0rkhkqk3ue5yhc0ahfzpcwhp54oe1u');
define('LOGGED_IN_SALT',   'p9trypaagyquyamxcbht9mqbg39kpzzbzbuy22efabhurku61xemvni59tit3w82');
define('NONCE_SALT',       'kz8kcledxinefo1whifo3fy0b2i89a8pjdfnxbjhz1io0v9u8mxo04kjhghtdlz4');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpot_';

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
define('WP_DEBUG', 0);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
