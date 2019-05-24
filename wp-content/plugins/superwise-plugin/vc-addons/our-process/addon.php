<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Our_Process {

	protected $namespace = 'scp_our_process';
	protected $namespace_item = 'scp_our_process_item';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_action( 'wp', array( $this, 'check_shortcodes' ) );
		add_action( "scp_load_styles_{$this->namespace_item}", array( $this, 'load_process_item_css' ) );
		add_shortcode( $this->namespace, array( $this, 'render_process' ) );
		add_shortcode( $this->namespace_item, array( $this, 'render_process_item' ) );
}
public function check_shortcodes() {
		if ( ! is_admin() ) {
			global $post;
			if ( $post && strpos( $post->post_content, $this->namespace ) != false ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
			}
		}
}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'name'                    => esc_html__( 'Our Process', 'superwise_plugin' ),
			'description'             => esc_html__( '', 'superwise_plugin' ),
			'base'                    => $this->namespace,
			'class'                   => '',
			'controls'                => 'full',
			"as_parent"               => array( 'only' => 'scp_our_process_item' ),
			'icon'                    => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'                => 'Aislin', 
			'js_view'                 => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => true,
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			'admin_enqueue_css'       => array( plugins_url( 'assets/css/admin.css', __FILE__ ) ), // This will load css file in the VC backend editor
			'params'                  => array(
				array(
					'type'       => 'textfield',
					'holder'     => 'h4',
					'class'      => 'our-process-container-title',
					'heading'    => esc_html__( 'Admin Title', 'superwise_plugin' ),
					'param_name' => 'title',
					'value'      => 'Our Process Container',
				),
				array(
					'type'        => 'attach_image',
					'class'       => '',
					'heading'     => esc_html__( 'Background Image', 'superwise_plugin' ),
					'param_name'  => 'bg_image',
					'value'       => '',
					'description' => esc_html__( 'Upload background image.', 'superwise_plugin' )
				),
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__( 'Breakpoint','superwise_plugin' ),
					'description' => esc_html__( 'Under this width boxes will be streched to 100%. (Value in px - enter nubmer only)', $this->textdomain ),
					'param_name'  => 'breakpoint',
					'value'       => '480',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'superwise_plugin' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'superwise_plugin' ),
				),
			),
		) );

		vc_map( array(
			'name'            => esc_html__( 'Our Process Item', 'superwise_plugin' ),
			'description'     => '',
			'base'            => $this->namespace_item,
			'class'           => '',
			'controls'        => 'full',
			'as_child'        => array( 'only' => 'scp_our_process' ),
			'icon'            => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'        => esc_html__( 'Aislin', 'js_composer' ),
			'content_element' => true,
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'          => array(
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Title', 'superwise_plugin' ),
					'param_name'  => 'title',
					'admin_label' => true,
					'value'       => '',
					'description' => esc_html__( 'Widget title.', 'superwise_plugin' ),
				),
				array(
					'type'       => 'dropdown',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Title Tag', 'superwise_plugin' ),
					'param_name' => 'title_tag',
					'value'      => array(
						'h1' => 'h1',
						'h2' => 'h2',
						'h3' => 'h3',
						'h4' => 'h4',
						'h5' => 'h5',
						'h6' => 'h6',
					),
				),
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Width', 'superwise_plugin' ),
					'param_name'  => 'width',
					'admin_label' => true,
					'value'       => esc_html__( '30%', 'superwise_plugin' ),
					'description' => esc_html__( 'Enter with for the item. Percent recommended', 'superwise_plugin' ),
				),
				array(
					'type'       => 'colorpicker',
					'class'      => '',
					'heading'    => esc_html__( 'Title Color', 'superwise_plugin' ),
					'param_name' => 'title_color',
					'value'      => '',
					'group'      => 'Style',
				),
				array(
					'type'       => 'colorpicker',
					'class'      => '',
					'heading'    => esc_html__( 'Icon Color', 'superwise_plugin' ),
					'param_name' => 'icon_color',
					'value'      => '',
					'group'      => 'Style',
				),
				array(
					'type'       => 'colorpicker',
					'class'      => '',
					'heading'    => esc_html__( 'Bar Color', 'superwise_plugin' ),
					'param_name' => 'bar_color',
					'value'      => '',
					'group'      => 'Style',
				),
				array(
					'type'       => 'colorpicker',
					'class'      => '',
					'heading'    => esc_html__( 'Text Color', 'superwise_plugin' ),
					'param_name' => 'text_color',
					'value'      => '',
					'group'      => 'Style',
				),
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Icon to display:', 'superwise_plugin' ),
					'param_name'  => 'icon_type',
					'value'       => array(
						'Font Icon Manager' => 'selector',
						'Custom Image Icon' => 'custom',
					),
					'description' => esc_html__( 'Use an existing font icon</a> or upload a custom image.', 'superwise_plugin' )
				),
				array(
					'type'        => 'iconpicker',
					'param_name'  => 'theme_icon',
					'heading'     => esc_html__( 'Icon', 'superwise_plugin' ),
					'value'       => '', // default value to backend editor admin_label
					'class'       => 'scp-theme-icon-name',
					'holder'      => 'div',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'theme-icons',
						// default true, display an "EMPTY" icon?
						'iconsPerPage' => 4000,
						// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
					),
					'description' => esc_html__( 'Select icon from library.', 'superwise_plugin' ),
					'dependency' => array( 'element' => 'icon_type', 'value' => array( 'selector' ) ),
				),
				array(
					'type'        => 'attach_image',
					'class'       => '',
					'heading'     => esc_html__( 'Upload Image Icon:', 'superwise_plugin' ),
					'param_name'  => 'icon_img',
					'value'       => '',
					'description' => esc_html__( 'Upload the custom image icon.', 'superwise_plugin' ),
					'dependency'  => Array( 'element' => 'icon_type', 'value' => array( 'custom' ) ),
				),
				array(
					'type'       => 'textarea_html',
					'class'      => '',
					'heading'    => esc_html__( 'Descripion', 'superwise_plugin' ),
					'param_name' => 'content',
					'value'      => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'superwise_plugin' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'superwise_plugin' ),
				),
			)
		) );
	}

	public function render_process( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'bg_image'   => false,
			'breakpoint' => 480,
			'el_class'   => '',
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'our-process ' . $el_class, $this->namespace, $atts );

		$content = wpb_js_remove_wpautop( $content ); // fix unclosed/unwanted paragraph tags in $content
		$img     = wp_get_attachment_image_src( $bg_image, 'large' );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>" data-breakpoint="<?php echo esc_attr( absint( $breakpoint ) ); ?>">
			<?php if ( $img[0] ): ?>
				<div class="img-wrap">
					<img class="bg-img center" src="<?php echo esc_url( $img[0] ); ?>"/>
				</div>
			<?php endif; ?>
			<div class="dots">
				<?php echo do_shortcode( $content ); ?>
			</div>
		</div>

		<?php

		return ob_get_clean();
	}

	public function load_process_item_css( $atts ) {

		$uid = SCP_Assets::get_uid( $this->namespace_item, $atts );

		extract( shortcode_atts( array(
			'width'       => '30%',
			'title_color' => '',
			'icon_color'  => '',
			'bar_color'   => '',
			'text_color'  => '',
		), $atts ) );

		$style = '';

		if ( $width ) {
			$style .= ".our-process .{$uid} {width:{$width}}";
		}
		if ( $title_color ) {
			$style .= ".our-process .{$uid} .title{color:{$title_color}}";
		}
		if ( $bar_color ) {
			$style .= ".our-process .{$uid} .line{background-color:{$bar_color}}";
			$style .= ".our-process .{$uid} .triangle{border-top-color:{$bar_color}}";
		}
		if ( $icon_color ) {
			$style .= ".our-process .{$uid} i{color:{$icon_color}}";
		}
		if ( $text_color ) {
			$style .= ".our-process .{$uid} .text{color:{$text_color}}";
		}

		if ( $style ) {
			wp_add_inline_style( 'superwise_options_style', $style );
		}

	public function render_process_item( $atts, $content = null ) {

		$uid = SCP_Assets::get_uid( $this->namespace_item, $atts );
		extract( shortcode_atts( array(
			'title'       => '',
			'title_tag'   => 'h2',
			'width'       => '30%',
			'theme_icon'  => '',
			'icon_img'    => '',
			'title_color' => '',
			'icon_color'  => '',
			'bar_color'   => '',
			'text_color'  => '',
			'el_class'    => '',
		), $atts ) );

		$content = wpb_js_remove_wpautop( $content ); // fix unclosed/unwanted paragraph tags in $content



		if ( $title ) {
			$title = esc_html( $title );
			$title = "<{$title_tag} class=\"title\">{$title}</{$title_tag}>";
		}

		ob_start();
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'dot-container ' . $el_class, $this->namespace, $atts );
		$css_class .= ' ' . $uid;
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>" >
			<div class="line"></div>
			<div class="triangle"></div>
			<div class="dot-wrap center">
				<div class="dot">
					<i class="<?php echo esc_attr( $theme_icon ); ?>"></i>
				</div>
			</div>
			<div class="text">
				<?php echo wp_kses_post( $title ); ?>
				<?php echo wp_kses_post( $content ); ?>
			</div>
		</div>
		<?php

		 return ob_get_clean();
	}

	
	public function loadCssAndJs() {
		wp_enqueue_script( 'jquery-appear', plugins_url( 'assets/js/jquery-appear.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'scp_our_process', plugins_url( 'assets/js/scp-our-process.js', __FILE__ ), array( 'jquery' ) );
	}
}

global $scp_our_process;
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_scp_our_process extends WPBakeryShortCodesContainer {
		function content( $atts, $content = null ) {

		}
	}

	class WPBakeryShortCode_scp_our_process_item extends WPBakeryShortCode {
		function content( $atts, $content = null ) {

		}
	}
}
if ( class_exists( 'SCP_Our_Process' ) ) {
	$scp_our_process = new SCP_Our_Process();
}
