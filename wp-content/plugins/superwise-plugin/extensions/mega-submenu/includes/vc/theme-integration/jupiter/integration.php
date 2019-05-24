<?php

add_filter( 'msm_filter_menu_location', 'msm_integration_filter_menu_location' );
function msm_integration_filter_menu_location( $menu_location ) {
	return 'primary-menu';
}

add_filter( 'msm_filter_submenu_before', 'msm_integration_filter_submenu_before', 11, 2 );
add_filter( 'msm_filter_submenu_after', 'msm_integration_filter_submenu_after', 11, 2 );

function msm_integration_filter_submenu_before($before, $menu_location) {

	if ( msm_in_mobile_menu() ) {
		return '<span class="mk-nav-arrow mk-nav-sub-closed"><svg class="mk-svg-icon" data-name="mk-moon-arrow-down" data-cacheid="icon-58987a0cb9e85" style=" height:16px; width: 16px; " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 192l-96-96-160 160-160-160-96 96 256 255.999z"></path></svg></span><ul class="sub-menu"><li>';
	}
	return $before;
}

function msm_integration_filter_submenu_after($after, $menu_location) {
	if ( msm_in_mobile_menu() ) {
		return '</li></ul>';
	}
	return $after;
}

/**
 * Add arrow in mobile nav
 */
//add_action( 'walker_nav_menu_start_el', 'msm_integration_filter_walker_nav_menu_start_el', 10, 4 );
//function msm_integration_filter_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {
//
//	if ( msm_in_mobile_menu() ) {
//		$mega_menu_id = get_post_meta( $item->ID, Mega_Submenu::META_ID, true );
//		if ( ! empty( $mega_menu_id ) && ( $mega_menu = get_post( $mega_menu_id ) ) && ! is_wp_error( $mega_menu ) ) {
//			if ( property_exists($args, 'link_after') ) {
//				$item_output = str_replace('</a>', $args->link_after . '</a>', $item_output);
//			}
//		}
//	}
//	return $item_output;
//}