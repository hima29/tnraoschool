<?php
/**
 * Custom functions
 */
add_filter( 'post_class', 'superwise_oddeven_post_class' );
add_filter( 'body_class', 'superwise_filter_body_class' );
add_filter( 'msm_filter_menu_location', 'superwise_msm_filter_menu_location' );
add_filter( 'msm_filter_load_compiled_style', 'superwise_msm_filter_load_compiled_style' );
add_filter( 'breadcrumb_trail_labels', 'superwise_breadcrumb_trail_labels' );

add_filter( 'template_redirect', 'superwise_search_template', 20 );


function superwise_add_layout_blocks_css() {

	$css = '';

	$header_layout_block_id = superwise_get_layout_block_id( 'header-layout-block' );
	$css .= superwise_get_vc_page_custom_css( $header_layout_block_id );
	$css .= superwise_get_vc_shortcodes_custom_css( $header_layout_block_id );

	$mobile_header_layout_block_id = superwise_get_layout_block_id( 'header-layout-block-mobile' );
	$css .= superwise_get_vc_page_custom_css( $mobile_header_layout_block_id );
	$css .= superwise_get_vc_shortcodes_custom_css( $mobile_header_layout_block_id );

	$footer_layout_block_id = superwise_get_layout_block_id( 'footer-layout-block' );
	$css .= superwise_get_vc_page_custom_css( $footer_layout_block_id );
	$css .= superwise_get_vc_shortcodes_custom_css( $footer_layout_block_id );

	$quick_sidebar_layout_block_id = superwise_get_layout_block_id( 'quick-sidebar-layout-block' );
	$css .= superwise_get_vc_page_custom_css( $quick_sidebar_layout_block_id );
	$css .= superwise_get_vc_shortcodes_custom_css( $quick_sidebar_layout_block_id );

	return $css;
}


function superwise_filter_body_class( $body_classes ) {

	$body_classes[] = 'header-' . superwise_get_option( 'header-location', 'top' );

	if (superwise_page_title_enabled()) {
		$body_classes[] = 'page-title-enabled';
	}

	return $body_classes;
}

function superwise_msm_filter_menu_location( $menu_location ) {
	global $post_id;
	$use_custom_menu_location = superwise_get_rwmb_meta( 'use_custom_menu', $post_id );
	if ( $use_custom_menu_location ) {
		$custom_menu_location = superwise_get_rwmb_meta( 'custom_menu_location', $post_id );
		if ( ! empty( $custom_menu_location ) ) {
			return $custom_menu_location;
		}
	}

	return $menu_location;
}

function superwise_msm_filter_load_compiled_style() {
	return false;
}

function superwise_get_vc_page_custom_css( $id ) {

	$out = '';
	if ( $id ) {
		$post_custom_css = get_post_meta( $id, '_wpb_post_custom_css', true );
		if ( ! empty( $post_custom_css ) ) {
			$post_custom_css = strip_tags( $post_custom_css );
			// $out .= '<style type="text/css" data-type="vc_custom-css">';
			$out .= $post_custom_css;
			// $out .= '</style>';
		}
	}

	return $out;
}

function superwise_get_vc_shortcodes_custom_css( $id ) {

	$out = '';
	if ( $id ) {
		$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $shortcodes_custom_css ) ) {
			$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
			// $out .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			$out .= $shortcodes_custom_css;
			// $out .= '</style>';
		}
	}

	return $out;
}

function superwise_register_custom_thumbnail_sizes() {
	$string = superwise_get_option( 'custom-thumbnail-sizes' );

	if ( $string ) {

		$pattern     = '/[^a-zA-Z0-9\-\|\:]/';
		$replacement = '';
		$string      = preg_replace( $pattern, $replacement, $string );

		$resArr = explode( '|', $string );
		$thumbs = array();

		foreach ( $resArr as $thumbString ) {
			if ( ! empty( $thumbString ) ) {
				$parts               = explode( ':', trim( $thumbString ) );
				$thumbs[ $parts[0] ] = explode( 'x', $parts[1] );
			}
		}

		foreach ( $thumbs as $name => $sizes ) {
			add_image_size( $name, (int) $sizes[0], (int) $sizes[1], true );
		}
	}
}

if ( ! function_exists( 'superwise_entry_meta' ) ) {

	/**
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * @return void
	 */
	function superwise_entry_meta() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="featured-post">' . esc_html__( 'Sticky', 'superwise' ) . '</span>';
		}

		if ( ! has_post_format( 'link' ) && 'post' == get_post_type() ) {
			superwise_entry_date();
		}

		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list( esc_html__( ', ', 'superwise' ) );
		if ( $categories_list ) {
			echo '<span class="categories-links"><i class="fa fa-folder"></i>' . $categories_list . '</span>';
		}

		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', esc_html__( ', ', 'superwise' ) );
		if ( $tag_list ) {
			echo '<span class="tags-links"><i class="fa fa-tag"></i> ' . $tag_list . '</span>';
		}

		// Post author
		if ( 'post' == get_post_type() ) {
			global $post;
			$author_display_name = get_the_author_meta( 'display_name', $post->post_author );
			
			printf( '<span class="author vcard"><i class="fa fa-user"></i> %1$s <a class="url fn n" href="%2$s" title="%3$s" rel="author">%4$s</a></span>', esc_html__( 'by', 'superwise' ), esc_url( get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) ) ),
				esc_attr( sprintf( esc_html__( 'View all posts by %s', 'superwise' ), $author_display_name ) ), $author_display_name );

			$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

			if ( $num_comments == 0 ) {

			} else {

				if ( $num_comments > 1 ) {
					$comments = $num_comments . esc_html__( ' Comments', 'superwise' );
				} else {
					$comments = esc_html__( '1 Comment', 'superwise' );
				}
				echo '<span class="comments-count"><i class="fa fa-comment"></i><a href="' . get_comments_link() . '">' . $comments . '</a></span>';
			}

		}


	}
}

if ( ! function_exists( 'superwise_entry_date' ) ) {

	/**
	 * Prints HTML with date information for current post.
	 *
	 * @param boolean $echo Whether to echo the date. Default true.
	 *
	 * @return string The HTML-formatted post date.
	 */
	function superwise_entry_date( $echo = true ) {
		if ( has_post_format( array( 'chat', 'status' ) ) ) {
			$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'superwise' );
		} else {
			$format_prefix = '%2$s';
		}

		$date = sprintf( '<span class="date"><i class="fa fa-calendar"></i><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', esc_url( get_permalink() ),
			esc_attr( sprintf( esc_html__( 'Permalink to %s', 'superwise' ), the_title_attribute( 'echo=0' ) ) ), esc_attr( get_the_date( 'c' ) ), esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) ) );

		if ( $echo ) {
			echo $date;
		}

		return $date;
	}

}


function superwise_add_editor_style() {
	add_editor_style( 'editor-style.css' );
}

function superwise_get_post_bg_img() {

	$image     = superwise_get_option( 'page-title-background-image', array() );
	$image_url = isset( $image['url'] ) && $image['url'] ? $image['url'] : '';

	if ( $image_url ) {

		echo 'style="min-height: 200px;background:transparent;" data-parallax="scroll" data-image-src="' . $image_url . '"';
	}
}

function superwise_oddeven_post_class( $classes ) {
	global $superwise_current_class;
	$classes[]     = $superwise_current_class;
	$superwise_current_class = ( $superwise_current_class == 'odd' ) ? 'even' : 'odd';

	return $classes;
}

global $superwise_current_class;
$superwise_current_class = 'odd';

function superwise_social_share() {
	?>
	<div class="share-this">
		<!-- http://simplesharingbuttons.com/ -->
		<ul class="share-buttons">
			<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( site_url() ); ?>&t="
			       target="_blank" title="Share on Facebook"
			       onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><i
						class="fa fa-facebook"></i></a></li>
			<li>
				<a href="https://twitter.com/intent/tweet?source=<?php echo urlencode( site_url() ); ?>&text=:%20<?php echo urlencode( site_url() ); ?>"
				   target="_blank" title="Tweet"
				   onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20' + encodeURIComponent(document.URL)); return false;"><i
						class="fa fa-twitter"></i></a></li>
			<li><a href="https://plus.google.com/share?url=<?php echo urlencode( site_url() ); ?>"
			       target="_blank" title="Share on Google+"
			       onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><i
						class="fa fa-google-plus"></i></a></li>
			<li>
				<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( site_url() ); ?>&description="
				   target="_blank" title="Pin it"
				   onclick="window.open('http://pinterest.com/pin/create/button/?url=' + encodeURIComponent(document.URL) + '&description=' +  encodeURIComponent(document.title)); return false;"><i
						class="fa fa-pinterest"></i></a></li>
			<li>
				<a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( site_url() ); ?>&title=&summary=&source=<?php echo urlencode( site_url() ); ?>"
				   target="_blank" title="Share on LinkedIn"
				   onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><i
						class="fa fa-linkedin"></i></a></li>
		</ul>
	</div>

<?php
}

function superwise_breadcrumb_trail_labels($labels) {


	return wp_parse_args( array(
		'browse'              => esc_html__( 'Browse:',                               'superwise' ),
		'aria_label'          => esc_attr_x( 'Breadcrumbs', 'breadcrumbs aria label', 'superwise' ),
		'home'                => esc_html__( 'Home',                                  'superwise' ),
		'error_404'           => esc_html__( '404 Not Found',                         'superwise' ),
		'archives'            => esc_html__( 'Archives',                              'superwise' ),
		// Translators: %s is the search query. The HTML entities are opening and closing curly quotes.
		'search'              => esc_html__( 'Search results for &#8220;%s&#8221;',   'superwise' ),
		// Translators: %s is the page number.
		'paged'               => esc_html__( 'Page %s',                               'superwise' ),
		// Translators: Minute archive title. %s is the minute time format.
		'archive_minute'      => esc_html__( 'Minute %s',                             'superwise' ),
		// Translators: Weekly archive title. %s is the week date format.
		'archive_week'        => esc_html__( 'Week %s',                               'superwise' ),
	), $labels );

}

function superwise_is_search_courses() {
	return isset( $_GET['s'] ) && isset( $_GET['search-type'] ) && $_GET['search-type'] == 'courses';
}

function superwise_search_template() {
	if ( superwise_is_search_courses() ) {

		include_once get_template_directory() . '/search-courses.php';
		exit;
	}
}
