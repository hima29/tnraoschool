<?php
/**
 * Plugin Name: Superwise Plugin
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Superwise theme helper plugin
 * Version:     1.2.8
 * Author:      Aislin Themes
 * Author URI:  http://themeforest.net/user/Aislin/portfolio
 * License:     GPLv2+
 * Text Domain: chp
 * Domain Path: /languages
 */
define( 'SCP_PLUGIN_VERSION', '1.2.8' );
define( 'SCP_PLUGIN_NAME', 'Superwise' );
define( 'SCP_PLUGIN_PREFIX', 'scp_' );
define( 'SCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SCP_PLUGIN_PATH', dirname( __FILE__ ) . '/' );
define( 'SCP_TEXT_DOMAIN', 'superwise-plugin' );

register_activation_hook( __FILE__, 'scp_activate' );
register_deactivation_hook( __FILE__, 'scp_deactivate' );

add_action( 'plugins_loaded', 'scp_init' );
add_action( 'widgets_init', 'scp_register_wp_widgets' );
add_action( 'wp_enqueue_scripts', 'scp_enqueue_scripts', 100 );
add_action( 'admin_init', 'scp_register_wp_widgets' );
add_action( 'admin_init', 'scp_vc_editor_set_post_types', 10 );
add_action( 'admin_menu', 'scp_register_show_theme_icons_page' );
add_action( 'wpcf7_before_send_mail', 'scp_action_wpcf7_before_send_mail' );
add_action( 'wp_head', 'scp_theme_debugging_info', 999);

add_filter( 'pre_get_posts', 'scp_portfolio_posts' );
add_filter( 'widget_text', 'do_shortcode' );

add_filter( 'vc_base_build_shortcodes_custom_css', 'scp_filter_vc_base_build_shortcodes_custom_css' );
add_filter( 'pll_get_post_types', 'scp_add_cpt_to_pll', 10, 2 );


require_once 'shortcodes.php';
require_once 'includes/theme-icons.php';
require_once 'includes/assets.php';


function scp_init() {
	scp_load_textdomain();
	scp_add_extensions();
	scp_add_vc_custom_addons();

	require_once 'extensions/CPT.php';

	$layout_blocks = new CPT('layout_block', array(
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_position' => 29,
		'supports' => array('title', 'editor', 'revisions')
	));
	$layout_blocks->register_taxonomy(array(
		'taxonomy_name' => 'layout_block_type',
		'singular' => 'Type',
		'plural' => 'Type',
		'slug' => 'type',
	));
	$layout_blocks->filters(array('layout_block_type'));
	require_once 'includes/google-classroom/init.php';
// Only way to remove Revolution slider activation notice
	global $productAdmin;
	remove_action( 'admin_notices', array( $productAdmin, 'addActivateNotification' ) );


}

function scp_activate() {
	scp_init();
	flush_rewrite_rules();
}

function scp_deactivate() {

}

function scp_load_textdomain() {
	load_plugin_textdomain( 'superwise-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

function scp_vc_not_active_message() {
	$plugin_data = get_plugin_data( __FILE__ );
	echo '
    <div class="updated">
      <p>' . sprintf( esc_html__( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'superwise-plugin' ), $plugin_data['Name'] ) . '</p>
    </div>';
}

function scp_add_vc_custom_addons() {
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		add_action( 'admin_notices', 'scp_vc_not_active_message' );
		return;
	}


	require_once 'vc-addons/content-box/addon.php';
	require_once 'vc-addons/video-popup/addon.php';
	require_once 'vc-addons/logo/addon.php';
	require_once 'vc-addons/theme-button/addon.php';
	require_once 'vc-addons/theme-icon/addon.php';
	require_once 'vc-addons/theme-map/addon.php';
	require_once 'vc-addons/menu/addon.php';
	require_once 'vc-addons/post-list/addon.php';
	require_once 'vc-addons/share-this/addon.php';
	require_once 'vc-addons/wc-mini-cart/addon.php';
	require_once 'vc-addons/search/addon.php';
	require_once 'vc-addons/quick-sidebar-trigger/addon.php';
	require_once 'vc-addons/dribbble-shots/addon.php';
	require_once 'vc-addons/events/addon.php';
	require_once 'vc-addons/schedule/addon.php';
	require_once 'vc-addons/instagram/addon.php';
	require_once 'vc-addons/teachers/addon.php';
	require_once 'vc-addons/google-calendar/addon.php';
	require_once 'vc-addons/circles/addon.php';
	require_once 'vc-addons/link-dropdown/addon.php';
}

function scp_add_extensions() {

	require_once 'extensions/teacher-post-type/teacher-post-type.php';
	require_once 'extensions/mega-submenu/mega-submenu.php';

	if ( ! scp_is_plugin_activating( 'breadcrumb-trail/breadcrumb-trail.php' ) && ! function_exists( 'breadcrumb_trail_theme_setup' ) ) {
		require_once 'extensions/breadcrumb-trail/breadcrumb-trail.php';
	}

	/**
	 * Events Settings the first time
	 */
	add_option( 'tribe_events_calendar_options', array(
		'tribeEventsTemplate' => 'template-fullwidth.php',
	) );
}

function scp_get_wheels_option( $option_name, $default = false ) {
	if ( function_exists( 'superwise_get_option' ) ) {
		return superwise_get_option( $option_name, $default );
	}

	return $default;
}

function scp_set_js_global_var() {
	return array(
		'data' => array(
			'vcWidgets' => array(
				'ourProcess' => array(
					'breakpoint' => 480
				),
			),
			'styles' => array(),
		),
	);
}

function scp_register_wp_widgets() {
	require_once 'wp-widgets/SCP_Latest_Posts_Widget.php';
	require_once 'wp-widgets/SCP_Contact_Info_Widget.php';
	require_once 'wp-widgets/SCP_Banner_Widget.php';
	require_once 'wp-widgets/twitter-widget/recent-tweets-widget.php';
}

function scp_portfolio_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( is_tax() && isset( $query->tax_query ) && $query->tax_query->queries[0]['taxonomy'] == 'portfolio_category' ) {
		$query->set( 'posts_per_page', 10 );

		return;
	}
}

function scp_vc_editor_set_post_types() {

	if ( is_admin() && function_exists( 'vc_set_default_editor_post_types' ) ) {
		vc_set_default_editor_post_types( array(
			'page', 'layout_block', 'project', 'events', 'teacher', 'msm_mega_menu', 'agc_course'
		) );
	}
}

function scp_enqueue_scripts() {
    wp_enqueue_script( 'scp-main-js', SCP_PLUGIN_URL . '/public/js/linp-main.js', array( 'jquery' ), false, true );

	// wp_enqueue_script( 'scp-main-js', SCP_PLUGIN_URL . '/public/js/main.js', array( 'jquery' ), false, true );
	wp_localize_script( 'jquery-migrate', 'superwise_plugin', scp_set_js_global_var() );
}

function scp_is_plugin_activating( $plugin ) {
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'activate' && isset( $_GET['plugin'] ) ) {
		if ( $_GET['plugin'] == $plugin ) {
			return true;
		}
	}

	return false;
}




function scp_sanitize_size( $value, $default = 'px' ) {

	return preg_match( '/(px|em|rem|\%|pt|cm)$/', $value ) ? $value : ( (int) $value ) . $default;
}


function scp_filter_vc_base_build_shortcodes_custom_css($css) {
	global $post;
	
	if (!$post) {
		return;
	}

	// had to be done like this so we get the post with newly saved content
	$post = get_post( $post->ID );


	if (!$post) {
		return;
	}

	$css .= scp_parseShortcodesCustomCss($post->post_content);

	return $css;
}

function scp_parseShortcodesCustomCss( $content ) {
	global $shortcode_tags;
	$css = '';

	WPBMap::addAllMappedShortcodes();
	preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );
	foreach ( $shortcodes[2] as $index => $tag ) {
		$shortcode = WPBMap::getShortCode( $tag );
		$attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );

		if (isset($shortcode_tags[$tag]) && is_array($shortcode_tags[$tag]) && is_object($shortcode_tags[$tag][0])) {
			$widget = $shortcode_tags[$tag][0];
			if (method_exists($widget, 'generate_css')) {
				$css .= $widget->generate_css($attr_array);
			}
		}
	}
	foreach ( $shortcodes[5] as $shortcode_content ) {
		$css .= scp_parseShortcodesCustomCss( $shortcode_content );
	}

	return $css;
}

function scp_register_show_theme_icons_page() {
    add_submenu_page( 
        // null,   //if you want to now show in the menu
        'tools.php',
        'Theme Icons',
        'Theme Icons',
        'manage_options',
        'theme-icons',
        'scp_show_theme_icons'
    );
}

function scp_show_theme_icons() {


		$icons = scp_get_theme_icon_list();
		echo '<h1>Theme Icons</h1>';
		echo '<ul class="icons">';
		foreach ($icons as $icon_data) {

			foreach ($icon_data as $icon) {
				echo "<li><i class=\"$icon\"></i> $icon</li>";
			}
		}
		echo '</ul>';
		echo '<style>.icons li{width: 30%;list-style:none;float: left;
    padding: 10px;}.icons li i{font-size:30px;margin-right: 15px;}</style>';
	
}


function scp_action_wpcf7_before_send_mail( $form ) {

	if ( isset( $_POST['_wpcf7_container_post'] ) ) {
		$post_id = (int) $_POST['_wpcf7_container_post'];

		if ( get_post_type( $post_id ) == 'teacher' ) {

			$teacher_is_recepient = 'yes' == superwise_get_rwmb_meta('teacher_is_wpcf7_recipient', $post_id);
			$teacher_email        = get_post_meta( $post_id, Aislin_Classroom_Post_Type::META_EMAIL, true );

			if ( $teacher_is_recepient && $teacher_email ) {

				$properites = $form->get_properties();
				$properites['mail']['recipient'] = $teacher_email;
				$properites['mail_2']['additional_headers'] = 'Reply-To: [your-email]';
				$form->set_properties($properites);
			}
		}
	} 
}

function scp_add_cpt_to_pll( $post_types, $is_settings ) {

	if ( $is_settings ) {
		// hides 'my_cpt' from the list of custom post types in Polylang settings
		// unset( $post_types['my_cpt'] );
	} else {
		// enables language and translation management for 'my_cpt'
	}
	
	$post_types['layout_block'] = 'layout_block';
	$post_types['msm_mega_menu'] = 'msm_mega_menu';
	return $post_types;
}

function scp_theme_debugging_info() {
	$desc = 'Powered by ' . wp_get_theme() . ' WordPress theme ' . 'compatible with Google Classroom - educators can create classes, distribute assignments, send feedback, and see everything in one place. Suitable for elementary school website, high school website or web presentation for teacher or tutor.';
	echo "<meta name=\"generator\" content=\"{$desc}\" />" . "\n";
}

function scp_get_thumbnail_sizes_vc() {
	global $_wp_additional_image_sizes;
	$thumbnail_sizes = array();
	foreach ( $_wp_additional_image_sizes as $name => $settings ) {
		$thumbnail_sizes[ $name . ' (' . $settings['width'] . 'x' . $settings['height'] . ')' ] = $name;
	}
	return $thumbnail_sizes;
}
