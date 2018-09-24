<?php
/**
 * Wherewithal - Bootstrap
 *
 * @package wherewithal
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

// Make sure WordPress is calling this page.
if (!defined('ABSPATH')) {
	exit(1);
}



// Actually load our files!
require(WHEREWITHAL_PLUGIN_DIR . 'functions-admin.php');
require(WHEREWITHAL_PLUGIN_DIR . 'lib/blobfolio/wp/wherewithal/search.php');
require(WHEREWITHAL_PLUGIN_DIR . 'lib/blobfolio/wp/wherewithal/settings.php');
require(WHEREWITHAL_PLUGIN_DIR . 'lib/blobfolio/wp/wherewithal/tools.php');

// More hooks.
add_action(
	'wp_loaded',
	array('\\blobfolio\\wp\\wherewithal\\search', 'init'),
	10,
	0
);
