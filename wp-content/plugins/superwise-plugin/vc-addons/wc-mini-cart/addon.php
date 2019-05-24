<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_WC_Mini_Cart {

	protected $shortcode_name = 'scp_wc_mini_cart';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			"name"        => esc_html__( 'WC Mini Cart', 'superwise-plugin' ),
			"description" => esc_html__( 'Mini Cart', 'superwise-plugin' ),
			"base"        => $this->shortcode_name,
			"class"       => "",
			"controls"    => "full",
			"icon"        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category"    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			"params"      => array(
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Position', 'superwise-plugin' ),
					'param_name'  => 'position',
					'value'       => array(
						'Left'   => 'vc_pull-left',
						'Right'  => 'vc_pull-right',
						'Center' => 'vc_txt_align_center',
					),
					'description' => esc_html__( 'Float.', 'superwise-plugin' )
				),
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

			)
		) );
	}

	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'position' => 'vc_pull-left',
			'css'      => '',
			'el_class' => '',
		), $atts ) );

		$class_to_filter = 'wh-minicart-wrapper ' . $position;
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->shortcode_name, $atts );

		ob_start();

		if ( function_exists( 'ed_school_wc_print_mini_cart' ) ) {
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php ed_school_wc_print_mini_cart(); ?>
		</div>
		<?php
		}
		return ob_get_clean();
	}

}

new SCP_WC_Mini_Cart();
