<?php
/**
 */
$is_boxed = superwise_get_option('single-post-is-boxed', false);
if ($is_boxed) {
    get_header('boxed');
} else {
    get_header();
}
?>
<?php get_template_part('templates/title'); ?>
<div class="<?php echo superwise_class('main-wrapper') ?>">
	<div class="<?php echo superwise_class('container'); ?>">
		<?php if ( superwise_get_option( 'single-post-sidebar-left', false ) ): ?>
			<div class="<?php echo superwise_class( 'sidebar' ) ?>">
				<?php get_sidebar(); ?>
			</div>
			<div class="<?php echo superwise_class( 'content' ) ?>">
				<?php get_template_part( 'templates/content-single-course' ); ?>
			</div>
		<?php else: ?>
			<div class="<?php echo superwise_class( 'content' ) ?>">
				<?php get_template_part( 'templates/content-single-course' ); ?>
			</div>
			<div class="<?php echo superwise_class( 'sidebar' ) ?>">
				<?php get_sidebar(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php
if ($is_boxed) {
    get_footer('boxed');
} else {
    get_footer();
}
?>
