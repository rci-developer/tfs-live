<?php
/**
 * Wherewithal - Admin
 *
 * Set up admin area pages, etc.
 *
 * @package wherewithal
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

// This must be called through Wordpress.
if (!defined('ABSPATH')) {
	exit(1);
}



// ---------------------------------------------------------------------
// Menus
// ---------------------------------------------------------------------

/**
 * Menu: Settings
 *
 * @return void Nothing.
 */
function wherewithal_admin_menu() {
	add_options_page(
		__('Wherewithal Enhanced Search', 'wherewithal'),
		__('Wherewithal Enhanced Search', 'wherewithal'),
		'manage_options',
		'wherewithal-settings',
		'wherewithal_settings_page'
	);
}
add_action('admin_menu', 'wherewithal_admin_menu');

/**
 * Plugin Link: Settings
 *
 * @param array $links Links.
 * @return array Links.
 */
function wherewithal_plugin_settings_link($links) {
	$links[] = '<a href="' . esc_url(admin_url('options-general.php?page=wherewithal-settings')) . '">' . __('Settings', 'wherewithal') . '</a>';
	return $links;
}
add_filter('plugin_action_links_wherewithal/index.php', 'wherewithal_plugin_settings_link');

/**
 * Page: Settings
 *
 * @return void Nothing.
 */
function wherewithal_settings_page() {
	require(WHEREWITHAL_PLUGIN_DIR . 'admin-settings.php');
}

// --------------------------------------------------------------------- end menus
