<?php
/**
 * Wheels includes
 */
$superwise_includes = array(
	// 'lib/supplement.php',                      // Custom logo implementation
	'lib/utils.php',                   // Utility functions
	'lib/css-classes.php',             // Dynamic CSS Classes
	'lib/init.php',                    // Initial theme setup and constants
	'lib/config.php',                  // Configuration
	'lib/activate-plugins.php',        // Activate plugins
	'lib/titles.php',                  // Page titles
	'lib/cleanup.php',                 // Cleanup
	'lib/comments.php',                // Custom comments modifications
	'lib/scripts.php',                 // Scripts and stylesheets
	'lib/extras.php',                  // Custom functions
	'lib/metaboxes.php',               // Metaboxes
	'lib/class-mobile-menu-walker.php',// Mobile Menu Walker
	'lib/vc-templates.php',            // VC Templates
	'lib/redux/redux-settings.php',    // Redux Settings
	'lib/redux/options.php',           // Redux Options
	'woocommerce/custom/init.php',     // Woocommerce
	'lib/envato_setup/envato_setup.php',
	'lib/envato_setup/envato_setup_init.php',
);
foreach ( $superwise_includes as $file ) {
	$filepath = get_template_directory() . '/' . $file;
	if ( ! file_exists( $filepath ) ) {
		trigger_error( sprintf( esc_html__( 'Error locating %s for inclusion', 'superwise' ), $file ), E_USER_ERROR );
	}
	require_once $filepath;
}
unset( $file, $filepath );
// Replaces the excerpt "Read More" text by a link
function new_excerpt_more($more) {
       global $post;
	return '<a class="moretag" href="'. get_permalink($post->ID) . '"> Read More...</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');