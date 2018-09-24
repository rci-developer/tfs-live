<?php

define('ETHEME_THEME_NAME', 'Royal<span>Store</span>');
define('THEME_LOGO', 'Royal');
define('THEME_SLUG', 'royal');
define('ETHEME_DOMAIN', 'royal');
define('ET_DOMAIN', 'royal');

define('ET_SUPPORT_LINK', 'http://www.8theme.com/forums/royal-wordpress-support-forum/');
define('ET_CHANGELOG_LINK', 'http://8theme.com/demo/docs/royal/royal-changelog.txt');
define('ET_TF_LINK', 'http://themeforest.net/item/royal-multipurpose-wordpress-theme/8611976');
define('ET_RATE_LINK', 'http://themeforest.net/downloads');

if(!function_exists('et_get_captcha_color')) {
	function et_get_captcha_color() {
		return apply_filters( 'et_get_captcha_color', array( 204, 168, 97 ) );
	}
}

if(!function_exists('et_get_tooltip_html')) {
    function et_get_tooltip_html($item_id) {
        $output = '';
        $post_thumbnail = get_post_thumbnail_id( $item_id, 'thumb' );
        $post_thumbnail_url = wp_get_attachment_url( $post_thumbnail );
        $output .= '<div class="nav-item-tooltip">';
            $output .= '<div data-src="' . $post_thumbnail_url . '"></div>'; // $output .= '<img src="' . $post_thumbnail_url . '">';
        $output .= '</div>';
        return $output;
    }
}


// **********************************************************************//
// ! Footer Demo Widgets
// **********************************************************************//

if(!function_exists('etheme_footer_demo')) {
    function etheme_footer_demo($position){
        switch ($position) {

            case 'footer1':
        	?>

        	<?php
        	break;
            case 'footer2':

                ?>

                    <div class="row">
                        <div class="col-md-3">
							<div class="about-company">
								<a class="pull-left" href="#"><img title="RoyalStore" src="<?php echo get_template_directory_uri(); ?>/images/small-logo.png" alt="..."><br></a>
							</div>
							<h5 class="media-heading">About <span class="default-colored">RoyalStore</span></h5>
							<p>Lorem ipsum dolor sit amet, consect etur adipisic ing elit, sed do eiusmod tempor incididunt ut labore.</p>
							<address class="address-company">30 South Avenue San Francisco<br>
								<span class="white-text">Phone</span>: +78 123 456 789<br>
								<span class="white-text">Email</span>: <a href="mailto:Support@Royal.com">Support@Royal.com</a><br>
								<a class="white-text letter-offset" href="#">www.royal.com</a><br>
								<?php echo etheme_share_shortcode(array()); ?>
							</address>
                        </div>
                        <div class="col-md-3">
							<div class="widget-container widget_text">
								<h3 class="widget-title"><span>Informations</span></h3>
								<div class="textwidget">
									<ul class="col-ct-6 list-unstyled">
										<li><a href="#">London</a></li>
										<li><a href="#">Singapore</a></li>
										<li><a href="#">Paris</a></li>
										<li><a href="#">Moscow</a></li>
										<li><a href="#">Berlin</a></li>
										<li><a href="#">Milano</a></li>
										<li><a href="#">Amsterdam</a></li>
									</ul>

									<ul class="col-ct-6 list-unstyled">
										<li><a href="#">London</a></li>
										<li><a href="#">Singapore</a></li>
										<li><a href="#">Paris</a></li>
										<li><a href="#">Moscow</a></li>
										<li><a href="#">Berlin</a></li>
										<li><a href="#">Milano</a></li>
										<li><a href="#">Amsterdam</a></li>
									</ul>
								</div>
							</div>
                        </div>
                        <div class="col-md-3">
                            <?php
                                $args = array(
                                    'widget_id' => 'etheme_widget_flickr',
                                    'before_widget' => '<div class="footer-sidebar-widget etheme_widget_flickr">',
                                    'after_widget' => '</div><!-- //sidebar-widget -->',
                                    'before_title' => '<h4 class="widget-title"><span>',
                                    'after_title' => '</span></h4>'
                                );

                                $instance = array(
                                    'screen_name' => '52617155@N08',
                                    'number' => 6,
                                    'show_button' => 1,
                                    'title' => esc_html__('Flickr Photos', 'royal')
                                );


                                $widget = new Etheme_Flickr_Widget();
                                $widget->widget($args, $instance);
                            ?>
                        </div>
                        <div class="col-md-3">
                        	<?php the_widget('Etheme_Recent_Posts_Widget', array('title' => esc_html__('Recent posts', 'royal'), 'number' => 2), array('before_title' => '<h3 class="widget-title">','after_title' => '</h3>', 'number' => 2)); ?>
                        </div>
                    </div>

                <?php

            break;
            case 'footer9':
                ?>
                	<div class="textwidget">
                		<p>© Created with <i class="fa fa-heart default-colored"></i> by <a href="#" class="default-link">8Theme</a>. All Rights Reserved</p>
                	</div>
                <?php
                break;
            case 'footer10':
                ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/assets/payments.png">
                <?php
                break;
        }
    }
}

if(!function_exists('et_get_versions_option')) {
	function et_get_versions_option() {
        $main_domain = 'http://8theme.com/demo/royal/';

		return apply_filters('et_get_versions_option', array(
            'base' => array(
                'home_id' => 24375,
                'title'   => 'Default',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . '/',
            ),
            'meeting' => array(
                'home_id' => 'false',
                'title'   => 'Meeting',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . '2/meeting/',
            ),
            'wedding' => array(
    			'home_id' => 'false',
    			'title'   => 'Wedding',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . '2/wedding/',
			),
            'businesscards' => array(
                'home_id' => 24283,
                'title'   => 'Business cards',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . '2/businesscard/',
            ),
            'polygraphy' => array(
                'home_id' => 'false',
                'title'   => 'Polygraphy',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . '2/polygraphy/',
            ),
            'designers' => array(
                'home_id' => 'false',
                'title'   => 'Designers',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . '2/designers/',
            ),
            'apps' => array(
                'home_id' => 24410,
                'title'   => 'Apps',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . '2/apps/',
            ),
    		'agency' => array(
    			'home_id' => 23509,
    			'title'   => 'Albatros agency',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'agency-one/?et_styles=0-1-1-6-0-1',
			),
    		'fashion' => array(
    			'home_id' => 23458,
    			'title'   => 'Fashion one page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'fashion-one-page/?et_styles=0-3-1-9-0-0',
			),
    		'poster' => array(
				'home_id' => 22879,
    			'title'   => 'Poster world',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'poster-world/?et_styles=0-10-2-3-0-2',
			),
    		'beauty' => array(
				'home_id' => 23531,
    			'title'   => 'Beauty one page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'beauty-one-page/?et_styles=0-1-1-5-0-1',
			),
    		'travel_one_page' => array(
				'home_id' => 23496,
    			'title'   => 'Travel one page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'travel-one-page/?et_styles=0-1-1-7-0-1',
			),
    		'travel' => array(
				'home_id' => 22905,
    			'title'   => 'Travel',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'travel/?et_styles=0-1-2-0-0-1',
			),
    		'cleopatra' => array(
				'home_id' => 23114,
    			'title'   => 'Jewelry store',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'cleopatra/?et_styles=0-6-2-9-0-1',
			),
    		'fishing_landing' => array(
				'home_id' => 23738,
    			'title'   => 'Fishing landing page',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'fishing-master/?et_styles=0-9-0-1-0-1',
			),
    		'food_landing' => array(
				'home_id' => 23748,
    			'title'   => 'Food landing page',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'american-food/?et_styles=0-1-0-8-0-5',
			),
    		'gaming_landing' => array(
				'home_id' => 23744,
    			'title'   => 'Gaming landing page',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'gaming-landing/?et_styles=0-1-0-6-0-5',
			),
    		'interior' => array(
				'home_id' => 22920,
    			'title'   => 'Interior store',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'interiors/?et_styles=0-0-0-1-0-3',
			),
    		'kidsstore_landing' => array(
				'home_id' => 23721,
    			'title'   => 'Kidsstore landing',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'kidsstore-landing/',
			),
    		'underwear' => array(
				'home_id' => 22935,
    			'title'   => 'Underwear store',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'underwear/?et_styles=0-9-2-5-0-1',
			),
    		'gym' => array(
				'home_id' => 23162,
    			'title'   => 'Gym store',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'gym/?et_styles=0-1-1-7-0-5',
			),
    		'photography_landing' => array(
				'home_id' => 23793,
    			'title'   => 'Photography landing',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'chris-photography/?et_styles=0-0-0-6-0-0',
			),
    		'electro_landing' => array(
				'home_id' => 23761,
    			'title'   => 'Electro landing',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'electro-market/',
			),
    		'intimi_landing' => array(
				'home_id' => 23753,
    			'title'   => 'Intimi page',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'intimistore/',
			),
    		'retro_inspiration' => array(
				'home_id' => 23796,
    			'title'   => 'Retro inspiration',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'retroinspiration/?et_styles=0-0-0-8-0-0',
			),
    		'lawyer_landing' => array(
				'home_id' => 23756,
    			'title'   => 'Lawyer page',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'roman-polanski/',
			),
    		'app_landing' => array(
				'home_id' => 23769,
    			'title'   => 'App landing',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'royalapp-com/?et_styles=0-0-0-8-0-0',
			),
    		'royal_landing' => array(
				'home_id' => 23693,
    			'title'   => 'Royal landing',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'royal-company/?et_styles=0-0-0-3-0-0',
			),
    		'show_room' => array(
				'home_id' => 23788,
    			'title'   => 'Show room',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'fashion-one-page/?et_styles=0-3-1-9-0-0',
			),
    		'travel_landing' => array(
				'home_id' => 23813,
    			'title'   => 'Travel landing',
    			'cat'     => 'landing',
                'preview_url'  => $main_domain . 'travel-one-page/?et_styles=0-1-1-7-0-1',
			),
    		'corporate' => array(
				'home_id' => 23187,
    			'title'   => 'Corporate',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'corporate-one-page/?et_styles=0-1-1-3-0-5',
			),
    		'gaming' => array(
				'home_id' => 22953,
    			'title'   => 'Gaming',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'gaming-variant/?et_styles=0-11-1-6-0-1',
			),
    		'corporate_one_page' => array(
				'home_id' => 23141,
    			'title'   => 'Corporate one page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'corporate-one-page/?et_styles=0-1-1-3-0-5',
			),
    		'snowboard' => array(
				'home_id' => 22971,
    			'title'   => 'Snowboard',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'snowboard/?et_styles=0-9-0-3-0-1',
			),
    		'royal_market' => array(
				'home_id' => 23007,
    			'title'   => 'Royal Market',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'royalmarket/?et_styles=1-9-0-15-4-1',
			),
    		'engineer' => array(
				'home_id' => 23027,
    			'title'   => 'Engineer',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'engineer/?et_styles=0-9-0-7-0-1',
			),
    		'food_shop' => array(
				'home_id' => 23055,
    			'title'   => 'Food shop',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'food/?et_styles=1-9-2-14-3-1',
			),
    		'animals' => array(
				'home_id' => 23083,
    			'title'   => 'Zoo shop',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'animals/?et_styles=0-7-1-14-0-1',
			),
    		'royal_minimal' => array(
				'home_id' => 23100,
    			'title'   => 'Royal minimal',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'minimal/?et_styles=0-5-0-2-0-3',
			),
    		'creative' => array(
				'home_id' => 23129,
    			'title'   => 'Creative',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'creative/?et_styles=0-1-1-8-0-1',
			),
    		'doctor' => array(
				'home_id' => 23196,
    			'title'   => 'Doctor',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'doctor/?et_styles=0-9-1-3-0-1',
			),
    		'transport' => array(
				'home_id' => 23212,
    			'title'   => 'Transport',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'transport/?et_styles=0-3-1-9-0-1',
			),
    		'app_one_page' => array(
				'home_id' => 23234,
    			'title'   => 'Mobile App',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'app/?et_styles=0-1-1-2-0-1',
			),
    		'royal_home' => array(
				'home_id' => 23351,
    			'title'   => 'Royal Home',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'flat-retailer/?et_styles=0-1-1-16-0-5',
			),
    		'royal_school' => array(
				'home_id' => 23373,
    			'title'   => 'Royal School',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . '19129-2/?et_styles=0-2-2-8-0-1',
			),
    		'royal_publisher' => array(
				'home_id' => 23390,
    			'title'   => 'Royal Publisher',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'publisher/?et_styles=0-3-1-3-0-1',
			),
    		'royal_university' => array(
				'home_id' => 23423,
    			'title'   => 'Royal University',
    			'cat'     => 'simple',
                'preview_url'  => $main_domain . 'university/?et_styles=0-6-1-14-0-1',
			),
    		'electronic_one_page' => array(
				'home_id' => 23566,
    			'title'   => 'Electronic One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'electronic-one-page/?et_styles=0-1-1-4-0-1',
			),
    		'lawyer_one_page' => array(
				'home_id' => 23582,
    			'title'   => 'Lawyer One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'lawyer-one-page/?et_styles=0-1-1-9-0-1',
			),
    		'restaurant_one_page' => array(
				'home_id' => 23598,
    			'title'   => 'Restaurant One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'restaurant-one-page/?et_styles=0-1-1-14-0-1',
			),
    		'hotel_one_page' => array(
				'home_id' => 23622,
    			'title'   => 'Hotel One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'hotel-one-page/?et_styles=0-1-2-8-0-7',
			),
    		'agency2_one_page' => array(
				'home_id' => 23643,
    			'title'   => 'Agency 2 One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'agency-one-page/?et_styles=0-13-1-6-0-1',
			),
    		'skate_one_page' => array(
				'home_id' => 23661,
    			'title'   => 'Skate One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'skate-one-page/?et_styles=0-1-1-8-0-8',
			),
    		'wheels_one_page' => array(
				'home_id' => 23699,
    			'title'   => 'Wheels One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'wheels/?et_styles=0-1-1-6-0-1',
			),
    		'spa_one_page' => array(
				'home_id' => 23764,
    			'title'   => 'Spa One Page',
    			'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'spa/?et_styles=0-1-1-14-0-1',
			),
            'green_corporate_one_page' => array(
                'home_id' => 23141,
                'title'   => 'Green Corporate One Page',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'green-corporate/?et_styles=0-3-1-14-0-1',
            ),
            'polygon_landing' => array(
                'home_id' => 23826,
                'title'   => 'Polygon Landing',
                'cat'     => 'landing',
                'preview_url'  => $main_domain . 'polygon-landing-page/?et_styles=0-1-0-8-0-5',
            ),
            'toy_store' => array(
                'home_id' => 23876,
                'title'   => 'Toy Store',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . 'kidsstore/?et_styles=1-8-0-5-2-4',
            ),
            'church' => array(
                'home_id' => 23897,
                'title'   => 'Church',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . 'church/?et_styles=0-1-1-9-0-1',
            ),
            'architect' => array(
                'home_id' => 23797,
                'title'   => 'Architecture',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'architecture/?et_styles=0-1-3-4-0-7',
            ),
            'irish_pub' => array(
                'home_id' => 23820,
                'title'   => 'Irish pub',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'pub/?et_styles=0-1-3-1-0-7',
            ),
            'retro_pub' => array(
                'home_id' => 23437,
                'title'   => 'Retro pub',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . 'retro-pub/?et_styles=0-1-3-8-0-7',
            ),
            'fishing' => array(
                'home_id' => 23913,
                'title'   => 'Fishing',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . 'fishing/?et_styles=0-11-1-3-0-1',
            ),
            'flowers' => array(
                'home_id' => 6,
                'title'   => 'Flowers',
                'cat'     => 'simple',
                'preview_url'  => $main_domain . 'flowers/?et_styles=0-3-0-6-0-1',
            ),
            'halloween' => array(
                'home_id' => 23233,
                'title'   => 'Halloween',
                'cat'     => 'landing',
                'preview_url'  => $main_domain . '',
            ),
            'phones' => array(
                'home_id' => 23867,
                'title'   => 'Phones',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'demo61/#1',
            ),
            'santa_page' => array(
                'home_id' => 9,
                'title'   => 'Santa',
                'cat'     => 'one_page',
                'preview_url'  => $main_domain . 'santa/#1',
            ),
    	));
	}
}

if(!function_exists('et_get_pages_option')) {
	function et_get_pages_option() {
        $main_domain = 'http://8theme.com/demo/royal/';

		return apply_filters('et_get_pages_option', array(
            'meeting-about' => array(
              'title'        => 'Meeting about us',
              'preview_url'  => $main_domain . '2/meeting/about-us-2/',
            ),
            'meeting-date' => array(
              'title'        => 'Meeting date',
              'preview_url'  => $main_domain . '2/meeting/meeting-date/',
            ),
            'meeting-gallery' => array(
              'title'        => 'Meeting gallery',
              'preview_url'  => $main_domain . '2/meeting/gallery/',
            ),
            'wedding-about' => array(
              'title'        => 'Wedding About',
              'preview_url'  => $main_domain . '2/wedding/about-us/',
            ),
            'wedding-ceremony' => array(
              'title'        => 'Wedding ceremony',
              'preview_url'  => $main_domain . '2/wedding/ceremony/',
            ),
            'wedding-gallery' => array(
              'title'        => 'Wedding gallery',
              'preview_url'  => $main_domain . '2/wedding/gellary/',
            ),
            'wedding-guests-list' => array(
              'title'        => 'Wedding guests list',
              'preview_url'  => $main_domain . '2/wedding/guests-list/',
            ),
            'businesscard-gallery' => array(
              'title'        => 'Business card gallery',
              'preview_url'  => $main_domain . '2/businesscard/our-gallery/',
            ),
            'apps-we-offer' => array(
              'title'        => 'Apps - we offer',
              'preview_url'  => $main_domain . '/2/apps/we-offer/',
            ),
            'designers-about' => array(
              'title'        => 'Designers about',
              'preview_url'  => $main_domain . '/2/designers/about-us/',
            ),
            'designers-working-process' => array(
              'title'        => 'Designers working process',
              'preview_url'  => $main_domain . '/2/designers/work-process/',
            ),
        ));
	}
}

if(!function_exists('et_filter_option_tree_settings')) {
	function et_filter_option_tree_settings( $settings ) {
		$theme_defaults = array(
			'activecol' => array(
				'default' => '#cda85c',
			),
		);
		foreach ($settings as $index => $option) {
			if( isset( $theme_defaults[$option['id']] ) ) {
				$settings[$index] = wp_parse_args( $theme_defaults[$option['id']], $settings[$index] );
			}
		}

        return $settings;
	}

	add_filter( 'et_options_tree_settings', 'et_filter_option_tree_settings', 10, 1 );
}


if(!function_exists('et_get_color_selectors')) {
	function et_get_color_selectors() {
$selectors = array();

$selectors['main_font'] = '
p,
.title-alt,
.header-type-8 .menu-wrapper .languages-area .lang_sel_list_horizontal a,
.header-type-8 .menu-wrapper .widget_currency_sel_widget ul.wcml_currency_switcher li,
.header-type-10 .menu-wrapper .languages-area .lang_sel_list_horizontal a,
.header-type-10 .menu-wrapper .widget_currency_sel_widget ul.wcml_currency_switcher li,
.shopping-container .small-h,
.order-list .media-heading,
.btn,
.button,
.wishlist_table .add_to_cart.button,
.review,
.products-grid .product-title,
.products-list .product .product-details .product-title,
.out-stock .wr-c,
.product-title,
.added-text,
.widget_layered_nav li a,
.widget_layered_nav li .count,
.widget_layered_nav_filters ul li a,
.blog-post-list .media-heading,
.date-event,
.read-more,
.teaser-box h3,
.widget-title,
.footer-top .title,
.product_list_widget .media-heading a,
.alert-message,
.main-footer h5,
.main-footer .vc_separator,
.main-footer .widget-title,
.address-company,
.post h2,
.share-post .share-title,
.related-posts .title,
.comment-reply-title,
.control-label,
.widget_categories a,
.latest-post-list .media-heading a,
.later-product-list .media-heading a,
.tab-content .comments-list .media-heading a,
.woocommerce-product-rating .woocommerce-review-link,
.comment-form-rating label,
.product_meta,
.product-navigation .next-product .hide-info span,
.product-navigation .prev-product .hide-info span,
.meta-title,
.categories-mask span.more,
.recentCarousel .slide-item .caption h3,
.recentCarousel .slide-item .caption h2,
.simple-list strong,
.amount-text,
.amount-text .slider-amount,
.custom-checkbox a,
.custom-checkbox .count,
.toggle-block .toggle-element > a,
.toggle-block .panel-body ul a,
.shop-table .table-bordered td.product-name a,
.coupon input[type="text"],
.shop_table.wishlist_table td.product-name,
.cust-checkbox a,
.shop_table tr > td,
.shop_table td.product-name,
.payment_methods li label,
form .form-row label,
.widget_nav_menu li a,
.header-type-12 .shopping-container .shopping-cart-widget .shop-text,
.mobile-nav-heading,
.mobile-nav .links li a,
.register-link .register-popup,
.register-link .login-popup,
.login-link .register-popup,
.login-link .login-popup,
.register-link .register-popup label,
.register-link .login-popup label,
.login-link .register-popup label,
.login-link .login-popup label,
.active-filters li a,
.product-categories >li >a,
.product-categories >li >ul.children li >a,
.emodal .emodal-text .btn,
#bbpress-forums .bbp-forum-title,
#bbpress-forums .bbp-topic-title > a,
#bbpress-forums .bbp-reply-title > a,
#bbpress-forums li.bbp-header,
#bbpress-forums li.bbp-footer,
.filter-title,
.medium-coast,
.big-coast,
.count-p .count-number,
.price,
.small-coast,
.blog-post-list .media-heading a,
.author-info .media-heading,
.comments-list .media-heading a,
.comments-list .media-heading,
.comment-reply-link,
.later-product-list .small-coast,
.product-information .woocommerce-price-suffix,
.quantity input[type="text"],
.product-navigation .next-product .hide-info span.price,
.product-navigation .prev-product .hide-info span.price,
table.variations td label,
.tabs .tab-title,
.etheme_widget_qr_code .widget-title,
.project-navigation .next-project .hide-info span,
.project-navigation .prev-project .hide-info span,
.project-navigation .next-project .hide-info span.price,
.project-navigation .prev-project .hide-info span.price,
.pagination-cubic li a,
.pagination-cubic li span.page-numbers.current,
.toggle-block.bordered .toggle-element > a,
.shop-table thead tr th,
.xlarge-coast,
.address .btn,
.step-nav li,
.xmedium-coast,
.cart-subtotal th,
.shipping th,
.order-total th,
.step-title,
.bel-title,
.lookbook-share,
.tabs.accordion .tab-title,
.register-link .register-popup .popup-title span,
.register-link .login-popup .popup-title span,
.login-link .register-popup .popup-title span,
.login-link .login-popup .popup-title span,
.show-quickly,
.reviews-position-outside #reviews h2, .meta-post, .mini-text ,
.blog-post-list .media-body, .shop_table th, .tabs .tab-content p,
.products-page-cats, .product-information .price
';


$selectors['active_color'] = '
a:hover,
a:focus,
a.active,
p.active,
em.active,
li.active,
strong.active,
span.active,
span.active a,
h1.active,
h2.active,
h3.active,
h4.active,
h5.active,
h6.active,
h1.active a,
h2.active a,
h3.active a,
h4.active a,
h5.active a,
h6.active a,
.color-main,
ins,
.product-information .out-of-stock,
.languages-area .widget_currency_sel_widget ul.wcml_currency_switcher li:hover,
.menu > li > a:hover,
.header-wrapper .header .navbar .menu-main-container .menu > li > a:hover,
.fixed-header .menu > li > a:hover,
.fixed-header-area.color-light .menu > li > a:hover,
.fixed-header-area.color-dark .menu > li > a:hover,
.fullscreen-menu .menu > li > a:hover, .fullscreen-menu .menu > li .inside > a:hover,
.menu .nav-sublist-dropdown ul > li.menu-item-has-children:hover:after,
.title-banner .small-h,
.header-vertical-enable .page-wrapper .header-type-vertical .header-search a .fa-search,
.header-vertical-enable .page-wrapper .header-type-vertical2 .header-search a .fa-search
.header-type-7 .menu-wrapper .menu >li >a:hover,
.header-type-10 .menu-wrapper .navbar-collapse .menu-main-container .menu >li > a:hover,
.big-coast,
.big-coast:hover,
.big-coast:focus,
.reset-filter,
.carousel-area li.active a,
.carousel-area li a:hover,
.filter-wrap .view-switcher .switchToGrid:hover,
.filter-wrap .view-switcher .switchToList:hover,
.products-page-cats a,
.read-more:hover,
.et-twitter-slider .et-tweet a,
.product_list_widget .small-coast .amount,
.default-link,
.default-colored,
.twitter-list li a,
.copyright-1 .textwidget .active,
.breadcrumbs li a,
.comment-reply-link,
.later-product-list .small-coast,
.product-categories.with-accordion ul.children li a:hover,
.product-categories >li >ul.children li.current-cat >a,
.product-categories >li >ul.children > li.current-cat >a+span,
.product_meta >span span,
.product_meta a,
.product-navigation .next-product .hide-info span.price,
.product-navigation .prev-product .hide-info span.price,
table.variations .reset_variations,
.products-tabs .tab-title.opened,
.categories-mask span,
.product-category:hover .categories-mask span.more,
.project-navigation .next-project .hide-info span,
.project-navigation .prev-project .hide-info span,
.caption .zmedium-h a,
.ship-title,
.mailto-company,
.blog-post .zmedium-h a,
.post-default .zmedium-h a,
.before-checkout-form .showlogin,
.before-checkout-form .showcoupon,
.cta-block .active,
.list li:before,
.pricing-table ul li.row-price,
.pricing-table.style3 ul li.row-price,
.pricing-table.style3 ul li.row-price sub,
.tabs.accordion .tab-title:hover,
.tabs.accordion .tab-title:focus,
.left-titles a:hover,
.tab-title-left:hover,
.team-member .member-details h5,
.plus:after,
.minus:after,
.header-type-12 .header-search a:hover,
.et-mobile-menu li > ul > li a:active,
.mobile-nav-heading a:hover,
.mobile-nav ul.wcml_currency_switcher li:hover,
.mobile-nav #lang_sel_list a:hover,
.mobile-nav .menu-social-icons li.active a,
.mobile-nav .links li a:hover,
.et-mobile-menu li a:hover,
.et-mobile-menu li .open-child:hover,
.et-mobile-menu.line-items li.active a,
.register-link .register-popup .popup-terms a,
.register-link .login-popup .popup-terms a,
.login-link .register-popup .popup-terms a,
.login-link .login-popup .popup-terms a,
.product-categories >li >ul.children li >a:hover,
.product-categories >li >ul.children li.current-cat >a,
.product-categories >li.current-cat,
.product-categories >li.current-cat a,
.product-categories >li.current-cat span,
.product-categories >li span:hover,
.product-categories.categories-accordion ul.children li a:hover,
.portfolio-descr .posted-in,
.menu .nav-sublist-dropdown ul li a:hover,
.show-quickly:hover,
.menu >li.current-menu-item >a,
.menu >li.current_page_ancestor >a,
.widget_nav_menu .menu-shortcodes-container .menu > li.current-menu-item > a,
.widget_nav_menu .menu-shortcodes-container .menu > li.current-menu-item > a:hover,
.header-wrapper .header .navbar .menu-main-container .menu > li.current-menu-item > a,
.header-wrapper .header .menu-wrapper .menu-main-container .menu > li.current-menu-item > a,
.header-wrapper .header .menu-wrapper .menu-main-container .menu > li > a:hover,
.fixed-header .menu > li.current-menu-item > a,
.fixed-header-area.color-dark .menu > li.current-menu-item > a,
.fixed-header-area.color-light .menu > li.current-menu-item > a,
.languages-area .lang_sel_list_horizontal a:hover,
.menu .nav-sublist-dropdown ul > li.current-menu-item >a,
.menu .menu-full-width .nav-sublist-dropdown > * > ul > li.current-menu-item > a,
.product-information .out-stock-wrapper .out-stock .wr-c,
.menu .menu-full-width .nav-sublist-dropdown ul >li.menu-item-has-children .nav-sublist ul li a:hover,
.header-wrapper .etheme_widget_search a:hover,
.header-type-2.slider-overlap .header .menu > li > a:hover,
.page-heading .breadcrumbs,
.bc-type-3 a:hover,
.bc-type-4 a:hover,
.bc-type-5 a:hover,
.bc-type-6 a:hover,
.back-history:hover:before,
.testimonial-info .testimonial-author .url a,
.product-image-wrapper.hover-effect-mask .hover-mask .mask-content .product-title a:hover,
.header-type-10 .menu-wrapper .languages li a:hover,
.header-type-10 .menu-wrapper .currency li a:hover,
.widget_nav_menu li.current-menu-item a:before,
.header-type-3.slider-overlap .header .menu > li > a:hover,
.et-tooltip >div a:hover, .et-tooltip >div .price,
.black-white-category .product-category .categories-mask span.more,
.etheme_widget_brands li a strong,
.main-footer-1 .blog-post-list .media-heading a:hover,
.category-1 .widget_nav_menu li .sub-menu a:hover,
.sidebar-widget .tagcloud a:hover,
.church-hover .icon_list_icon:hover i,
.tabs .tab-title:hover,
footer .address-company a.white-text,
.blog-post-list .media-heading a:hover,
.footer-top-2 .product_list_widget li .media-heading a:hover,
.tagcloud a:hover,
.product_list_widget .media-heading a:hover,
.menu .menu-full-width .nav-sublist-dropdown ul > li.menu-item-has-children .nav-sublist ul li.current-menu-item a,
.header-vertical-enable .page-wrapper .header-type-vertical .header-search a .fa-search,
.header-vertical-enable .page-wrapper .header-type-vertical2 .header-search a .fa-search,
.main-footer-1 .container .hidden-tooltip i:hover,
.list-unstyled a:hover,
.portfolio-descr a, .header-type-10 .menu-wrapper .et-search-result li a:hover,
.fullscreen-menu .menu > li .inside.over > .item-link
';

// important
$selectors['active_color_important'] = '
.header-vertical-enable .shopping-container a:hover,
.header-vertical-enable .header-search a:hover,
.header-vertical-enable .container .menu >li >a:hover,
.products-tabs .tab-title.opened:hover,
.header-vertical-enable .container .menu >li.current-menu-item >a,
.header-vertical-enable .page-wrapper .container .menu .nav-sublist-dropdown ul >li.menu-item-has-children .nav-sublist ul li a:hover,
.header-vertical-enable .page-wrapper .container .menu .menu-full-width .nav-sublist-dropdown ul >li >a:hover,
.header-vertical-enable .page-wrapper .container .menu .nav-sublist-dropdown ul >li.menu-item-has-children .nav-sublist ul >li.current-menu-item >a,
.header-vertical-enable .page-wrapper .container .menu .nav-sublist-dropdown ul >li.menu-item-has-children .nav-sublist ul li a:hover,
.slid-btn.active:hover,
.btn.bordered:hover

';

// Price COLOR!
$selectors['pricecolor'] = '
';

$selectors['active_bg'] = '
hr.active,
.btn.filled.active,
.header-type-9 .top-bar,
.shopping-container .btn.border-grey:hover,
.bottom-btn .btn.btn-black:hover,
#searchModal .large-h:after,
#searchModal .btn-black,
.details-tools .btn-black:hover,
.product-information .cart button[type="submit"]:hover,
.all-fontAwesome .fa-hover a:hover,
.all-fontAwesome .fa-hover a:hover span,
.header-type-12 .shopping-container,
.portfolio-filters li .btn.active,
.progress-bar > div,
.wp-picture .zoom >i,
.swiper-slide .zoom >i,
.portfolio-image .zoom >i,
.thumbnails-x .zoom >i,
.teaser_grid_container .post-thumb .zoom >i,
.teaser-box h3:after,
.mc4wp-form input[type="submit"],
.ui-slider .ui-slider-handle,
.et-tooltip:hover,
.btn-active,
.rev_slider_wrapper .type-label-2,
.menu-social-icons.larger li a:hover, .menu-social-icons.larger li a:focus,
.ui-slider .ui-slider-handle:hover,
.category-1 .widget_product_categories .widget-title,
.category-1 .widget_product_categories .widgettitle,
.category-1 .widget_nav_menu .widget-title,
.menu-social-icons.larger.white li a:hover,
.type-label-2,
.btn.filled:hover, .btn.filled:focus,
.widget_shopping_cart .bottom-btn a:hover,
.horizontal-break-alt:after,
.price_slider_wrapper .price_slider_amount button:hover,
.btn.btn-black:hover,
.etheme_widget_search .button:hover,
input[type=submit]:hover,
.project-navigation .prev-project a:hover,
.project-navigation .next-project a:hover,
.button:hover,
.mfp-close:hover,
.mfp-close:focus,
.tabs.accordion .tab-title:before,
#searchModal .btn-black:hover,
.toggle-block.bordered .toggle-element > a:before,
.place-order .button:hover,
.cart-bag .ico-sum,
.cart-bag .ico-sum:after,
.main-footer-1 .blog-post-list li .date-event,
.menu-social-icons.larger a i:hover
';
$selectors['active_bg_important'] = '
.active-hover .top-icon:hover .aio-icon,
.active-hover .left-icon:hover .aio-icon,
.project-navigation .next-project:hover,
.project-navigation .prev-project:hover
';
$selectors['active_border'] = '
.btn.filled.active,
.btn.filled.active.medium,
.bottom-btn .btn.btn-black:hover,
.details-tools .btn-black:hover,
a.list-group-item.active,
a.list-group-item.active:hover,
a.list-group-item.active:focus,
.shopping-container .btn.border-grey:hover,
.btn-active,
.category-1 .widget_product_categories,
.category-1 .widget_nav_menu,
.main-footer-1 .blog-post-list li .date-event,
.sidebar-widget .tagcloud a:hover,
.dotted-menu-link a:hover,
.header-type-3.slider-overlap .header .menu > li.dotted-menu-link > a:hover,
.header-vertical-enable .page-wrapper .header-type-vertical .container .menu > li.dotted-menu-link > a,
.btn.filled:hover, .btn.filled:focus,
.btn.btn-black:hover,
.etheme_widget_search .button:hover,
.project-navigation .prev-project a:hover,
.project-navigation .next-project a:hover,
.button:hover,
.project-navigation .next-project:hover a,
.project-navigation .prev-project:hover a,
.tagcloud a:hover,
.slid-btn.active:hover,
.cart-bag .ico-sum:before,
.btn.bordered:hover
';

$selectors['fixed_menu_font']='
.fixed-header .menu li > a
';

$selectors['fixed_menu_font_hover']='
.fixed-header .menu li > a:hover,
.fixed-header .menu li.current-menu-item > a:hover
';
$selectors['fixed_menu_font_active']='
.fixed-header .menu li.current-menu-item > a
';

$selectors['fixed_menu_l2']='
body .fixed-header .menu .menu-full-width .nav-sublist-dropdown > * > ul > li > a
';

$selectors['fixed_menu_l2_hover']='
body .fixed-header .menu .menu-full-width .nav-sublist-dropdown > * > ul > li > a:hover,
body .fixed-header .menu .menu-full-width .nav-sublist-dropdown > * > ul > li >.current-menu-item a:hover
';

$selectors['fixed_menu_l2_active']='
body .fixed-header .menu .menu-full-width .nav-sublist-dropdown > * > ul > li >.current-menu-item a
';

$selectors['fixed_menu_l3']='
.fixed-header .menu li:not(.menu-full-width) .nav-sublist-dropdown ul > li > a
';
$selectors['fixed_menu_l3_hover']='
.fixed-header .menu li:not(.menu-full-width) .nav-sublist-dropdown ul > li > a:hover,
.fixed-header .menu li:not(.menu-full-width) .nav-sublist-dropdown ul > li.current-menu-item > a:hover
';
$selectors['fixed_menu_l3_active']='
.fixed-header .menu li:not(.menu-full-width) .nav-sublist-dropdown ul > li.current-menu-item > a
';

$selectors['menu_font_hover']='
.header-wrapper .menu > li > a:hover,
.header-wrapper .header .menu-main-container .menu > li > a:hover,
.fixed-header .menu > li > a:hover,
.fixed-header-area.color-light .menu > li > a:hover,
.fixed-header-area.color-dark .menu > li > a:hover,
.header-type-2.slider-overlap .header .menu > li > a:hover,
.header-type-3.slider-overlap .header .menu > li > a:hover,
.header-type-7 .menu-wrapper .menu > li > a:hover,
.header-type-10 .menu-wrapper .navbar-collapse .menu-main-container .menu > li > a:hover,
.header-vertical-enable .page-wrapper .header-type-vertical .container .menu > li > a:hover,
.header-vertical-enable .page-wrapper .header-type-vertical2 .container .menu > li > a:hover,
.fullscreen-menu .menu > li > a:hover,
.fullscreen-menu .menu > li > .inside > a:hover
';

$selectors['menu_font_active']='
.header-wrapper .menu > li.current-menu-item > a,
.header-wrapper .header .menu-main-container .menu > li.current-menu-item > a,
.fixed-header .menu > li.current-menu-item > a,
.fixed-header-area.color-light .menu > li.current-menu-item > a,
.fixed-header-area.color-dark .menu > li.current-menu-item > a,
.header-type-2.slider-overlap .header .menu > li.current-menu-item > a,
.header-type-3.slider-overlap .header .menu > li.current-menu-item > a,
.header-type-7 .menu-wrapper .menu > li.current-menu-item > a,
.header-type-10 .menu-wrapper .navbar-collapse .menu-main-container .menu > li.current-menu-item > a,
.header-vertical-enable .page-wrapper .header-type-vertical .container .menu > li.current-menu-item > a,
.header-vertical-enable .page-wrapper .header-type-vertical2 .container .menu > li.current-menu-item > a,
.fullscreen-menu .menu > li.current-menu-item > a,
.fullscreen-menu .menu > li.current-menu-item > .inside > a
';

$selectors['menu_font_l2_hover']='
.menu .menu-full-width .nav-sublist-dropdown > * > ul > li > a:hover,
.header-vertical-enable .page-wrapper .header-type-vertical .container .menu .menu-full-width .nav-sublist-dropdown > * > ul > li > a:hover,
.header-vertical-enable .page-wrapper .header-type-vertical2 .container .menu .menu-full-width .nav-sublist-dropdown > * > ul > li > a:hover
';

$selectors['menu_font_l2_active']='
.menu .menu-full-width .nav-sublist-dropdown > * > ul > li.current-menu-item > a,
.header-vertical-enable .page-wrapper .header-type-vertical .container .menu .menu-full-width .nav-sublist-dropdown > * > ul > li.current-menu-item > a,
.header-vertical-enable .page-wrapper .header-type-vertical2 .container .menu .menu-full-width .nav-sublist-dropdown > * > ul > li.current-menu-item > a
';

$selectors['menu_font_l3_hover']='
.menu li:not(.menu-full-width) .nav-sublist-dropdown ul > li > a:hover,
.menu .menu-full-width .nav-sublist-dropdown ul > li.menu-item-has-children .nav-sublist ul li a:hover,
.header-vertical-enable .page-wrapper .header-type-vertical .container .menu .nav-sublist-dropdown ul > li.menu-item-has-children .nav-sublist ul li a:hover,
.header-vertical-enable .page-wrapper .header-type-vertical2 .container .menu .nav-sublist-dropdown ul > li.menu-item-has-children .nav-sublist ul li a:hover,
.fullscreen-menu .menu li .nav-sublist-dropdown li a:hover
';

$selectors['menu_font_l3_active']='
.menu li:not(.menu-full-width) .nav-sublist-dropdown ul > li.current-menu-item > a,
.menu .menu-full-width .nav-sublist-dropdown ul > li.menu-item-has-children .nav-sublist ul li.current-menu-item a,
.header-vertical-enable .page-wrapper .header-type-vertical .container .menu .nav-sublist-dropdown ul > li.menu-item-has-children .nav-sublist ul li.current-menu-item a,
.header-vertical-enable .page-wrapper .header-type-vertical2 .container .menu .nav-sublist-dropdown ul > li.menu-item-has-children .nav-sublist ul li.current-menu-item a,
.fullscreen-menu .menu li .nav-sublist-dropdown li.current-menu-item a
';

$selectors['darken_color'] = '
';

$selectors['darken_bg'] = '
';

$selectors['darken_border'] = '
';
		return $selectors;
	}
}

if(!function_exists('et_get_active_color')) {
    function et_get_active_color() {
        return apply_filters('et_get_active_color', '#cda85c');
    }
}
