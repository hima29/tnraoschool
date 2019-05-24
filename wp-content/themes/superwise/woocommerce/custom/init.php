<?php
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 22 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 21 );

add_action( 'woocommerce_share', 'superwise_social_share' );


add_filter( 'loop_shop_columns', 'superwise_loop_shop_columns' );
add_filter( 'woocommerce_related_products_columns', 'superwise_loop_shop_columns' );
add_filter( 'wp_nav_menu_items', 'superwise_wcmenucart', 10, 2 );

// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );


function superwise_is_shop() {
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return true;
	}

	return false;
}

function superwise_get_shop_page_id() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		return wc_get_page_id( 'shop' );
	}

	return 0;
}


function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	superwise_wc_print_mini_cart();

	$fragments['.wh-minicart'] = ob_get_clean();

	return $fragments;
}


function superwise_loop_shop_columns() {

	return 3;
}


/**
 * Place a cart icon with number of items and total cost in the menu bar.
 *
 * Source: http://wordpress.org/plugins/woocommerce-menu-bar-cart/
 */
function superwise_wcmenucart( $menu, $args ) {

	// Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
	     || !in_array($args->theme_location, array('primary_navigation', 'mobile_navigation'))
	) {
		return $menu;
	}

	ob_start();
	global $woocommerce;
	$viewing_cart        = esc_html__( 'View your shopping cart', 'superwise' );
	$start_shopping      = esc_html__( 'Start shopping', 'superwise' );
	$shop_page_url       = get_permalink( wc_get_page_id( 'shop' ) );
	$cart_contents_count = $woocommerce->cart->cart_contents_count;
//	$cart_contents = sprintf(_n('%d item', '%d items', $cart_contents_count, 'superwise'), $cart_contents_count);
	$cart_contents = sprintf( _n( '%d', '%d', $cart_contents_count, 'superwise' ), $cart_contents_count );
	$cart_total    = $woocommerce->cart->get_cart_total();
	$menu_item     = '';
	// Uncomment the line below to hide nav menu cart item when there are no items in the cart
	if ( $cart_contents_count > 0 ) {
		if ( $cart_contents_count == 0 ) {
			$menu_item = '<li class="menu-item"><a class="wcmenucart-contents" href="' . $shop_page_url . '" title="' . $start_shopping . '">';
		} else {
			$menu_item = '<li class="menu-item"><a class="wcmenucart-contents" href="' . wc_get_cart_url() . '" title="' . $viewing_cart . '">';
		}

		$menu_item .= '<i class="fa fa-shopping-cart"></i> ';

		$menu_item .= $cart_contents . ' - ' . $cart_total;
		$menu_item .= '</a></li>';
		// Uncomment the line below to hide nav menu cart item when there are no items in the cart
	}
	echo '' . $menu_item;
	$social = ob_get_clean();

	return $menu . $social;

}

/* Custom Shoping Cart in the top */
function superwise_wc_print_mini_cart() {

	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	$count = sizeof( WC()->cart->get_cart() );

	?>

	<div class="wh-minicart">
		<i class="icon-square-hand-bag"></i>
		<span class="count"><?php echo esc_html( $count ); ?></span>

		<div id="wh-minicart-top">
			<?php woocommerce_mini_cart(); ?>
		</div>
	</div>
<?php
}
