<?php

/**
 * Total theme is disabling
 */
add_filter( 'msm_integration_vc_templates', 'msm_register_vc_templates' );
add_action( 'vc_load_default_templates_action', 'msm_integration_vc_default_templates' );

function msm_integration_vc_default_templates() {

	$vc_templates = apply_filters( 'msm_integration_vc_templates', array() );

	foreach ( $vc_templates as $vc_template ) {
		vc_add_default_templates( $vc_template );
	}
}


/**
 * Filtering css classes of mobile menu items
 */
add_action( 'msm_filter_menu_item_css_class', 'msm_integration_filter_menu_item_css_class', 10 );
function msm_integration_filter_menu_item_css_class( $classes ) {

	if ( msm_in_mobile_menu() ) {
		foreach ( $classes as $key => $class ) {
			if ( $class == 'msm-click' || $class == 'msm-hover' ) {
				unset( $classes[ $key ] );
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

function msm_integration_filter_submenu_before( $before, $menu_location ) {
	if ( msm_in_mobile_menu() ) {
		return '<ul class="sub-menu"><li>';
	}

	return $before;
}

function msm_integration_filter_submenu_after( $after, $menu_location ) {
	if ( msm_in_mobile_menu() ) {
		return '</li></ul>';
	}

	return $after;
}