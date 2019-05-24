<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Theme_Map {

	protected $namespace = 'scp_theme_map';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_action( 'wp', array( $this, 'check_shortcodes' ) );

		add_shortcode( $this->namespace, array( $this, 'render' ) );
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
			'name'        => esc_html( 'Theme Map', 'superwise-plugin' ),
			'description' => '',
			'base'        => $this->namespace,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ), // or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Map Height', 'superwise-plugin' ),
					'param_name'  => 'height',
					'value'       => '400',
					'description' => esc_html__( 'Value in px. Enter number only.', 'superwise-plugin' ),

				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Latitude', 'superwise-plugin' ),
					'param_name'  => 'latitude',
					'value'       => '40.7143528',
					'description' => sprintf( esc_html__( 'Visit %s to get coordinates.', 'superwise-plugin' ), '<a href="http://www.mapcoordinates.net/en" target="_blank">' . esc_html__( 'this site', 'superwise-plugin' ) . '</a>' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Longitude', 'superwise-plugin' ),
					'param_name'  => 'longitude',
					'value'       => '-74.0059731',
					'description' => sprintf( esc_html__( 'Visit %s to get coordinates.', 'superwise-plugin' ), '<a href="http://www.mapcoordinates.net/en" target="_blank">' . esc_html__( 'this site', 'superwise-plugin' ) . '</a>' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Zoom Level', 'superwise-plugin' ),
					'param_name' => 'zoom',
					'value'      => '10',
				),
				array(
					'type'        => 'textarea_safe',
					'heading'     => esc_html__( 'Snazzy Maps Style', 'superwise-plugin' ),
					'param_name'  => 'snazzy_style',
					'description' => sprintf( esc_html__( 'Visit %s to create your map style. Copy JavaScript Style Array and paste here.', 'superwise-plugin' ), '<a href="https://snazzymaps.com/style/15/subtle-grayscale" target="_blank">' . esc_html__( 'Example', 'superwise-plugin' ) . '</a>' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Disable Map Zoom Scroll', 'superwise-plugin' ),
					'param_name' => 'disable_scroll',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'superwise-plugin' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'superwise-plugin' ),
				),
			)
		) );
	}

	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'height'         => '400',
			'latitude'       => '40.7143528',
			'longitude'      => '-74.0059731',
			'zoom'           => '10',
			'snazzy_style'   => 'false',
			'disable_scroll' => '',
			'el_class'       => '',
		), $atts ) );
		// $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class, $this->namespace, $atts );

		$uid = uniqid( 'theme-map-' );

		$snazzy_style = trim( vc_value_from_safe( $snazzy_style ) );
		$snazzy_style = str_replace( '`', '', $snazzy_style );
		// make sure it is properly formated
		$snazzy_style = $snazzy_style ? json_decode( $snazzy_style ) : '[]';

		$scroll_wheel = $disable_scroll === 'true' ? 'false' : 'true';
		$zoom         = absint( $zoom );
		$longitude    = floatval( $longitude );
		$latitude     = floatval( $latitude );

		$inline_js = "
		jQuery(function () {
			if (google) {

				var el = '{$uid}';
				var zoom = {$zoom};
				var latitude = {$latitude};
				var longitude = {$longitude};
				var snazzyStyle = '{$snazzy_style}';
				var scrollwheel = {$scroll_wheel};

				google.maps.event.addDomListener(window, 'load', function () {
					var mapOptions = {
						zoom: zoom,
						center: new google.maps.LatLng(latitude, longitude),
						scrollwheel: scrollwheel,
						styles: snazzyStyle
					};
					var mapElement = document.getElementById(el);
					var map = new google.maps.Map(mapElement, mapOptions);
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(latitude, longitude),
						map: map,
						title: ''
					});
				});
			}
		});
		";

		wp_enqueue_script( 'gmaps' );
		wp_add_inline_script( 'gmaps', $inline_js );

		ob_start();
		?>
		<div id="<?php echo esc_attr( $uid ); ?>" style="width: 100%; height:<?php echo (int) $height; ?>px;"
		     class="<?php echo esc_attr( $css_class ); ?>"></div>
		<?php
		return ob_get_clean();
	}

	public function loadCssAndJs() {
		$url          = 'https://maps.googleapis.com/maps/api/js';
		$user_api_key = scp_get_wheels_option( 'gmaps_api_key' );
		if ( $user_api_key ) {
			$url = $url . '?key=' . $user_api_key;
		}
		wp_register_script( 'gmaps', $url, array( 'jquery' ) );
	}

}

new SCP_Theme_Map();
