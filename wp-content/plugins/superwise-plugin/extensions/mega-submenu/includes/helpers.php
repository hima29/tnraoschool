<?php

/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_is_plugin_activating' ) ) {

	function msm_is_plugin_activating( $plugin ) {
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'activate' && isset( $_GET['plugin'] ) ) {
			if ( $_GET['plugin'] == $plugin ) {
				return true;
			}
		}

		return false;
	}
}

/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_get_option' ) ) {

	function msm_get_option( $option_name, $default = false ) {
		$options = isset( $GLOBALS[ MSM_OPTION_NAME ] ) ? $GLOBALS[ MSM_OPTION_NAME ] : false;

		$res = $default;

		if ( $options && is_string( $option_name ) && isset( $options[ $option_name ] ) ) {
			$res = $options[ $option_name ];
		}

		return apply_filters( 'msm_option_filer', $res, $option_name );
	}
}

/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_add_compiled_style' ) ) {

	function msm_add_compiled_style() {
		$upload_dir = wp_upload_dir();

		$opt_name = MSM_OPTION_NAME;

		if ( file_exists( $upload_dir['basedir'] . '/' . $opt_name . '_style.css' ) ) {
			$upload_url = $upload_dir['baseurl'];
			if ( strpos( $upload_url, 'https' ) !== false ) {
				$upload_url = str_replace( 'https:', '', $upload_url );
			} else {
				$upload_url = str_replace( 'http:', '', $upload_url );
			}
			wp_enqueue_style( $opt_name . '_style', $upload_url . '/' . $opt_name . '_style.css', false );
		} else {
			$default_file = MSM_PLUGIN_URL . '/public/css/msm_options_style.css';
			if ( file_exists( $default_file ) ) {
				wp_enqueue_style( $opt_name . '_style', $default_file, false );
			}
		}
	}
}

/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_get_rwmb_meta' ) ) {

	function msm_get_rwmb_meta( $key, $post_id, $options = array() ) {
		$prefix = 'msm_';
		$value  = false;

		if ( function_exists( 'rwmb_meta' ) ) {
			$value = rwmb_meta( $prefix . $key, $options, $post_id );
		}

		return $value;
	}
}

/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_sanitize_size' ) ) {

	function msm_sanitize_size( $value, $default = 'px' ) {

		return preg_match( '/(px|em|rem|\%|pt|cm)$/', $value ) ? $value : ( (int) $value ) . $default;
	}
}


/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_mobile_menu_render_start' ) ) {
	function msm_mobile_menu_render_start() {
		Mega_Submenu::$in_mobile_nav = true;
	}
}

/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_mobile_menu_render_end' ) ) {
	function msm_mobile_menu_render_end() {
		Mega_Submenu::$in_mobile_nav = false;
	}
}

/**
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_in_mobile_menu' ) ) {
	function msm_in_mobile_menu() {
		return Mega_Submenu::$in_mobile_nav;
	}
}

/**
 * @since 1.1.0
 */
if ( ! function_exists( 'msm_mobile_wp_nav_menu' ) ) {
	function msm_mobile_wp_nav_menu( $args ) {

		$original_echo = true;
		if ( isset( $args['echo'] ) && $args['echo'] == false ) {
			$original_echo = false;
		}
		msm_mobile_menu_render_start();
		$args['echo'] = false;
		$menu         = wp_nav_menu( $args );
		msm_mobile_menu_render_end();
		if ( $original_echo ) {
			echo $menu;
		} else {
			return $menu;
		}
	}
}

/**
 * Get filtered primary menu location
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'msm_get_menu_location_primary' ) ) {
	function msm_get_menu_location_primary() {
		return apply_filters( Mega_Submenu::FILTER_MENU_LOCATION, msm_get_option( 'menu-location', Mega_Submenu::NAVIGATION_PRIMARY ) );
	}
}

/**
 * Get filtered custom theme mobile menu location
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'msm_get_menu_location_theme_mobile' ) ) {
	function msm_get_menu_location_theme_mobile() {
		return apply_filters( Mega_Submenu::FILTER_MENU_LOCATION_THEME_MOBILE, msm_get_option( 'theme-mobile-menu-location', false ) );
	}
}
