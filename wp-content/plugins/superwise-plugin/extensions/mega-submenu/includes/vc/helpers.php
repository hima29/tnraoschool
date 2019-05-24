<?php

/**
 * Visual Composer post CSS
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_get_vc_post_custom_css' ) ) {
	function msm_get_vc_post_custom_css( $id ) {

		$out = '';
		if ( $id ) {
			$post_custom_css = get_post_meta( $id, '_wpb_post_custom_css', true );
			if ( ! empty( $post_custom_css ) ) {
				$out .= '<style type="text/css" data-type="vc_custom-css">';
				$out .= $post_custom_css;
				$out .= '</style>';
			}
		}

		return $out;
	}
}

/**
 * Visual Composer shortcodes CSS
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_get_vc_shortcodes_custom_css' ) ) {
	function msm_get_vc_shortcodes_custom_css( $id ) {

		$out = '';
		if ( $id ) {
			$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$out .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				$out .= $shortcodes_custom_css;
				$out .= '</style>';
			}
		}

		return $out;
	}
}