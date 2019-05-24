<?php
$logo_url                    = null;
$respmenu_display_switch_url = null;


if ( function_exists( 'superwise_get_option' ) ) {

	$logo     = superwise_get_option( 'respmenu-logo', array() );
	$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';

	if ( ! $logo_url ) {
		$logo     = superwise_get_option( 'logo', array() );
		$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';
	}

	$respmenu_display_switch     = superwise_get_option( 'respmenu-display-switch-img', array() );
	$respmenu_display_switch_url = isset( $respmenu_display_switch['url'] ) && $respmenu_display_switch['url'] ? $respmenu_display_switch['url'] : '';
}

?>
<div id="wh-mobile-menu" class="respmenu-wrap">
	<div class="respmenu-header">
		<?php if ( $logo_url ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="respmenu-header-logo-link">
				<img src="<?php echo esc_url( $logo_url ); ?>" class="respmenu-header-logo" alt="mobile-logo">
			</a>
		<?php endif; ?>
		<div class="respmenu-open">
		<?php if ( $respmenu_display_switch_url ) : ?>
			<img src="<?php echo esc_url( $respmenu_display_switch_url ); ?>" alt="mobile-menu-display-switch">
		<?php else: ?>
			<hr>
			<hr>
			<hr>
		<?php endif; ?>
		</div>
	</div>
	<?php wp_nav_menu( $args ); ?>
</div>