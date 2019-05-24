<?php
add_action( 'init', 'marq_custom_logo_init' );
// add_action( 'customize_save_after', 'marq_customize_save_after' );
add_action( 'redux/customizer/live_preview', 'marq_customizer');

function marq_customizer() {
	$redux = ReduxFrameworkInstances::get_instance( MARQ_THEME_OPTION_NAME );
	$redux->_enqueue_output();
	echo '<style id="ed-school-customizer-css">'.$redux->compilerCSS.'</style>';
}

function marq_set_option( $option_name, $value ) {
	$options = isset( $GLOBALS[ ED_SCHOOL_THEME_OPTION_NAME ] ) ? $GLOBALS[ MARQ_THEME_OPTION_NAME ] : false;

	if ( $options && is_string( $option_name ) ) {
		$options[ $option_name ] = $value;
		update_option( ED_SCHOOL_THEME_OPTION_NAME, $options );
		return true;
	}
	return false;
}

function marq_get_logo_url() {
	$logo_url = '';

	// Get custom page logo
	$logo_url = marq_get_rwmb_meta_image_url( 'custom_logo' );
	if ( $logo_url ) {
		return $logo_url;
	}

	// Default WP Custom Logo implementation
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );

	if ( isset( $image[0] ) ) {
		$logo_url = $image[0];
	}
	return $logo_url;
}


function marq_custom_logo_init() {

	$custom_logo_id = get_theme_mod( 'custom_logo' );

	// do this only the first time when custom_logo does not exist
	if ( $custom_logo_id === false ) {
		$logo     = marq_get_option( 'logo', array() );
		$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';

		$attachment_id = attachment_url_to_postid( $logo_url );

		if ( $attachment_id ) {
			set_theme_mod( 'custom_logo', $attachment_id );
		}
	} 
}

function marq_customize_save_after( $customizer ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	// update_option( '_00_test_test', $customizer->get_setting( 'custom_logo' )->value() );

	$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
	$logo = array( 'url' => $image[0] );

	if ( isset( $image[0] ) && ! empty( $image[0] ) ) {
		marq_set_option( 'logo', $logo );
	}
}

function marq_get_image_id( $image_url ) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
    return (int) $attachment[0]; 
}

function marq_set_custom_logo_from_theme_options( $logo ) {
	$logo_url      = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';
	$attachment_id = attachment_url_to_postid( $logo_url );
	if ( $attachment_id ) {
		set_theme_mod( 'custom_logo', $attachment_id );
	}
}
