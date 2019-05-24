<?php
$textdomain = SCP_TEXT_DOMAIN;

$sections[] = array(
	'title'      => __( 'Tribe Events', $textdomain ),
	'subsection' => true,
	'fields'     => array(

		array(
			'id'          => 'linp-tribe-events-widget-title-typography',
			'type'        => 'typography',
			'title'       => __( 'Title Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'compiler'    => array( '.linp-tribe-events .event .info .title' ),
			'units'       => 'px',
			'default'     => array(
			),
		),
		array(
			'id'             => 'linp-tribe-events-widget-title-margin',
			'type'           => 'spacing',
			'compiler'       => array( '.linp-tribe-events .event .info .title' ),
			'mode'           => 'margin',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => __( 'Title Margin', $textdomain ),
			'default'        => array(
				'margin-top'    => '0',
				'margin-right'  => 0,
				'margin-bottom' => '10px',
				'margin-left'   => 0,
				'units'         => 'px',
			)
		),
		array(
			'id'          => 'linp-tribe-events-widget-date-typography',
			'type'        => 'typography',
			'title'       => __( 'Date Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'compiler'    => array( '.linp-tribe-events .event .date' ),
			'units'       => 'px',
			'default'     => array(
				'font-size'   => '20px',
				'line-height' => '20px',
				'color'       => '#fff',
				'text-align'  => 'center',
			),
		),
		array(
			'id'             => 'linp-tribe-events-widget-date-padding',
			'type'           => 'spacing',
			'compiler'       => array( '.linp-tribe-events .event .date' ),
			'mode'           => 'padding',
			'units'          => array( 'em', 'px' ),
			'units_extended' => 'false',
			'title'          => __( 'Date Padding', $textdomain ),
			'default'        => array(

			)
		),
		array(
			'id'       => 'linp-tribe-events-widget-date-width',
			'type'     => 'dimensions',
			'units'    => array( 'em', 'px', '%' ),
			'title'    => __( 'Date Width', 'redux-framework-demo' ),
			'compiler' => array( '.linp-tribe-events .event .date' ),
			'height'   => false,
			'default'  => array(
				'units' => 'px',
				'width' => '50px'
			),
		)


	)
);
