<?php
$logo     = superwise_get_option( 'quick-sidebar-logo', array() );
$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';

?>

<div class="wh-quick-sidebar">
	<span class="wh-close"><i class="icon-close-1"></i></span>

	<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( $logo_url ); ?>" alt="logo">
	</a>
	<hr/>
	<?php $text_block = superwise_get_option( 'quick-sidebar-text', '' ); ?>
	<?php if ( $text_block ): ?>
		<div class="<?php echo superwise_class( 'quick-sidebar-text' ); ?>">
			<?php echo do_shortcode( $text_block ); ?>
		</div>
	<?php endif; ?>
	<?php
	$menu_options = array(
		'theme_location'  => 'quick_sidebar_navigation',
		'menu_class'      => superwise_class( 'quick-sidebar-menu' ),
		'container_class' => superwise_class( 'quick-sidebar-menu-container' ),
		'depth'           => 1
	);
	?>
	<?php wp_nav_menu( $menu_options ); ?>
	<hr/>
	<?php $social_links = superwise_get_option( 'quick-sidebar-social-links', array() ); ?>
	<div class="<?php echo superwise_class( 'quick-sidebar-social-links' ); ?>">
		<h4><?php esc_html_e('Keep Connected', 'superwise'); ?></h4>
		<?php foreach ( $social_links as $social_link ): ?>
			<?php
			$parts = explode( '|', $social_link );
			$icon  = isset( $parts[0] ) ? trim( $parts[0] ) : false;
			$link  = isset( $parts[1] ) ? trim( $parts[1] ) : '';
			$style = isset( $parts[2] ) ? 'font-size:' . (int) $parts[2] . 'px' : '';
			?>
			<?php if ( $icon ): ?>
				<a target="_blank" href="<?php echo esc_url( $link ); ?>" style="<?php echo esc_attr( $style ); ?>">
					<i class="<?php echo esc_html( $icon ); ?>"></i>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>