<?php
if ( ! function_exists( 'msm_elementor_print_menu' ) ) {
	function msm_elementor_print_menu( $menu_id ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $menu_id );
		}
	}
}