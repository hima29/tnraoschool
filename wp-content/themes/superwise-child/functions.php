<?php
// WP will add the style src only once
// this script runs before the theme style hook and registers theme style file
// because theme style hook is using get_stylesheet_uri which will load child theme style.css
add_action( 'wp_enqueue_scripts', 'superwise_child_theme_enqueue_styles' );
function superwise_child_theme_enqueue_styles() {
	$parent_style = 'superwise-style';
	wp_register_style( $parent_style, get_template_directory_uri() . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'superwise_child_enqueue_styles', 101 );
function superwise_child_enqueue_styles() {

	$parent_style = 'superwise-style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version')
	);
}

// put custom code here

// if ( function_exists( 'is_rtl' ) && is_rtl() && defined( 'WPB_VC_VERSION' )) {
// 	wp_deregister_script( 'wpb_composer_front_js' );
// 	wp_enqueue_script( 'wpb_composer_front_js', get_template_directory_uri() . '/assets/js/rtl.js', array( 'jquery' ), WPB_VC_VERSION, true );
// }
