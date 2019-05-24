<?php
/**
 * Utility functions
 */
function superwise_add_filters( $tags, $function ) {
	foreach ( $tags as $tag ) {
		add_filter( $tag, $function );
	}
}

function superwise_is_element_empty( $element ) {
	$element = trim( $element );

	return empty( $element ) ? true : false;
}

function superwise_get_thumbnail( $args ) {

	$defaults = array(
		'thumbnail' => 'thumbnail',
		'post_id'   => null,
		'link'      => false,
		'echo'      => true,
		'format'    => '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['post_id'] ) {
		$post_id = $args['post_id'];
	} else {
		global $post_id;
	}

	$img_url = '';
	if ( has_post_thumbnail( $post_id ) ) {
		$img_url = get_the_post_thumbnail( $post_id, $args['thumbnail'], array(
			'class' => $args['thumbnail']
		) );
	}

	if ( '' != $img_url && $args['format'] === 'array' ) {

		/* Set up a default empty array. */
		$out = array();

		/* Get the image attributes. */
		$atts = wp_kses_hair( $img_url, array( 'http', 'https' ) );

		/* Loop through the image attributes and add them in key/value pairs for the return array. */
		foreach ( $atts as $att ) {
			$out[ $att['name'] ] = $att['value'];
		}

		/* Return the array of attributes. */

		return $out;
	}


	$out = '';
	if ( '' != $img_url ) {
		if ( $args['link'] ) {
			$out = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $img_url . '</a>';
		} else {
			$out = $img_url;
		}
	}
	if ( $args['echo'] ) {
		echo $out;
	} else {
		return $out;
	}
}

function superwise_pagination( $pages = '', $range = 2 ) {
	$showitems = ( $range * 2 ) + 1;

	global $paged;
	if ( empty( $paged ) ) {
		$paged = 1;
	}

	if ( $pages == '' ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}

	if ( 1 != $pages ) {
		echo "<div class='pagination'>";
		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
			echo "<a href='" . get_pagenum_link( 1 ) . "'>&laquo;</a>";
		}
		if ( $paged > 1 && $showitems < $pages ) {
			echo "<a href='" . get_pagenum_link( $paged - 1 ) . "'>&lsaquo;</a>";
		}

		for ( $i = 1; $i <= $pages; $i ++ ) {
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				echo ( $paged == $i ) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link( $i ) . "' class='inactive' >" . $i . "</a>";
			}
		}

		if ( $paged < $pages && $showitems < $pages ) {
			echo "<a href='" . get_pagenum_link( $paged + 1 ) . "'>&rsaquo;</a>";
		}
		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
			echo "<a href='" . get_pagenum_link( $pages ) . "'>&raquo;</a>";
		}
		echo "</div>\n";
	}
}

function superwise_grid_class_map() {

	return array(
		array( 'one twelfth', 'eleven twelfths' ), // 1/11
		array( 'one sixth', 'five sixths' ),     // 2/10
		array( 'one fourth', 'three fourths' ),   // 3/9
		array( 'one third', 'two thirds' ),      // 4/8
		array( 'five twelfths', 'seven twelfths' ),  // 5/7
		array( 'one half', 'one half' ),        // 6/6
		array( 'seven twelfths', 'five twelfths' ),   // 7/5
		array( 'two thirds', 'one third' ),       // 8/4
		array( 'three fourths', 'one fourth' ),      // 9/3
		array( 'five sixths', 'one sixth' ),       // 10/2
		array( 'eleven twelfths', 'one twelfth' ),     // 11/1
		array( 'one whole', 'one whole' ),       // 12/12
	);
}

function superwise_get_grid_class( $index, $invert = false ) {
	$grid = superwise_grid_class_map();

	return isset( $grid[ $index ] ) ? $grid[ $index ][ $invert ? 1 : 0 ] : '';
}

function superwise_get_option( $option_name, $default = false ) {
	$options = isset( $GLOBALS[ SUPERWISE_THEME_OPTION_NAME ] ) ? $GLOBALS[ SUPERWISE_THEME_OPTION_NAME ] : false;

	if ( $options && is_string( $option_name ) ) {
		return isset( $options[ $option_name ] ) ? $options[ $option_name ] : $default;
	}

	return $default;
}

function superwise_get_page_template() {

	$post_id = null;
	if ( isset( $_GET['post'] ) ) {
		$post_id = $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = $_POST['post_ID'];
	} else {
		global $post;
		$post_id = $post->ID;
	}

	if ( $post_id ) {
		return get_post_meta( $post_id, '_wp_page_template', true );
	}

}

function superwise_is_page_template( $template_file ) {
	return superwise_get_page_template() == $template_file;
}

function superwise_custom_css() {
	$custom_css = superwise_get_option( 'custom-css' );

	// Get custom page title bg
	$custom_page_title_bg_image_url = superwise_get_rwmb_meta_image_url('custom_page_title_background');
	if ( $custom_page_title_bg_image_url ) {
		$custom_css .= ".wh-page-title-bar{background-image:url({$custom_page_title_bg_image_url})}";
	}

	if ( ! superwise_is_element_empty( $custom_css ) ) {
		return $custom_css;
	}
}

function superwise_custom_js_code() {
	$customJsCode = superwise_get_option( 'custom-js-code', false );
	if ( $customJsCode ) {
		echo '<script id="wh-custom-js-code">' . "\n" . $customJsCode . "\n</script>\n";
	}
}

function superwise_responsive_menu_scripts() {

	$css = '';

	$respmenu_show_start = (int) superwise_get_option( 'header-mobile-break-point', 767 );

	if ( $respmenu_show_start ) {

		$css .= '.header-mobile {display: none;}';
		$css .= '@media screen and (max-width:' . intval( $respmenu_show_start ) . 'px) {';
		$css .= '.header-left {padding-left: 0;}';
		$css .= '.wh-header {display: none;}';
		$css .= '.header-mobile {display: block;}';
		$css .= '}';
	}
	return $css;

}

function superwise_filter_array( $filter_name, $default = array() ) {

	$filtered = apply_filters( $filter_name, $default );

	if ( ! is_array( $filtered ) || ! count( $filtered ) ) {
		$filtered = $default;
	}

	return array_unique( $filtered );
}

function superwise_array_val_concat( $array = null, $postfix = '', $default ) {

	if ( is_array( $array ) ) {

		$res = array();

		foreach ( $array as $val ) {
			$res[] = $val . $postfix;
		}

		return $res;
	}

	return $default;
}

function superwise_get_rwmb_meta( $key, $post_id, $options = array() ) {
	$prefix = 'superwise_';
	$value  = false;

	if ( function_exists( 'rwmb_meta' ) ) {
		$value = rwmb_meta( $prefix . $key, $options, $post_id );
	}

	return $value;
}

function superwise_get_rwmb_meta_image_url( $key, $post_id = false ) {
	if ( ! $key ) {
		return '';
	}

	if (!$post_id) {

		global $post;
		if ($post) {
			$post_id = $post->ID;
		}
	}

	if (!$post_id) {
		return '';
	}


	$image_url = '';

	$image = superwise_get_rwmb_meta( $key, $post_id, array( 'type' => 'image' ) );
	if ( is_array( $image ) && count( $image ) ) {
		$image = reset( $image );    // get first element
		$image_url = isset( $image['full_url'] ) ? $image['full_url'] : '';
	}

	return $image_url;
}

if ( ! function_exists( 'superwise_get_logo_url' ) ) {

	function superwise_get_logo_url() {
		$logo_url = '';

		// Get custom page logo
		$logo_url = superwise_get_rwmb_meta_image_url('custom_logo');
		if ( $logo_url ) {
			return $logo_url;
		}

		// Get default logo
		$logo     = superwise_get_option( 'logo', array() );
		$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';


		return $logo_url;
	}
}

function superwise_strip_comments( $string ) {

	$regex = array(
		"`^([\t\s]+)`ism"                       => '',
		"`^\/\*(.+?)\*\/`ism"                   => "",
		"`([\n\A;]+)\/\*(.+?)\*\/`ism"          => "$1",
		"`//(.+?)[\n\r]`ism"                    => "",
		"`([\n\A;\s]+)//(.+?)[\n\r]`ism"        => "$1\n",
		"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism" => "\n"
	);

	return preg_replace( array_keys( $regex ), $regex, $string );
}

function superwise_get_layout_block_id( $key ) {
	global $post;
	$layout_block_id = null;

	if ( $post ) {
		$layout_block_id = superwise_get_rwmb_meta( str_replace( '-', '_', $key ), $post->ID );
	}
	if ( ! $layout_block_id ) {

		$layout_block_id = superwise_get_option( $key, false );

		// WPML
		if ( $layout_block_id && defined( 'ICL_LANGUAGE_CODE' ) ) {

			$t_post_id = icl_object_id($layout_block_id, 'layout_block', true, ICL_LANGUAGE_CODE);

			if ( $t_post_id ) {
				return $t_post_id;
			}
		}
	}

	return $layout_block_id;
}


function superwise_get_layout_block( $key ) {

	$layout_block_id = superwise_get_layout_block_id( $key );
	if ( $layout_block_id ) {
		$layout_block = get_post( $layout_block_id );

		return $layout_block;
	}
}

function superwise_get_layout_block_content( $key ) {
	$layout_block = superwise_get_layout_block( $key );
	$content      = false;
	if ( $layout_block ) {

		$content = $layout_block->post_content;

		$search  = array(
			'vc_basic_grid',
			'vc_media_grid',
			'vc_masonry_grid',
			'vc_masonry_media_grid'
		);
		$replace = array();
		foreach ( $search as $val ) {
			$replace[] = $val . ' page_id="' . $layout_block->ID . '"';
		}

		$content = str_replace( $search, $replace, $content );
	}

	return $content;

}

function superwise_get_child_pages() {

	global $post;

	$args = array(
		'child_of'    => $post->ID,
		'sort_column' => 'menu_order',
	);

	$pages = get_pages( $args );

	return count( $pages );

}

function superwise_get_top_ancestor_id() {

	global $post;

	if ( $post->post_parent ) {
		$ancestors = array_reverse( get_post_ancestors( $post->ID ) );

		return $ancestors[0];

	}

	return $post->ID;

}

function superwise_page_title_enabled() {

	if ( is_home() ) {
		return false;
	}
	return is_single() ? superwise_get_option( 'archive-single-use-page-title', true ) : true;
	}
