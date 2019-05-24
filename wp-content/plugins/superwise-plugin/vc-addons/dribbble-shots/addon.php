<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Dribbble_Shots {

	protected $shortcode_name = 'scp_dribbble_shots';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_action( 'wp', array( $this, 'check_shortcodes' ) );

		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function check_shortcodes() {
		if ( ! is_admin() ) {
			global $post;
			if ( $post && strpos( $post->post_content, $this->shortcode_name ) != false ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
			}
		}
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			"name"        => esc_html__( 'Dribbble Shots', 'superwise-plugin' ),
			"description" => '',
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
					'heading'     => esc_html__( 'Token', 'superwise-plugin' ),
					'param_name'  => 'token',
					'description' => esc_html__( 'You need to generate token for Dribble app in order to use this widget.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Username', 'superwise-plugin' ),
					'param_name'  => 'user',
					'description' => esc_html__( 'Dribbble username.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Number of Items', 'superwise-plugin' ),
					'param_name'  => 'number_of_items',
					'description' => esc_html__( 'Number of items to fetch.', 'superwise-plugin' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Number of Columns', 'superwise-plugin' ),
					'param_name' => 'number_of_columns',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Shot Width', 'superwise-plugin' ),
					'param_name' => 'shot_width',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Gap', 'superwise-plugin' ),
					'param_name' => 'gap',
					'description' => esc_html__( 'Gap between shots.', 'superwise-plugin' ),
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
			'token'             => '',
			'user'              => '',
			'number_of_items'   => 6,
			'number_of_columns' => 3,
			'shot_width'        => '',
			'gap'               => 15,
			'position'          => 'vc_pull-left',
			'css'               => '',
			'el_class'          => '',
		), $atts ) );

		if ( ! $token || ! $user ) {
			return;
		}

		$class_to_filter = 'dribble-shots ' . $position;
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->shortcode_name, $atts );

		$settings = json_encode( array(
			'token'             => $token,
			// 'token'             => 'f688ac519289f19ce5cebc1383c15ad5c02bd58205cd83c86cbb0ce09170c1b4',
			'user'              => $user,
			// 'user'              => 'tylergaw',
			'number_of_items'   => $number_of_items,
			'number_of_columns' => $number_of_columns,
			'shot_width'        => scp_sanitize_size( $shot_width ),
			'gap'               => scp_sanitize_size( $gap ),
		) );

		$inline_js = "
		jQuery(function ($) {
			var settings = {$settings};
			$.jribbble.setToken(settings.token);

			$.jribbble.users(settings.user).shots({per_page: settings.number_of_items}).then(function (shots) {
				var html = [];
				var style = '';
				if (settings.shot_width) {
					style += 'width:' + settings.shot_width + ';';
				}
				if (settings.gap) {
					style += 'padding-right:' + settings.gap + ';';
					style += 'padding-bottom:' + settings.gap + ';';
				}
				shots.forEach(function (shot, i) {
					var itemStyle = style;

					itemStyle = 'style=\"' + itemStyle + '\"';

					html.push('<li class=\"dribble-shots--shot\"' + itemStyle + '>');
					html.push('<a href=\"' + shot.html_url + '\" target=\"_blank\">');
					html.push('<img src=\"' + shot.images.normal + '\">');
					html.push('</a></li>');
				});
				$('.dribble-shots').html(html.join(''));
			});
		});
		";

		wp_enqueue_script( 'jribbble' );
		wp_add_inline_script( 'jribbble', $inline_js );

		ob_start();
		?>
		<ul class="<?php echo esc_attr( $css_class ); ?>"></ul>
		<?php
		return ob_get_clean();
	}

	public function loadCssAndJs() {
		wp_register_script( 'jribbble', plugins_url( 'assets/jribbble.min.js', __FILE__ ), array( 'jquery' ) );
	}
}

new SCP_Dribbble_Shots();
