<?php

// **********************************************************************//
// ! Register all 8theme Widgets
// **********************************************************************//
add_action( 'widgets_init', 'etheme_register_general_widgets' );
function etheme_register_general_widgets() {
    register_widget('Etheme_Twitter_Widget');
    register_widget('Etheme_Recent_Posts_Widget');
    register_widget('Etheme_Recent_Comments_Widget');
    register_widget('Etheme_Flickr_Widget');
    register_widget('Etheme_StatickBlock_Widget');
    register_widget('Etheme_QRCode_Widget');
    register_widget('Etheme_Search_Widget');
    register_widget('Etheme_Brands_Widget');
    register_widget('null_instagram_widget');
    register_widget('Etheme_Socials_Widget');
    //register_widget('Etheme_Subcategories_Widget');
}

// **********************************************************************//
// ! Brands Filter Widget
// **********************************************************************//
class Etheme_Brands_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_brands', 'description' => esc_html__( "Products Filter by brands", 'royal') );
        parent::__construct('etheme-brands', '8theme - '.esc_html__('Brands Filter', 'royal'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_brans';
    }

    function widget($args, $instance) {
        extract($args);

        $title = $instance['title'];
        $hierarchy = ( isset( $instance['hierarchy'] ) ) ? (bool) $instance['hierarchy'] : false;
        echo $before_widget;
        if(!$title == '' ){
            echo $before_title;
            echo $title;
            echo $after_title;
        }
        $current_term = get_queried_object();
        $args = ( true == $hierarchy ) ? array( 'taxonomy' => 'taxonomy_name', 'parent' => 0 ,'hide_empty' => false ) : array( 'hide_empty' => false);
        $terms = get_terms('brand', $args);
        $count = count($terms); $i=0;
        if ($count > 0) {
            ?>
            <ul>
                <?php
                    foreach ($terms as $term) {
                        $i++;
                        $curr = false;
                        $thumbnail_id   = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
                        if(isset($current_term->term_id) && $current_term->term_id == $term->term_id) {
                            $curr = true;
                        }
                        $termID = $term->term_id;
                        $taxonomyName = "brand";
                        $termchildren = get_term_children( $termID, $taxonomyName );
                        ?>
                            <li>
                                <a href="<?php echo get_term_link( $term ); ?>" title="<?php esc_html_e('View all products from ', 'royal'); echo $term->name; ?>">
                                    <?php if($curr) echo '<strong>'; ?><?php echo $term->name; ?><?php if($curr) echo '</strong>'; ?>
                                </a>
                                <?php
                                    if ( !empty( $termchildren ) && true == $hierarchy ) {
                                        echo '<div class="open-this">+</div>';
                                        echo '<ul class="children">';
                                            foreach ( $termchildren as $child ) :
                                                $termq = get_term_by( 'id', $child, $taxonomyName );
                                                $curr_inner = false;
                                                if(isset($current_term->term_id) && $current_term->term_id == $termq->term_id) {
                                                    $curr_inner = true;
                                                } ?>
                                                <li>
                                                    <a href="<?php echo get_term_link( $termq ); ?>">
                                                        <?php if($curr_inner) echo '<strong>'; ?><?php echo $termq->name; ?><?php if($curr_inner) echo '</strong>'; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach;
                                        echo '</ul>';
                                    }
                                ?>
                            </li>
                        <?php
                    }
                ?>
            </ul>
            <?php
        }
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['hierarchy'] = $new_instance['hierarchy'];

        return $instance;
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $hierarchy = isset($instance['hierarchy']) ? (bool) $instance['hierarchy'] : false;

?>
        <?php etheme_widget_input_text(esc_html__('Title', 'royal'), $this->get_field_id('title'),$this->get_field_name('title'), $title); ?>
        <?php etheme_widget_input_checkbox(esc_html__('Show hierarchy', 'royal'), $this->get_field_id('hierarchy'), $this->get_field_name('hierarchy'),checked($hierarchy, true, false), 1); ?>
<?php
    }
}
// **********************************************************************//
// ! Mega Search Widget
// **********************************************************************//
class Etheme_Search_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_search', 'description' => esc_html__( "AJAX Search form for Products, Posts, Portfolio and Pages", 'royal') );
        parent::__construct('etheme-search', '8theme - '.esc_html__('Search Form', 'royal'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_search';
    }

    function widget($args, $instance) {
        extract($args);

        $count = (int) $instance['count'];
        $images = (bool) $instance['images'];
        $post_type = $instance['post_type'];
        $instance['text'] = isset($instance['text']) ? $instance['text'] : 'Go';
        $text = trim ( $instance['text'] ) ? $instance['text'] : 'Go';

        echo $before_widget;
        echo etheme_search(array(
            'images' => $images,
            'count' => $count,
            'widget' => 1,
            'post_type' => $post_type,
            'text' => $text
        ));
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['count'] = (int) $new_instance['count'];

        $instance['images'] = (bool) $new_instance['images'];

        $instance['post_type'] = esc_attr($new_instance['post_type']);

        $instance['text'] = $new_instance['text'] ? esc_attr($new_instance['text']) : 'Go';

        return $instance;
    }

    function form( $instance ) {
        $images = isset($instance['images']) ? (bool) $instance['images'] : false;

        $count = isset($instance['count']) ? $instance['count'] : '';
        $instance['text'] = isset($instance['text']) ? trim ($instance['text']) : "Go";

        $text = $instance['text'];

?>

        <?php $post_type = array('product' => 'products','etheme_portfolio' => 'portfolios', 'post' => 'posts', 'page' => 'pages', 'testimonial' => 'testimonial', 'any' => 'all' ); ?>

        <p><label for="<?php echo $this->get_field_id('post_type'); ?>"><?php esc_html_e('Search type:', 'royal'); ?></label>
            <select name="<?php echo $this->get_field_name('post_type'); ?>" id="<?php echo $this->get_field_id('post_type'); ?>">
                <option>--Select--</option>
                <?php foreach ($post_type as $kay => $value) : ?>
                    <option value="<?php echo $kay; ?>"<?php if (isset($instance['post_type'])) selected( $instance['post_type'], $kay ); ?>><?php echo $value;?></option>
                <?php endforeach; ?>

            </select>
        </p>

        <?php $count = (empty($count)) ? 3:$count; ?>

        <?php etheme_widget_input_checkbox(esc_html__('Display images', 'royal'), $this->get_field_id('images'), $this->get_field_name('images'),checked($images, true, false), 1); ?>

        <?php etheme_widget_input_text(esc_html__('Number of items', 'royal'), $this->get_field_id('count'),$this->get_field_name('count'), $count); ?>

        <?php etheme_widget_input_text(esc_html__('Button text', 'royal'), $this->get_field_id('text'),$this->get_field_name('text'), $text); ?>

<?php
    }
}

// **********************************************************************//
// ! QR code Widget
// **********************************************************************//
class Etheme_QRCode_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_qr_code', 'description' => esc_html__( "You can add a QR code image in sidebar to allow your users get quick access from their devices", 'royal') );
        parent::__construct('etheme-qr-code', '8theme - '.esc_html__('QR Code', 'royal'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_qr_code';
    }

    function widget($args, $instance) {
        extract($args);

        $title = $instance['title'];
        $info = $instance['info'];
        $text = $instance['text'];
        $size = (int) $instance['size'];
        $lightbox = (bool) $instance['lightbox'];
        $currlink = (bool) $instance['currlink'];

        echo $before_widget;
        if ( $title ) echo $before_title . $title . $after_title;
        echo generate_qr_code($info, 'Open', $size, '', $currlink, $lightbox );
        if($text != '')
            echo $text;
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['info'] = strip_tags($new_instance['info']);
        $instance['text'] = ($new_instance['text']);
        $instance['size'] = (int) $new_instance['size'];
        $instance['lightbox'] = (bool) $new_instance['lightbox'];
        $instance['currlink'] = (bool) $new_instance['currlink'];



        return $instance;
    }

    function form( $instance ) {
        $block_id = 0;
        if(!empty($instance['block_id']))
            $block_id = esc_attr($instance['block_id']);

        $info = isset($instance['info']) ? $instance['info'] : '';
        $text = isset($instance['text']) ? $instance['text'] : '';
        $title = isset($instance['title']) ? $instance['title'] : '';
        $size = isset($instance['size']) ? (int) $instance['size'] : 256;
        $lightbox = isset($instance['lightbox']) ? (bool) $instance['lightbox'] : false;
        $currlink = isset($instance['currlink']) ? (bool) $instance['currlink'] : false;

?>
        <?php etheme_widget_input_text(esc_html__('Widget title:', 'royal'), $this->get_field_id('title'),$this->get_field_name('title'), $title); ?>

        <?php etheme_widget_textarea(esc_html__('Information to encode:', 'royal'), $this->get_field_id('info'),$this->get_field_name('info'), $info); ?>

        <?php etheme_widget_input_text(esc_html__('Image size:', 'royal'), $this->get_field_id('size'), $this->get_field_name('size'), $size); ?>

        <?php etheme_widget_input_checkbox(esc_html__('Show in lightbox', 'royal'), $this->get_field_id('lightbox'), $this->get_field_name('lightbox'),checked($lightbox, true, false), 1); ?>

        <?php etheme_widget_input_checkbox(esc_html__('Encode link to the current page', 'royal'), $this->get_field_id('currlink'), $this->get_field_name('currlink'),checked($currlink, true, false), 1); ?>

        <?php etheme_widget_textarea(esc_html__('Additional information in widget', 'royal'), $this->get_field_id('text'),$this->get_field_name('text'), $text); ?>

<?php
    }
}


// **********************************************************************//
// ! Recent posts Widget
// **********************************************************************//
class Etheme_StatickBlock_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_satick_block', 'description' => esc_html__( "Insert static block, that you created", 'royal') );
        parent::__construct('etheme-static-block', '8theme - '.esc_html__('Statick Block', 'royal'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_satick_block';
    }

    function widget($args, $instance) {
        extract($args);

        $block_id = $instance['block_id'];

        et_show_block($block_id);

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['block_id'] = $new_instance['block_id'];

        return $instance;
    }

    function form( $instance ) {
        $block_id = 0;
        if(!empty($instance['block_id']))
            $block_id = esc_attr($instance['block_id']);

?>
        <p><label for="<?php echo $this->get_field_id('block_id'); ?>"><?php esc_html_e('Block name:', 'royal'); ?></label>
            <?php $sb = et_get_static_blocks(); ?>
            <select name="<?php echo $this->get_field_name('block_id'); ?>" id="<?php echo $this->get_field_id('block_id'); ?>">
                <option>--Select--</option>
                <?php if (count($sb > 0)): ?>
                    <?php foreach ($sb as $key): ?>
                        <option value="<?php echo $key['value']; ?>" <?php selected( $block_id, $key['value'] ); ?>><?php echo $key['label'] ?></option>
                    <?php endforeach ?>
                <?php endif ?>
            </select>
        </p>
<?php
    }
}



// **********************************************************************//
// ! Recent posts Widget
// **********************************************************************//
class Etheme_Recent_Posts_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_recent_entries', 'description' => esc_html__( "The most recent posts on your blog (Etheme Edit)", 'royal') );
        parent::__construct('etheme-recent-posts', '8theme - '.esc_html__('Recent Posts', 'royal'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_recent_entries';

        add_action( 'save_post', array(&$this, 'flush_widget_cache') );
        add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
        add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('etheme_widget_recent_entries', 'widget');

        if ( !is_array($cache) )
                $cache = array();

        if ( isset($args['widget_id']) && isset($cache[$args['widget_id']]) ) {
                echo $cache[$args['widget_id']];
                return;
        }

        ob_start();
        extract($args);

        $box_id = rand(1000,10000);

        $title = apply_filters('widget_title', empty($instance['title']) ? false : $instance['title']);

        $number = ( empty( $instance['number'] ) ) ? 5 : $instance['number'];

        if ( $number > 15 ) $number = 15;

        $slider = (!empty($instance['slider'])) ? (int) $instance['slider'] : false;

        $r = new WP_Query(array('posts_per_page' => $number, 'post_type' => 'post', 'post_status' => 'publish', 'ignore_sticky_posts' => 1));
        if ($r->have_posts()) : ?>
        <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
            <?php if($slider): ?>
                <div class="owl-carousel blogCarousel slider-<?php echo $box_id; ?>">
            <?php endif; ?>
            <ul class="blog-post-list slide-item">
                <?php $i=0;  while ($r->have_posts()) : $r->the_post(); $i++; ?>
                    <?php
                        if ( get_the_title() ) $title = get_the_title(); else $title = get_the_ID();
                        $title = trunc($title, 10);
                    ?>
                    <li>
                        <div class="media">
                            <a class="pull-left" href="#">
                                <time class="date-event"><span class="number"><?php the_time('d'); ?></span> <?php the_time('M'); ?></time>
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="<?php the_permalink() ?>"><?php echo $title; ?></a></h4>
                                <?php esc_html_e('by', 'royal') ?> <strong><?php the_author(); ?></strong>
                            </div>
                        </div>
                    </li>
                <?php if( $i%2 == 0 && $i != $r->post_count && $slider ): ?>
                        </ul>
                        <ul class="blog-post-list slide-item">
                <?php endif; ?>
                <?php endwhile; ?>
            </ul>
            <?php if($slider): ?>
                </div>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        jQuery(".slider-<?php echo $box_id; ?>").owlCarousel({
                            items:1,
                            nav: true,
                            navText: ["",""],
                            lazyLoad: true,
                            rewind: false,
                            addClassActive: true,
                            responsive: {
                                1600: {
                                    items: 1
                                }
                            }
                        });
                    });
                </script>
            <?php endif; ?>
        <?php echo $after_widget; ?>
<?php
                wp_reset_query();  // Restore global post data stomped by the_post().
        endif;

        if(isset($args['widget_id'])) {
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_add('etheme_widget_recent_entries', $cache, 'widget');
        }
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['slider'] = (int) $new_instance['slider'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['etheme_widget_recent_entries']) )
                delete_option('etheme_widget_recent_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('etheme_widget_recent_entries', 'widget');
    }

    function form( $instance ) {
        $title = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );

        $number = ( empty( $instance['number'] ) || ! is_int( $instance['number'] ) ) ? 5 : $instance['number'];

        $slider = empty( $instance['slider'] ) ? 0 : 1;

?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'royal'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:', 'royal'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
        <small><?php esc_html_e('(at most 15)', 'royal'); ?></small></p>

        <?php etheme_widget_input_checkbox(esc_html__('Enable slider', 'royal'), $this->get_field_id('slider'), $this->get_field_name('slider'),checked($slider, true, false), 1); ?>

<?php
    }
}

// **********************************************************************//
// ! Twitter Widget
// **********************************************************************//

class Etheme_Twitter_Widget extends WP_Widget {
    function __construct() {
        $widget_ops = array( 'classname' => 'etheme_twitter', 'description' => esc_html__('Display most recent Twitter feed', 'royal') );
        $control_ops = array( 'id_base' => 'etheme-twitter' );
        parent::__construct( 'etheme-twitter', '8theme - '.esc_html__('Twitter Feed', 'royal'), $widget_ops, $control_ops );
    }
    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title'] );
        echo $before_widget;
        if ( $title ) echo $before_title . $title . $after_title;

        if ( $instance['consumer_key'] && $instance['consumer_secret'] && $instance['user_token'] && $instance['user_secret'] && $instance['usernames'] ) {
            $attr = array( 'usernames' => $instance['usernames'], 'limit' => $instance['limit'], 'interval' => $instance['interval'] );
            $attr['interval'] = $attr['interval'] * 10;
            echo etheme_print_tweets($instance['consumer_key'],$instance['consumer_secret'],$instance['user_token'],$instance['user_secret'],$attr['usernames'], $attr['limit'], 50);
        } else {
            esc_html_e( 'Not enough information', 'royal' );
        }

        echo $after_widget;
    }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['usernames'] = strip_tags( $new_instance['usernames'] );
        $instance['consumer_key'] = strip_tags( $new_instance['consumer_key'] );
        $instance['consumer_secret'] = strip_tags( $new_instance['consumer_secret'] );
        $instance['user_token'] = strip_tags( $new_instance['user_token'] );
        $instance['user_secret'] = strip_tags( $new_instance['user_secret'] );
        $instance['limit'] = strip_tags( $new_instance['limit'] );
        $instance['interval'] = strip_tags( $new_instance['interval'] );
        return $instance;
    }
    function form( $instance ) {
        $defaults = array( 'title' => '', 'usernames' => '8theme', 'limit' => '2', 'interval' => '5', 'consumer_key' => '', 'consumer_secret' => '', 'user_token' => '', 'user_secret' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        etheme_widget_input_text( esc_html__('Title:', 'royal'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
        etheme_widget_input_text( esc_html__('Username:', 'royal'), $this->get_field_id( 'usernames' ), $this->get_field_name( 'usernames' ), $instance['usernames'] );
        etheme_widget_input_text( esc_html__('Customer Key:', 'royal'), $this->get_field_id( 'consumer_key' ), $this->get_field_name( 'consumer_key' ), $instance['consumer_key'] );
        etheme_widget_input_text( esc_html__('Customer Secret:', 'royal'), $this->get_field_id( 'consumer_secret' ), $this->get_field_name( 'consumer_secret' ), $instance['consumer_secret'] );
        etheme_widget_input_text( esc_html__('Access Token:', 'royal'), $this->get_field_id( 'user_token' ), $this->get_field_name( 'user_token' ), $instance['user_token'] );
        etheme_widget_input_text( esc_html__('Access Token Secret:', 'royal'), $this->get_field_id( 'user_secret' ), $this->get_field_name( 'user_secret' ), $instance['user_secret'] );
        etheme_widget_input_text( esc_html__('Number of tweets:', 'royal'), $this->get_field_id( 'limit' ), $this->get_field_name( 'limit' ), $instance['limit'] );
    }
}

// **********************************************************************//
// ! Flickr Photos
// **********************************************************************//
class Etheme_Flickr_Widget extends WP_Widget {

    function __construct()
    {
        $widget_ops = array('classname' => 'flickr', 'description' => 'Photos from flickr.');
        $control_ops = array('id_base' => 'etheme_flickr-widget');
        parent::__construct('etheme_flickr-widget', '8theme Flickr Photos', $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', empty( $instance['title'] ) ? esc_html__('Flickr', 'royal') : $instance['title'], $instance, $this->id_base);
        $screen_name = @$instance['screen_name'];
        $number = @$instance['number'];
        $show_button = @$instance['show_button'];

        if(!$screen_name || $screen_name == '') {
            $screen_name = '95572727@N00';
        }

        echo $before_widget;
        if($title) {
            echo $before_title.'<span class="footer_title">'.$title.'</span>'.$after_title;
        }

        if($screen_name && $number) {
            echo '<script type="text/javascript" src="//www.flickr.com/badge_code_v2.gne?count='.$number.'&display=latest&size=s&layout=x&source=user&user='.$screen_name.'"></script>';
        }

        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['screen_name'] = $new_instance['screen_name'];
        $instance['number'] = $new_instance['number'];

        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => 'Photos from Flickr', 'screen_name' => '', 'number' => 6, 'show_button' => 1);
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('screen_name'); ?>">Flickr ID</label>
            <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>" value="<?php echo $instance['screen_name']; ?>" />
            <br/>
            <p class="help">To find your flickID visit <a href="http://idgettr.com/" target="_blank">idGettr</a>.</p>
        </p>


        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>">Number of photos to show:</label>
            <input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
        </p>


    <?php
    }
}

// **********************************************************************//
// ! Recent comments Widget
// **********************************************************************//

class Etheme_Recent_Comments_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_recent_comments', 'description' => esc_html__( 'The most recent comments (Etheme edit)', 'royal' ) );
        parent::__construct('etheme-recent-comments', '8theme - '.esc_html__('Recent Comments', 'royal'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_recent_comments';

        if ( is_active_widget(false, false, $this->id_base) )
            add_action( 'wp_head', array(&$this, 'recent_comments_style') );

        add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
        add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
    }

    function recent_comments_style() {
        if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
            || ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
            return;
        ?>
    <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
    }

    function flush_widget_cache() {
        wp_cache_delete('etheme_widget_recent_comments', 'widget');
    }

    function widget( $args, $instance ) {
        global $comments, $comment;

        $cache = wp_cache_get('etheme_widget_recent_comments', 'widget');

        if ( ! is_array( $cache ) )
            $cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

        extract($args, EXTR_SKIP);
        $output = '';
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
            $number = 5;

        $comments = get_comments( array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) );
        $output .= $before_widget;
        if ( $title != '')
            $output .= $before_title . $title . $after_title;

        $output .= '<ul id="recentcomments">';
        if ( $comments ) {
            foreach ( (array) $comments as $comment) {
                //$output .=  '<li class="recentcomments"><div class="comment-date">' . get_comment_date('d') . ' <span>' . get_comment_date('M') . '</span>' . '</div>' . sprintf(_x('<span class="comment_author">%1$s</span> <br> %2$s', 'widgets'), get_comment_author_link(), '<span class="comment_link"><a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a></span>') . '<div class="clear"></div></li>';

                $output .=  '<li class="recentcomments">';
                    $output .=  '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '" class="post-title">' . get_the_title($comment->comment_post_ID) . '</a><br>';
                    $output .=  get_the_time('d M Y', $comment->comment_post_ID);
                    $output .=  ' @ '.get_the_time(get_option('time_format'), $comment->comment_post_ID);
                    $output .=  ' '.esc_html__('by', 'royal').' <span class="comment_author">'.get_comment_author_link().'</span>';
                $output .=  '</li>';
            }
        }
        $output .= '</ul>';
        $output .= $after_widget;

        echo $output;
        $cache[$args['widget_id']] = $output;
        wp_cache_set('widget_recent_comments', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = absint( $new_instance['number'] );
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['etheme_widget_recent_comments']) )
            delete_option('etheme_widget_recent_comments');

        return $instance;
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'royal'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of comments to show:', 'royal'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
    }
}

/* Forms
-------------------------------------------------------------- */
function etheme_widget_label( $label, $id ) {
    echo "<label for='{$id}'>{$label}</label>";
}
function etheme_widget_input_checkbox( $label, $id, $name, $checked, $value = 1 ) {
    echo "\n\t\t\t<p>";
    echo "<label for='{$id}'>";
    echo "<input type='checkbox' id='{$id}' value='{$value}' name='{$name}' {$checked} /> ";
    echo "{$label}</label>";
    echo '</p>';
}
function etheme_widget_textarea( $label, $id, $name, $value ) {
    echo "\n\t\t\t<p>";
    etheme_widget_label( $label, $id );
    echo "<textarea id='{$id}' name='{$name}' rows='3' cols='10' class='widefat'>" . strip_tags( $value ) . "</textarea>";
    echo '</p>';
}
function etheme_widget_input_text( $label, $id, $name, $value ) {
    echo "\n\t\t\t<p>";
    etheme_widget_label( $label, $id );
    echo "<input type='text' id='{$id}' name='{$name}' value='" . strip_tags( $value ) . "' class='widefat' />";
    echo '</p>';
}


class null_instagram_widget extends WP_Widget {

    function __construct() {
		parent::__construct(
			'null-instagram-feed',
			__( 'Instagram', 'royal' ),
			array(
				'classname' => 'null-instagram-feed',
				'description' => esc_html__( 'Displays your latest Instagram photos', 'royal' ),
				'customize_selective_refresh' => true,
			)
		);
	}

	function widget($args, $instance) {

		extract($args, EXTR_SKIP);

		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
        $username = empty( $instance['username'] ) ? '' : $instance['username'];
		$limit = empty( $instance['number'] ) ? 9 : $instance['number'];
		$columns = empty($instance['columns']) ? 3 : (int) $instance['columns'];
		$size = empty( $instance['size'] ) ? 'large' : $instance['size'];
        $target = empty( $instance['target'] ) ? '_self' : $instance['target'];
		$link = empty( $instance['link'] ) ? '' : $instance['link'];
		$filter = empty($instance['filter_img']) ? '' : $instance['filter_img'];
		$info = empty($instance['info']) ? false : true;
		$slider = empty($instance['slider']) ? false : true;
		$spacing = empty($instance['spacing']) ? false : true;

		// slider args
		$large = empty($instance['large']) ? 4 : $instance['large'];
		$notebook = empty($instance['notebook']) ? 3 : $instance['notebook'];
		$tablet_land = empty($instance['tablet_land']) ? 2 : $instance['tablet_land'];
        $tablet_portrait = empty($instance['tablet_portrait']) ? 2 : $instance['tablet_portrait'];
        $mobile = empty($instance['mobile']) ? 1 : $instance['mobile'];
        $slider_autoplay = empty($instance['slider_autoplay']) ? false : true;
        $slider_speed = empty($instance['slider_speed']) ? 10000 : $instance['slider_speed'];
        $pagination_type = empty($instance['pagination_type']) ? 'hide' : $instance['pagination_type'];
        $default_color = empty($instance['default_color']) ? '#e6e6e6' : $instance['default_color'];
        $active_color = empty($instance['active_color']) ? '#b3a089' : $instance['active_color'];
        $hide_fo = empty($instance['hide_fo']) ? '' : $instance['hide_fo'];
        $hide_buttons = empty($instance['hide_buttons']) ? false : true;

		echo $args['before_widget'];

		if ( ! empty( $title ) ) { echo $args['before_title'] . wp_kses_post( $title ) . $args['after_title']; };

		do_action( 'wpiw_before_widget', $instance );

		if ( '' !== $username ) {

			$media_array = $this->scrape_instagram( $username );

			if ( is_wp_error($media_array) ) {

				echo wp_kses_post( $media_array->get_error_message() );

			} else {

                // filter for images only?
				if ( $images_only = apply_filters( 'wpiw_images_only', false ) ) {
					$media_array = array_filter( $media_array, array( $this, 'images_only' ) );
				}

                // slice list down to required limit.
				$media_array = array_slice( $media_array, 0, $limit );

				// filters for custom classes
                $ulclass = apply_filters( 'wpiw_list_class', 'instagram-pics instagram-size-' . $size );
				$liclass = apply_filters( 'wpiw_item_class', '' );
				$aclass = apply_filters( 'wpiw_a_class', '' );
				$imgclass = apply_filters( 'wpiw_img_class', '' );
				$template_part = apply_filters( 'wpiw_template_part', 'parts/wp-instagram-widget.php' );
				$box_id = rand(1000,10000);

				?><ul class="<?php if( $slider ) { echo "owl-carousel"; } ?> instagram-pics instagram-size-<?php echo esc_attr( $size ); ?> instagram-columns-<?php echo esc_attr( $columns ); ?> <?php if($spacing) echo 'instagram-no-space'; ?> <?php if($slider) echo 'instagram-slider instagram-slider-'.$box_id.''; ?> <?php if ($hide_buttons == true) echo 'navigation_off';  ?> clearfix"><?php
				foreach ( $media_array as $item ) {
					// copy the else line into a new file (parts/wp-instagram-widget.php) within your theme and customise accordingly
					if ( locate_template( $template_part ) !== '' ) {
						include( locate_template( $template_part ) );
					} else {
                            if( $size == 'large' && isset($item['large'])) {
                                $image_src = $item['large'];
                            } else {
                                $image_src = $item['thumbnail'];
                            }
                            
                            if( $size == 'thumbnail' && isset($item['thumbnail'])) {
                                $image_src = $item['thumbnail'];
                            }

                            if ($size == 'medium' && isset($item['small'])) {
                                $image_src = $item['small'];
                            }

                            echo '<li class="' . esc_attr( $liclass ) . '">
                                <a href="' . esc_url( $item['link'] ) . '" target="' . esc_attr( $target ) . '"  class="' . esc_attr( $aclass ) . '">
                                    <img src="' . esc_url( $image_src ) . '"  alt="' . esc_attr( $item['description'] ) . '" title="' . esc_attr( $item['description'] ) . '"  class="' . esc_attr( $imgclass ) . '"/>';

							if ($info) {
							echo '<div class="insta-info">
								<span class="insta-likes">' . $item['likes']. '</span>
								<span class="insta-comments">' . $item['comments']. '</span>
							</div>';
							}
						echo '</a></li>';
					}
				}
				?></ul><?php

				if($slider) {
					// $large_items = 6;
					// switch ($instance['size']) {
					// 	case 'thumbnail':
					// 		$large_items = 8;
					// 	break;
					// 	case 'medium':
					// 		$large_items = 6;
					// 	break;
					// 	case 'large':
					// 		$large_items = 4;
					// 	break;
					// }
			        $items = '{0:{items:' . $mobile . '}, 481:{items: 2}, 619:{items:3}, 768:{items:' . $tablet_portrait . '}, 1024:{items:' . $tablet_land . '},  1200:{items:' . $notebook . '}, 1600:{items:' . $large . '}}';
		        	echo '
				        <script type="text/javascript">
				            (function() {
				                var instaOptions = {
				                    items:4,
				                    lazyLoad : false,
				                    autoPlay: ' . (($slider_autoplay == true) ? $slider_speed : "false" ). ',
				                    dots: ' . (($pagination_type == "hide") ? "false" : "true") . ',
				                    nav: ' . (($hide_buttons == true) ? "false" : "true" ). ',
				                    navText:["",""],
				                    rewind: ' . (($slider_autoplay == true) ? "true" : "false" ). ',
				                    responsive: '.$items.'
				                };

				                jQuery(".instagram-slider-'.$box_id.'").owlCarousel(instaOptions);

								var instaOwl = jQuery(".instagram-slider-'.$box_id.'").data("owl.carousel");

				                jQuery( window ).bind( "vc_js", function() {
				                	jQuery(".instagram-slider-'.$box_id.'").trigger(\'refresh.owl.carousel\');
									jQuery(".instagram-slider-'.$box_id.' .owl-pagination").addClass("pagination-type-'.$pagination_type.' hide-for-'.$hide_fo.'");
								} );

				            })();
				        </script>
				    ';
			        if ( $pagination_type != 'hide' && $default_color != '#e6e6e6' && $active_color !='#b3a089' ) {
				        echo '
				            <style>
				                .instagram-slider-'.$box_id.' .owl-pagination .owl-page{
				                    background-color:'.$default_color.';
				                }
				                .instagram-slider-'.$box_id.'.owl-carousel .owl-pagination .owl-page:hover{
				                    background-color:'.$active_color.';
				                }
				                .instagram-slider-'.$box_id.' .owl-pagination .owl-page.active{
				                    background-color:'.$active_color.';
				                }
				            </style>
				        ';
				    }
				}
			}
		}

        $linkclass = apply_filters( 'wpiw_link_class', 'clear et-follow-instagram' );
		$linkaclass = apply_filters( 'wpiw_linka_class', '' );

		switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url = '//instagram.com/explore/tags/' . str_replace( '#', '', $username );
				break;

			default:
				$url = '//instagram.com/' . str_replace( '@', '', $username );
				break;
		}

		if ( '' !== $link ) {
			?><p class="<?php echo esc_attr( $linkclass ); ?>"><a href="<?php echo trailingslashit( esc_url( $url ) ); ?>" rel="me" target="<?php echo esc_attr( $target ); ?>" class="<?php echo esc_attr( $linkaclass ); ?>"><?php echo wp_kses_post( $link ); ?></a></p><?php
		}

		do_action( 'wpiw_after_widget', $instance );

		echo $args['after_widget'];
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array(
            'title' => esc_html__('Instagram', 'royal'),
            'username' => '',
            'link' => esc_html__('Follow Us', 'royal'),
            'number' => 9,
            'size' => 'large',
            'target' => '_self',
            'info' => false,
            'slider' => false) );

        $title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$number = absint($instance['number']);
		$size = esc_attr($instance['size']);
		$columns = @(int) $instance['columns'];
		$target = esc_attr($instance['target']);
		$link = esc_attr($instance['link']);
		$info = esc_attr($instance['info']);
		$slider = esc_attr($instance['slider']);
		$spacing = @esc_attr($instance['spacing']);

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'royal'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('username'); ?>"><?php esc_html_e('Username or hashtag', 'royal'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of photos', 'royal'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('size'); ?>"><?php esc_html_e('Photo size', 'royal'); ?>:</label>
			<select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" class="widefat">
				<option value="thumbnail" <?php selected('thumbnail', $size) ?>><?php esc_html_e('Thumbnail', 'royal'); ?></option>
				<option value="medium" <?php selected('medium', $size) ?>><?php esc_html_e('Medium', 'royal'); ?></option>
				<option value="large" <?php selected('large', $size) ?>><?php esc_html_e('Large', 'royal'); ?></option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('target'); ?>"><?php esc_html_e('Open links in', 'royal'); ?>:</label>
			<select id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" class="widefat">
				<option value="_self" <?php selected('_self', $target) ?>><?php esc_html_e('Current window (_self)', 'royal'); ?></option>
				<option value="_blank" <?php selected('_blank', $target) ?>><?php esc_html_e('New window (_blank)', 'royal'); ?></option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('columns'); ?>"><?php esc_html_e('Columns', 'royal'); ?>:</label>
			<select id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" class="widefat">
				<option value="2" <?php selected(2, $columns) ?>>2</option>
				<option value="3" <?php selected(3, $columns) ?>>3</option>
				<option value="4" <?php selected(4, $columns) ?>>4</option>
				<option value="5" <?php selected(5, $columns) ?>>5</option>
				<option value="6" <?php selected(6, $columns) ?>>6</option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php esc_html_e('Link text', 'royal'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
		<p>
			<input type="checkbox" <?php checked( true, $info, true); ?> id="<?php echo $this->get_field_id('info'); ?>" name="<?php echo $this->get_field_name('info'); ?>">
			<label for="<?php echo $this->get_field_id('info'); ?>"><?php esc_html_e('Additional information', 'royal'); ?></label>
		</p>
		<p>
			<input type="checkbox" <?php checked( true, $slider, true); ?> id="<?php echo $this->get_field_id('slider'); ?>" name="<?php echo $this->get_field_name('slider'); ?>">
			<label for="<?php echo $this->get_field_id('slider'); ?>"><?php esc_html_e('Carousel', 'royal'); ?></label>
		</p>
		<p>
			<input type="checkbox" <?php checked( true, $spacing, true); ?> id="<?php echo $this->get_field_id('spacing'); ?>" name="<?php echo $this->get_field_name('spacing'); ?>">
			<label for="<?php echo $this->get_field_id('spacing'); ?>"><?php esc_html_e('Without spacing', 'royal'); ?></label>
		</p>
		<?php

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = trim(strip_tags($new_instance['username']));
		$instance['number'] = !absint($new_instance['number']) ? 9 : $new_instance['number'];
		$instance['columns'] = !absint($new_instance['columns']) ? 3 : $new_instance['columns'];
		$instance['size'] = (($new_instance['size'] == 'thumbnail' || $new_instance['size'] == 'medium' || $new_instance['size'] == 'large' || $new_instance['size'] == 'small') ? $new_instance['size'] : 'thumbnail');
		$instance['target'] = (($new_instance['target'] == '_self' || $new_instance['target'] == '_blank') ? $new_instance['target'] : '_self');
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['info'] = ($new_instance['info'] != '') ? true : false;
		$instance['slider'] = ($new_instance['slider'] != '') ? true : false;
		$instance['spacing'] = ($new_instance['spacing'] != '') ? true : false;
		return $instance;
	}

    // based on https://gist.github.com/cosmocatalano/4544576.
    	function scrape_instagram( $username ) {

    		$username = trim( strtolower( $username ) );

            switch ( substr( $username, 0, 1 ) ) {
                case '#':
                    $url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
                    $transient_prefix = 'h';
                    break;

                default:
                    $url              = 'https://instagram.com/' . str_replace( '@', '', $username );
                    $transient_prefix = 'u';
                    break;
            }

    		if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

    			$remote = wp_remote_get( $url );

    			if ( is_wp_error( $remote ) ) {
    				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'royal' ) );
    			}

    			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
    				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'royal' ) );
    			}

    			$shards = explode( 'window._sharedData = ', $remote['body'] );
    			$insta_json = explode( ';</script>', $shards[1] );
    			$insta_array = json_decode( $insta_json[0], true );

    			if ( ! $insta_array ) {
    				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'royal' ) );
    			}

                if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
    				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
    			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
    				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
    			} else {
    				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'royal' ) );
    			}

    			if ( ! is_array( $images ) ) {
    				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'royal' ) );
    			}

    			$instagram = array();

                foreach ( $images as $image ) {
                    if ( true === $image['node']['is_video'] ) {
                        $type = 'video';
                    } else {
                        $type = 'image';
                    }

                    $caption = __( 'Instagram Image', 'royal' );
                    if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
                        $caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
                    }

                    $instagram[] = array(
                        'description' => $caption,
                        'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
                        'time'        => $image['node']['taken_at_timestamp'],
                        'comments'    => $image['node']['edge_media_to_comment']['count'],
                        'likes'       => $image['node']['edge_liked_by']['count'],
                        'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
                        'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
                        'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
                        'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
                        'type'        => $type,
                    );
                } // End foreach().

                // do not set an empty transient - should help catch private or empty accounts.
    			if ( ! empty( $instagram ) ) {
    				$instagram = base64_encode( serialize( $instagram ) );
    				set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
    			}
    		}

    		if ( ! empty( $instagram ) ) {

    			return unserialize( base64_decode( $instagram ) );

    		} else {

    			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'royal' ) );

    		}
    	}

    	function images_only( $media_item ) {

    		if ( 'image' === $media_item['type'] ) {
    			return true;
    		}

    		return false;
    	}
    }



// **********************************************************************//
// ! Recent socials Widget
// **********************************************************************//
class Etheme_Socials_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_socials', 'description' => esc_html__( "Social links widget", 'royal') );
        parent::__construct('etheme-socials', '8theme - '.esc_html__('Social links', 'royal'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_socials';
    }

    function widget($args, $instance) {
        extract($args);


        $title = apply_filters('widget_title', empty($instance['title']) ? false : $instance['title']);
        if ( !$number = (int) $instance['number'] )
                $number = 10;
        else if ( $number < 1 )
                $number = 1;
        else if ( $number > 15 )
                $number = 15;


        $slider = (!empty($instance['slider'])) ? (int) $instance['slider'] : false;
        $image = (!empty($instance['image'])) ? (int) $instance['image'] : false;
        $size = (!empty($instance['size'])) ? $instance['size'] : '';
        $target = (!empty($instance['target'])) ? $instance['target'] : '';

        $facebook = (!empty($instance['facebook'])) ? $instance['facebook'] : '';
        $twitter = (!empty($instance['twitter'])) ? $instance['twitter'] : '';
        $instagram = (!empty($instance['instagram'])) ? $instance['instagram'] : '';
        $google = (!empty($instance['google'])) ? $instance['google'] : '';
        $pinterest = (!empty($instance['pinterest'])) ? $instance['pinterest'] : '';
        $linkedin = (!empty($instance['linkedin'])) ? $instance['linkedin'] : '';
        $tumblr = (!empty($instance['tumblr'])) ? $instance['tumblr'] : '';
        $youtube = (!empty($instance['youtube'])) ? $instance['youtube'] : '';
        $vimeo = (!empty($instance['vimeo'])) ? $instance['vimeo'] : '';
        $rss = (!empty($instance['rss'])) ? $instance['rss'] : '';
        $colorfull = (!empty($instance['colorfull'])) ? $instance['colorfull'] : '';


        echo $before_widget;
        if(!$title == '' ){
            echo $before_title;
            echo $title;
            echo $after_title;
        }

        echo et_follow_shortcode(array(
            'size' => $size,
            'target' => $target,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'instagram' => $instagram,
            'google' => $google,
            'pinterest' => $pinterest,
            'linkedin' => $linkedin,
            'tumblr' => $tumblr,
            'youtube' => $youtube,
            'vimeo' => $vimeo,
            'rss' => $rss,
            'colorfull' => $colorfull,
        ));

        echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['size'] = strip_tags($new_instance['size']);
        $instance['target'] = strip_tags($new_instance['target']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['slider'] = (int) $new_instance['slider'];
        $instance['image'] = (int) $new_instance['image'];

        $instance['facebook'] = strip_tags($new_instance['facebook']);
        $instance['twitter'] = strip_tags($new_instance['twitter']);
        $instance['instagram'] = strip_tags($new_instance['instagram']);
        $instance['google'] = strip_tags($new_instance['google']);
        $instance['pinterest'] = strip_tags($new_instance['pinterest']);
        $instance['linkedin'] = strip_tags($new_instance['linkedin']);
        $instance['tumblr'] = strip_tags($new_instance['tumblr']);
        $instance['youtube'] = strip_tags($new_instance['youtube']);
        $instance['vimeo'] = strip_tags($new_instance['vimeo']);
        $instance['rss'] = strip_tags($new_instance['rss']);
        $instance['colorfull'] = (int) ($new_instance['colorfull']);



        return $instance;
    }

    function form( $instance ) {
        $title = @esc_attr($instance['title']);
        $size = @esc_attr($instance['size']);
        $target = @esc_attr($instance['target']);

        $facebook = @esc_attr($instance['facebook']);
        $twitter = @esc_attr($instance['twitter']);
        $instagram = @esc_attr($instance['instagram']);
        $google = @esc_attr($instance['google']);
        $pinterest = @esc_attr($instance['pinterest']);
        $linkedin = @esc_attr($instance['linkedin']);
        $tumblr = @esc_attr($instance['tumblr']);
        $youtube = @esc_attr($instance['youtube']);
        $vimeo = @esc_attr($instance['vimeo']);
        $rss = @esc_attr($instance['rss']);


        $slider = (int) @$instance['slider'];
        $image = (int) @$instance['image'];
        $colorfull = (int) @$instance['colorfull'];

        etheme_widget_input_text(esc_html__('Title', 'royal'), $this->get_field_id('title'),$this->get_field_name('title'), $title);
        etheme_widget_input_dropdown(esc_html__('Size', 'royal'), $this->get_field_id('size'),$this->get_field_name('size'), $size, array(
            'small' => 'Small',
            'normal' => 'Normal',
            'large' => 'Large',
        ));

        etheme_widget_input_text(esc_html__('Facebook link', 'royal'), $this->get_field_id('facebook'),$this->get_field_name('facebook'), $facebook);
        etheme_widget_input_text(esc_html__('Twitter link', 'royal'), $this->get_field_id('twitter'),$this->get_field_name('twitter'), $twitter);
        etheme_widget_input_text(esc_html__('Instagram link', 'royal'), $this->get_field_id('instagram'),$this->get_field_name('instagram'), $instagram);
        etheme_widget_input_text(esc_html__('Google + link', 'royal'), $this->get_field_id('google'),$this->get_field_name('google'), $google);
        etheme_widget_input_text(esc_html__('Pinterest link', 'royal'), $this->get_field_id('pinterest'),$this->get_field_name('pinterest'), $pinterest);
        etheme_widget_input_text(esc_html__('LinkedIn link', 'royal'), $this->get_field_id('linkedin'),$this->get_field_name('linkedin'), $linkedin);
        etheme_widget_input_text(esc_html__('Tumblr link', 'royal'), $this->get_field_id('tumblr'),$this->get_field_name('tumblr'), $tumblr);
        etheme_widget_input_text(esc_html__('YouTube link', 'royal'), $this->get_field_id('youtube'),$this->get_field_name('youtube'), $youtube);
        etheme_widget_input_text(esc_html__('Vimeo link', 'royal'), $this->get_field_id('vimeo'),$this->get_field_name('vimeo'), $vimeo);
        etheme_widget_input_text(esc_html__('RSS link', 'royal'), $this->get_field_id('rss'),$this->get_field_name('rss'), $rss);
        etheme_widget_input_checkbox(esc_html__('Colorfull icons', 'royal'), $this->get_field_id('colorfull'),$this->get_field_name('colorfull'), checked( 1, $colorfull, false ), 1);

        etheme_widget_input_dropdown(esc_html__('Link Target', 'royal'), $this->get_field_id('target'),$this->get_field_name('target'), $target, array(
            '_self' => 'Current window',
            '_blank' => 'Blank',
        ));

    }
}

if(!function_exists('etheme_widget_input_dropdown')) {
    function etheme_widget_input_dropdown( $label, $id, $name, $value, $options ) {
        echo "\n\t\t\t<p>";
        etheme_widget_label( $label, $id );
        echo "<select id='{$id}' name='{$name}' class='widefat'>";
        echo '<option value=""></option>';
        foreach ($options as $key => $option) {
            echo '<option value="' . $key . '" ' . selected( strip_tags( $value ), $key ) . '>' . $option . '</option>';
        }
        echo "</select>";
        echo '</p>';
    }
}
