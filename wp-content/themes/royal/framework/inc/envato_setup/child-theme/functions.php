<?php add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'bootstrap', 'parent-style' ) );
    wp_enqueue_style( 'dynamic-css', get_stylesheet_directory_uri() . '/dynamic.css' );
}
