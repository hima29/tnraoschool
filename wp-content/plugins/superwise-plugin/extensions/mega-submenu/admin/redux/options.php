<?php

$opt_name    = MSM_OPTION_NAME;
$text_domain = 'mega-submenu';

if ( ! class_exists( 'Redux' ) ) {
	return;
}


$menus       = get_registered_nav_menus();
$menus_array = array();

foreach ( $menus as $location => $description ) {
	$menus_array[ $location ] = $description;
}

// ----------------------------------
// -> Theme Integration
// ----------------------------------
if ( MSM_Theme_Integration::is_supported() ) {


	$fields = array();

	$welcome_file = MSM_PLUGIN_PATH . 'includes/theme-integration/' . MSM_Theme_Integration::get_template() . '/welcome.php';

	if ( file_exists( $welcome_file ) ) {
		$fields = include_once $welcome_file;
	}


	Redux::setSection( $opt_name, array(
		'id'     => $opt_name . 'section-theme-integration',
		'title'  => __( 'Theme Integration', $text_domain ),
		'icon'   => 'el el-screen',
		'fields' => array(
			$fields
		),
	) );
	// -> End Theme Integration

}

// ----------------------------------
// -> General
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => $opt_name . 'section-general',
	'title'  => __( 'General Settings', $text_domain ),
	'icon'   => 'el-icon-home',
	// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
	'fields' => array(
		array(
			'compiler' => true,
			'id'       => 'menu-location',
			'type'     => 'select',
			'title'    => __( 'Select Menu Location', $text_domain ),
			'desc'     => __( 'Mega Menus will be applied only on selected menu location.', $text_domain ),
			'options'  => $menus_array,
			'default'  => '',
		),
		array(
			'compiler' => true,
			'id'       => 'theme-mobile-menu-location',
			'type'     => 'select',
			'title'    => __( 'Mobile Menu Location', $text_domain ),
			'desc'     => __( 'Select this if your theme is using a separate menu location for Mobile.', $text_domain ),
			'options'  => $menus_array,
			'default'  => '',
		),
		array(
			'compiler' => true,
			'id'       => 'submenu-offset-top',
			'type'     => 'text',
			'title'    => __( 'Offset Top', $text_domain ),
			'desc'     => __( 'Value in px. Enter number only.', $text_domain ),
			'validate' => 'number',
			'msg'      => 'Enter number only',
			'default'  => '60'
		),
		array(
			'compiler' => true,
			'id'       => 'submenu-top-hover-area',
			'type'     => 'text',
			'title'    => __( 'Submenu Top Hover Area', $text_domain ),
			'subtitle' => __( 'The space above the submenu that when hovered on will keep the submenu open.', $text_domain ),
			'desc'     => __( 'Value in px. Enter number only.', $text_domain ),
			'validate' => 'number',
			'msg'      => 'Enter number only',
			'default'  => '30'
		),
		array(
			'id'      => 'submenu-items-position-relative',
			'type'    => 'switch',
			'title'   => __( 'Submenu items position relative', $text_domain ),
			'desc'    => __( 'Enable this if mega menus in submenus are not aligned correctly.', $text_domain ),
			'default' => false,
		),
		array(
			'id'      => 'mobile-menu-trigger-click-bellow',
			'type'    => 'spinner',
			'title'   => esc_html__( 'Trigger click for mobile menus bellow', $text_domain ),
			'desc'    => esc_html__( 'When used inside theme mobile menu, when to trigger click on item', $text_domain ),
			'default' => '767',
			'min'     => '50',
			'max'     => '2000',
			'step'    => '1',
		),
		/**
		 * @since  1.1.0
		 */
		array(
			'id'       => 'mobile-menu-wrapper',
			'type'     => 'textarea',
			'title'    => __( 'Mobile menu wrapper', $text_domain ),
			'subtitle' => __( 'HTML to wrap when printed in mobile menu', $text_domain ),
			'desc'     => __( 'Open and close tags separated with |. Example <pre>' . htmlspecialchars( '<ul class="sub-menu"><li>|</li></ul>' ) . '</pre>', $text_domain ),
			'validate' => 'html_custom',
			'default'  => '<ul class="sub-menu"><li>|</li></ul>',

		),
		array(
			'id'       => 'menu-wrapper',
			'type'     => 'textarea',
			'title'    => __( 'General menu wrapper', $text_domain ),
			'subtitle' => __( 'HTML', $text_domain ),
			'desc'     => __( 'Open and close tags separated with |. Example <pre>' . htmlspecialchars( '<ul class="sub-menu"><li>|</li></ul>' ) . '</pre>', $text_domain ),
			'validate' => 'html_custom',
			'default'  => '',

		),
	),
) );
// -> End General


if ( apply_filters( Mega_Submenu::FILTER_USE_STYLE_MENU, true ) ) {


// ----------------------------------
// -> Styling
// ----------------------------------
	// Redux::setSection( $opt_name, array(
	// 	'id'     => $opt_name . 'section-styling',
	// 	'title'  => __( 'Styling', $text_domain ),
	// 	'icon'   => 'el el-brush',
	// 	// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
	// 	'fields' => array(

	// 		array(
	// 			'compiler' => true,
	// 			'id'       => 'style-menu',
	// 			'type'     => 'switch',
	// 			'title'    => __( 'Style Menu', $text_domain ),
	// 			'subtitle' => __( 'Enable this to style top level menu items', $text_domain ),
	// 			'default'  => true,
	// 		),

	// 	),
	// ) );
// -> End General

// Redux::setSection( $opt_name, array(
// 	'subsection' => true,
// 	'id'         => 'subsection-header-main-menu',
// 	'title'      => esc_html__( 'Main Menu', 'mega-submenu' ),
// 	'fields'     => array(
// 		array(
// 			'id'             => 'menu-main-top-level-typography',
// 			'type'           => 'typography',
// 			'title'          => esc_html__( 'Top Level Items Typography', 'mega-submenu' ),
// 			'google'         => true,    // Disable google fonts. Won't work if you haven't defined your google api key
// 			'font-backup'    => true,    // Select a backup non-google font in addition to a google font
// 			'color'          => false,
// 			'text-transform' => true,
// 			'all_styles'     => true,    // Enable all Google Font style/weight variations to be added to the page
// 			'letter-spacing' => true,
// 			'compiler'       => array(
// 				'.sf-menu.wh-menu-main a',
// 				'.respmenu li a',
// 			), // An array of CSS selectors to apply this font style to dynamically
// 			'units'          => 'px', // Defaults to px
// 			'default'        => array(
// 				'font-style'  => '700',
// 				'font-family' => 'Abel',
// 				'google'      => true,
// 				'font-size'   => '18px',
// 				'line-height' => '24px'
// 			),
// 		),
// 		array(
// 			'id'             => 'menu-main-sub-items-typography',
// 			'type'           => 'typography',
// 			'title'          => esc_html__( 'Subitems Typography', 'mega-submenu' ),
// 			'google'         => true,
// 			// Disable google fonts. Won't work if you haven't defined your google api key
// 			'font-backup'    => true,
// 			// Select a backup non-google font in addition to a google font
// 			'color'          => false,
// 			'text-transform' => true,
// 			'all_styles'     => true,
// 			'letter-spacing' => true,
// 			// Enable all Google Font style/weight variations to be added to the page
// 			'compiler'       => array( '.sf-menu.wh-menu-main ul li a' ),
// 			// An array of CSS selectors to apply this font style to dynamically
// 			'units'          => 'px',
// 			// Defaults to px
// 			'default'        => array(
// 				'font-style'  => '700',
// 				'font-family' => 'Abel',
// 				'google'      => true,
// 				'font-size'   => '16px',
// 				'line-height' => '24px'
// 			),
// 		),
// 		array(
// 			'id'       => 'main-menu-link-color',
// 			'type'     => 'link_color',
// 			'title'    => esc_html__( 'Menu Item Link Color', 'mega-submenu' ),
// 			'active'   => false, // Disable Active Color
// 			'compiler' => array(
// 				'.sf-menu.wh-menu-main a',
// 				'.respmenu li a',
// 				'.cbp-respmenu-more',
// 				'.wh-quick-sidebar-toggler i',
// 				'.wh-search-toggler i',
// 			),
// 			'default'  => array(
// 				'regular' => '#000',
// 				'hover'   => '#333',
// 			),
// 		),
// 		array(
// 			'id'       => 'main-menu-menu-item-hover-background',
// 			'type'     => 'background',
// 			'compiler' => array( '.sf-menu.wh-menu-main > li:hover, .sf-menu.wh-menu-main > li.sfHover' ),
// 			'title'    => esc_html__( 'Menu Item Hover Background', 'mega-submenu' ),
// 			'subtitle' => esc_html__( 'Pick a background color for the menu item on hover.', 'mega-submenu' ),
// 		),
// 		array(
// 			'id'       => 'main-menu-current-item-background',
// 			'type'     => 'background',
// 			'compiler' => array(
// 				'.sf-menu.wh-menu-main .current-menu-item',
// 				'.respmenu_current'
// 			),
// 			'title'    => esc_html__( 'Current Menu Item Background', 'mega-submenu' ),
// 			'subtitle' => esc_html__( 'Pick a background color for the current menu item.', 'mega-submenu' ),
// 		),
// 		array(
// 			'id'       => 'main-menu-current-item-link-color',
// 			'type'     => 'link_color',
// 			'title'    => esc_html__( 'Current Menu Item Link Color', 'mega-submenu' ),
// 			'active'   => false, // Disable Active Color
// 			'compiler' => array( '.sf-menu.wh-menu-main .current-menu-item > a' ),
// 			'default'  => array(
// 				'regular' => '#000',
// 				'hover'   => '#333',
// 			),
// 		),
// 		array(
// 			'id'       => 'main-menu-submenu-current-item-link-color',
// 			'type'     => 'link_color',
// 			'title'    => esc_html__( 'Current Menu Item Submenu Link Color', 'mega-submenu' ),
// 			'active'   => false, // Disable Active Color
// 			'compiler' => array( '.sf-menu.wh-menu-main .sub-menu .current-menu-item > a' ),
// 			'default'  => array(
// 				'regular' => '#000',
// 				'hover'   => '#333',
// 			),
// 		),
// 		array(
// 			'id'       => 'main-menu-submenu-item-background',
// 			'type'     => 'background',
// 			'compiler' => array(
// 				'.sf-menu.wh-menu-main ul li',
// 				'.sf-menu.wh-menu-main .sub-menu',
// 			),
// 			'title'    => esc_html__( 'Submenu Menu Item Background', 'mega-submenu' ),
// 			'default'  => array(
// 				'background-color' => '#fff',
// 			),
// 		),
// 		array(
// 			'id'       => 'main-menu-submenu-item-hover-background',
// 			'type'     => 'background',
// 			'compiler' => array( '.sf-menu.wh-menu-main ul li:hover, .sf-menu.wh-menu-main ul ul li:hover' ),
// 			'title'    => esc_html__( 'Subenu Item Hover Background', 'mega-submenu' ),
// 			'subtitle' => esc_html__( 'Pick a background color for the menu item on hover.', 'mega-submenu' ),
// 		),
// 		array(
// 			'id'       => 'main-menu-submenu-item-link-color',
// 			'type'     => 'link_color',
// 			'title'    => esc_html__( 'Submenu Item Link Color', 'mega-submenu' ),
// 			'active'   => false, // Disable Active Color
// 			'compiler' => array(
// 				'.sf-menu.wh-menu-main .sub-menu li a',
// 				'.sf-menu.wh-menu-main .sub-menu li.menu-item-has-children:after',
// 			),
// 			'default'  => array(
// 				'regular' => '#000',
// 				'hover'   => '#333',
// 			),
// 		),
// 		array(
// 			'id'             => 'main-menu-padding',
// 			'type'           => 'spacing',
// 			'compiler'       => array( '.wh-menu-main' ),
// 			'mode'           => 'padding',
// 			'units'          => array( 'px' ),
// 			'units_extended' => 'false',
// 			'title'          => esc_html__( 'Padding Top/Bottom', 'mega-submenu' ),
// 			'description'    => esc_html__( 'Use it to better vertical align the menu', 'mega-submenu' ),
// 			'left'           => false,
// 			'right'          => false,
// 			'default'        => array(
// 				'padding-top'    => '0',
// 				'padding-bottom' => '0',
// 				'units'          => 'px',
// 			),
// 		),
// 		array(
// 			'id'          => 'main-menu-initial-waypoint-compensation',
// 			'type'        => 'text',
// 			'title'       => esc_html__( 'Initial Waypoint Scroll Compensation', 'mega-submenu' ),
// 			'description' => esc_html__( 'Enter number only.', 'mega-submenu' ),
// 			'validate'    => 'number',
// 			'default'     => 120
// 		),


// 	)
// ) );

	Redux::setSection( $opt_name, array(
		'subsection' => true,
		'id'         => 'subsection-header-responsive-menu',
		'title'      => esc_html__( 'Mobile Header', 'mega-submenu' ),
		'fields'     => array(
			array(
				'id'       => 'respmenu-use',
				'type'     => 'switch',
				'compiler' => 'true',
				'title'    => esc_html__( 'Use Responsive Menu?', 'mega-submenu' ),
				'default'  => false,
			),
			array(
				'id'       => 'respmenu-show-start',
				'type'     => 'spinner',
				'title'    => esc_html__( 'Display bellow', 'mega-submenu' ),
				'desc'     => esc_html__( 'Set the width of the screen in px bellow which the menu is shown and main menu is hidden', 'mega-submenu' ),
				'default'  => '767',
				'min'      => '50',
				'max'      => '2000',
				'step'     => '1',
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-logo',
				'type'     => 'media',
				'title'    => esc_html__( 'Logo', 'mega-submenu' ),
				'url'      => true,
				'mode'     => false,
				// Can be set to false to allow any media type, or can also be set to any mime type.
				'subtitle' => esc_html__( 'Set logo image', 'mega-submenu' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-logo-dimensions',
				'type'     => 'dimensions',
				'units'    => array( 'em', 'px', '%' ),
				'title'    => esc_html__( 'Logo Dimensions (Width/Height)', 'mega-submenu' ),
				'compiler' => array( '.respmenu-header .respmenu-header-logo-link' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-background',
				'type'     => 'background',
				'title'    => esc_html__( 'Background', 'mega-submenu' ),
				'compiler' => array( '#msm-mobile-menu' ),
				'default'  => array(
					'background-color' => '#fff',
				),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),

			),
			array(
				'id'       => 'respmenu-link-color',
				'type'     => 'link_color',
				'title'    => esc_html__( 'Menu Link Color', 'mega-submenu' ),
				'compiler' => array(
					'#msm-mobile-menu .respmenu li a',
				),
				'active'   => false,
				'visited'  => false,
				'default'  => array(
					'regular' => '#000', // blue
					'hover'   => '#333', // red
				),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'          => 'respmenu-display-switch-color',
				'type'        => 'color',
				'mode'        => 'border-color',
				'title'       => esc_html__( 'Display Toggle Color', 'mega-submenu' ),
				'compiler'    => array( '#msm-mobile-menu .respmenu-open hr' ),
				'transparent' => false,
				'default'     => '#000',
				'validate'    => 'color',
				'required'    => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'          => 'respmenu-display-switch-color-hover',
				'type'        => 'color',
				'mode'        => 'border-color',
				'title'       => esc_html__( 'Display Toggle Hover Color', 'mega-submenu' ),
				'compiler'    => array( '#msm-mobile-menu .respmenu-open:hover hr' ),
				'transparent' => false,
				'default'     => '#999',
				'validate'    => 'color',
				'required'    => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-display-switch-img',
				'type'     => 'media',
				'title'    => esc_html__( 'Display Toggle Image', 'mega-submenu' ),
				'url'      => true,
				'mode'     => false,
				// Can be set to false to allow any media type, or can also be set to any mime type.
				'subtitle' => esc_html__( 'Set the image to replace default 3 lines for menu toggle button.', 'mega-submenu' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-display-switch-img-dimensions',
				'type'     => 'dimensions',
				'units'    => array( 'em', 'px', '%' ),
				'title'    => esc_html__( 'Display Toggle Image Dimensions (Width/Height)', 'mega-submenu' ),
				'compiler' => array( '.respmenu-header .respmenu-open img' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'             => 'respmenu-menu-padding',
				'type'           => 'spacing',
				'compiler'       => array( '#msm-mobile-menu' ),
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Padding', 'mega-submenu' ),
				'description'    => esc_html__( 'Use it to better vertical align the menu', 'mega-submenu' ),
				'default'        => array(
					'padding-top'    => '0',
					'padding-right'  => '0',
					'padding-bottom' => '0',
					'padding-left'   => '0',
					'units'          => 'px',
				),
				'required'       => array(
					array( 'respmenu-use', 'equals', '1' ),
				)
			),
		)
	) );

// Redux::setSection( $opt_name, array(
// 	'subsection' => true,
// 	'id'         => 'subsection-sticky-header',
// 	'title'      => esc_html__( 'Sticky Header', 'mega-submenu' ),
// 	'fields'     => array(
// 		array(
// 			'id'      => 'main-menu-use-menu-is-sticky',
// 			'type'    => 'switch',
// 			'title'   => esc_html__( 'Enable Sticky Menu', 'mega-submenu' ),
// 			'default' => 1,
// 		),
// 		array(
// 			'id'       => 'main-menu-sticky-background',
// 			'type'     => 'background',
// 			'title'    => esc_html__( 'Sticky Menu Background', 'mega-submenu' ),
// 			'compiler' => array(
// 				'.wh-header.is_stuck',
// 				'body.page-template-template-home-transparent-header .wh-header.is_stuck',
// 				'body.page-template-template-home-transparent-header-boxed .wh-header.is_stuck',
// 			),
// 			'default'  => array(
// 				'background-color' => '#fff',
// 			),
// 			'required' => array(
// 				array( 'main-menu-use-menu-is-sticky', 'equals', '1' ),
// 			),
// 		),
// 		array(
// 			'id'       => 'main-menu-sticky-link-color',
// 			'type'     => 'link_color',
// 			'title'    => esc_html__( 'Sticky Menu Link Color', 'mega-submenu' ),
// 			'compiler' => array(
// 				'.wh-header.is_stuck .sf-menu.wh-menu-main > li > a',
// 			),
// 			'active'   => false,
// 			'visited'  => false,
// 			'default'  => array(
// 				'regular' => '#000', // blue
// 				'hover'   => '#333', // red
// 			)
// 		),
// 		array(
// 			'id'             => 'main-menu-sticky-padding',
// 			'type'           => 'spacing',
// 			'compiler'       => array( '.wh-sticky-header .wh-menu-main' ),
// 			'mode'           => 'padding',
// 			'units'          => array( 'px' ),
// 			'units_extended' => 'false',
// 			'title'          => esc_html__( 'Sticky Menu Padding', 'mega-submenu' ),
// 			'description'    => esc_html__( 'Use it to better vertical align the menu', 'mega-submenu' ),
// 			'left'           => false,
// 			'right'          => false,
// 			'default'        => array(
// 				'padding-top'    => '0',
// 				'padding-bottom' => '0',
// 				'units'          => 'px',
// 			),
// 			'required'       => array(
// 				array( 'main-menu-use-menu-is-sticky', 'equals', '1' ),
// 			)
// 		),
// 		array(
// 			'id'       => 'main-menu-sticky-border',
// 			'type'     => 'border',
// 			'title'    => __( 'Sticky Header Border Bottom', 'mega-submenu' ),
// 			'compiler' => array(
// 				'.wh-header.is_stuck',
// 				'body.page-template-template-home-transparent-header .wh-header.is_stuck',
// 				'body.page-template-template-home-transparent-header-boxed .wh-header.is_stuck'
// 			),
// 			'all'      => false,
// 			'top'      => false,
// 			'right'    => false,
// 			'left'     => false,
// 			'default'  => array(
// 				'border-color'  => '#ebebeb',
// 				'border-style'  => 'solid',
// 				'border-bottom' => '1px',
// 			)
// 		),

// 	)
// ) );


}

