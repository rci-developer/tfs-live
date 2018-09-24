<?php
/**
 * Extend WP's built-in search capabilities to automatically include matches from comments, custom fields, taxonomies, and more.
 *
 * @package Wherewithal Enhanced Search
 * @version 1.6.1
 *
 * @wordpress-plugin
 * Plugin Name: Wherewithal Enhanced Search
 * Version: 1.6.1
 * Plugin URI: https://wordpress.org/plugins/wherewithal/
 * Description: Extend WP's built-in search capabilities to automatically include matches from comments, custom fields, taxonomies, and more.
 * Text Domain: wherewithal
 * Domain Path: /languages/
 * Author: Blobfolio, LLC
 * Author URI: https://blobfolio.com/
 * License: WTFPL
 * License URI: http://www.wtfpl.net
 */

// This must be called through Wordpress.
if (!defined('ABSPATH')) {
	exit(1);
}

// Set up some constants.
define('WHEREWITHAL_PLUGIN_DIR', __DIR__ . '/');
define('WHEREWITHAL_INDEX', __FILE__);

// Are we installed in Must-Use mode?
$wherewithal_must_use = (
	defined('WPMU_PLUGIN_DIR') &&
	@is_dir(WPMU_PLUGIN_DIR) &&
	(0 === strpos(WHEREWITHAL_PLUGIN_DIR, WPMU_PLUGIN_DIR))
);
define('WHEREWITHAL_MUST_USE', $wherewithal_must_use);



// ---------------------------------------------------------------------
// Compatibility
// ---------------------------------------------------------------------

/**
 * Force Deactivation
 *
 * @return void Nothing.
 */
function wherewithal_deactivate() {
	// We cannot deactivate an MU plugin.
	if (!WHEREWITHAL_MUST_USE) {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		deactivate_plugins(WHEREWITHAL_INDEX);
	}
}

/**
 * Compatibility Notice
 *
 * @param string $error Error.
 * @return void Nothing.
 */
function wherewithal_compatibility_notice($error) {
	if (WHEREWITHAL_MUST_USE) {
		$error .= ' ' . __('Because this plugin was installed to the Must Use folder, it must be removed manually.', 'wherewithal');
	}
	else {
		$error .= ' ' . __('The plugin has been deactivated automatically.', 'wherewithal');
	}
	?>
	<div class="notice notice-error">
		<p>
			<strong><?php echo __('Error', 'wherewithal'); ?>:</strong>
			<?php echo $error; ?>
		</p>
	</div>
	<?php
}

/**
 * Compatibility Issue: Multisite
 *
 * @return void Nothing.
 */
function wherewithal_compatibility_multisite() {
	wherewithal_compatibility_notice(
		__('Wherewithal Enhanced Search is not compatible with WordPress Multisite.', 'wherewithal')
	);
}
if (is_multisite()) {
	add_action('admin_init', 'wherewithal_deactivate');
	add_action('admin_notices', 'wherewithal_compatibility_multisite');
	add_action('plugins_loaded', 'wherewithal_localize');
	return;
}

/**
 * Compatibility Issue: Low PHP Version
 *
 * @return void Nothing.
 */
function wherewithal_compatibility_php() {
	wherewithal_compatibility_notice(
		__('Wherewithal Enhanced Search requires PHP 5.6.0 or newer.', 'wherewithal')
	);
}
if (version_compare(PHP_VERSION, '5.6.0') < 0) {
	add_action('admin_init', 'wherewithal_deactivate');
	add_action('admin_notices', 'wherewithal_compatibility_php');
	add_action('plugins_loaded', 'wherewithal_localize');
	return;
}

// --------------------------------------------------------------------- end compatibility



// ---------------------------------------------------------------------
// True Init
// ---------------------------------------------------------------------

/**
 * Localize
 *
 * @return void Nothing.
 */
function wherewithal_localize() {
	// A slightly different function is required for MU plugins for some
	// reason. Haha.
	if (WHEREWITHAL_MUST_USE) {
		load_muplugin_textdomain(
			'wherewithal',
			basename(WHEREWITHAL_PLUGIN_DIR) . '/languages'
		);
	}
	else {
		load_plugin_textdomain(
			'wherewithal',
			false,
			basename(WHEREWITHAL_PLUGIN_DIR) . '/languages'
		);
	}
}
add_action('plugins_loaded', 'wherewithal_localize');

// We made it! Load our bootstrap.
require(WHEREWITHAL_PLUGIN_DIR . 'bootstrap.php');

// --------------------------------------------------------------------- end init
