<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Hexagon_Icon {

	protected $namespace = 'scp_hexagon_icon';

	public function __construct() {

		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'loadCssAndJs' ) );	
		add_action( "scp_load_styles_{$this->namespace}", array( $this, 'load_css' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			

			return;
		}

		
		vc_map( array(
			'name'             => esc_html( 'Hexagon Icon', 'superwise-plugin' ),
			'description'      => '',
			'base'             => $this->namespace,
			'class'            => '',
			'controls'         => 'full',
			'icon'             => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ), // or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'         => 'Aislin',
			'admin_enqueue_js' => array( plugins_url( 'assets/admin-theme-icon.js', __FILE__ ) ),
			// This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'           => array(
				array(
					'type'        => 'iconpicker',
					'param_name'  => 'theme_icon',
					'heading'     => esc_html__( 'Icon', 'superwise-plugin' ),
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
					'description' => esc_html__( 'Select icon from library.', 'superwise-plugin' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Position Absolute?', 'superwise-plugin' ),
					'param_name' => 'position_absolute',
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Use Hexagon with a line?', 'superwise-plugin' ),
					'param_name' => 'hexagon_with_line',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Hexagon Width', 'superwise-plugin' ),
					'param_name'  => 'hexagon_width',
					'description' => esc_html__( 'Value in px. Enter number only.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Icon Position Top', 'superwise-plugin' ),
					'param_name'  => 'icon_position_top',
					'description' => esc_html__( 'Value in px. Enter number only.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Icon Position Left', 'superwise-plugin' ),
					'param_name'  => 'icon_position_left',
					'description' => esc_html__( 'Value in px. Enter number only.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Icon Font Size', 'superwise-plugin' ),
					'param_name'  => 'icon_font_size',
					'description' => esc_html__( 'Value in px. Enter number only.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Icon alignment', 'js_composer' ),
					'param_name'  => 'alignment',
					'value'       => array(
						__( 'Left', 'js_composer' )   => 'left',
						__( 'Right', 'js_composer' )  => 'right',
						__( 'Center', 'js_composer' ) => 'center',
					),
					'description' => esc_html__( 'Select alignment.', 'js_composer' ),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'URL (Link)', 'js_composer' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'Add link to icon.', 'js_composer' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Hexagon Background Color', 'superwise-plugin' ),
					'param_name'  => 'color_hexagon_bg',
					'edit_field_class' => 'vc_col-sm-4',
					'description' => esc_html__( 'If color is not set, theme accent color will be used.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Hexagon Color', 'superwise-plugin' ),
					'param_name'  => 'color_hexagon',
					'edit_field_class' => 'vc_col-sm-4',
					'description' => esc_html__( 'If color is not set, theme accent color will be used.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Icon Color', 'superwise-plugin' ),
					'param_name'  => 'color_icon',
					'edit_field_class' => 'vc_col-sm-4',
					'description' => esc_html__( 'If color is not set, theme accent color will be used.', 'superwise-plugin' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Use Theme Accent Color for Hover', 'superwise-plugin' ),
					'param_name' => 'hover_accent_color',
				),

				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Hexagon Background Hover Color', 'superwise-plugin' ),
					'param_name'  => 'hover_color_hexagon_bg',
					'edit_field_class' => 'vc_col-sm-4',
					'description' => esc_html__( 'Will not be used if Use Accent Color is checked.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Hexagon Hover Color', 'superwise-plugin' ),
					'param_name'  => 'hover_color_hexagon',
					'edit_field_class' => 'vc_col-sm-4',
					'description' => esc_html__( 'Will not be used if Use Accent Color is checked.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Icon Hover Color', 'superwise-plugin' ),
					'param_name'  => 'hover_color_icon',
					'edit_field_class' => 'vc_col-sm-4',
					'description' => esc_html__( 'Will not be used if Use Accent Color is checked.', 'superwise-plugin' ),
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
			)
		) );
	}

	public function load_css($atts) {

		$uid = SCP_Assets::get_uid( $this->namespace, $atts );

		extract( shortcode_atts( array(
			'position_absolute'    => '',
			'hexagon_width'        => '150',
			'icon_position_top'    => '68',
			'icon_position_left'   => '85',
			'icon_font_size'       => '',
			'alignment'            => 'left',
			'color_hexagon_bg'     => '',
			'color_hexagon'        => '',
			'color_icon'           => '',
			'hover_color_hexagon_bg'  => '',
			'hover_color_hexagon'  => '',
			'hover_color_icon'     => '',
			'hover_accent_color'   => '',
		), $atts ) );

		$theme_accent_color = false;
		if ( $hover_accent_color == 'true' && function_exists( 'superwise_get_option' ) ) {
			$theme_accent_color = superwise_get_option( 'global-accent-color' );
		}

		$style = '';
		$style .= ".$uid{";

		if ( $position_absolute == 'true' ) {
			$style .= 'position:absolute;'; 
		}
		// is always defined
		if ( $hexagon_width ) {
			$style .= 'width:' . (int) $hexagon_width . 'px;';
		}

		if ( $icon_font_size ) {
			$style .= 'font-size:' . (int) $icon_font_size . 'px;';
		}

		$style .= '}';

		if ($color_hexagon_bg) {
			$style .= ".$uid .st1{";
			$style .= 'fill:' . $color_hexagon_bg . ';';
			$style .= '}';
		}

		if ($color_hexagon) {
			$style .= ".$uid .st0{";
			$style .= 'fill:' . $color_hexagon . ';';
			$style .= '}';
		}

		if ($hexagon_width) {
			$style .= ".{$uid}.wh-hexagon-icon i{";
			$style .= 'top:' . $icon_position_top . 'px;';
			$style .= 'left:' . $icon_position_left . 'px;';

			if ($color_icon) {
				$style .= 'color:' . $color_icon . ';';
			}


			$style .= '}';
		}

		if ($theme_accent_color) {
			$style .= ".{$uid}:hover .st0{";
			$style .= 'fill:' . $theme_accent_color . ';';
			$style .= '}';
			$style .= ".{$uid}:hover i{";
			$style .= 'color:' . $theme_accent_color . ';';
			$style .= '}';
		} else {

			if ($hover_color_hexagon_bg) {
				$style .= ".{$uid}:hover .st1{";
				$style .= 'fill:' . $hover_color_hexagon_bg . ';';
				$style .= '}';
			}

			if ($hover_color_hexagon) {
				$style .= ".{$uid}:hover .st0{";
				$style .= 'fill:' . $hover_color_hexagon . ';';
				$style .= '}';
			}

			if ($hover_color_icon) {
				$style .= ".{$uid}:hover i{";
				$style .= 'color:' . $hover_color_icon . ';';
				$style .= '}';
			}
		} 

		if ( $style ) {
			wp_add_inline_style( 'superwise_options_style', $style );
		}
	}

	public function render( $atts, $content = null ) {

		$uid = SCP_Assets::get_uid( $this->namespace, $atts );

		extract( shortcode_atts( array(
			'position_absolute'    => '',
			'theme_icon'           => 'Text on the button',
			'hexagon_width'        => '150',
			'hexagon_with_line'    => '',
			'icon_position_top'    => '68',
			'icon_position_left'   => '85',
			'icon_font_size'       => '',
			'link'                 => '',
			'alignment'            => 'left',
			'color_hexagon_bg'     => '',
			'color_icon'           => '',
			'hover_color_hexagon_bg'  => '',
			'hover_color_hexagon'  => '',
			'hover_color_icon'     => '',
			'hover_accent_color'   => '',
			'css'                  => '',
			'el_class'             => '',
		), $atts ) );

		$class_to_filter = 'wh-hexagon-icon';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->namespace, $atts );

		$css_class .= ' ' . $uid;

		$link     = vc_build_link( $link );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'];

		ob_start();
		?>
		<?php if ( $a_href ) : ?>
				<a
					href="<?php echo esc_attr( $a_href ); ?>"
					<?php if ( $a_title ) : ?>
						title="<?php echo esc_attr( $a_title ); ?>"
					<?php endif; ?>
					<?php if ( $a_target ) : ?>
						target="<?php echo esc_attr( $a_target ); ?>"
					<?php endif; ?>
					>
			<?php endif; ?>
		<div class="<?php echo esc_attr( trim( $css_class ) ); ?>">

		<?php if ( $hexagon_with_line == 'true' ): ?>
			
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				 viewBox="0 0 73.9 109.5" style="enable-background:new 0 0 73.9 109.5;" xml:space="preserve">
			<style type="text/css">
				.st0{fill:#020202;}
			</style>
			<g id="XMLID_9_">
				<!-- <polygon id="XMLID_1_" class="st1" points="46.3,1.1 16.3,1.1 1.3,27.1 16.3,53.1 46.3,53.1 61.3,27.1 "/> -->
				<path id="XMLID_10_" class="st0" d="M58.3,67.3H27L11.3,40.2L27,13h31.3l15.6,27.1L58.3,67.3z M28.1,65.3h29l14.5-25.1L57.1,15h-29
					L13.6,40.2L28.1,65.3z"/>
			</g>
			<g id="XMLID_3_">
				<polygon id="XMLID_8_" class="st0" points="60.1,7.2 63.8,0.7 62.5,0 58.3,7.2 	"/>
				<polygon id="XMLID_4_" class="st0" points="21.7,71 0,108.7 1.3,109.5 23.4,71 	"/>
			</g>
		<?php else: ?>
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				 viewBox="0 0 62.6 54.2" style="enable-background:new 0 0 62.6 54.2;" xml:space="preserve">
			<style type="text/css">
				.st0{fill:#020202;}
				.st1{fill:transparent;}
			</style>
			<g id="XMLID_17_">
				<polygon id="XMLID_1_" class="st1" points="46.3,1.1 16.3,1.1 1.3,27.1 16.3,53.1 46.3,53.1 61.3,27.1 "/>
				<path id="XMLID_18_" class="st0" d="M46.9,54.2H15.6L0,27.1L15.6,0h31.3l15.6,27.1L46.9,54.2z M16.8,52.2h29l14.5-25.1L45.8,2h-29
					L2.3,27.1L16.8,52.2z"/>
			</g>
			</svg>
		<?php endif ?>
			<i class="<?php echo esc_html( $theme_icon ); ?>"></i>
		</div>
		<?php if ( $a_href ) : ?>
			</a>
		<?php endif; ?>

		<?php
		return ob_get_clean();

		return $content;
	}

	public function loadCssAndJs() {
		wp_register_style( 'superwise-theme-icons', get_template_directory_uri() . '/assets/css/theme-icons.css', false );
		wp_enqueue_style( 'superwise-theme-icons' );
	}
}

new SCP_Hexagon_Icon();
