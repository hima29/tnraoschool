<?php

$defaults = array(
	'theme_location'  => Mega_Submenu::NAVIGATION_MOBILE,
	'menu_class'      => 'respmenu',
	// 'container_class' => petal_class( 'main-menu-container' ),
	'depth'           => 3,
	'fallback_cb'     => false,
	'walker'          => new MSM_Mobile_Menu_Walker()
);

$logo     = msm_get_option( 'respmenu-logo', array() );
$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';
if ( ! $logo_url ) {
	$logo     = msm_get_option( 'logo', array() );
	$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';
}

$respmenu_display_switch     = msm_get_option( 'respmenu-display-switch-img', array() );
$respmenu_display_switch_url = isset( $respmenu_display_switch['url'] ) && $respmenu_display_switch['url'] ? $respmenu_display_switch['url'] : '';

?>
<div id="msm-mobile-menu">
	<div class="respmenu-wrap">
		<div class="respmenu-header">
			<?php if ($logo_url) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="respmenu-header-logo-link">
					<img src="<?php echo esc_url( $logo_url ); ?>" class="respmenu-header-logo" alt="mobile-logo">
				</a>
			<?php endif; ?>
			<div class="respmenu-open">
			<?php if ($respmenu_display_switch_url) : ?>
				<img src="<?php echo esc_url( $respmenu_display_switch_url ); ?>" alt="mobile-menu-display-switch">
			<?php else: ?>
				<hr><hr><hr>
			<?php endif; ?>
			</div>
		</div>
		<?php
		msm_mobile_menu_render_start();
		wp_nav_menu( $defaults );
		msm_mobile_menu_render_end();
		?>
	</div>
</div>
