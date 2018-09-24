<?php
/**
 * The template for displaying search forms
 *
 */
?>

<?php if(class_exists('Woocommerce')) : ?>

	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="hide-input searchform" method="get">
		<div class="form-horizontal modal-form">
			<div class="form-group has-border">
				<div class="col-xs-10">
					<input type="text" placeholder="Search for..." value="<?php if(get_search_query() != '') { the_search_query(); } ?>" class="form-control" name="s" />
					<input type="hidden" name="post_type" value="<?php esc_attr_e( etheme_get_option('search_post_type') ); ?>" />
				</div>
			</div>
			<div class="form-group form-button">
				<button type="submit" class="btn medium-btn btn-black"><?php esc_attr_e( 'Search', 'royal' ); ?></button>
			</div>
		</div>
	</form>

<?php else: ?>
	<?php get_template_part('searchform'); ?>
<?php endif ?>
