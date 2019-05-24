<?php
add_filter( 'excerpt_length', 'superwise_excerpt_length' );
add_filter( 'excerpt_more', 'superwise_excerpt_more' );
add_filter( 'request', 'superwise_request_filter' );
add_filter( 'get_search_form', 'superwise_get_search_form' );

/**
 * Clean up the_excerpt()
 */

function superwise_excerpt_length( $length ) {
	$post_excerpt_length = superwise_get_option('post-excerpt-length', POST_EXCERPT_LENGTH);
	return $post_excerpt_length;
}

function superwise_excerpt_more( $more ) {
	return '&nbsp;<a href="' . get_permalink() . '">&hellip;</a>';
}

/**
 * Fix for empty search queries redirecting to home page
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function superwise_request_filter( $query_vars ) {
	if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) && ! is_admin() ) {
		$query_vars['s'] = ' ';
	}

	return $query_vars;
}

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
function superwise_get_search_form( $form ) {
	$form = '';
	include_once get_template_directory() . '/templates/searchform.php';

	return $form;
}
