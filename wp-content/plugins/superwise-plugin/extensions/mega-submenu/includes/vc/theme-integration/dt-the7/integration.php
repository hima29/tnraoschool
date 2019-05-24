<?php

/**
 * The 7 theme is using outdated and custom version of Meta Box plugin which is included directly in the theme.
 * This disables Mammoth plugin form using it's own up to date version.
 * So we need to use the same way of meta box registration the theme is using.
 */
add_action( 'admin_init', 'msm_the7_register_meta_boxes', 40 );
function msm_the7_register_meta_boxes() {

	$meta_boxes = apply_filters( 'rwmb_meta_boxes', array() );
	foreach ( $meta_boxes as $meta_box ) {
		new RW_Meta_Box( $meta_box );
	}

}

/**
 * The 7 theme disables Meta Box plugin on front end. We need to enable it.
 */
if ( ! is_admin() ) {
	include_once get_template_directory() . '/inc/extensions/meta-box/meta-box.php';
}

/**
 * Filtering the output of the mobile menu item wrappers.
 */
add_filter( 'msm_filter_submenu_before', 'msm_the7_filter_submenu_before', 11, 2 );
add_filter( 'msm_filter_submenu_after', 'msm_the7_filter_submenu_after', 11, 2 );

function msm_the7_filter_submenu_before($before, $menu_location) {
	if (msm_get_menu_location_theme_mobile() == $menu_location) {
		return '<ul class="sub-menu sub-nav gradient-hover hover-style-click-bg level-arrows-on"><li>';
	}
	return $before;
}

function msm_the7_filter_submenu_after($after, $menu_location) {
	if (msm_get_menu_location_theme_mobile() == $menu_location) {
		return '</li></ul>';
	}
	return $after;
}


/**
 * Filtering css classes of mobile menu items
 */
add_action( 'msm_filter_menu_item_css_class', 'msm_the7_filter_menu_item_css_class', 10, 3 );
function msm_the7_filter_menu_item_css_class( $classes, $theme_location ) {

	if ( msm_get_menu_location_theme_mobile() == $theme_location ) {
		foreach ($classes as $key => $class) {
			if ($class == 'msm-click' || $class == 'msm-hover') {
				unset($classes[$key]);
			}
		}
		$classes[] = 'msm-mobile has-children';
	}


	return $classes;
}