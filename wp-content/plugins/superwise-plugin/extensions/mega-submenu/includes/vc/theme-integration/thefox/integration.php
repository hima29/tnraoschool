<?php

/**
 * The Fox theme registers menu locations very late so we have to do it via filter
 */
add_filter( 'msm_filter_menu_location', 'msm_integration_filter_menu_location' );
function msm_integration_filter_menu_location( $menu_location ) {
	return 'main-menu';

}
add_filter( 'msm_filter_menu_location_theme_mobile', 'msm_integration_filter_menu_location_mobile' );
function msm_integration_filter_menu_location_mobile( $menu_location ) {
	return 'mobile-menu';

}

/**
 * Filtering css classes of mobile menu items
 * The Fox theme removes the third $args argument from nav_menu_css_class filter
 */
add_action( 'msm_filter_menu_item_css_class', 'msm_integration_filter_menu_item_css_class', 10 );
function msm_integration_filter_menu_item_css_class( $classes ) {

	if ( msm_in_mobile_menu() ) {
		foreach ($classes as $key => $class) {
			if ($class == 'msm-click' || $class == 'msm-hover') {
				unset($classes[$key]);
			}
		}
		$classes[] = 'msm-mobile menu-item-has-children';
	}

	return $classes;
}

/**
 * Filtering the output of the mobile menu item wrappers.
 * The Fox theme removes the third $args argument from nav_menu_css_class filter
 */
add_filter( 'msm_filter_submenu_before', 'msm_integration_filter_submenu_before', 11, 2 );
add_filter( 'msm_filter_submenu_after', 'msm_integration_filter_submenu_after', 11, 2 );

function msm_integration_filter_submenu_before($before, $menu_location) {
	if ( msm_in_mobile_menu() ) {
		return '<ul class="sub-menu"><li>';
	}
	return $before;
}

function msm_integration_filter_submenu_after($after, $menu_location) {
	if ( msm_in_mobile_menu() ) {
		return '</li></ul>';
	}
	return $after;
}