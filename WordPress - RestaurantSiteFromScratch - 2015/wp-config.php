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
define('DB_NAME', 'Ramon');

/** MySQL database username */
define('DB_USER', 'Ramon');

/** MySQL database password */
define('DB_PASSWORD', 'Rt6547kL$5');

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
define('AUTH_KEY',         'DpKXQkR0AQ`-F1&@{R|WSPMcxk@E20j;~VObI1TTs`Ts0d$(9h&+fd)(x{grc76-');
define('SECURE_AUTH_KEY',  '%^myp:|5WH^|i:?~E~gUSv4<-A3>FdjAPB9-PuTOb!K:J#Jo!0^]}g6bugolU<kU');
define('LOGGED_IN_KEY',    'R&w_Lr/B[^UoV+?>]Bc$_-N#X1Ss[z5fjIAK$)nd@[c@<:5};^QR.AfcsBQ[hJkp');
define('NONCE_KEY',        '30VI&eO-(He!<c2-7&8y*LhmDES5rt&`C~q^MJn94->-8<S<1ZvIx[g?b!X@sAHu');
define('AUTH_SALT',        'Y8|+`1}M+v6S#[`p,}Kkz4$B1tFxbO%-k40QD`y+Z+GG,V#q7W22WkRKalH[/Vl@');
define('SECURE_AUTH_SALT', 'E*+ =GVNGKW?I6[ZB<[*s6oAjKcCudg #[LA!H~};4>qxOY<HyrWG4PWn5P6_`=+');
define('LOGGED_IN_SALT',   'M+o`/J}@Sn7;J_e8DB0/Hj*;A:e+T1*yDg[K|qc=Q*rSF9bVi:wGnR x{*S,kJ- ');
define('NONCE_SALT',       '`{9g6<+i;f)]-mS46#xJUD}@A36pnz{>1lsBJD5jY ]&:q>~ym3m85}BbApA<)XS');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'Ramon_';

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
