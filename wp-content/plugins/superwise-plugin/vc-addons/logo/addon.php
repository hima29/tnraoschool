<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Logo extends WPBakeryShortCode {

	protected $shortcode_name = 'st_logo';
	
	public function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'name'        => esc_html__( 'Logo', 'superwise-plugin' ),
			'description' => esc_html__( 'Uses logo image set in Theme Options', 'superwise-plugin'  ),
			'base'        => $this->shortcode_name,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			'category'    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'      => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Image alignment', 'js_composer' ),
					'param_name'  => 'alignment',
					'value'       => array(
						__( 'Left', 'js_composer' )   => 'left',
						__( 'Right', 'js_composer' )  => 'right',
						__( 'Center', 'js_composer' ) => 'center',
					),
					'description' => esc_html__( 'Select image alignment.', 'js_composer' ),
				),
				vc_map_add_css_animation(),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'js_composer' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),
			),
		) );
	}

	public function render( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'alignment'      => 'left',
			'css'            => '',
			'el_class'       => '',
		), $atts ) );
		// $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

		if ( function_exists( 'superwise_get_logo_url' ) ) {
			$logo_url = superwise_get_logo_url();
		}

		$placeholer_class = '';
		if ( ! $logo_url ) {
			$logo_url = vc_asset_url( 'vc/no_image.png' );
			$placeholer_class = 'vc_img-placeholder';
		}

		$html = '<img class="' . esc_attr( $placeholer_class ) . ' vc_single_image-img" src="' . esc_url( $logo_url ) . '" alt="logo"/>';

		$html = '<a href="' . esc_url( home_url( '/' ) ) . '">' . $html . '</a>';

		$class_to_filter = 'logo wpb_single_image wpb_content_element vc_align_' . $alignment;
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

		$output = '
          	<div class="' . esc_attr( trim( $css_class ) ) . '">
          		<figure class="wpb_wrapper vc_figure">
          			' . $html . '
          		</figure>
          	</div>
         ';
			return $output;
	}

	public function loadCssAndJs() {
		if ( function_exists( 'superwise_get_option' ) ) {
			$logo_width_settings = superwise_get_option( 'logo-width-exact', '' );
			if ( $logo_width_settings && isset( $logo_width_settings['width'] ) && (int) $logo_width_settings['width'] ) {
				$logo_width = '.logo.wpb_single_image {width:' . $logo_width_settings['width'] . '}';
				wp_add_inline_style( 'superwise_options_style', $logo_width );
			}
		}
	}
}

new SCP_Logo();
