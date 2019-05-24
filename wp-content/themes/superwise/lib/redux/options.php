<?php

$opt_name = SUPERWISE_THEME_OPTION_NAME;

if ( ! class_exists( 'Redux' ) ) {
	return;
}

$other_settings = '';
$sass_vars_file = get_template_directory() . '/lib/redux/css/other-settings/vars.scss';
if ( file_exists( $sass_vars_file ) ) {
	ob_start();
	include $sass_vars_file;
	$other_settings = ob_get_clean();
}

// ----------------------------------
// -> General
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-general',
	'title'  => esc_html__( 'General Settings', 'superwise' ),
	'icon'   => 'el-icon-home',
	// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
	'fields' => array(
		array(
			'id'    => 'header-layout-block',
			'type'  => 'select',
			'title' => esc_html__( 'Header Layout Block', 'superwise' ),
			'data'  => 'posts',
			'args'  => array( 'post_type' => array( 'layout_block' ), 'posts_per_page' => - 1 ),
		),
		array(
			'id'    => 'header-layout-block-mobile',
			'type'  => 'select',
			'title' => esc_html__( 'Mobile Header Layout Block', 'superwise' ),
			'data'  => 'posts',
			'args'  => array( 'post_type' => array( 'layout_block' ), 'posts_per_page' => - 1 ),
		),
		array(
			'id'    => 'footer-layout-block',
			'type'  => 'select',
			'title' => esc_html__( 'Footer Layout Block', 'superwise' ),
			'data'  => 'posts',
			'args'  => array( 'post_type' => array( 'layout_block' ), 'posts_per_page' => - 1 ),
		),
		array(
			'id'    => 'quick-sidebar-layout-block',
			'type'  => 'select',
			'title' => esc_html__( 'Quick Sidebar Layout Block', 'superwise' ),
			'data'  => 'posts',
			'args'  => array( 'post_type' => array( 'layout_block' ), 'posts_per_page' => - 1 ),
		),
		array(
			'id'      => 'header-mobile-break-point',
			'type'    => 'spinner',
			'title'   => esc_html__( 'Header Mobile Show Bellow', 'superwise' ),
			'desc'    => esc_html__( 'Set the width of the screen in px bellow which the Mobile header is shown.', 'superwise' ),
			'default' => '767',
			'min'     => '50',
			'max'     => '2000',
			'step'    => '1',
		),
		array(
			'id'          => 'custom-thumbnail-sizes',
			'type'        => 'ace_editor',
			'title'       => esc_html__( 'Custom Thumbnail Sizes', 'superwise' ),
			'subtitle'    => esc_html__( 'Pipe separated list of custom thumbnail size names and sizes.', 'superwise' ),
			'description' => 'Please use this format: <br><strong>custom-thumbnail-size:500x500|another-custom-thumbnail-size:320x150</strong>. <br>No spaces allowed. Thumnail Sizes you register here will only be applied to any new image from now on. If you wish to apply them on any of the old images we recomend using <a href="http://wordpress.org/plugins/regenerate-thumbnails/">Regenerate Thumbnails Plugin</a>',
			'mode'        => 'text',
			'theme'       => 'monokai',
			'default'     => ""
		),

	),
) );
// -> End General

$accent_colors = Ed_Suite_Accent_Colors::get_redux_select_options();
// ----------------------------------
// -> Styling
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-styling',
	'icon'   => 'el-icon-website',
	'title'  => esc_html__( 'Styling', 'superwise' ),
	'fields' => array(
		array(
			'id'       => 'global-accent-color',
			'type'     => 'color',
			'title'    => esc_html__( 'Global Accent Color', 'superwise' ),
			'desc'     => esc_html__( 'This color will be used accross the site.', 'superwise' ),
			'compiler' => 'true',
			'default'  => '#ffc000',
			'validate' => 'color',
		),
		array(
			'id'       => 'global-accent-color-elements',
			'type'     => 'select',
			'multi'    => true,
			'title'    => __( 'Global Accent Color Elements', 'superwise' ),
			'desc'     => __( 'This is where you set what site elements will be affected by Global Accent Color.', 'superwise' ),
			'compiler' => 'true',
			'options'  => $accent_colors,
			'default'  => array_keys( $accent_colors ),
		),
		array(
			'id'       => 'global-accent-color-2',
			'type'     => 'color',
			'title'    => esc_html__( 'Global Accent Color 2', 'superwise' ),
			'desc'     => esc_html__( 'This color will be used accross the site.', 'superwise' ),
			'compiler' => 'true',
			'default'  => '#e6be1e',
			'validate' => 'color',
		),
		array(
			'id'       => 'global-accent-color-2-elements',
			'type'     => 'select',
			'multi'    => true,
			'title'    => __( 'Global Accent Color 2 Elements', 'superwise' ),
			'desc'     => __( 'This is where you set what site elements will be affected by Global Accent Color 2.', 'superwise' ),
			'compiler' => 'true',
			'options'  => $accent_colors,
			'default'  => array(),
		),
		array(
			'id'       => 'custom-css',
			'type'     => 'ace_editor',
			'title'    => esc_html__( 'Custom CSS Code', 'superwise' ),
			'subtitle' => esc_html__( 'Paste your CSS code here.', 'superwise' ),
			'compiler' => 'true',
			'mode'     => 'css',
			'theme'    => 'monokai',
			'default'  => '',
			'options'  => array(
				'minLines' => 50
			),
		),
	)
) );
// -> End Styling

// ----------------------------------
// -> Body
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-body',
	'title'  => esc_html__( 'Body', 'superwise' ),
	'icon'   => 'el-icon-check-empty',
	'fields' => array(
		array(
			'id'       => 'container-width',
			'type'     => 'dimensions',
			'units'    => array( 'px' ),
			'title'    => esc_html__( 'Container Width', 'superwise' ),
			'compiler' => array( '.cbp-container', '#tribe-events-pg-template' ),
			'height'   => false,
			'mode'     => 'max-width',
			'default'  => array(
				'width' => '980',
				'units' => 'px',
			),
		),
		array(
			'id'       => 'boxed-outer-container-width',
			'type'     => 'dimensions',
			'units'    => array( 'px' ),
			'title'    => esc_html__( 'Boxed Outer Container Width', 'superwise' ),
			'subtitle' => esc_html__( 'This is only applicable when "Boxed" page template is used.', 'superwise' ),
			'compiler' => array( '.wh-main-wrap' ),
			'height'   => false,
			'mode'     => 'max-width',
			'default'  => array(
				'width' => '1100',
				'units' => 'px',
			),
		),
		array(
			'id'       => 'body-background',
			'type'     => 'background',
			'compiler' => array( 'body' ),
			'title'    => esc_html__( 'Background', 'superwise' ),
		),
		array(
			'id'             => 'body-typography',
			'type'           => 'typography',
			'title'          => esc_html__( 'Font', 'superwise' ),
			'subtitle'       => esc_html__( 'Specify the body font properties.', 'superwise' ),
			'google'         => true,
			'text-align'     => false,
			'letter-spacing' => true,
			'compiler'       => array( 'body' ),
			'default'        => array(
				'color'       => '#333',
				'font-size'   => '14px',
				'line-height' => '20px',
				'font-family' => 'Arial,Helvetica,sans-serif',
				'font-weight' => 'Normal',
			),
		),
		array(
			'id'       => 'body-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Link Color', 'superwise' ),
			'compiler' => array( 'a' ),
			'default'  => array(
				'regular' => '#353434',
				'hover'   => '#585757',
				'active'  => '#353434',
			)
		),
		array(
			'id'             => 'main-padding',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-padding', '#tribe-events-pg-template' ),
			'mode'           => 'padding',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Padding', 'superwise' ),
			'desc'           => esc_html__( 'This is where you select a padding for all layout elements. For widgets compiled from a page you need to set the padding on each widget.', 'superwise' ),
			'default'        => array(
				'padding-top'    => '20px',
				'padding-right'  => '20px',
				'padding-bottom' => '20px',
				'padding-left'   => '20px',
				'units'          => 'px',
			)
		),
	)
) );


Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-body-headings',
	'title'      => esc_html__( 'Headings', 'superwise' ),
	'fields'     => array(
		array(
			'id'             => 'headings-typography-h1',
			'type'           => 'typography',
			'title'          => esc_html__( 'H1', 'superwise' ),
			'google'         => true,
			'text-align'     => false,
			'letter-spacing' => true,
			'compiler'       => array( 'h1', 'h1 a' ),
			'default'        => array(
				'font-size'   => '48px',
				'line-height' => '52px',
			),
		),
		array(
			'id'             => 'headings-margin-h1',
			'type'           => 'spacing',
			'compiler'       => array( 'h1', 'h1 a' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'H1 Margin', 'superwise' ),
			'default'        => array(
				'margin-top'    => '33px',
				'margin-right'  => 0,
				'margin-bottom' => '33px',
				'margin-left'   => 0,
				'units'         => 'px',
			)
		),
		array(
			'id'             => 'headings-typography-h2',
			'type'           => 'typography',
			'title'          => esc_html__( 'H2', 'superwise' ),
			'google'         => true,
			'text-align'     => false,
			'letter-spacing' => true,
			'compiler'       => array( 'h2', 'h2 a' ),
			'default'        => array(
				'font-size'   => '30px',
				'line-height' => '34px',
			),
		),
		array(
			'id'             => 'headings-margin-h2',
			'type'           => 'spacing',
			'compiler'       => array( 'h2', 'h2 a' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'H2 Margin', 'superwise' ),
			'default'        => array(
				'margin-top'    => '25px',
				'margin-right'  => 0,
				'margin-bottom' => '25px',
				'margin-left'   => 0,
				'units'         => 'px',
			)
		),
		array(
			'id'             => 'headings-typography-h3',
			'type'           => 'typography',
			'title'          => esc_html__( 'H3', 'superwise' ),
			'google'         => true,
			'text-align'     => false,
			'letter-spacing' => true,
			'compiler'       => array( 'h3', 'h3 a' ),
			'default'        => array(
				'font-size'   => '22px',
				'line-height' => '24px',
			),
		),
		array(
			'id'             => 'headings-margin-h3',
			'type'           => 'spacing',
			'compiler'       => array( 'h3', 'h3 a' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'H3 Margin', 'superwise' ),
			'default'        => array(
				'margin-top'    => '22px',
				'margin-right'  => 0,
				'margin-bottom' => '22px',
				'margin-left'   => 0,
				'units'         => 'px',
			)
		),
		array(
			'id'             => 'headings-typography-h4',
			'type'           => 'typography',
			'title'          => esc_html__( 'H4', 'superwise' ),
			'google'         => true,
			'text-align'     => false,
			'letter-spacing' => true,
			'compiler'       => array( 'h4', 'h4 a' ),
			'default'        => array(
				'font-size'   => '20px',
				'line-height' => '24px',
			),
		),
		array(
			'id'             => 'headings-margin-h4',
			'type'           => 'spacing',
			'compiler'       => array( 'h4', 'h4 a' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'H4 Margin', 'superwise' ),
			'default'        => array(
				'margin-top'    => '25px',
				'margin-right'  => 0,
				'margin-bottom' => '25px',
				'margin-left'   => 0,
				'units'         => 'px',
			)
		),
		array(
			'id'             => 'headings-typography-h5',
			'type'           => 'typography',
			'title'          => esc_html__( 'H5', 'superwise' ),
			'google'         => true,
			'text-align'     => false,
			'letter-spacing' => true,
			'compiler'       => array( 'h5', 'h5 a' ),
			'default'        => array(
				'font-size'   => '18px',
				'line-height' => '22px',
			),
		),
		array(
			'id'             => 'headings-margin-h5',
			'type'           => 'spacing',
			'compiler'       => array( 'h5', 'h5 a' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'H5 Margin', 'superwise' ),
			'default'        => array(
				'margin-top'    => '30px',
				'margin-right'  => 0,
				'margin-bottom' => '30px',
				'margin-left'   => 0,
				'units'         => 'px',
			)
		),
		array(
			'id'             => 'headings-typography-h6',
			'type'           => 'typography',
			'title'          => esc_html__( 'H6', 'superwise' ),
			'google'         => true,
			'text-align'     => false,
			'letter-spacing' => true,
			'compiler'       => array( 'h6', 'h6 a' ),
			'default'        => array(
				'font-size'   => '16px',
				'line-height' => '20px',
			),
		),
		array(
			'id'             => 'headings-margin-h6',
			'type'           => 'spacing',
			'compiler'       => array( 'h6', 'h6 a' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'H6 Margin', 'superwise' ),
			'default'        => array(
				'margin-top'    => '36px',
				'margin-right'  => 0,
				'margin-bottom' => '36px',
				'margin-left'   => 0,
				'units'         => 'px',
			)
		),
	)
) );
// -> End Body

// ----------------------------------
// -> Header
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'header',
	'title'  => esc_html__( 'Header', 'superwise' ),
	'icon'   => 'el-icon-delicious',
	'fields' => array(
		array(
			'id'      => 'header-location',
			'type'    => 'select',
			'title'   => esc_html__( 'Header Location', 'superwise' ),
			'options' => array(
				'top'           => 'Top',
				'left'          => 'Left',
			),
			'default' => 'top',
		),
		array(
			'id'       => 'header-background',
			'type'     => 'background',
			'compiler' => array( '.wh-header' ),
			'title'    => esc_html__( 'Background', 'superwise' ),
			'subtitle' => esc_html__( 'Pick a background color for the header', 'superwise' ),
			'default'  => array(
				'background-color' => '#fff'
			),
		),
		array(
			'id'       => 'logo',
			'type'     => 'media',
			'title'    => esc_html__( 'Logo', 'superwise' ),
			'url'      => true,
			'mode'     => false, // Can be set to false to allow any media type, or can also be set to any mime type.
			'subtitle' => esc_html__( 'Upload logo', 'superwise' ),

		),
		array(
			'id'       => 'header-border',
			'type'     => 'border',
			'title'    => __( 'Header Border Bottom', 'superwise' ),
			'compiler' => array( '.wh-header' ),
			'all'      => false,
			'top'      => false,
			'right'    => false,
			'left'     => false,
			'default'  => array(
				'border-color'  => '#ebebeb',
				'border-style'  => 'solid',
				'border-bottom' => '1px',
			)
		),
		array(
			'id'      => 'main-menu-alignment',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Menu Alignment', 'superwise' ),
			'options' => array(
				'left'   => 'Left',
				'center' => 'Center',
				'right'  => 'Right',
			),
			'default' => 'right',
		),
		array(
			'id'      => 'header-padding-override',
			'type'    => 'switch',
			'title'   => esc_html__( 'Override Header Padding', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'             => 'header-padding',
			'type'           => 'spacing',
			'compiler'       => array(
				'.wh-header',
			),
			'mode'           => 'padding',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Header Padding', 'superwise' ),
			'default'        => array(
				'padding-top'    => '5px',
				'padding-right'  => '20px',
				'padding-bottom' => '5px',
				'padding-left'   => '20px',
				'units'          => 'px',
			),
			'required'       => array(
				array( 'header-padding-override', 'equals', '1' ),
			),

		),
	)
) );


Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-header-main-menu',
	'title'      => esc_html__( 'Main Menu', 'superwise' ),
	'fields'     => array(
		array(
			'id'             => 'menu-main-top-level-typography',
			'type'           => 'typography',
			'title'          => esc_html__( 'Top Level Items Typography', 'superwise' ),
			'google'         => true,    // Disable google fonts. Won't work if you haven't defined your google api key
			'font-backup'    => true,    // Select a backup non-google font in addition to a google font
			'color'          => false,
			'text-transform' => true,
			'all_styles'     => true,    // Enable all Google Font style/weight variations to be added to the page
			'letter-spacing' => true,
			'compiler'       => array(
				'.sf-menu.wh-menu-main a',
				'.respmenu li a',
			), // An array of CSS selectors to apply this font style to dynamically
			'units'          => 'px', // Defaults to px
			'default'        => array(
				'font-style'  => '700',
				'font-family' => 'Abel',
				'google'      => true,
				'font-size'   => '18px',
				'line-height' => '24px'
			),
		),
		array(
			'id'             => 'menu-main-sub-items-typography',
			'type'           => 'typography',
			'title'          => esc_html__( 'Subitems Typography', 'superwise' ),
			'google'         => true,
			// Disable google fonts. Won't work if you haven't defined your google api key
			'font-backup'    => true,
			// Select a backup non-google font in addition to a google font
			'color'          => false,
			'text-transform' => true,
			'all_styles'     => true,
			'letter-spacing' => true,
			// Enable all Google Font style/weight variations to be added to the page
			'compiler'       => array( '.sf-menu.wh-menu-main ul li a' ),
			// An array of CSS selectors to apply this font style to dynamically
			'units'          => 'px',
			// Defaults to px
			'default'        => array(
				'font-style'  => '700',
				'font-family' => 'Abel',
				'google'      => true,
				'font-size'   => '16px',
				'line-height' => '24px'
			),
		),
		array(
			'id'       => 'main-menu-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Menu Item Link Color', 'superwise' ),
			'active'   => false, // Disable Active Color
			'compiler' => array(
				'.sf-menu.wh-menu-main a',
				'.respmenu li a',
				'.cbp-respmenu-more',
				'.wh-quick-sidebar-toggler i',
				'.wh-search-toggler i',
			),
			'default'  => array(
				'regular' => '#000',
				'hover'   => '#333',
			),
		),
		array(
			'id'       => 'main-menu-menu-item-hover-background',
			'type'     => 'background',
			'compiler' => array( '.sf-menu.wh-menu-main > li:hover, .sf-menu.wh-menu-main > li.sfHover' ),
			'title'    => esc_html__( 'Menu Item Hover Background', 'superwise' ),
			'subtitle' => esc_html__( 'Pick a background color for the menu item on hover.', 'superwise' ),
		),
		array(
			'id'       => 'main-menu-current-item-background',
			'type'     => 'background',
			'compiler' => array(
				'.sf-menu.wh-menu-main .current-menu-item',
				'.respmenu_current'
			),
			'title'    => esc_html__( 'Current Menu Item Background', 'superwise' ),
			'subtitle' => esc_html__( 'Pick a background color for the current menu item.', 'superwise' ),
		),
		array(
			'id'       => 'main-menu-current-item-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Current Menu Item Link Color', 'superwise' ),
			'active'   => false, // Disable Active Color
			'compiler' => array( '.sf-menu.wh-menu-main .current-menu-item > a' ),
			'default'  => array(
				'regular' => '#000',
				'hover'   => '#333',
			),
		),
		array(
			'id'       => 'main-menu-submenu-current-item-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Current Menu Item Submenu Link Color', 'superwise' ),
			'active'   => false, // Disable Active Color
			'compiler' => array( '.sf-menu.wh-menu-main .sub-menu .current-menu-item > a' ),
			'default'  => array(
				'regular' => '#000',
				'hover'   => '#333',
			),
		),
		array(
			'id'       => 'main-menu-submenu-item-background',
			'type'     => 'background',
			'compiler' => array(
				'.sf-menu.wh-menu-main ul li',
				'.sf-menu.wh-menu-main .sub-menu',
			),
			'title'    => esc_html__( 'Submenu Menu Item Background', 'superwise' ),
			'default'  => array(
				'background-color' => '#fff',
			),
		),
		array(
			'id'       => 'main-menu-submenu-item-hover-background',
			'type'     => 'background',
			'compiler' => array( '.sf-menu.wh-menu-main ul li:hover, .sf-menu.wh-menu-main ul ul li:hover' ),
			'title'    => esc_html__( 'Submenu Item Hover Background', 'superwise' ),
			'subtitle' => esc_html__( 'Pick a background color for the menu item on hover.', 'superwise' ),
		),
		array(
			'id'       => 'main-menu-submenu-item-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Submenu Item Link Color', 'superwise' ),
			'active'   => false, // Disable Active Color
			'compiler' => array(
				'.sf-menu.wh-menu-main .sub-menu li a',
				'.sf-menu.wh-menu-main .sub-menu li.menu-item-has-children:after',
			),
			'default'  => array(
				'regular' => '#000',
				'hover'   => '#333',
			),
		),
		array(
			'id'             => 'main-menu-padding',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-menu-main' ),
			'mode'           => 'padding',
			'units'          => array( 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Padding Top/Bottom', 'superwise' ),
			'description'    => esc_html__( 'Use it to better vertical align the menu', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'padding-top'    => '0',
				'padding-bottom' => '0',
				'units'          => 'px',
			),
		),
		array(
			'id'          => 'main-menu-initial-waypoint-compensation',
			'type'        => 'text',
			'title'       => esc_html__( 'Initial Waypoint Scroll Compensation', 'superwise' ),
			'description' => esc_html__( 'Enter number only.', 'superwise' ),
			'validate'    => 'number',
			'default'     => 120
		),


	)
) );

Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-header-responsive-menu',
	'title'      => esc_html__( 'Mobile Header', 'superwise' ),
	'fields'     => array(
		array(
			'id'       => 'respmenu-logo',
			'type'     => 'media',
			'title'    => esc_html__( 'Logo', 'superwise' ),
			'url'      => true,
			'mode'     => false, // Can be set to false to allow any media type, or can also be set to any mime type.
			'subtitle' => esc_html__( 'Set logo image', 'superwise' ),
		),
		array(
			'id'       => 'respmenu-logo-dimensions',
			'type'     => 'dimensions',
			'units'    => array( 'em', 'px', '%' ),
			'title'    => esc_html__( 'Logo Dimensions (Width/Height)', 'superwise' ),
			'compiler' => array( '.respmenu-header .respmenu-header-logo-link img' ),
		),
		array(
			'id'       => 'respmenu-background',
			'type'     => 'background',
			'title'    => esc_html__( 'Background', 'superwise' ),
			'compiler' => array( '.header-mobile' ),
			'default'  => array(
				'background-color' => '#fff',
			),
		),
		array(
			'id'       => 'respmenu-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Menu Link Color', 'superwise' ),
			'compiler' => array(
				'.respmenu li a',
				'.cbp-respmenu-more'
			),
			'active'   => false,
			'visited'  => false,
			'default'  => array(
				'regular' => '#000', // blue
				'hover'   => '#333', // red
			)
		),
		array(
			'id'          => 'respmenu-display-switch-color',
			'type'        => 'color',
			'mode'        => 'border-color',
			'title'       => esc_html__( 'Display Toggle Color', 'superwise' ),
			'compiler'    => array( '.respmenu-open hr' ),
			'transparent' => false,
			'default'     => '#000',
			'validate'    => 'color',
		),
		array(
			'id'          => 'respmenu-display-switch-color-hover',
			'type'        => 'color',
			'mode'        => 'border-color',
			'title'       => esc_html__( 'Display Toggle Hover Color', 'superwise' ),
			'compiler'    => array( '.respmenu-open:hover hr' ),
			'transparent' => false,
			'default'     => '#999',
			'validate'    => 'color',
		),
		array(
			'id'       => 'respmenu-display-switch-img',
			'type'     => 'media',
			'title'    => esc_html__( 'Display Toggle Image', 'superwise' ),
			'url'      => true,
			'mode'     => false, // Can be set to false to allow any media type, or can also be set to any mime type.
			'subtitle' => esc_html__( 'Set the image to replace default 3 lines for menu toggle button.', 'superwise' ),
		),
		array(
			'id'       => 'respmenu-display-switch-img-dimensions',
			'type'     => 'dimensions',
			'units'    => array( 'em', 'px', '%' ),
			'title'    => esc_html__( 'Display Toggle Image Dimensions (Width/Height)', 'superwise' ),
			'compiler' => array( '.respmenu-header .respmenu-open img' ),
		),
	)
) );
Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-sticky-header',
	'title'      => esc_html__( 'Sticky Header', 'superwise' ),
	'fields'     => array(
		array(
			'id'      => 'main-menu-use-menu-is-sticky',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable Sticky Menu', 'superwise' ),
			'default' => 1,
		),
		array(
			'id'       => 'main-menu-sticky-background',
			'type'     => 'background',
			'title'    => esc_html__( 'Sticky Menu Background', 'superwise' ),
			'compiler' => array(
				'.is-sticky .sticky-bar',
				'.wh-header.is_stuck',
				'body.page-template-template-home-transparent-header .wh-header.is_stuck',
				'body.page-template-template-home-transparent-header-boxed .wh-header.is_stuck',
			),
			'default'  => array(
				'background-color' => '#fff',
			),
			'required' => array(
				array( 'main-menu-use-menu-is-sticky', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'main-menu-sticky-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Sticky Menu Link Color', 'superwise' ),
			'compiler' => array(
				'.wh-header.is_stuck .sf-menu.wh-menu-main > li > a',
			),
			'active'   => false,
			'visited'  => false,
			'default'  => array(
				'regular' => '#000', // blue
				'hover'   => '#333', // red
			)
		),
		array(
			'id'             => 'main-menu-sticky-padding',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-sticky-header .wh-menu-main' ),
			'mode'           => 'padding',
			'units'          => array( 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Sticky Menu Padding', 'superwise' ),
			'description'    => esc_html__( 'Use it to better vertical align the menu', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'padding-top'    => '0',
				'padding-bottom' => '0',
				'units'          => 'px',
			),
			'required'       => array(
				array( 'main-menu-use-menu-is-sticky', 'equals', '1' ),
			)
		),
		array(
			'id'       => 'main-menu-sticky-border',
			'type'     => 'border',
			'title'    => __( 'Sticky Header Border Bottom', 'superwise' ),
			'compiler' => array(
				'.wh-header.is_stuck',
				'body.page-template-template-home-transparent-header .wh-header.is_stuck',
				'body.page-template-template-home-transparent-header-boxed .wh-header.is_stuck',
				'.wh-header .is-sticky .sticky-bar',
				'body.page-template-template-home-transparent-header  .is-sticky .sticky-bar',
				'body.page-template-template-home-transparent-header-boxed  .is-sticky .sticky-bar',
			),
			'all'      => false,
			'top'      => false,
			'right'    => false,
			'left'     => false,
			'default'  => array(
				'border-color'  => '#ebebeb',
				'border-style'  => 'solid',
				'border-bottom' => '1px',
			)
		),

	)
) );

Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-mega-menu',
	'title'      => esc_html__( 'Mega Menu', 'superwise' ),
	'fields'     => array(
		array(
			'compiler' => true,
			'id'       => 'mega-menu-offset-top',
			'type'     => 'text',
			'title'    => __( 'Offset Top', 'superwise' ),
			'desc'     => __( 'Value in px. Enter number only.', 'superwise' ),
			'validate' => 'number',
			'msg'      => 'Enter number only',
			'default'  => '40'
		),
		array(
			'compiler' => true,
			'id'       => 'mega-menu-top-hover-area',
			'type'     => 'text',
			'title'    => __( 'Submenu Top Hover Area', 'superwise' ),
			'subtitle' => __( 'The space above the submenu that when hovered on will keep the submenu open.', 'superwise' ),
			'desc'     => __( 'Value in px. Enter number only.', 'superwise' ),
			'validate' => 'number',
			'msg'      => 'Enter number only',
			'default'  => '25'
		),
	)
) );
// -> End Header

// ----------------------------------
// -> Quick Sidebar
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-quick-sidebar',
	'title'  => esc_html__( 'Quick Sidebar', 'superwise' ),
	'icon'   => 'el-icon-cog',
	'fields' => array(
		array(
			'id'      => 'quick-sidebar-enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable Quick Sidebar', 'superwise' ),
			'default' => true,
		),
	),
) );

// ----------------------------------
// -> Page Title
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-page-title',
	'title'  => esc_html__( 'Page Title', 'superwise' ),
	'icon'   => 'el-icon-font',
	'fields' => array(
		array(
			'id'      => 'page-title-layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Layout', 'superwise' ),
			'options' => array(
				'default'    => 'Default',
				'background_only' => 'Background Only',
			),
			'default' => 'default',
		),
		array(
			'id'       => 'page-title-background',
			'type'     => 'background',
			'compiler' => array( '.wh-page-title-bar' ),
			'title'    => esc_html__( 'Background', 'superwise' ),
			'subtitle' => esc_html__( 'Pick a background color for the page title.', 'superwise' ),
			'default'  => array(
				'background-color' => '#bfbfbf'
			),
		),
		array(
			'id'       => 'page-title-min-height',
			'type'     => 'dimensions',
			'units'    => array( 'px' ),
			'title'    => esc_html__( 'Min Height', 'superwise' ),
			'compiler' => array( '.wh-page-title-bar' ),
			'height'   => false, // width is only used when using mode 
			'mode'     => 'min-height',
			'default'  => array(
				'width' => '100',
				'units' => 'px',
			),
		),
		array(
			'id'             => 'page-title-typography',
			'type'           => 'typography',
			'title'          => esc_html__( 'Page Title Font', 'superwise' ),
			'subtitle'       => esc_html__( 'Specify the page title font properties.', 'superwise' ),
			'google'         => true,
			'text-align'     => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'compiler'       => array( 'h1.page-title' ),
			'default'        => array(
				'color'       => '#333',
				'font-size'   => '48px',
				'line-height' => '48px',
				'font-family' => 'Arial,Helvetica,sans-serif',
				'font-weight' => 'Normal',
			),
		),
		array(
			'id'             => 'page-title-spacing',
			'type'           => 'spacing',
			'compiler'       => array( '.page-title' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Page Title Margin', 'superwise' ),
			'default'        => array(
				'margin-top'    => '33px',
				'margin-right'  => '0px',
				'margin-bottom' => '33px',
				'margin-left'   => '0px',
				'units'         => 'px',
			),

		),
		array(
			'id'      => 'page-title-wrapper-padding-override',
			'type'    => 'switch',
			'title'   => esc_html__( 'Override Page Title Wrapper Padding', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'             => 'page-title-wrapper-padding',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-page-title-wrapper' ),
			'mode'           => 'padding',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Page Title Wrapper Padding', 'superwise' ),
			'default'        => array(
				'padding-top'    => '5px',
				'padding-right'  => '20px',
				'padding-bottom' => '5px',
				'padding-left'   => '20px',
				'units'          => 'px',
			),
			'required'       => array(
				array( 'page-title-wrapper-padding-override', 'equals', '1' ),
			),

		),
	),
) );

Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-page-title-breadcrumbs',
	'title'      => esc_html__( 'Breadcrumbs', 'superwise' ),
	'fields'     => array(
		array(
			'id'      => 'page-title-breadcrumbs-enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable', 'superwise' ),
			'default' => true,
		),
		array(
			'id'       => 'page-title-breadcrumbs-position',
			'type'     => 'button_set',
			'title'    => esc_html__( 'Position', 'superwise' ),
			'options'  => array(
				'above_title'  => 'Above the title',
				'bellow_title' => 'Bellow the title',
			),
			'default'  => 'bellow_title',
			'required' => array(
				array( 'page-title-breadcrumbs-enable', 'equals', '1' ),
			),
		),
		array(
			'id'             => 'page-title-breadcrumbs-typography',
			'type'           => 'typography',
			'title'          => esc_html__( 'Font', 'superwise' ),
			'google'         => true,
			'font-backup'    => true,
			'text-transform' => true,
			'compiler'       => array( '.wh-breadcrumbs' ),
			'units'          => 'px',
			'default'        => array(
				'color'       => '#333',
				'font-style'  => '700',
				'font-family' => 'Abel',
				'google'      => true,
				'font-size'   => '14px',
				'line-height' => '10px'
			),
			'required'       => array(
				array( 'page-title-breadcrumbs-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'page-title-breadcrumbs-link-color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Links Color', 'superwise' ),
			'active'   => false,
			'compiler' => array( '.wh-breadcrumbs a' ),
			'default'  => array(
				'regular' => '#333',
				'hover'   => '#999',
			),
			'required' => array(
				array( 'page-title-breadcrumbs-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'page-title-breadcrumbs-alignment',
			'type'     => 'button_set',
			'title'    => esc_html__( 'Alignment', 'superwise' ),
			'options'  => array(
				'left'   => 'Left',
				'center' => 'Center',
				'right'  => 'Right',
			),
			'default'  => 'left',
			'required' => array(
				array( 'page-title-breadcrumbs-enable', 'equals', '1' ),
			),
		),
		array(
			'id'             => 'page-title-breadcrumbs-padding',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-breadcrumbs-wrapper' ),
			'mode'           => 'padding',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Padding', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'padding-top'    => '20',
				'padding-bottom' => '20',
				'units'          => 'px',
			),
			'required'       => array(
				array( 'page-title-breadcrumbs-enable', 'equals', '1' ),
			),
		),
	)
) );

Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-page-title-embellishments',
	'title'      => esc_html__( 'Embellishments', 'superwise' ),
	'fields'     => array(
		array(
			'id'      => 'page-title-embellishments-enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable', 'superwise' ),
			'default' => false,
		),
		array(
			'id'       => 'page-title-embellishment-background-top',
			'type'     => 'background',
			'compiler' => array( '.wh-embellishment-page-title-top' ),
			'title'    => esc_html__( 'Embellishment Top Background', 'superwise' ),
			'required' => array(
				array( 'page-title-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'page-title-embellishment-background-top-dimensions',
			'type'     => 'dimensions',
			'units'    => array( 'em', 'px', '%' ),
			'title'    => esc_html__( 'Embellishment Top Container Height', 'superwise' ),
			'compiler' => array( '.wh-embellishment-page-title-top' ),
			'width'    => false,
			'default'  => array(
				'height' => '20',
				'units'  => 'px'
			),
			'required' => array(
				array( 'page-title-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'             => 'page-title-embellishment-background-top-margin',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-embellishment-page-title-top' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Embellishment Top Container Margin', 'superwise' ),
			'desc'           => esc_html__( 'Use negative top margin to pull it up.', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'margin-top'    => '0',
				'margin-bottom' => '0',
				'units'         => 'px',
			),
			'required'       => array(
				array( 'page-title-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'page-title-embellishment-background-bottom',
			'type'     => 'background',
			'compiler' => array( '.wh-embellishment-page-title-bottom' ),
			'title'    => esc_html__( 'Embellishment Bottom Background', 'superwise' ),
			'required' => array(
				array( 'page-title-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'page-title-embellishment-background-bottom-dimensions',
			'type'     => 'dimensions',
			'units'    => array( 'em', 'px', '%' ),
			'title'    => esc_html__( 'Embellishment Bottom Container Height', 'superwise' ),
			'compiler' => array( '.wh-embellishment-page-title-bottom' ),
			'width'    => false,
			'default'  => array(
				'height' => '20',
				'units'  => 'px'
			),
			'required' => array(
				array( 'page-title-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'             => 'page-title-embellishment-background-bottom-margin',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-embellishment-page-title-bottom' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Embellishment Bottom Container Margin', 'superwise' ),
			'desc'           => esc_html__( 'Use negative bottom margin to pull it down.', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'margin-top'    => '0',
				'margin-bottom' => '0',
				'units'         => 'px',
			),
			'required'       => array(
				array( 'page-title-embellishments-enable', 'equals', '1' ),
			),
		),

	)
) );
// -> End Page Title

// ----------------------------------
// -> Content
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-content',
	'title'  => esc_html__( 'Content', 'superwise' ),
	'icon'   => 'el-icon-file-edit',
	'fields' => array(
		array(
			'id'       => 'content-background',
			'type'     => 'background',
			'compiler' => array( '.wh-content' ),
			'title'    => esc_html__( 'Background', 'superwise' ),
			'subtitle' => esc_html__( 'Pick a background color for the content', 'superwise' ),
		),
		array(
			'id'             => 'content-padding',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-content' ),
			'mode'           => 'padding',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Padding', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'padding-top'    => '20',
				'padding-bottom' => '20',
				'units'          => 'px',
			)
		),
		array(
			'id'            => 'content-width',
			'type'          => 'slider',
			'title'         => esc_html__( 'Content Width', 'superwise' ),
			'subtitle'      => esc_html__( 'Drag the slider to change menu width grid steps.', 'superwise' ),
			'desc'          => esc_html__( 'The grid has 12 steps.', 'superwise' ),
			'default'       => 9,
			'min'           => 1,
			'step'          => 1,
			'max'           => 12,
			'display_value' => 'label'
		),
		array(
			'id'            => 'sidebar-width',
			'type'          => 'slider',
			'title'         => esc_html__( 'Sidebar Width', 'superwise' ),
			'subtitle'      => esc_html__( 'Drag the slider to change menu width grid steps.', 'superwise' ),
			'desc'          => esc_html__( 'The grid has 12 steps.', 'superwise' ),
			'default'       => 3,
			'min'           => 1,
			'step'          => 1,
			'max'           => 12,
			'display_value' => 'label'
		),
	),
) );

Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-content-embellishments',
	'title'      => esc_html__( 'Embellishments', 'superwise' ),
	'fields'     => array(
		array(
			'id'      => 'content-embellishments-enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable', 'superwise' ),
			'default' => false,
		),
		array(
			'id'       => 'content-embellishment-background-top',
			'type'     => 'background',
			'compiler' => array( '.wh-embellishment-content-top' ),
			'title'    => esc_html__( 'Embellishment Top Background', 'superwise' ),
			'required' => array(
				array( 'content-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'content-embellishment-background-top-dimensions',
			'type'     => 'dimensions',
			'units'    => array( 'em', 'px', '%' ),
			'title'    => esc_html__( 'Embellishment Top Container Height', 'superwise' ),
			'compiler' => array( '.wh-embellishment-content-top' ),
			'width'    => false,
			'default'  => array(
				'height' => '20',
				'units'  => 'px'
			),
			'required' => array(
				array( 'content-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'             => 'content-embellishment-background-top-margin',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-embellishment-content-top' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Embellishment Top Container Margin', 'superwise' ),
			'desc'           => esc_html__( 'Use negative top margin to pull it up.', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'margin-top'    => '0',
				'margin-bottom' => '0',
				'units'         => 'px',
			),
			'required'       => array(
				array( 'content-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'content-embellishment-background-bottom',
			'type'     => 'background',
			'compiler' => array( '.wh-embellishment-content-bottom' ),
			'title'    => esc_html__( 'Embellishment Bottom Background', 'superwise' ),
			'required' => array(
				array( 'content-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'content-embellishment-background-bottom-dimensions',
			'type'     => 'dimensions',
			'units'    => array( 'em', 'px', '%' ),
			'title'    => esc_html__( 'Embellishment Bottom Container Height', 'superwise' ),
			'compiler' => array( '.wh-embellishment-content-bottom' ),
			'width'    => false,
			'default'  => array(
				'height' => '20',
				'units'  => 'px'
			),
			'required' => array(
				array( 'content-embellishments-enable', 'equals', '1' ),
			),
		),
		array(
			'id'             => 'content-embellishment-background-bottom-margin',
			'type'           => 'spacing',
			'compiler'       => array( '.wh-embellishment-content-bottom' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Embellishment Bottom Container Margin', 'superwise' ),
			'desc'           => esc_html__( 'Use negative bottom margin to pull it up.', 'superwise' ),
			'left'           => false,
			'right'          => false,
			'default'        => array(
				'margin-top'    => '0',
				'margin-bottom' => '0',
				'units'         => 'px',
			),
			'required'       => array(
				array( 'content-embellishments-enable', 'equals', '1' ),
			),
		),

	)
) );
// -> End Content

// ----------------------------------
// -> Blog Archive
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-blog-archive',
	'title'  => esc_html__( 'Blog/Archive', 'superwise' ),
	'icon'   => 'el-icon-file',
	'fields' => array(
		array(
			'id'       => 'post-excerpt-length',
			'type'     => 'text',
			'title'    => esc_html__( 'Post Excerpt Length', 'superwise' ),
			'subtitle' => esc_html__( 'This setting will be applied to any section using post excerpt', 'superwise' ),
			'validate' => 'numeric',
			'msg'      => 'You must enter a number.',
			'default'  => 20
		),
		array(
			'id'    => 'blog-archive-subtitle',
			'type'  => 'text',
			'title' => esc_html__( 'Archive Page Subtitle', 'superwise' ),
		),
		array(
			'id'      => 'blog-archive-layout',
			'type'    => 'select',
			'title'   => __( 'Archive Layout', 'superwise' ),
			'options' => array(
				'default'         => 'Default',
				'fullwidth'       => 'Fullwidth',
				'boxed'           => 'Boxed',
				'boxed-fullwidth' => 'Boxed Fullwidth',
			),
			'default' => 'default',
		)
	)
) );

Redux::setSection( $opt_name, array(
	'id'         => 'section-blog-archive-single',
	'title'      => esc_html__( 'Blog/Archive Single', 'superwise' ),
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'single-post-is-boxed',
			'type'    => 'switch',
			'title'   => esc_html__( 'Is Boxed?', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'      => 'single-post-sidebar-left',
			'type'    => 'switch',
			'title'   => esc_html__( 'Sidebar on the Left?', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'      => 'archive-single-use-share-this',
			'type'    => 'switch',
			'title'   => esc_html__( 'Use Share This buttons?', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'      => 'archive-single-use-page-title',
			'type'    => 'switch',
			'title'   => esc_html__( 'Use Page Title?', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),

	)
) );
// -> End Blog Archive


// ----------------------------------
// -> Search Page
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-search-page',
	'title'  => esc_html__( 'Search Page', 'superwise' ),
	'icon'   => 'el-icon-search',
	'fields' => array(
		array(
			'id'      => 'search-page-use-sidebar',
			'type'    => 'switch',
			'title'   => esc_html__( 'Use Sidebar?', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'       => 'search-page-items-per-page',
			'type'     => 'text',
			'title'    => esc_html__( 'Items Per Page', 'superwise' ),
			'validate' => 'numeric',
			'msg'      => 'You must enter a number.',
			'default'  => 10
		),

	)
) );
// -> End Search Page


// ----------------------------------
// -> Footer
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-footer',
	'title'  => esc_html__( 'Footer', 'superwise' ),
	'icon'   => 'el-icon-credit-card',
	'fields' => array(
		array(
			'id'       => 'footer-background',
			'type'     => 'background',
			'compiler' => array( '.wh-footer' ),
			'title'    => esc_html__( 'Background', 'superwise' ),
			'subtitle' => esc_html__( 'Pick a background color for the footer.', 'superwise' ),
			'default'  => array(
				'background-color' => '#fff'
			),
		),
	)
) );


// -> End Footer

// ----------------------------------
// -> Misc
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-misc',
	'title'  => esc_html__( 'Misc', 'superwise' ),
	'icon'   => 'el-icon-website',
	'fields' => array(
		array(
			'id'      => 'gmaps_api_key',
			'type'    => 'text',
			'title'   => esc_html__( 'Google Maps API Key', 'superwise' ),
			'default' => '',
			'desc'    => esc_html__( 'Enter GMaps API key', 'superwise' ),
		),
		array(
			'id'      => 'preloader',
			'type'    => 'select',
			'title'   => __( 'Select Preloader Spinner', 'superwise' ),
			'options' => array(
				'0' => 'None',
				'1' => 'Spinner 1',
				'2' => 'Spinner 2',
				'3' => 'Spinner 3',
				'4' => 'Spinner 4',
				'5' => 'Spinner 5',
				'6' => 'Spinner 6',
				'7' => 'Spinner 7',
			),
			'default' => '0',
		),
		array(
			'id'       => 'preloader-bg-color',
			'type'     => 'color',
			'title'    => __( 'Preloader Background Color', 'superwise' ),
			'default'  => '#ffffff',
			'validate' => 'color',
			'mode'     => 'background-color',
			'compiler' => array( '.wh-preloader' ),
		),
	)
) );

Redux::setSection( $opt_name, array(
	'subsection' => true,
	'id'         => 'subsection-misc-scroll-to-top-button',
	'title'      => esc_html__( 'Scroll to Top Button', 'superwise' ),
	'fields'     => array(
		array(
			'id'      => 'use-scroll-to-top',
			'type'    => 'switch',
			'title'   => esc_html__( 'Use Scroll to Top Button?', 'superwise' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'       => 'scroll-to-top-text',
			'type'     => 'text',
			'title'    => esc_html__( 'Scroll to Top Text', 'superwise' ),
			'default'  => '',
			'required' => array(
				array( 'use-scroll-to-top', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'scroll-to-top-button-override',
			'type'     => 'switch',
			'title'    => esc_html__( 'Override Scroll to Top Button?', 'superwise' ),
			'default'  => false,
			'on'       => 'Yes',
			'off'      => 'No',
			'required' => array(
				array( 'use-scroll-to-top', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'scroll-to-top-button',
			'type'     => 'background',
			'compiler' => array( '#scrollUp' ),
			'title'    => esc_html__( 'Scroll to Top Button', 'superwise' ),
			'required' => array(
				array( 'use-scroll-to-top', 'equals', '1' ),
				array( 'scroll-to-top-button-override', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'scroll-to-top-dimensions',
			'type'     => 'dimensions',
			'units'    => array( 'px' ),
			'compiler' => array( '#scrollUp' ),
			'title'    => esc_html__( 'Dimensions (Width/Height)', 'superwise' ),
			'default'  => array(
				'width'  => '70',
				'height' => '70'
			),
			'required' => array(
				array( 'use-scroll-to-top', 'equals', '1' ),
				array( 'scroll-to-top-button-override', 'equals', '1' ),
			),
		),

	)
) );

// -> End Misc


// ----------------------------------
// -> Other Settings
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => 'section-other-settings',
	'title'  => esc_html__( 'Other Settings', 'superwise' ),
	'icon'   => 'el-icon-website',
	'fields' => array(
		array(
			'id'   => 'other-settings-info',
			'type' => 'info',
			'desc' => esc_html__( 'If you have made edits to the code and wish to see the original code click on the link bellow. If you wish to completely restore the original code either copy this reference code to the editor bellow or reset the section.', 'superwise' ),
		),
		array(
			'id'   => 'other-settings-info-link',
			'type' => 'info',
			'desc' => '<a href="' . get_template_directory_uri() . '/lib/redux/css/other-settings/vars.scss" target="_blank">Click here to see a refrence of original code</a>'
		),
		array(
			'id'       => 'other-settings-vars',
			'type'     => 'ace_editor',
			'title'    => esc_html__( 'Settings', 'superwise' ),
			'mode'     => 'scss',
			'compiler' => 'true',
			'theme'    => 'monokai',
			'default'  => $other_settings,
			'options'  => array(
				'minLines' => 100
			),
		),
	)
) );
// -> End Other Settings
