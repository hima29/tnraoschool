<?php

add_filter( 'msm_filter_submenu_before', 'msm_integration_filter_submenu_before', 11 );
function msm_integration_filter_submenu_before( $before ) {
	if ( msm_in_mobile_menu() ) {
		return '<ul class="sub-menu"><li>';
	}
	return $before;
}

add_filter( 'msm_filter_submenu_after', 'msm_integration_filter_submenu_after', 11 );
function msm_integration_filter_submenu_after( $after ) {
	if ( msm_in_mobile_menu() ) {
		return '</li></ul>';
	}
	return $after;
}

/**
 * Custom css
 */
add_action('wp_head', 'msm_integration_style');

function msm_integration_style() {
	?>
	<style id="msm-integration-style">
		#slide-out-widget-area  .msm-submenu {
			width: 100% !important;
			left: auto !important;
			color: inherit;
			background-color: inherit !important;
			box-shadow: none;
		}
		.sf-menu li .msm-submenu {
			line-height: 25px !important;
		}
	</style>
<?php
}

add_action('wp_footer', 'msm_integration_script');

function msm_integration_script() {
	?>
	<script>
		setTimeout(function () {
			jQuery(window).resize()
		}, 1000);
	</script>
<?php
}