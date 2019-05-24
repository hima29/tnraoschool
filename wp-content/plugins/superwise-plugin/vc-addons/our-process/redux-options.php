<?php
$textdomain = SCP_TEXT_DOMAIN;

$sections[] = array(
	'title'      => __( 'Our Process', $textdomain ),
	'subsection' => true,
	'fields'     => array(
		array(
			'id'          => 'dntp-our-process-widget-title-typography',
			'type'        => 'typography',
			'title'       => __( 'Title Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'output'      => array( '.our-process .dots .title' ),
			'units'       => 'px',
			'default'     => array(
				'color'       => '#303030',
				'font-style'  => '700',
				'font-family' => 'Roboto',
				'google'      => true,
				'font-size'   => '16px',
				'line-height' => '20px'
			),
		),
		array(
			'id'          => 'dntp-our-process-widget-icon',
			'type'        => 'typography',
			'title'       => __( 'Icon Typography', 'wheels' ),
			'google'      => false,
			'font-backup' => false,
			'font-family' => false,
			'compiler'    => array( '.our-process .dot-container .dot' ),
			'units'       => 'px',
			'default'     => array(
				'color'       => '#333',
				'font-size'   => '33px',
				'line-height' => '40px'
			),
		),
		array(
			'id'       => 'dntp-our-process-widget-bar-bg-color',
			'type'     => 'color',
			'mode'     => 'background-color',
			'compiler' => array( '.our-process .dot-container .line' ),
			'title'    => __( 'Bar Background Color', 'wheels' ),
			'default'  => '#ff5a5f',
			'validate' => 'color',
		),
		array(
			'id'       => 'dntp-our-process-widget-triangle-bg-color',
			'type'     => 'color',
			'mode'     => 'border-top-color',
			'compiler' => array( '.our-process .dot-container .triangle' ),
			'title'    => __( 'Triangle Background Color', 'wheels' ),
			'default'  => '#ff5a5f',
			'validate' => 'color',
		),
		array(
			'id'          => 'dntp-our-process-widget-text-typography',
			'type'        => 'typography',
			'title'       => __( 'Text Typography', $textdomain ),
			'google'      => true,
			'font-backup' => true,
			'output'      => array( '.our-process .dot-container' ),
			'units'       => 'px',
			'default'     => array(
				'color'       => '#070708',
				'font-style'  => 'normal',
				'font-weight' => '300',
				'font-family' => 'Roboto',
				'google'      => true,
				'font-size'   => '13px',
				'line-height' => '16px'
			),
		),
		array(
			'id'       => 'dntp-our-process-widget-device-trigger',
			'type'     => 'spinner',
			'title'    => __('Breakpoint', 'wheels'),
			'subtitle' => __('Under this width boxes will be streched to 100%','wheels'),
			'default'  => '480',
			'min'      => '20',
			'step'     => '1',
			'max'      => '2000',
		),
	)
);
