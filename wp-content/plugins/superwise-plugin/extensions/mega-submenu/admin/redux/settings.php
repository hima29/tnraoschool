<?php

if ( ! class_exists( 'Redux' ) ) {
	return;
}

// This is your option name where all the Redux data is stored.
$opt_name = MSM_OPTION_NAME;
$name = __( 'Settings', 'mega-submenu' );
$display_name = __( 'Mega Submenu Settings', 'mega-submenu' );
$page_parent = 'edit.php?post_type=' . Mega_Submenu::POST_TYPE;


// Compiler hook and demo CSS output.
// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
add_filter( 'redux/options/' . $opt_name . '/compiler', 'msm_compiler_action', 10, 3 );

function msm_compiler_action( $options, $css, $changed_values ) {

	$opt_name = MSM_OPTION_NAME;

	$upload_dir = wp_upload_dir();
	$filename   = $upload_dir['basedir'] . '/' . $opt_name . '_style.css';
	$filename = apply_filters('wheels_redux_compiler_filename', $filename);


	$filecontent = "/********* Compiled file/Do not edit *********/\n";
	$filecontent .= $css;

	$option_name = 'submenu-offset-top';
	if (isset($options[$option_name]) && $options[$option_name]) {
		$filecontent .= '.msm-menu-item .msm-submenu{top:' . (int) $options[$option_name] . 'px}';
	}

	$option_name = 'submenu-top-hover-area';
	if (isset($options[$option_name])) {
		$option = (int) $options[$option_name];
		$filecontent .= '.msm-menu-item .msm-submenu:before{';
		$filecontent .= 'top:' . (int) $options[$option_name] . 'px;';
		$filecontent .= 'height:' . (int) $options[$option_name] . 'px;';
		$filecontent .= '}';
	}


	if (is_writable($upload_dir['basedir'])) {
		file_put_contents($filename, $filecontent);
		// chmod($filename, 0644);

	} else {
		wp_die(__("It looks like your upload folder isn't writable, so PHP couldn't make any changes (CHMOD).", 'wheels'), __('Cannot write to file', 'wheels'), array('back_link' => true));
	}

}

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

// $theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
	// TYPICAL -> Change these values as you need/desire
	'opt_name'             => $opt_name,
	// This is where your data is stored in the database and also becomes your global variable name.
	'display_name'         => $display_name,
	// Name that appears at the top of your panel
	'display_version'      => MSM_PLUGIN_VERSION,
	// Version that appears at the top of your panel
	'menu_type'            => 'submenu',
	//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
	'allow_sub_menu'       => false,
	// Show the sections below the admin menu item or not
	'menu_title'           => $name,
	'page_title'           => $name,
	// You will need to generate a Google API key to use this feature.
	// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
	'google_api_key'       => 'AIzaSyBETK1Pd_dt2PYIGteFgKS25rp6MmQFErw',
	// Set it you want google fonts to update weekly. A google_api_key value is required.
	'google_update_weekly' => false,
	// Must be defined to add google fonts to the typography module
	'async_typography'     => true,
	// Use a asynchronous font on the front end or font string
	//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
	'admin_bar'            => true,
	// Show the panel pages on the admin bar
	'admin_bar_icon'       => 'dashicons-portfolio',
	// Choose an icon for the admin bar menu
	'admin_bar_priority'   => 50,
	// Choose an priority for the admin bar menu
	'global_variable'      => '',
	// Set a different name for your global variable other than the opt_name
	'dev_mode'             => false,
	// Show the time the page took to load, etc
	'update_notice'        => true,
	// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
	'customizer'           => false,
	// Enable basic customizer support
	//'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
	//'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

	// OPTIONAL -> Give you extra features
	'page_priority'        => 50,
	// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	'page_parent'          => $page_parent,
	// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	'page_permissions'     => 'manage_options',
	// Permissions needed to access the options panel.
	'menu_icon'            => '',
	// Specify a custom URL to an icon
	'last_tab'             => '',
	// Force your panel to always open to a specific tab (by id)
	'page_icon'            => 'icon-themes',
	// Icon displayed in the admin panel next to your menu_title
	'page_slug'            => '_mega_submenu_settings',
	// Page slug used to denote the panel
	'save_defaults'        => true,
	// On load save the defaults to DB before user clicks save or not
	'default_show'         => false,
	// If true, shows the default value next to each field that is not the default value.
	'default_mark'         => '*',
	// What to print by the field's title if the value shown is default. Suggested: *
	'show_import_export'   => true,
	// Shows the Import/Export panel when not used as a field.

	// CAREFUL -> These options are for advanced use only
	'transient_time'       => 60 * MINUTE_IN_SECONDS,
	'output'               => true,
	// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	'output_tag'           => true,
	// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

	// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	'database'             => '',
	// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

	//'compiler'             => true,

	// HINTS
	'hints'                => array(
		'icon'          => 'el el-question-sign',
		'icon_position' => 'right',
		'icon_color'    => 'lightgray',
		'icon_size'     => 'normal',
		'tip_style'     => array(
			'color'   => 'light',
			'shadow'  => true,
			'rounded' => false,
			'style'   => '',
		),
		'tip_position'  => array(
			'my' => 'top left',
			'at' => 'bottom right',
		),
		'tip_effect'    => array(
			'show' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'mouseover',
			),
			'hide' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'click mouseleave',
			),
		),
	)
);

Redux::setArgs( $opt_name, $args );
