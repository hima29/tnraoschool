<?php
add_action( 'wp_enqueue_scripts', 'SCP_Assets::parse_post_content_shortcodes', 1000 );

class SCP_Assets {

	public static function parse_post_content_shortcodes() {
		global $post;
		if ( $post ) {
			self::parse_shortcodes( $post->post_content );
		}
		
		$top_bar_use = scp_get_wheels_option( 'top-bar-use', false );
		if ( $top_bar_use ) {
			$top_bar_text = scp_get_wheels_option( 'top-bar-text', false );
			if ( $top_bar_text ) {
				self::parse_shortcodes( $top_bar_text );
			}
		}

		$top_bar_additional_use = scp_get_wheels_option( 'top-bar-additional-use', false );
		if ( $top_bar_additional_use ) {
			$top_bar_additional_text = scp_get_wheels_option( 'top-bar-additional-text', false );
			if ( $top_bar_additional_text ) {
				self::parse_shortcodes( $top_bar_additional_text );
			}
		}
		// Layout Blocks
		if ( function_exists( 'superwise_get_layout_block_content' ) ) {
			$layout_blocks = array( 
				'header-layout-block', 
				'header-layout-block-mobile', 
				'footer-layout-block', 
				'quick-sidebar-layout-block',
			);
			foreach ( $layout_blocks as $layout_block ) {
				$layout_block_content = superwise_get_layout_block_content( $layout_block );
				if ( $layout_block_content ) {
					self::parse_shortcodes( $layout_block_content );
				}
			}
		}
	}

	public static function parse_shortcodes( $content ) {
		if ( ! $content ) {
			return;
		}
		global $shortcode_tags;
		preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );
		foreach ( $shortcodes[2] as $index => $tag ) {
			$attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
			if ( isset( $shortcode_tags[$tag] ) ) {
				do_action( "scp_load_styles_{$tag}", $attr_array );
			}
		}
		foreach ( $shortcodes[5] as $shortcode_content ) {
			SCP_Assets::parse_shortcodes( $shortcode_content );
		}
	}

	public static function get_uid( $namespace, $atts ) {
		$class = '';
		if ( is_array( $atts ) ) {
			$class = implode('', $atts);
			$class = hash('md5', $class);
		}
		return "{$namespace}-{$class}";
	}
}
