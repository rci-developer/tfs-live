<?php if (etheme_get_option('top_bar_sw') == 'on'): ?>
	<?php
		$top_bar_class = '';
		if ( etheme_get_option('top_bar_colors') && etheme_get_option('top_bar_colors') != 'default' ) {
			$top_bar_class = ' color-'.etheme_get_option('top_bar_colors');
		}
	 ?>
	<div class="top-bar<?php echo $top_bar_class; ?>">
		<div class="container">
				<div class="languages-area">
					<?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('languages-sidebar'))): ?>
						<div class="languages">
							<ul class="links">
								<li class="active">EN</li>
								<li><a href="#">FR</a></li>
								<li><a href="#">DE</a></li>
							</ul>
						</div>
						<div class="currency">
							<ul class="links">
								<li><a href="#">£</a></li>
								<li><a href="#">€</a></li>
								<li class='active'>$</li>
							</ul>
						</div>
					<?php endif; ?>
				</div>


				<div class="top-links">
					<?php etheme_top_links(); ?>
					<?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('top-bar-right'))): ?>
					<?php endif; ?>
				</div>
		</div>
	</div>
<?php endif; ?>
