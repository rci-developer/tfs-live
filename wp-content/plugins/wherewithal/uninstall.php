<?php
/**
 * Wherewithal - Uninstall
 *
 * @package wherewithal
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

// Make sure WordPress is calling this page.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit(1);
}



// Remove options.
delete_option('wherewithal_options');
