<?php 

if ( (int) $show_ratings  && function_exists( 'skilled_course_print_stars') ) {
	// product id
	$wc_post_id = get_post_meta( intval( $post_id ), '_course_woocommerce_product', true );
	skilled_course_print_stars($wc_post_id, true);
}
