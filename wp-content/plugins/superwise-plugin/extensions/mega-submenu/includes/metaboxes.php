<?php


add_filter( 'rwmb_meta_boxes', 'msm_register_meta_boxes' );

function msm_register_meta_boxes( $meta_boxes ) {
	$prefix = MSM_PREFIX;

	/**
	 * Mega Menus
	 */

	$meta_boxes[] = array(
		'title'  => 'Settings',
		'pages'  => array( Mega_Submenu::POST_TYPE ), // can be used on multiple CPTs
		'fields' => array(
			array(
				'id'   => $prefix . 'width', // it's named the same for pages, posts and projects
				'type' => 'text',
				'name' => esc_html__( 'Width', 'mega-submenu' ),
				'desc' => esc_html__( 'Value in px or %.', 'mega-submenu' ),
			),
			array(
				'id'   => $prefix . 'margin', // it's named the same for pages, posts and projects
				'type' => 'text',
				'name' => esc_html__( 'Left/Right Margin', 'mega-submenu' ),
				'desc' => esc_html__( 'Value in px. Enter number only.', 'mega-submenu' ),
			),
			array(
				'id'   => $prefix . 'bg_color', // it's named the same for pages, posts and projects
				'type' => 'color',
				'name' => esc_html__( 'Background Color', 'mega-submenu' ),
			),
			array(
				'id'          => $prefix . 'position',
				'type'        => 'select',
				'name'        => esc_html__( 'Menu Position', 'mega-submenu' ),
				'options'     => array(
					'center'      => 'Center',
					'center_full' => 'Center Full',
					'left'        => 'Left',
					'left_edge'   => 'Left Edge',
					'right'       => 'Right',
					'right_edge'  => 'Right Edge',
				),
			),
			array(
				'id'          => $prefix . 'trigger',
				'type'        => 'select',
				'name'        => esc_html__( 'Trigger', 'mega-submenu' ),
				'options'     => array(
					'hover' => 'Hover',
					'click' => 'Click',
				),
//				'placeholder' => 'Trigger menu by hover or click',
			),


		)
	);

	return $meta_boxes;
}