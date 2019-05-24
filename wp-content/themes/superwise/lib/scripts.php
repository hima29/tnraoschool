<?php

add_action( 'wp_enqueue_scripts', 'superwise_scripts', 100 );
add_action( 'wp_enqueue_scripts', 'superwise_add_compiled_style', 999 );

function superwise_scripts() {
	// styles
	wp_enqueue_style( 'groundwork-grid', get_template_directory_uri() . '/assets/css/groundwork-responsive.css', false );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', false );
	wp_enqueue_style( 'js_composer_front' );
	wp_enqueue_style( 'superwise-theme-icons', get_template_directory_uri() . '/assets/css/theme-icons.css', false );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.css', false );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', false );
	wp_enqueue_style( 'superwise-css', get_template_directory_uri() . '/assets/css/jquery-ui.css', false );
 
	wp_enqueue_style( 'superwise-style', get_stylesheet_uri(), false );

	// inline styles
	wp_add_inline_style( 'superwise-style', superwise_responsive_menu_scripts() );


	if ( function_exists( 'is_rtl' ) && is_rtl() ) {
		wp_enqueue_style( 'superwise_rtl', get_template_directory_uri() . '/assets/css/rtl.css', false );
	}

	// scripts
	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.7.0.min.js', array(), null, false );
	wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/assets/js/plugins/fitvids.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'superfish', get_template_directory_uri() . '/assets/js/plugins/superfish.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'hoverintent', get_template_directory_uri() . '/assets/js/plugins/hoverintent.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'scrollup', get_template_directory_uri() . '/assets/js/plugins/scrollup.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'jquery.sticky', get_template_directory_uri() . '/assets/js/plugins/jquery.sticky.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'fakeLoader', get_template_directory_uri() . '/assets/js/plugins/fakeLoader.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'natural-width-height', get_template_directory_uri() . '/assets/js/plugins/natural-width-height.js', array( 'jquery' ), null, true );
	wp_localize_script( 'jquery-migrate', 'wheels', superwise_set_js_global_var() );
   
	wp_enqueue_script( 'superwise-scripts', get_template_directory_uri() . '/assets/js/wheels-main.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/bootstrap/js/bootstrap.js', array(), null, false );
    wp_enqueue_script( 'bootstrap-min', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array(), null, false );
	wp_enqueue_script( 'jquery-js', get_template_directory_uri() . '/assets/js/jquery-ui.js', array(), null, false );
	wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/assets/js/custom.js', array(), null, false );
}

if ( ! function_exists( 'superwise_add_compiled_style' ) ) {

	function superwise_add_compiled_style() {
		$upload_dir = wp_upload_dir();

		$opt_name = SUPERWISE_THEME_OPTION_NAME;

		if ( file_exists( $upload_dir['basedir'] . '/' . $opt_name . '_style.css' ) ) {
			$upload_url = $upload_dir['baseurl'];
			if ( strpos( $upload_url, 'https' ) !== false ) {
				$upload_url = str_replace( 'https:', '', $upload_url );
			} else {
				$upload_url = str_replace( 'http:', '', $upload_url );
			}
			wp_enqueue_style( $opt_name . '_style', $upload_url . '/' . $opt_name . '_style.css', false );
		} else {

	        $font_url = add_query_arg( 'family', urlencode( 'Libre Franklin:300,400,500,600&amp;subset=latin' ), "//fonts.googleapis.com/css" );

			wp_enqueue_style( 'superwise-fonts', $font_url, false );
			wp_enqueue_style( $opt_name . '_style', get_template_directory_uri() . '/assets/css/wheels_options_style.css', false );
		}


		wp_add_inline_style( $opt_name . '_style', superwise_custom_css() );
		wp_add_inline_style( $opt_name . '_style', superwise_add_layout_blocks_css() );

	}
}

function superwise_set_js_global_var() {

	return array(
		'siteName' => get_bloginfo( 'name', 'display' ),
		'data'     => array(
			'useScrollToTop'                    => filter_var( superwise_get_option( 'use-scroll-to-top', false ), FILTER_VALIDATE_BOOLEAN ),
			'useStickyMenu'                     => filter_var( superwise_get_option( 'main-menu-use-menu-is-sticky', true ), FILTER_VALIDATE_BOOLEAN ),
			'scrollToTopText'                   => superwise_get_option( 'scroll-to-top-text', '' ),
			'isAdminBarShowing'                 => is_admin_bar_showing() ? true : false,
			'initialWaypointScrollCompensation' => superwise_get_option( 'main-menu-initial-waypoint-compensation', 120 ),
			'preloaderSpinner'                  => (int) superwise_get_option( 'preloader', 0 ),
			'preloaderBgColor'                  => superwise_get_option( 'preloader-bg-color', '#304ffe' ),

		)
	);

}
