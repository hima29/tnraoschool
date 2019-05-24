<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Schedule {

	protected $shortcode_name = 'scp_schedule';
	

	function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			"name"        => esc_html__( 'Schedule', 'superwise-plugin' ),
			"description" => '',
			"base"        => $this->shortcode_name,
			"class"       => "",
			"controls"    => "full",
			"icon"        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category"    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			"params"      => array(
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Schedule', 'superwise-plugin' ),
					'param_name'  => 'schedule',
					'description' => esc_html__( 'Pipe separated list of items. %% is row separator. Example: item 1 | time 1 %% item 2 | time 2', 'superwise-plugin' ),
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
			'schedule' => '',
			'css'      => '',
			'el_class' => '',
		), $atts ) );

		if ( ! $schedule ) {
			return;
		}

		$rows = explode( '%%', $schedule );

		$class_to_filter = 'schedule';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->shortcode_name, $atts );

		ob_start();
		?>
		<ul class="<?php echo esc_attr( $css_class ); ?>">

			<?php foreach ( $rows as $key => $row ) : ?>
				<?php
				$parts = explode( '|', $row );
				?>
				<?php if ( count( $parts ) == 2 ) : ?>
					<li class="<?php echo esc_attr( $key % 2 ? 'even' : 'odd' ); ?>">
						<span class="left">
							<?php echo trim( $parts[0] ); ?>
						</span>
						<span class="right">
							<?php echo trim( $parts[1] ); ?>
						</span>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>

		<?php
		 return ob_get_clean();	
	}
}

new SCP_Schedule();
