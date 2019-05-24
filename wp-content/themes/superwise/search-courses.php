<?php
/**
 * @package WordPress
 * @subpackage Wheels
 */
get_header();

global $paged, $woothemes_sensei;
if ( empty( $paged ) ) {
	$paged = 1;
}

$per_page = superwise_get_option( 'search-page-items-per-page', 10 );
$offset   = $per_page * ( $paged - 1 );

$search_args = array(
	'post_type'      => 'agc_course',
	'posts_per_page' => $per_page,
	'offset'         => $offset,
);

if ( isset( $_GET['s'] ) && $_GET['s'] ) {
	$search_args['s'] = $_GET['s'];
}


if ( isset( $_GET['course-category'] ) && $_GET['course-category'] ) {
	$search_args['tax_query'] = array(
		array(
			'taxonomy' => 'agc_course_category',
			'field'    => 'ID',
			'terms'    => $_GET['course-category']
		),
	);
}

if ( isset( $_GET['status'] ) ) {

	$meta_key   = null;
	$meta_query = null;
	$compare    = 'IN';

	if ( $_GET['status'] == 'paid' ) {
		$meta_key = '_regular_price';

		$meta_query = array(
			array(
				'key'     => $meta_key,
				'value'   => '0',
				'compare' => '>',
			),
		);

	} elseif ( $_GET['status'] == 'free' ) {
		$meta_key = '_regular_price';

		// look for paid
		$meta_query = array(
			array(
				'key'     => $meta_key,
				'value'   => '0',
				'compare' => '>',
			),
		);

		// but reverse compare
		$compare = 'NOT IN';

	}

	if ( $meta_key && $meta_query ) {

		$args = array(
			'numberposts' => - 1,
			'post_type'   => 'product',
			'fields'      => 'ids',
			'meta_key'    => $meta_key,
			'meta_query'  => $meta_query,
		);
		// get product ids
		$products = new WP_Query( $args );

		// fill product ids
		$product_ids = array();
		if ( $products->have_posts() ) {
			foreach ( $products->posts as $id ) {
				$product_ids[] = $id;
			}
		}

		if ( count( $product_ids ) ) {
			$search_args['meta_key']   = '_course_woocommerce_product';
			$search_args['meta_query'] = array(
				array(
					'key'     => '_course_woocommerce_product',
					'value'   => $product_ids,
					'compare' => $compare,
				),
			);
		} else {
			// if no products are found no courses should be found
			// except if status free
			if ( $_GET['status'] != 'free' ) {
				$search_args = null;
			}
		}
	}
}


//New results loop
$results = new WP_Query( $search_args );

// in order to get all results
$search_args['posts_per_page'] = -1;
unset( $search_args['offset'] );

// just for count
$all_results = new WP_Query( $search_args );

$pages           = ceil( $all_results->post_count / $per_page );
$use_sidebar     = superwise_get_option( 'search-page-use-sidebar', false );
$class_namespace = $use_sidebar ? 'content' : 'content-fullwidth';
?>
<?php get_template_part( 'templates/title' ); ?>
<div class="<?php echo superwise_class( 'main-wrapper' ); ?>">
	<div class="<?php echo superwise_class( 'container' ); ?>">
		<div class="<?php echo superwise_class( $class_namespace ); ?> course-container">

			<div class="search-course-page-search-form-wrap">
				<?php get_template_part( 'templates/searchform-courses-big' ); ?>
			</div>
			<?php if ( $results->have_posts() ): ?>
				<?php while ( $results->have_posts() ) : $results->the_post(); ?>
					<?php get_template_part( 'templates/content', 'course' ); ?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php get_template_part( 'templates/search', 'none' ); ?>
			<?php endif; ?>
			<div class="<?php echo superwise_class( 'pagination' ); ?>">
				<?php superwise_pagination( $pages ); ?>
			</div>
		</div>
		<?php if ( $use_sidebar ): ?>
			<div class="<?php echo superwise_class( 'sidebar' ); ?>">
				<?php get_sidebar(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
