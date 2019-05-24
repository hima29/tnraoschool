<?php

class MSM_VC {

	public static function print_vc_css( $menu_location ) {

		$meta_key_id     = Mega_Submenu::META_ID;
		$theme_locations = get_nav_menu_locations();

		if ( isset( $theme_locations[ $menu_location ] ) ) {

			$menu_obj = get_term( $theme_locations[ $menu_location ], 'nav_menu' );

			$meta_query = array(
				array(
					'key'     => $meta_key_id,
					'value'   => '0',
					'compare' => '>',
				),
			);

			if ( $menu_obj && property_exists( $menu_obj, 'slug' ) ) {

				$main_menu_items = wp_get_nav_menu_items( $menu_obj->slug, array( 'meta_query' => $meta_query ) );

				$mega_menu_ids = array();
				foreach ( $main_menu_items as $menu_item ) {

					$mega_menu_ids[] = get_post_meta( $menu_item->ID, $meta_key_id, true );
				}

				foreach ( $mega_menu_ids as $mega_menu_id ) {

					echo msm_get_vc_post_custom_css( $mega_menu_id );
					echo msm_get_vc_shortcodes_custom_css( $mega_menu_id );
				}
			}
		}

	}


}