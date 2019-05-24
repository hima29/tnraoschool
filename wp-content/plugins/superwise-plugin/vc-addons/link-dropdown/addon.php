<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Link_Dropdown {

	protected $shortcode_name = 'scp_link_dropdown';
	protected $shortcode_name_item = 'scp_link_dropdown_item';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );

		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
		add_shortcode( $this->shortcode_name_item, array( $this, 'render_item' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			"name"        => esc_html__( 'Link Dropdown', 'superwise-plugin' ),
			"description" => esc_html__( 'Display a dropdown with links', 'superwise-plugin' ),
			"base"        => $this->shortcode_name,
			"class"       => '',
			"controls"    => "full",
			'as_parent'   => array( 'only' => $this->shortcode_name_item ),
			"icon"        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category"    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'js_view'                 => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => true,
			"params"      => array(
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => __( 'Title', 'superwise-plugin' ),
					'param_name'  => 'dropdown_title',
					'value'       => '',
					'description' => __( 'This displays as first item in a dropdown.', 'superwise-plugin' )
				)
				
			)
		) );

		vc_map( array(
			'name'            => __( 'Link Dropdown Item', 'superwise-plugin' ),
			'description'     => __( '', 'superwise-plugin' ),
			'base'            => $this->shortcode_name_item,
			'class'           => '',
			'controls'        => 'full',
			'as_child'        => array( 'only' => $this->shortcode_name ),
			'icon'            => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'        => __( 'Aislin', 'js_composer' ),
			'content_element' => true,
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'          => array(
				array(
					'type'        => 'vc_link',
					'heading'     => __( 'URL (Link)', 'js_composer' ),
					'param_name'  => 'link',
					'description' => __( 'Add link.', 'js_composer' ),
					'admin_label' => true,
				),
			)
		) );
	}

	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'dropdown_title' => 'Select a link',
			'css'   => '',
		), $atts ) );

		if (!$content) {
			return;
		}

		$api_key = get_option('ac_calendar_api_key');
		if (!$api_key) {
			return;
		}

		$uid = uniqid('google-calendar-');

		$container_class = 'scp-link-dropdown';
		$container_class .= vc_shortcode_custom_css_class( $css, ' ' );
		$container_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $container_class, $this->shortcode_name, $atts );

		ob_start();
		?>
			<div class="<?php echo esc_attr( $container_class ); ?>">
				
				<select id="<?php echo $uid; ?>">
					<option value=""><?php echo esc_html( $dropdown_title ); ?></option>
					<?php echo do_shortcode( $content ); ?>
				</select>

				<script>
					jQuery(function ($) {
						$('#<?php echo $uid?>').change(function() {

							$this = $(this);
							$url = $this.val();
							if ($url) {
								window.location.href = $url;
							}

						});
					});
				</script>

			</div>
		<?php

		$content = ob_get_clean();

		return $content;
	}

	public function render_item($atts) {
		extract( shortcode_atts( array(
			'link' => ''
		), $atts ) );

		if ($link) {
			$link = vc_build_link( $link );
			$url = $link['url'];
			$title = $link['title'];
			return "<option value=\"$url\">$title</option>";
		}
		return;
	}

}

new SCP_Link_Dropdown();

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_scp_link_dropdown extends WPBakeryShortCodesContainer {
	}
	class WPBakeryShortCode_scp_link_dropdown_item extends WPBakeryShortCodesContainer {
	}
}

