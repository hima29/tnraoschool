<?php
/**
 * @package WordPress
 * @subpackage Wheels
 *
 * Template Name: Full Width - No Title
 */
get_header();
?>
<div class="<?php echo superwise_class( 'main-wrapper' ) ?>">
	<div class="<?php echo superwise_class( 'container' ) ?>">
		<div class="<?php echo superwise_class( 'content-fullwidth' ) ?>">
			<?php get_template_part( 'templates/content-page' ); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
