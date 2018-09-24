<?php
	get_header();
?>

<?php

    $l = et_page_config();

    $blog_slider = etheme_get_option('blog_slider');
    $disable_featured = etheme_get_custom_field('disable_featured');
    $postspage_id = get_option('page_for_posts');
    $post_format = get_post_format();

    $post_content = $post->post_content;
    preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
    if(!empty($ids)) {
	    $attach_ids = explode(",", $ids[1]);
	    $content =  str_replace($ids[0], "", $post_content);
	    $filtered_content = apply_filters( 'the_content', $content);
    }

    $slider_id = rand(100,10000);
?>

<?php do_action( 'et_page_heading' ); ?>

<div class="container">
	<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?>">
		<div class="row">

			<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">
				<?php if(have_posts()): while(have_posts()) : the_post(); ?>

					<article <?php post_class('blog-post post-single'); ?> id="post-<?php the_ID(); ?>" >

						<?php
							$width = etheme_get_option('blog_page_image_width');
							$height = etheme_get_option('blog_page_image_height');
							$crop = etheme_get_option('blog_page_image_cropping');
						?>


						<?php if($post_format == 'quote' || $post_format == 'video'): ?>

					            <?php the_content(); ?>

						<?php elseif($post_format == 'gallery'): ?>
					            <?php if(count($attach_ids) > 0): ?>
					                <div class="owl-carousel post-gallery-slider slider_id-<?php echo $slider_id; ?>">
					                    <?php foreach($attach_ids as $attach_id): ?>
					                        <div>
												<?php
													$image = wp_get_attachment_image_src( $attach_id, 'large' );

													echo sprintf(
														'<img data-src="%s" alt="%s" class="owl-lazy attachment-large size-large" data-srcset="%s" sizes="%s" height="%s" width="%s" />',
														esc_url( $image[0] ),
														get_post_meta( $attach_id, '_wp_attachment_image_alt', true),
														wp_get_attachment_image_srcset( $attach_id, 'large' ),
														wp_get_attachment_image_sizes( $attach_id, 'large' ),
														esc_attr( $image[1] ),
														esc_attr( $image[2] )
													);


												 ?>

					                        </div>
					                    <?php endforeach; ?>
					                </div>

					                <script type="text/javascript">
										jQuery(document).ready(function(){
											 jQuery('.slider_id-<?php echo $slider_id; ?>').owlCarousel({
												items:1,
												nav: true,
												lazyLoad: true,
												rewind: false,
												addClassActive: true,
												autoHeight:true,
												responsive: {
													1600 : {
														items: 1
													}
												}
											});
										});
					                </script>
					            <?php endif; ?>

						<?php elseif(has_post_thumbnail() && ! $disable_featured): ?>
							<div class="wp-picture">
								<?php the_post_thumbnail('large'); ?>
								<div class="zoom">
									<div class="btn_group">
										<a href="<?php echo etheme_get_image(); ?>" class="btn btn-black xmedium-btn" rel="pphoto"><span><?php esc_html_e('View large', 'royal'); ?></span></a>
									</div>
									<i class="bg"></i>
								</div>
							</div>
						<?php endif; ?>

                        <?php if($post_format != 'quote'): ?>
                            <h6 class="active"><?php the_category(',&nbsp;') ?></h6>

                            <h2 class="entry-title"><?php the_title(); ?></h2>

                        	<?php if(etheme_get_option('blog_byline')): ?>
                                <div class="meta-post">
                                        <?php esc_html_e('Posted on', 'royal') ?>
                                        <?php the_time(get_option('date_format')); ?>
                                        <?php esc_html_e('at', 'royal') ?>
                                        <?php the_time(get_option('time_format')); ?>
                                        <?php esc_html_e('by', 'royal');?> <span class="vcard author"> <span class="fn"><?php the_author_posts_link(); ?></span></span>
                                        <?php // Display Comments

                                                if(comments_open() && !post_password_required()) {
                                                        echo ' / ';
                                                        comments_popup_link('0', '1 Comment', '% Comments', 'post-comments-count');
                                                }

                                         ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($post_format != 'quote' && $post_format != 'video' && $post_format != 'gallery'): ?>
                            <div class="content-article entry-content">
                                    <?php the_content(); ?>
                            </div>
                        <?php elseif($post_format == 'gallery'): ?>
                            <div class="content-article entry-content">
                                <?php echo $filtered_content; ?>
                            </div>
                        <?php endif; ?>

						<?php if( etheme_get_option('post_tags') ): ?>
							<?php
								$tag_list = get_the_tag_list();

								if ( $tag_list ) : ?>
									<div class="tagcloud">
										<?php echo $tag_list; ?>
									</div><!-- /.tagcloud -->
								<?php endif;
							?>
						<?php endif; ?>

						<?php if(etheme_get_option('post_share')): ?>
							<div class="share-post">
								<?php echo do_shortcode('[share title="'.__('Share Post', 'royal').'"]'); ?>
							</div>
						<?php endif; ?>

						<?php if(etheme_get_option('posts_links')): ?>
							<?php etheme_project_links(array()); ?>
						<?php endif; ?>


						<?php if(etheme_get_option('about_author')): ?>
							<h4 class="title-alt"><span><?php esc_html_e('About Author', 'royal'); ?></span></h4>

							<div class="author-info vcard">
								<a class="pull-left" href="#">
									<?php echo get_avatar( get_the_author_meta('email') , 90 ); ?>
								</a>
								<div class="media-body">
									<h4 class="media-heading url"><?php the_author_link(); ?></h4>
									<p class="note"><?php echo get_the_author_meta('description'); ?></p>
								</div>
							</div>
						<?php endif; ?>

						<?php if(etheme_get_option('post_related')): ?>
							<div class="related-posts">
								<?php et_get_related_posts(); ?>
							</div>
						<?php endif; ?>

					</article>


				<?php endwhile; else: ?>

					<h1><?php esc_html_e('No posts were found!', 'royal') ?></h1>

				<?php endif; ?>

				<?php comments_template('', true); ?>

			</div>

			<?php get_sidebar(); ?>

		</div>

	</div>
</div>

<?php
	get_footer();
?>
