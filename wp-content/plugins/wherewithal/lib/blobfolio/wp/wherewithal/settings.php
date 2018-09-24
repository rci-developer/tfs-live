<?php
/**
 * Wherewithal - Settings
 *
 * @package wherewithal
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\wherewithal;

use WP_Error;

class settings {

	// Default options template.
	const TEMPLATE = array(
		'exclude'=>array(
			'post_types'=>array(),
			'postmeta_keys'=>array(),
			'posts'=>array(),
			'taxonomies'=>array(),
			'termmeta_keys'=>array(),
			'terms'=>array(),
		),
		'haystack'=>array(
			'comments'=>0,
			'postmeta'=>0,
			'term_descriptions'=>0,
			'term_names'=>0,
			'termmeta'=>0,
		),
		'exclude_global'=>array(
			'post_types'=>array(),
			'posts'=>array(),
		),
	);

	private static $options;
	private static $active = false;



	/**
	 * Get Option
	 *
	 * @param string $category Category.
	 * @param string $option Option.
	 * @param bool $refresh Refresh.
	 * @return mixed Option(s).
	 */
	public static function get_option($category=null, $option=null, $refresh=false) {
		static::get_options($refresh);

		// Return everything.
		if (is_null($category)) {
			return static::$options;
		}
		// Invalid category.
		elseif (!isset(static::$options[$category])) {
			return new WP_Error('category', __('Invalid option category.', 'wherewithal'));
		}

		// Return all options in the category.
		if (is_null($option)) {
			return static::$options[$category];
		}
		// Invalid option.
		elseif (!isset(static::$options[$category][$option])) {
			return new WP_Error('option', __('Invalid option.', 'wherewithal'));
		}

		// Return one thing.
		return static::$options[$category][$option];
	}

	/**
	 * Is Deep Search Active?
	 *
	 * @return bool True/false.
	 */
	public static function is_active() {
		static::get_options();
		return static::$active;
	}

	/**
	 * Load Options
	 *
	 * @param bool $refresh Refresh.
	 * @return void Nothing.
	 */
	private static function get_options($refresh=false) {
		// Nothing to do.
		if (!$refresh && is_array(static::$options)) {
			return;
		}

		// Parse raw options.
		$raw = get_option('wherewithal_options', array());
		$options = tools::parse_args($raw, static::TEMPLATE);

		// All post types.
		$post_types_all = get_post_types();
		$post_types_hidden = get_post_types(array('exclude_from_search'=>true));
		$post_types_all = array_diff_key($post_types_all, $post_types_hidden);

		// Global exclude post types.
		static::key_settings($options['exclude_global']['post_types'], $post_types_all);

		// Deep search post types.
		$possible = array_diff($post_types_all, $options['exclude_global']['post_types']);
		static::key_settings($options['exclude']['post_types'], $possible);

		// Deep search taxonomies.
		$taxonomies = get_taxonomies();
		static::key_settings($options['exclude']['taxonomies'], array_keys($taxonomies));

		// Misc deep search keys.
		foreach (array('postmeta_keys', 'termmeta_keys') as $field) {
			static::key_settings($options['exclude'][$field]);
		}

		// Misc deep search numeric.
		foreach (array('posts', 'terms') as $field) {
			static::int_settings($options['exclude'][$field]);
		}
		// Remove global posts from deep posts.
		$options['exclude']['posts'] = array_diff($options['exclude']['posts'], $options['exclude_global']['posts']);
		sort($options['exclude']['posts']);

		// Global exclude posts.
		static::int_settings($options['exclude_global']['posts']);

		// Sanitize haystacks.
		$options['haystack'] = tools::to_binary($options['haystack']);

		// Resave if changed.
		if (json_encode($raw) !== json_encode($options)) {
			update_option('wherewithal_options', $options);
		}

		static::$options = $options;
		static::$active = array_sum($options['haystack']) > 0;
	}

	/**
	 * Save Options
	 *
	 * @param array $raw Raw.
	 * @return array Options.
	 */
	public static function save_options($raw=null) {
		// The sanitizing will be handled by get_options() and if
		// necessary resave the data.
		update_option('wherewithal_options', $raw);
		static::get_options(true);
		return static::$options;
	}

	/**
	 * Sanitize Integer Options
	 *
	 * @param array $settings Settings.
	 * @return void Nothing.
	 */
	protected static function int_settings(&$settings) {
		if (!is_array($settings)) {
			$settings = array();
			return;
		}

		foreach ($settings as $k=>$v) {
			$settings[$k] = tools::to_int($v, true);
			if ($settings[$k] <= 0) {
				unset($settings[$k]);
			}
		}

		$settings = array_unique($settings);
		sort($settings);
	}

	/**
	 * Sanitize Key Options
	 *
	 * @param array $settings Settings.
	 * @param array $possible Possible.
	 * @return void Nothing.
	 */
	protected static function key_settings(&$settings, $possible=null) {
		if (!is_array($settings)) {
			$settings = array();
			return;
		}

		$settings = array_filter(array_map('sanitize_key', $settings), 'strlen');
		$settings = array_unique($settings);

		if (is_array($possible) && count($possible)) {
			$possible = array_unique($possible);
			$settings = array_intersect($settings, $possible);
		}

		sort($settings);
	}
}
