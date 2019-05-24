<?php
/**
 * @package WordPress
 * @subpackage Wheels
 *
 * Template Name: Sidebar - Right with Child Pages
 */
get_header();
?>
<?php get_template_part( 'templates/title' ); ?>
<div class="<?php echo superwise_class( 'main-wrapper' ) ?>">
	<div class="<?php echo superwise_class( 'container' ) ?>">
		<div class="<?php echo superwise_class( 'content' ) ?>">
			<div class="child-pages-mobile-wrap">
				<?php get_template_part( 'templates/child-pages-sidebar' ); ?>
			</div>
			<?php get_template_part( 'templates/content-page' ); ?>
		</div>
		<div class="<?php echo superwise_class( 'sidebar' ) ?>">
			<?php get_template_part( 'templates/child-pages-sidebar' ); ?>
			<?php get_sidebar( 'child-pages' ); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
