<?php
$textdomain = SCP_TEXT_DOMAIN;

$sections[] = array(
	'title'      => __( 'Sensei Courses Carousel', $textdomain ),
	'subsection' => true,
	'fields'     => array(
		array(
			'id'          => 'linp-featured-courses-item-title-typography',
			'type'        => 'typography',
			'title'       => __( 'Title Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'text-transform' => true,
			'compiler'    => array( '.linp-featured-courses-carousel .course-title, .linp-featured-courses-carousel .course-title a' ),
			'units'       => 'px',
			'subtitle'    => __( 'Typography option with each property can be called individually.', $textdomain ),
			'default'     => array(
				'color'       => '#333',
				'font-style'  => '700',
				'font-family' => 'Abel',
				'google'      => true,
				'font-size'   => '33px',
				'line-height' => '40px'
			),
		),
		// array(
		// 	'id'             => 'linp-featured-courses-item-title-spacing',
		// 	'type'           => 'spacing',
		// 	'compiler'       => array( '.linp-featured-courses-carousel .course-title' ),
		// 	'mode'           => 'margin',
		// 	'units'          => array( 'em', 'px' ),
		// 	'units_extended' => 'false',
		// 	'title'          => __( 'Title Margin', $textdomain ),
		// 	'right'          => false,
		// 	'left'           => false,
		// 	'default'        => array(
		// 		'margin-top'    => '0px',
		// 		'margin-bottom' => '5px',
		// 		'units'         => 'px',
		// 	)
		// ),
		// array(
		// 	'id'       => 'linp-featured-courses-item-separator',
		// 	'type'     => 'border',
		// 	'title'    => __( 'Separator', $textdomain ),
		// 	'subtitle' => __( 'Only color validation can be done on this field type', $textdomain ),
		// 	'compiler' => array( '.linp-featured-courses-carousel hr' ),
		// 	'desc'     => __( 'This is the description field, again good for additional info.', $textdomain ),
		// 	'top'      => false,
		// 	'left'     => false,
		// 	'right'    => false,
		// 	'default'  => array(
		// 		'border-color'  => '#1e73be',
		// 		'border-style'  => 'solid',
		// 		'border-bottom' => '1px',
		// 	)
		// ),
		// array(
		// 	'id'       => 'linp-featured-courses-item-separator-width',
		// 	'type'     => 'dimensions',
		// 	'units'    => array( '%' ),
		// 	'title'    => __( 'Separator Width', $textdomain ),
		// 	'compiler' => array( '.linp-featured-courses-carousel hr' ),
		// 	'height'   => false,
		// 	'default'  => array(
		// 		'width' => '100',
		// 	),
		// ),
		array(
			'id'          => 'linp-featured-courses-item-meta-typography',
			'type'        => 'typography',
			'title'       => __( 'Meta Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'text-transform' => true,
			'compiler'    => array(
				'.linp-featured-courses-carousel .sensei-course-meta',
				'.linp-featured-courses-carousel .sensei-course-meta a',
				'.linp-featured-courses-carousel .course-lesson-count',
			),
			'units'       => 'px',
			'subtitle'    => __( 'Typography option with each property can be called individually.', $textdomain ),
			'default'     => array(
				'color'       => '#333',
				'font-style'  => '700',
				'font-family' => 'Abel',
				'google'      => true,
				'font-size'   => '15px',
				'line-height' => '20px'
			),
		),
		array(
			'id'          => 'linp-featured-courses-item-text-typography',
			'type'        => 'typography',
			'title'       => __( 'Text Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'text-transform' => true,
			'compiler'    => array(
				'.linp-featured-courses-carousel .course-excerpt',
				'.linp-featured-courses-carousel .post-ratings',
				'.owl-theme .owl-controls .owl-buttons div',
				'.owl-theme .owl-controls .owl-page span',
			),
			'units'       => 'px',
			'subtitle'    => __( 'Typography option with each property can be called individually.', $textdomain ),
			'default'     => array(
				'color'       => '#333',
				'font-style'  => '700',
				'font-family' => 'Abel',
				'google'      => true,
				'font-size'   => '15px',
				'line-height' => '20px'
			),
		),
		array(
			'id'          => 'linp-featured-courses-item-price-regular-typography',
			'type'        => 'typography',
			'title'       => __( 'Regular Price Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'compiler'    => array(
				'.linp-featured-courses-carousel .owl-item .course-price .amount',
				'.linp-featured-courses-carousel .owl-item .course-price del .amount',
				'.wh-course-list-item .img-container .course-price .amount',
			),
			'units'       => 'px',
			'subtitle'    => __( 'Typography option with each property can be called individually.', $textdomain ),
			'default'     => array(),
		),
		// array(
		// 	'id'          => 'linp-featured-courses-item-price-discount-typography',
		// 	'type'        => 'typography',
		// 	'title'       => __( 'Discounted Price Typography', $textdomain ),
		// 	'google'      => true,
		// 	'font-backup' => true,
		// 	'compiler'    => array(
		// 		'.linp-featured-courses-carousel .owl-item .course-price del .amount',
		// 	),
		// 	'units'       => 'px',
		// 	'subtitle'    => __( 'Typography option with each property can be called individually.', $textdomain ),
		// 	'default'     => array(),
		// ),
//		array(
//			'id'             => 'linp-featured-courses-item-padding-outer',
//			'type'           => 'spacing',
//			'compiler'       => array( '.linp-featured-courses-carousel .owl-item' ),
//			'mode'           => 'padding',
//			'units'          => array( 'em', 'px' ),
//			'units_extended' => 'false',
//			'title'          => __( 'Outer Wrapper Padding', $textdomain ),
//			'default'        => array(
//				'padding-top'    => '10px',
//				'padding-right'  => '10px',
//				'padding-bottom' => '10px',
//				'padding-left'   => '10px',
//				'units'          => 'px',
//			)
//		),
//		array(
//			'id'             => 'linp-featured-courses-item-padding-inner',
//			'type'           => 'spacing',
//			'compiler'       => array( '.linp-featured-courses-carousel .owl-item .item-inner-wrap' ),
//			'mode'           => 'padding',
//			'units'          => array( 'em', 'px' ),
//			'units_extended' => 'false',
//			'title'          => __( 'Inner Wrapper Padding', $textdomain ),
//			'default'        => array(
//				'padding-top'    => '10px',
//				'padding-right'  => '10px',
//				'padding-bottom' => '10px',
//				'padding-left'   => '10px',
//				'units'          => 'px',
//			)
//		),
		array(
			'id'       => 'linp-featured-courses-item-border-color',
			'type'     => 'color',
			'compiler' => 'true',
		    'title'    => __('Border Color', $textdomain),
		    'default'  => '#E4E4E4',
		    'validate' => 'color',
		),
		array(
			'id'       => 'linp-featured-courses-item-wrapper-bg-color-inner',
			'type'     => 'color',
			'mode'     => 'background-color',
			'title'    => __( 'Inner Wrapper Background Color', $textdomain ),
			'compiler' => array(
				'.linp-featured-courses-carousel .owl-item .item-inner-wrap',
				'.owl-theme .owl-controls .owl-buttons div',
				'.owl-theme .owl-controls .owl-page span',
			),
			'default'  => '',
			'validate' => 'color',
		),
		array(
			'id'       => 'linp-featured-courses-item-price-bg-color',
			'type'     => 'color',
			'mode'     => 'background-color',
			'title'    => __( 'Price Wrapper Background Color', $textdomain ),
			'compiler' => array(
				'.linp-featured-courses-carousel .owl-item .course-price',
				'.wh-course-list-item .img-container .course-price',
			),
			'default'  => '',
			'validate' => 'color',
		),
		array(
			'id'       => 'linp-featured-courses-item-ribbon-back-bg-color',
			'type'     => 'color',
			'compiler' => 'true',
		    'title'    => __('Ribbon Back Background Color', $textdomain),
		    'default'  => '#333',
		    'validate' => 'color',
		),
		array(
			'id'       => 'linp-featured-courses-ratings-color',
			'type'     => 'link_color',
			'title'    => __( 'Rating Color', $textdomain ),
			'compiler' => array(
				'.linp-featured-courses-carousel .star-rating',
			),
			'visited'  => false,
			'active'   => false,
			'default'  => array(
				'regular' => '#1e73be', // blue
				'hover'   => '#dd3333', // red
			)
		),
		array(
			'id'      => 'linp-featured-courses-item-img-is-rounded',
			'type'    => 'switch',
			'title'   => __( 'Image is rounded?', $textdomain ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
		),

	)
);
