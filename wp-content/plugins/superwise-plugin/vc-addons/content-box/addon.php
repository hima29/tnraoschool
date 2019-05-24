<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Content_Box {

	protected $namespace = 'scp_content_box';
	
	function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );
		add_action( "scp_load_styles_{$this->namespace}", array( $this, 'load_css' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'name'        => esc_html__( 'Content Box', 'superwise-plugin' ),
			'description' => '',
			'base'        => $this->namespace,
			'class'       => '',
			'controls'    => 'full',
			'is_container'     => true,
			'js_view'     => 'VcColumnView',
			'as_parent'   => array( 'except' => $this->namespace ),
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'    => 'Aislin',
			'admin_enqueue_js' => array( plugins_url( 'assets/admin-theme-icon.js', __FILE__ ) ),
			// This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'      => array(
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'URL (Link)', 'js_composer' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'Add link to icon.', 'js_composer' ),
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'use_overlay',
					'heading'    => esc_html__( 'Use Overlay', 'superwise-plugin' ),
					'value'      => array(
						'No'  => 'no',
						'Yes' => 'yes'
					),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'overlay_title',
					'heading'    => esc_html__( 'Overlay Title', 'superwise-plugin' ),
					'dependency'  => array( 'element' => 'use_overlay', 'value' => 'yes' ),
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'el_class',
					'heading'     => esc_html__( 'Extra class name', 'superwise-plugin' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'superwise-plugin' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Bg Color', 'superwise-plugin' ),
					'param_name' => 'custom_background_color',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Hover Bg Color', 'superwise-plugin' ),
					'param_name' => 'hover_bg_color',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_top',
					'heading'    => esc_html__( 'Top', 'superwise-plugin' ),
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_left',
					'heading'    => esc_html__( 'Left', 'superwise-plugin' ),
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_spread',
					'heading'    => esc_html__( 'Spread', 'superwise-plugin' ),
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Box Shadow Color', 'superwise-plugin' ),
					'param_name' => 'box_shadow_color',
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_top_hover',
					'heading'    => esc_html__( 'Top Hover', 'superwise-plugin' ),
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_left_hover',
					'heading'    => esc_html__( 'Left Hover', 'superwise-plugin' ),
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_spread_hover',
					'heading'    => esc_html__( 'Spread Hover', 'superwise-plugin' ),
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Box Shadow Color Hover', 'superwise-plugin' ),
					'param_name' => 'box_shadow_color_hover',
					'group'      => esc_html__( 'Box Shadow', 'js_composer' ),
				),
			)
		) );
	}

	public function load_css( $atts ) {

		$uid = SCP_Assets::get_uid( $this->namespace, $atts );

		extract( shortcode_atts( array(
			'custom_background_color' => '', // bg_color name is vc default
			'hover_bg_color'          => '',
			'box_shadow_color'        => '',
			'box_shadow_top'          => '',
			'box_shadow_left'         => '',
			'box_shadow_spread'       => '',
			'box_shadow_color_hover'  => '',
			'box_shadow_top_hover'    => '',
			'box_shadow_left_hover'   => '',
			'box_shadow_spread_hover' => '',
		), $atts ) );

		$style = '';
		$style_hover = '';

		/**
		 * Custom BG Color
		 */
		if ( $custom_background_color ) {
			$style .= 'background-color:' . $custom_background_color . ';';
		}
		if ( $hover_bg_color ) {
			$style_hover .= 'background-color:' . $hover_bg_color . ';';
		}

		/**
		 * Box Shadow
		 */
		$box_shadow = '';
		if ( $box_shadow_color ) {
			$box_shadow_top    = $box_shadow_top ? (int) $box_shadow_top . 'px' : '0px';
			$box_shadow_left   = $box_shadow_left ? (int) $box_shadow_left . 'px' : '0px';
			$box_shadow_spread = $box_shadow_spread ? (int) $box_shadow_spread . 'px' : '5px';
			$box_shadow        = $box_shadow_top . ' ' . $box_shadow_left . ' ' . $box_shadow_spread . ' ' . $box_shadow_color;

			$style .= 'box-shadow:' . $box_shadow . ';';
		}

		/**
		 * Box Shadow Hover
		 */
		$box_shadow_hover = '';
		if ( $box_shadow_color_hover ) {
			$box_shadow_top_hover    = $box_shadow_top_hover ? (int) $box_shadow_top_hover . 'px' : '0px';
			$box_shadow_left_hover   = $box_shadow_left_hover ? (int) $box_shadow_left_hover . 'px' : '0px';
			$box_shadow_spread_hover = $box_shadow_spread_hover ? (int) $box_shadow_spread_hover . 'px' : '5px';
			$box_shadow_hover        = $box_shadow_top_hover . ' ' . $box_shadow_left_hover . ' ' . $box_shadow_spread_hover . ' ' . $box_shadow_color_hover;
			$style_hover .= 'box-shadow:' . $box_shadow_hover . ';';
		}


		$final_style = '';
		if ( $style ) {
			$final_style .= ".$uid{{$style}}";
		}
		if ( $style_hover ) {
			$final_style .= ".$uid:hover{{$style_hover}}";
		}
		if ( $final_style ) {
			wp_add_inline_style( 'superwise_options_style', $final_style );
		}
	}

	public function render( $atts, $content = null ) {

		$uid = SCP_Assets::get_uid( $this->namespace, $atts );

		extract( shortcode_atts( array(
			'link'                    => '',
			'use_overlay'             => 'no',
			'overlay_title'           => '',
			'custom_background_color' => '', // bg_color name is vc default
			'hover_bg_color'          => '',
			'box_shadow_color'        => '',
			'box_shadow_top'          => '',
			'box_shadow_left'         => '',
			'box_shadow_spread'       => '',
			'box_shadow_color_hover'  => '',
			'box_shadow_top_hover'    => '',
			'box_shadow_left_hover'   => '',
			'box_shadow_spread_hover' => '',
			'css'                     => '',
			'el_class'                => '',
		), $atts ) );
		// $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content

		$class_to_filter = 'wh-content-box';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->namespace, $atts );
		$css_class .= ' ' . $uid;
 
		$link     = vc_build_link( $link );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'];

		ob_start();
		?>

		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php if ( $use_overlay ==  'yes' ) : ?>
				<div class="overlay"><?php echo esc_html( $overlay_title ); ?></div>
			<?php endif; ?>
			<?php if ( $a_href ) : ?>
				<a class="wh-content-box-link"
				   href="<?php echo esc_attr( $a_href ); ?>"
					<?php if ( $a_title ) : ?>
						title="<?php echo esc_attr( $a_title ); ?>"
					<?php endif; ?>
					<?php if ( $a_target ) : ?>
						target="<?php echo esc_attr( $a_target ); ?>"
					<?php endif; ?>
					></a>
			<?php endif; ?>

			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php 
		return ob_get_clean();
	}
}

new SCP_Content_Box();

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_scp_content_box extends WPBakeryShortCodesContainer {
	}
}
