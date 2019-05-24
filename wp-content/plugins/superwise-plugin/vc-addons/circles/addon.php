<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Circles {

	protected $shortcode_name = 'scp_circles';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
		add_action( "scp_load_styles_{$this->shortcode_name}", array( $this, 'load_css' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		$thumbnail_sizes = array_merge( array( 'Full' => 'full' ), scp_get_thumbnail_sizes_vc() );

		vc_map( array(
			'name'        => esc_html__( 'Circles', 'superwise-plugin' ),
			'description' => '',
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
					'type'        => 'vc_link',
					'heading'     => __( 'URL (Link)', 'js_composer' ),
					'param_name'  => 'link',
					'description' => __( 'Add link to icon.', 'js_composer' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => __( 'Thumbnail', 'superwise-plugin' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => __( 'Select image from media library.', 'superwise-plugin' ),
					'dependency'  => array(
						'value' => 'media_library',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => __( 'Image Size', 'superwise-plugin' ),
					'param_name' => 'img_size',
					'value'      => $thumbnail_sizes,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Image alignment', 'js_composer' ),
					'param_name'  => 'alignment',
					'value'       => array(
						__( 'Left', 'js_composer' )   => 'left',
						__( 'Right', 'js_composer' )  => 'right',
						__( 'Center', 'js_composer' ) => 'center',
					),
					'description' => __( 'Select image alignment.', 'js_composer' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => __( 'Top Text', 'superwise-plugin' ),
					'param_name'  => 'top_text',
					'value'       => '',
					'description' => ''
				),
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => __( 'Bottom Text', 'superwise-plugin' ),
					'param_name'  => 'bottom_text',
					'value'       => '',
					'description' => ''
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Text Color', 'superwise-plugin' ),
					'param_name' => 'text_color',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Color 1', 'superwise-plugin' ),
					'param_name' => 'color_1',
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Color 2', 'superwise-plugin' ),
					'param_name' => 'color_2',
				),
				
			)
		) );
	}

	public function load_css( $atts ) {
		$uid = SCP_Assets::get_uid( $this->shortcode_name, $atts );

		extract( shortcode_atts( array(
			'text_color'  => '',
			'color_1'     => '',
			'color_2'     => '',
		), $atts ) );

		$style = '';

		if ( $color_1 ) {
			$style .= ".$uid .circle-main, .$uid .circle-small{background-color: {$color_1}}";
		}

		if ( $color_2 ) {
			$style .= ".$uid .circle-middle{background-color: {$color_2}}";
		}

		if ( $text_color ) {
			$style .= ".$uid .circle-main h3, .$uid .circle-main h4{color: {$text_color}}";
		}

		if ( $style ) {
			wp_add_inline_style( 'superwise_options_style', $style );
		}
	}

	public function render( $atts, $content = null ) {
		$uid = SCP_Assets::get_uid( $this->shortcode_name, $atts );

		extract( shortcode_atts( array(
			'link'        => '',
			'top_text'    => '',
			'bottom_text' => '',
			'text_color'  => '',
			'color_1'     => '',
			'color_2'     => '',
			'image'       => '',
			'img_size'    => 'full',
			'img_style'   => 'rounded',
			'alignment'   => 'left',
			'css'         => ''
		), $atts ) );


		$link     = vc_build_link( $link );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'];


		$default_src = vc_asset_url( 'vc/no_image.png' );

		$img_id = preg_replace( '/[^\d]/', '', $image );


		$img = wpb_getImageBySize( array(
			'attach_id'  => $img_id,
			'thumb_size' => $img_size,
			'class'      => 'circle-img ' . $img_style,
		) );

		if ( ! $img ) {
			$img['thumbnail'] = '<img class="vc_img-placeholder vc_single_image-img" src="' . $default_src . '" alt="circles-addon-image"/>';
		}

		$class_to_filter = 'scp-circles vc_align_' . $alignment;
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->shortcode_name, $atts );
		$css_class .= ' ' . $uid;

      	ob_start();
        ?>

        <div class="<?php echo esc_attr( trim( $css_class ) ); ?>">
        	<div class="circle-main">
        		<h4><?php echo $top_text; ?></h4>
        		<h3><?php echo $bottom_text; ?></h3>
        	</div>
        	<div class="circle-middle"></div>
        	<div class="circle-small"></div>
        	<?php echo $img['thumbnail'] ?>
        	<?php if ( $a_href ) : ?>
				<a class="link"
				   href="<?php echo esc_attr( $a_href ); ?>"
					<?php if ( $a_title ) : ?>
						title="<?php echo esc_attr( $a_title ); ?>"
					<?php endif; ?>
					<?php if ( $a_target ) : ?>
						target="<?php echo esc_attr( $a_target ); ?>"
					<?php endif; ?>
				></a>
			<?php endif; ?>
        </div>
        <?php
		return ob_get_clean();
	}
}

new SCP_Circles();
