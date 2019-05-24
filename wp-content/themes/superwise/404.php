<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Wheels
 */
get_header();
?>
<?php get_template_part( 'templates/title' ); ?>
<div class="<?php echo superwise_class( 'main-wrapper' ); ?>">
	<div class="<?php echo superwise_class( 'container' ); ?>">
		<div class="double-padded">
			<h1 class="entry-title"><?php esc_html_e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'superwise' ); ?></h1>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'superwise' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>

</div>
<?php get_footer(); ?>
