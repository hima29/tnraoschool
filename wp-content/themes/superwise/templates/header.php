<?php
$header_layout_block = superwise_get_layout_block_content( 'header-layout-block' );
$show_preloader = (int) superwise_get_option( 'preloader', 0 );

?>
<?php if ($show_preloader) : ?>
	<div class="wh-preloader"></div>
<?php endif; ?>
<?php if ( $header_layout_block ): ?>
	<div class="<?php echo superwise_class( 'header' ); ?>">
			<?php echo do_shortcode( $header_layout_block ); ?>
	</div>
<?php else: ?>

	<header class="<?php echo superwise_class( 'header' ); ?>">

		<div class="<?php echo superwise_class( 'main-menu-bar-wrapper' ); ?>">
			<div class="<?php echo superwise_class( 'container' ); ?>">
				<div class="<?php echo superwise_class( 'logo-wrapper' ); ?>">
					<?php get_template_part( 'templates/logo' ); ?>
				</div>
				<?php get_template_part( 'templates/logo-sticky' ); ?>

				<div class="<?php echo superwise_class( 'main-menu-wrapper' ); ?>">
					<?php get_template_part( 'templates/menu-main' ); ?>
				</div>
			</div>
		</div>
	</header>
<?php endif; ?>
