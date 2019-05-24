<?php

add_action( 'after_setup_theme', 'superwise_setup' );
add_action( 'widgets_init', 'superwise_widgets_init' );

add_action('admin_head', 'superwise_custom_fonts');

function superwise_custom_fonts() {
	echo '<style>
    .redux-notice {
        display: none;
    }
  </style>';
}


if ( ! function_exists( 'superwise_setup' ) ) {

	function superwise_setup() {

		add_filter('superwise_alt_buttons', 'superwise_add_to_alt_button_list');

		// Make theme available for translation
		load_theme_textdomain( 'superwise', get_template_directory() . '/languages' );

		// Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
		register_nav_menus( array(
			'primary_navigation' => esc_html__( 'Primary Navigation', 'superwise' ),
		) );
		register_nav_menus( array(
			'secondary_navigation' => esc_html__( 'Secondary Navigation', 'superwise' ),
		) );
		register_nav_menus( array(
			'mobile_navigation' => esc_html__( 'Mobile Navigation', 'superwise' ),
		) );
		register_nav_menus( array(
			'quick_sidebar_navigation' => esc_html__( 'Quick Sidebar Navigation', 'superwise' ),
		) );
		register_nav_menus( array(
			'custom_navigation_1' => esc_html__( 'Custom Navigation 1', 'superwise' ),
		) );
		register_nav_menus( array(
			'custom_navigation_2' => esc_html__( 'Custom Navigation 2', 'superwise' ),
		) );
		register_nav_menus( array(
			'custom_navigation_3' => esc_html__( 'Custom Navigation 3', 'superwise' ),
		) );

		// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 300, 200, true );

		add_image_size( 'superwise-featured-image', 895, 430, true );
		add_image_size( 'superwise-medium', 768, 510, true );
		add_image_size( 'superwise-square', 768, 768, true );
		add_image_size( 'superwise-square-small', 420, 420, true );

		// Add post formats (http://codex.wordpress.org/Post_Formats)
		add_theme_support( 'post-formats', array(
			'aside',
			'gallery',
			'link',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat'
		) );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-logo' );
		
		superwise_register_custom_thumbnail_sizes();
	}
}

function superwise_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Primary', 'superwise' ),
		'id'            => 'wheels-sidebar-primary',
		'before_widget' => '<div class="widget %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Child Pages', 'superwise' ),
		'id'            => 'wheels-sidebar-child-pages',
		'before_widget' => '<div class="widget %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Recent Tweets for VC', 'superwise' ),
		'description'   => esc_html__( 'Add Recent Tweets widget here and save your credetials. You can use it from Visual Composer Widgetised Sidebar widget throughout the site.', 'superwise' ),
		'id'            => 'wheels-sidebar-twitter-widget',
		'before_widget' => '<div class="widget %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

}

function superwise_add_to_alt_button_list($alt_button_arr) {

	$alt_button_arr[] = '.yith-wcwl-add-button a';

	return $alt_button_arr;

}
