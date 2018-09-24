<?php
/**
 * Wherewithal - Search Settings
 *
 * @package wherewithal
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

// This must be called through Wordpress.
if (!defined('ABSPATH')) {
	exit(1);
}

use \blobfolio\wp\wherewithal\settings;
use \blobfolio\wp\wherewithal\tools;



$options = settings::get_option();
$placeholders = array(
	'post_types'=>'post, page,…',
	'postmeta_keys'=>'meta_1, meta_2,…',
	'posts'=>'1, 3, 5,…',
	'taxonomies'=>'post_tag, category,…',
	'termmeta_keys'=>'meta_1, meta_2,…',
	'terms'=>'1, 3, 5,…',
);

$types = get_post_types(null, 'objects');

// Translations for data we'll be pulling from a variable.
$translated = array(
	'comments'=>__('Comments', 'wherewithal'),
	'post_types'=>__('Post Types', 'wherewithal'),
	'postmeta_keys'=>__('Postmeta Keys', 'wherewithal'),
	'postmeta'=>__('Postmeta', 'wherewithal'),
	'posts'=>__('Posts', 'wherewithal'),
	'taxonomies'=>__('Taxonomies', 'wherewithal'),
	'term_descriptions'=>__('Term Descriptions', 'wherewithal'),
	'term_names'=>__('Term Names', 'wherewithal'),
	'termmeta_keys'=>__('Termmeta Keys', 'wherewithal'),
	'termmeta'=>__('Termmeta', 'wherewithal'),
	'terms'=>__('Terms', 'wherewithal'),
);



// ---------------------------------------------------------------------
// Save
// ---------------------------------------------------------------------
if (getenv('REQUEST_METHOD') === 'POST') {
	if (!isset($_POST['n']) || !wp_verify_nonce($_POST['n'], 'wherewithal')) {
		?>
		<div class="error fade"><p><?php
			echo __('The form had expired. Please try again.', 'wherewithal');
		?></p></div>
		<?php
	}
	else {
		// Get rid of pesky magic quotes.
		$raw = stripslashes_deep($_POST);

		// Convert our comma-separated bits to arrays.
		foreach (array('exclude', 'exclude_global') as $field) {
			if (isset($raw[$field]) && is_array($raw[$field])) {
				foreach ($raw[$field] as $k=>$v) {
					$v = tools::to_string($v, false, false);
					$v = preg_replace('/\s/u', '', $v);
					$raw[$field][$k] = array_filter(explode(',', $v), 'strlen');
					if (!is_array($raw[$field][$k])) {
						$raw[$field][$k] = array();
					}
				}
			}
		}

		// Save and reload.
		$options = settings::save_options($raw);
		?>
		<div class="updated fade"><p><?php
			echo __('The search settings have been saved.', 'wherewithal');
		?></p></div>
		<?php
	}
}
// --------------------------------------------------------------------- end save
?>
<style type="text/css">
	.settings-table {
		border: 0;
		border-collapse: collapse;
		width: 100%;
	}
	.settings-table th,
	.settings-table td {
		padding: 10px 5px;
		vertical-align: top;
	}
	.settings-table th {
		width: 120px;
		text-align: left;
	}
	.settings-table input[type=number] { width: 75px; }
	.settings-table input[type=text] {
		width: 100%;
		min-width: 200px;
		max-width: 300px;
	}
	.settings-table .settings-text {
		padding-left: 20px;
	}

	.wherewithal-about-logo {
		transition: color .3s ease;
	}

	.wherewithal-about-logo svg {
		transition: color .3s ease;
		display: block;
		width: 100%;
		height: auto;
	}

	.settings-table .description { margin-top: 1.5em; }
	.settings-table .description:first-child { margin-top: 0; }

	@media(min-width: 1200px){
		.settings-table input[type=text] {
			width: 300px;
		}
	}
</style>
<div class="wrap">

	<h1><?php
		echo __('Wherewithal Enhanced Search', 'wherewithal');
	?></h1>

	<div id="poststuff">
		<div id="post-body" class="meta-holder">
			<form method="post" action="<?php echo admin_url('options-general.php?page=wherewithal-settings'); ?>" class="postbox-container" id="postbox-container-2">
				<input type="hidden" name="n" value="<?php echo wp_create_nonce('wherewithal'); ?>" />

				<div class="postbox">
					<h3 class="hndle"><?php
						echo __('Haystacks', 'wherewithal');
					?></h3>
					<div class="inside">
						<table class="settings-table">
							<tbody>
								<?php
								$num = 0;
								foreach ($options['haystack'] as $k=>$v) {
									$num++;
									?>
									<tr>
										<th scope="row">
											<label for="haystack-<?php echo $k; ?>"><?php
												echo $translated[$k];
											?></label>
										</th>
										<td>
											<input type="checkbox" name="haystack[<?php echo $k; ?>]" id="haystack-<?php echo $k; ?>" value="1" <?php echo $v ? 'checked' : ''; ?> />
										</td>
										<?php if (1 === $num) { ?>
											<td class="settings-text" rowspan="<?php echo count($options['haystack']); ?>">
												<p class="description"><?php
													echo __('Check any of the boxes at left to have WordPress include matches from those areas in site searches.', 'wherewithal');
												?></p>

												<p class="description"><?php
													echo __('Because each additional area brings extra computational complexity to a search, you should only enable areas that are actually relevant to your site.', 'wherewithal');
												?></p>
											</td>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div><!--.inside-->
				</div><!--.postbox-->

				<div class="postbox">
					<h3 class="hndle"><?php
						echo __('General Search Overrides', 'wherewithal');
					?></h3>
					<div class="inside">
						<table class="settings-table">
							<tbody>
								<?php
								$num = 0;
								foreach ($options['exclude_global'] as $k=>$v) {
									$num++;
									?>
									<tr>
										<th scope="row">
											<label for="exclude_global-<?php echo $k; ?>"><?php
												echo $translated[$k];
											?></label>
										</th>
										<td>
											<input type="text" name="exclude_global[<?php echo $k; ?>]" id="exclude_global-<?php echo $k; ?>" value="<?php echo esc_attr(implode(', ', $v)); ?>" placeholder="<?php echo $placeholders[$k]; ?>" />
										</td>
										<?php if (1 === $num) { ?>
											<td class="settings-text" rowspan="<?php echo count($options['exclude_global']); ?>">
												<p class="description"><?php
													echo __('You can optionally exclude certain content from ever showing up in a site search. Enter any such values at left, separated with a comma.', 'wherewithal');
												?></p>

												<p class="description"><?php
													echo __('WordPress automatically excludes certain post types from appearing in searches; you do not need to re-specify those here.', 'wherewithal');
												?></p>
											</td>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div><!--.inside-->
				</div><!--.postbox-->

				<div class="postbox">
					<h3 class="hndle"><?php
						echo __('Deep Search Exclusions', 'wherewithal');
					?></h3>
					<div class="inside">
						<table class="settings-table">
							<tbody>
								<?php
								$num = 0;
								foreach ($options['exclude'] as $k=>$v) {
									$num++;
									?>
									<tr>
										<th scope="row">
											<label for="exclude-<?php echo $k; ?>"><?php
												echo $translated[$k];
											?></label>
										</th>
										<td>
											<input type="text" name="exclude[<?php echo $k; ?>]" id="exclude-<?php echo $k; ?>" value="<?php echo esc_attr(implode(', ', $v)); ?>" placeholder="<?php echo $placeholders[$k]; ?>" />
										</td>
										<?php if (1 === $num) { ?>
											<td class="settings-text" rowspan="<?php echo count($options['exclude']); ?>">
												<p class="description"><?php
													echo __('Any exclusions entered at left will prevent that content from being matched based on a "deep search" result (i.e. what this plugin does). The content may still appear in the search results, but only if the match comes from the normal site search. Enter any such values at left, separated with a comma.', 'wherewithal');
												?></p>

												<p class="description"><?php
													echo sprintf(
														__('Certain items, such as internal WP keys prefixed with %s, are excluded automatically.', 'wherewithal'),
														'<code>_</code>'
													);
												?></p>

												<p class="description"><?php
													echo sprintf(
														__('Exclusions are haystack-dependent and will only come into play if a relevant search source is enabled. For example, excluded terms will only come into play if %s or %s are enabled.', 'wherewithal'),
														'<code>' . __('Term Names', 'wherewithal') . '</code>',
														'<code>' . __('Termmeta', 'wherewithal') . '</code>'
													);
												?></p>
											</td>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div><!--.inside-->
				</div><!--.postbox-->

				<p>
					<button type="submit" class="button button-primary"><?php
						echo __('Save', 'wherewithal');
					?></button>
				</p>

			</form><!--.postbox-container-->

		</div><!--#post-body-->
	</div><!--#poststuff-->

</div><!--.wrap-->
