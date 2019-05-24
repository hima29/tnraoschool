<?php

add_filter( 'rwmb_meta_boxes', 'superwise_register_meta_boxes');

function superwise_register_meta_boxes( $meta_boxes ) {

	$prefix     = 'superwise_';

	$meta_boxes[] = array(
		'title'  => 'Teacher Settings',
		'pages'  => array( 'teacher' ), // can be used on multiple CPTs
		'fields' => array(
			array(
			    'id' => $prefix . 'teacher_is_wpcf7_recipient',
			    'type' => 'radio',
			    'name' => esc_html__( 'Set teacher email as Contact Form 7 recipient', 'superwise' ),
			    'options' => array(
			        'yes' => esc_html__( 'Yes', 'superwise' ), // Value => Label
			        'no' => esc_html__( 'No', 'superwise' ),
			    ),
			    'desc' => esc_html__( 'All contact forms inside the post content will get teacher email as recipient. Useful when you want users to be able to contact the teacher directly.', 'superwise' ),
			),
			array(
				'id'   => $prefix . 'job_title',
				'type' => 'text',
				'name' => esc_html__( 'Job Title', 'superwise' ),
				'desc' => esc_html__( 'This will be printed in Teacher Widget', 'superwise' ),
			),
			array(
				'id'   => $prefix . 'location',
				'type' => 'text',
				'name' => esc_html__( 'Location', 'superwise' ),
				'desc' => esc_html__( 'Printed only on teacher single page', 'superwise' ),
			),
			array(
				'id'   => $prefix . 'summary',
				'type' => 'wysiwyg',
				'name' => esc_html__( 'Summary', 'superwise' ),
				'desc' => esc_html__( 'This will be printed in Teacher Widget', 'superwise' ),
			),
			array(
				'id'   => $prefix . 'social_meta',
				'type' => 'textarea',
				'name' => esc_html__( 'Social Icon Shortcodes', 'superwise' ),
				'desc' => esc_html__( 'This will be printed in Teacher Widget.', 'superwise' ),
			),
			array(
				'id'               => $prefix . 'teacher_hover_img',
				'type'             => 'image_advanced',
				'name'             => esc_html__( 'Hover Image', 'superwise' ),
				'desc'             => esc_html__( 'Image that will be shown on hover.', 'superwise' ),
				'max_file_uploads' => 1,
			),
		)
	);

	/**
	 * Pages
	 */

	$menus       = get_registered_nav_menus();
	$menus_array = array();

	foreach ( $menus as $location => $description ) {
		$menus_array[ $location ] = $description;
	}

	$layout_blocks = get_posts(array('post_type' => 'layout_block', 'posts_per_page' => -1));
	$layout_blocks_array = array();
	foreach ( $layout_blocks as $layout_block ) {
		$layout_blocks_array[ $layout_block->ID ] = $layout_block->post_title;
	}


	$meta_boxes[] = array(
		'title'  => 'Page Settings',
		'pages'  => array( 'page' ), // can be used on multiple CPTs
		'fields' => array(
			array(
				'id'   => $prefix . 'subtitle_single_page', // it's named the same for pages, posts and projects
				'type' => 'text',
				'name' => esc_html__( 'Subtitle', 'superwise' ),
			),
			array(
				'id'   => $prefix . 'use_custom_menu',
				'type' => 'checkbox',
				'name' => esc_html__( 'Use Custom Menu', 'superwise' ),
				'desc' => esc_html__( 'When using one page menu functionality you need to add an id on each vc row you want to link to a menu item. Also you need to create a menu in Appearance/Menus and create custom links where each link url has the same name as the row class prefixed with # sign', 'superwise' ),
			),
			array(
				'id'          => $prefix . 'custom_menu_location',
				'type'        => 'select',
				'name'        => esc_html__( 'Select Custom Menu Location', 'superwise' ),
				'desc'        => esc_html__( 'Used only if Use Custom Menu is checked. It overrides Primary Navigation.', 'superwise' ),
				'options'     => $menus_array,
				'placeholder' => 'Select Menu Location',
			),
			array(
				'id'               => $prefix . 'custom_logo',
				'type'             => 'image_advanced',
				'name'             => esc_html__( 'Custom Logo', 'superwise' ),
				'desc'             => esc_html__( 'Use it to override the logo from theme options. This works well when using Transparent Header Template.', 'superwise' ),
				'max_file_uploads' => 1,
			),
			array(
				'id'               => $prefix . 'custom_page_title_background',
				'type'             => 'image_advanced',
				'name'             => esc_html__( 'Custom Page Title Background', 'superwise' ),
				'desc'             => esc_html__( 'Use it to override the page title background image from theme options.', 'superwise' ),
				'max_file_uploads' => 1,
			),
			array(
				'id'          => $prefix . 'header_layout_block',
				'type'        => 'select',
				'name'        => esc_html( 'Header Layout Block', 'superwise' ),
				'desc'        => esc_html( 'Override Theme Options settings.', 'superwise' ),
				'options'     => $layout_blocks_array,
				'placeholder' => esc_html( 'Default' ),
			),
			array(
				'id'          => $prefix . 'header_layout_block_mobile',
				'type'        => 'select',
				'name'        => esc_html( 'Mobile Header Layout Block', 'superwise' ),
				'desc'        => esc_html( 'Override Theme Options settings.', 'superwise' ),
				'options'     => $layout_blocks_array,
				'placeholder' => esc_html( 'Default' ),
			),
			array(
				'id'          => $prefix . 'footer_layout_block',
				'type'        => 'select',
				'name'        => esc_html( 'Footer Layout Block', 'superwise' ),
				'desc'        => esc_html( 'Override Theme Options settings.', 'superwise' ),
				'options'     => $layout_blocks_array,
				'placeholder' => esc_html( 'Default' ),
			),
			array(
				'id'          => $prefix . 'quick_sidebar_layout_block',
				'type'        => 'select',
				'name'        => esc_html( 'Quick Sidebar Layout Block', 'superwise' ),
				'desc'        => esc_html( 'Override Theme Options settings.', 'superwise' ),
				'options'     => $layout_blocks_array,
				'placeholder' => esc_html( 'Default' ),
			),
		)
	);

	return $meta_boxes;
}
