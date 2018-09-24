<?php
/**
 * Wherewithal - Search
 *
 * @package wherewithal
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\wherewithal;

class search {

	// Always ignore these keys.
	const IGNORE_POSTMETA_EXACT = array(
		'_edit_last',
		'_edit_lock',
		'_thumbnail_id',
	);

	// Always ignore keys beginning like so.
	const IGNORE_POSTMETA_LIKE = array(
		'_bbp',
		'_oembed',
		'_wp_',
	);

	const ALIAS_POSTMETA = 'wesPM';
	const ALIAS_COMMENTMETA = 'wesCM';
	const ALIAS_COMMENTS = 'wesC';
	const ALIAS_TERMS = 'wesT';
	const ALIAS_TERMMETA = 'wesTM';
	const ALIAS_TERM_TAXONOMY = 'wesTT';
	const ALIAS_TERM_RELATIONSHIPS = 'wesTR';

	private static $exact = false;
	private static $init = false;
	private static $active = true;

	// -----------------------------------------------------------------
	// INIT
	// -----------------------------------------------------------------

	/**
	 * Init.
	 *
	 * @return void Nothing.
	 */
	public static function init() {
		// Only do this once.
		if (static::$init) {
			return;
		}
		static::$init = true;

		// Nothing to do?
		if (!isset($_GET['s']) || !settings::is_active()) {
			return;
		}

		// Hook into search, though we may not end up doing anything.
		$class = get_called_class();
		add_filter('posts_search', array($class, 'posts_search'), 10, 2);
		add_filter('posts_join', array($class, 'posts_join'), 10, 2);
		add_filter('posts_request', array($class, 'posts_request'), 10, 2);
	}

	// ----------------------------------------------------------------- end init



	// -----------------------------------------------------------------
	// WHERE PARSING
	// -----------------------------------------------------------------

	/**
	 * Term Yes/No
	 *
	 * Alter search parameters for inclusion/exclusion.
	 *
	 * @param string $term Term.
	 * @return array Data.
	 */
	private static function post_term_status(&$term) {
		$out = array(
			'mode'=>'include',
			'like'=>'LIKE',
			'join'=>'OR',
		);

		// Excluding instead.
		if (0 === strpos($term, '-')) {
			$term = substr($term, 1);
			$out = array(
				'mode'=>'exclude',
				'like'=>'NOT LIKE',
				'join'=>'AND',
			);
		}

		return $out;
	}

	/**
	 * Posts Search: Main Callback
	 *
	 * @param string $where Where.
	 * @param WP_Query $query Query.
	 * @return string Where.
	 */
	public static function posts_search($where, $query) {
		global $wpdb;

		// The query might (or might not) end in a post_password
		// condition separate from the others. If it does, let's
		// temporarily remove it so we can get on with extending
		// the main conditions. We'll add it back at the end.
		$suffix = "  AND ({$wpdb->posts}.post_password = '') ";
		if (substr($where, 0 - strlen($suffix)) === $suffix) {
			$where = substr($where, 0, strlen($where) - strlen($suffix));
		}
		else {
			$suffix = '';
		}

		// Don't modify admin or secondary queries.
		if (
			!static::$active ||
			is_admin() ||
			!$query->is_search() ||
			!$query->is_main_query() ||
			!is_array($query->query_vars['search_terms']) ||
			!count($query->query_vars['search_terms']) ||
			!preg_match('/^\s+AND\s+\(\(.*\)\)\s*$/', $where)
		) {
			static::$active = false;
			return "{$where}{$suffix}";
		}

		// Try to tease out the original conditions.
		$old_conds = preg_replace('/^\s+AND\s\(\(/', '', $where);
		$old_conds = trim(preg_replace('/\)\)\s*$/', '', $old_conds));
		if (!$old_conds) {
			return "{$where}{$suffix}";
		}

		$q = $query->query_vars;
		static::$exact = !empty($q['exact']) ? '' : '%';
		$haystacks = settings::get_option('haystack');

		// Loop through our terms.
		$conds = array();
		foreach ($q['search_terms'] as $term) {
			$include = static::post_term_status($term);
			// Pre-escape the term.
			$term = static::$exact . esc_sql($wpdb->esc_like($term)) . static::$exact;

			// Search in each haystack.
			if ($haystacks['comments']) {
				static::posts_search_comments($term, $include, $conds);
			}
			if ($haystacks['postmeta']) {
				static::posts_search_postmeta($term, $include, $conds);
			}
			if ($haystacks['term_names']) {
				static::posts_search_term_names($term, $include, $conds);
			}
			if ($haystacks['term_descriptions']) {
				static::posts_search_term_descriptions($term, $include, $conds);
			}
			if ($haystacks['termmeta']) {
				static::posts_search_termmeta($term, $include, $conds);
			}
		}
		$new_conds = implode(' OR ', $conds);

		// Are there global excludes?
		$global_conds = array();
		$exclude_global = settings::get_option('exclude_global');

		if (count($exclude_global['post_types'])) {
			$exclude_global['post_types'] = array_map('esc_sql', $exclude_global['post_types']);
			$global_conds[] = "NOT({$wpdb->posts}.post_type IN ('" . implode("','", $exclude_global['post_types']) . "'))";
		}

		if (count($exclude_global['posts'])) {
			$global_conds[] = "NOT({$wpdb->posts}.ID IN (" . implode(',', $exclude_global['posts']) . '))';
		}

		$global_conds = count($global_conds) ? '(' . implode(' AND ', $global_conds) . ')' : 1;

		// Build the new WHERE, old + new.
		$where = " AND ($global_conds) AND (($old_conds OR $new_conds)) $suffix";

		return $where;
	}

	/**
	 * Posts Search: Comments
	 *
	 * @param string $term Term.
	 * @param array $include Include data.
	 * @param array $conds Conditions.
	 * @return void Nothing.
	 */
	private static function posts_search_comments(&$term, &$include, &$conds) {
		global $wpdb;
		$conds[] = '(' . static::ALIAS_COMMENTS . ".comment_content {$include['like']} '$term')";
	}

	/**
	 * Posts Search: Postmeta
	 *
	 * @param string $term Term.
	 * @param array $include Include data.
	 * @param array $conds Conditions.
	 * @return void Nothing.
	 */
	private static function posts_search_postmeta(&$term, &$include, &$conds) {
		global $wpdb;
		$conds[] = '(' . static::ALIAS_POSTMETA . ".meta_value {$include['like']} '$term')";
	}

	/**
	 * Posts Search: Term Name
	 *
	 * @param string $term Term.
	 * @param array $include Include data.
	 * @param array $conds Conditions.
	 * @return void Nothing.
	 */
	private static function posts_search_term_names(&$term, &$include, &$conds) {
		global $wpdb;
		$conds[] = '(' . static::ALIAS_TERMS . ".name {$include['like']} '$term')";
	}

	/**
	 * Posts Search: Term Description
	 *
	 * @param string $term Term.
	 * @param array $include Include data.
	 * @param array $conds Conditions.
	 * @return void Nothing.
	 */
	private static function posts_search_term_descriptions(&$term, &$include, &$conds) {
		global $wpdb;
		$conds[] = '(' . static::ALIAS_TERM_TAXONOMY . ".description {$include['like']} '$term')";
	}

	/**
	 * Posts Search: Term Meta
	 *
	 * @param string $term Term.
	 * @param array $include Include data.
	 * @param array $conds Conditions.
	 * @return void Nothing.
	 */
	private static function posts_search_termmeta(&$term, &$include, &$conds) {
		global $wpdb;
		$conds[] = '(' . static::ALIAS_TERMMETA . ".meta_value {$include['like']} '$term')";
	}

	// ----------------------------------------------------------------- end where




	// -----------------------------------------------------------------
	// JOIN PARSING
	// -----------------------------------------------------------------

	/**
	 * Posts Join: Main Callback
	 *
	 * @param string $join Join.
	 * @param WP_Query $wp_query Query.
	 * @return string Join.
	 */
	public static function posts_join($join, $wp_query) {
		global $wpdb;

		// Don't modify admin or secondary queries.
		if (!static::$active) {
			return $join;
		}

		$haystacks = settings::get_option('haystack');

		// Rebuild the excludes to be SQL-ready.
		$excludes = settings::get_option('exclude');
		foreach ($excludes as $k=>$v) {
			// Strings.
			if (in_array($k, array('post_types', 'postmeta_keys', 'taxonomies', 'termmeta_keys'), true)) {
				// Always exclude certain postmeta_keys.
				if ('postmeta_keys' === $k) {
					$v = array_merge(static::IGNORE_POSTMETA_EXACT, $v);
					$v = array_unique($v);
				}

				$excludes[$k] = count($v) ? "('" . implode("','", array_map('esc_sql', $v)) . "')" : false;
			}
			else {
				$excludes[$k] = count($v) ? '(' . implode(',', $v) .  ')' : false;
			}
		}

		// Apply relevant filters.
		if ($haystacks['comments']) {
			static::posts_join_comments($join, $excludes);
		}
		if ($haystacks['postmeta']) {
			static::posts_join_postmeta($join, $excludes);
		}
		// Gotta load a bunch of term tables if any term filters are
		// used.
		if ($haystacks['term_names'] || $haystacks['term_descriptions'] || $haystacks['termmeta']) {
			static::posts_join_terms($join, $excludes);
		}
		// Term meta is on its own.
		if ($haystacks['termmeta']) {
			static::posts_join_termmeta($join, $excludes);
		}

		return $join;
	}

	/**
	 * Posts Join: Comments
	 *
	 * @param string $join Join.
	 * @param array $excludes Excludes.
	 * @return void Nothing.
	 */
	private static function posts_join_comments(&$join, &$excludes) {
		global $wpdb;

		$on = array();
		$on[] = static::ALIAS_COMMENTS . ".comment_post_ID = {$wpdb->posts}.ID";
		$on[] = static::ALIAS_COMMENTS . '.comment_approved = 1';
		if ($excludes['post_types']) {
			$on[] = "NOT({$wpdb->posts}.post_type IN {$excludes['post_types']})";
		}
		if ($excludes['posts']) {
			$on[] = "NOT({$wpdb->posts}.ID IN {$excludes['posts']})";
		}

		$join .= " LEFT JOIN {$wpdb->comments} AS " . static::ALIAS_COMMENTS . ' ON (' . implode(' AND ', $on) . ') ';
	}

	/**
	 * Posts Join: Postmeta
	 *
	 * @param string $join Join.
	 * @param array $excludes Excludes.
	 * @return void Nothing.
	 */
	private static function posts_join_postmeta(&$join, &$excludes) {
		global $wpdb;

		$on = array();
		$on[] = static::ALIAS_POSTMETA . ".post_id = {$wpdb->posts}.ID";

		// Ignore our likes.
		foreach (static::IGNORE_POSTMETA_LIKE as $v) {
			$length = strlen($v);
			$on[] = 'LEFT(' . static::ALIAS_POSTMETA . ".meta_key, $length) != '$v'";
		}

		// Don't waste time on ACF fields.
		$on[] = 'LEFT(' . static::ALIAS_POSTMETA . ".meta_value, 6) != 'field_'";

		if ($excludes['post_types']) {
			$on[] = "NOT({$wpdb->posts}.post_type IN {$excludes['post_types']})";
		}

		if ($excludes['posts']) {
			$on[] = "NOT({$wpdb->posts}.ID IN {$excludes['posts']})";
		}

		if ($excludes['postmeta_keys']) {
			$on[] = 'NOT(' . static::ALIAS_POSTMETA . ".meta_key IN {$excludes['postmeta_keys']})";
		}

		$join .= " LEFT JOIN {$wpdb->postmeta} AS " . static::ALIAS_POSTMETA . ' ON (' . implode(' AND ', $on) . ') ';
	}

	/**
	 * Posts Join: Terms (bundled)
	 *
	 * @param string $join Join.
	 * @param array $excludes Excludes.
	 * @return void Nothing.
	 */
	private static function posts_join_terms(&$join, &$excludes) {
		global $wpdb;

		// Relationships.
		$on = array();
		$on[] = static::ALIAS_TERM_RELATIONSHIPS . ".object_id = {$wpdb->posts}.ID";
		if ($excludes['post_types']) {
			$on[] = "NOT({$wpdb->posts}.post_type IN {$excludes['post_types']})";
		}
		if ($excludes['posts']) {
			$on[] = "NOT({$wpdb->posts}.ID IN {$excludes['posts']})";
		}

		$join .= " LEFT JOIN {$wpdb->term_relationships} AS " . static::ALIAS_TERM_RELATIONSHIPS . ' ON (' . implode(' AND ', $on) . ') ';

		// Taxonomy.
		$on = array();
		$on[] = static::ALIAS_TERM_TAXONOMY . '.term_taxonomy_id = ' . static::ALIAS_TERM_RELATIONSHIPS . '.term_taxonomy_id';
		if ($excludes['taxonomies']) {
			$on[] = 'NOT(' . static::ALIAS_TERM_TAXONOMY . ".taxonomy IN {$excludes['taxonomies']})";
		}

		$join .= " LEFT JOIN {$wpdb->term_taxonomy} AS " . static::ALIAS_TERM_TAXONOMY . ' ON (' . implode(' AND ', $on) . ') ';

		// Terms.
		$on = array();
		$on[] = static::ALIAS_TERMS . '.term_id = ' . static::ALIAS_TERM_TAXONOMY . '.term_id';
		if ($excludes['terms']) {
			$on[] = 'NOT(' . static::ALIAS_TERMS . ".term_id IN {$excludes['terms']})";
		}

		$join .= " LEFT JOIN {$wpdb->terms} AS " . static::ALIAS_TERMS . ' ON (' . implode(' AND ', $on) . ') ';
	}

	/**
	 * Posts Join: Termmeta
	 *
	 * @param string $join Join.
	 * @param array $excludes Excludes.
	 * @return void Nothing.
	 */
	private static function posts_join_termmeta(&$join, &$excludes) {
		global $wpdb;

		$on = array();
		$on[] = static::ALIAS_TERMMETA . '.term_id = ' . static::ALIAS_TERMS . '.term_id';
		if ($excludes['termmeta_keys']) {
			$on[] = 'NOT(' . static::ALIAS_TERMMETA . ".meta_key IN {$excludes['termmeta_keys']})";
		}

		$join .= " LEFT JOIN {$wpdb->termmeta} AS " . static::ALIAS_TERMMETA . ' ON (' . implode(' AND ', $on) . ') ';
	}

	// ----------------------------------------------------------------- end join



	// -----------------------------------------------------------------
	// REQUEST PARSING
	// -----------------------------------------------------------------

	/**
	 * Posts Request: Main Callback
	 *
	 * @param string $query Query.
	 * @param WP_Query $wp_query Query.
	 * @return string Query.
	 */
	public static function posts_request($query, $wp_query) {
		// Don't modify admin or secondary queries.
		if (
			!static::$active ||
			!preg_match('/^SELECT SQL_CALC_FOUND_ROWS/', $query)
		) {
			return $query;
		}

		// Our billion JOIN statements are going to result in a lot of
		// duplication; we need to alter the query one last time to make
		// sure only DISTINCT results are passed.
		$query = preg_replace('/^SELECT /', 'SELECT DISTINCT ', $query);

		return $query;
	}

	// ----------------------------------------------------------------- end request
}
