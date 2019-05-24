<?php
/*
Plugin Name: Extend Visual Composer Plugin Example
Plugin URI: http://wpbakery.com/vc
Description: Extend Visual Composer with your own set of shortcodes.
Version: 0.1.1
Author: WPBakery
Author URI: http://wpbakery.com
License: GPLv2 or later
*/

/*
This example/starter plugin can be used to speed up Visual Composer plugins creation process.
More information can be found here: http://kb.wpbakery.com/index.php?title=Category:Visual_Composer
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class MSM_Content_Box {

	protected $shortcode_name = 'msm_content_box';
	protected $title = 'Mega Menu Content Box';
	protected $description = 'Add this widget to page content as container for other widgets. It displays a mega menu on hover';
	protected $textdomain = 'mega-submenu';

	function __construct() {
		// We safely integrate with VC with this hook
		add_action( 'init', array( $this, 'integrateWithVC' ) );

		// Use this when creating a shortcode addon
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );

		// Register CSS and JS
		add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );

	}

	public function integrateWithVC() {
		// Check if Visual Composer is installed
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			// Display notice that Visual Compser is required
//			add_action( 'admin_notices', array( $this, 'showVcVersionNotice' ) );

			return;
		}

		$mega_menus_array = array( 'None' => '' );
		$mega_menus       = get_posts( array(
			'post_type'      => Mega_Submenu::POST_TYPE,
			'posts_per_page' => - 1,
			'orderby'        => 'post_title',
			'order'          => 'ASC',
		) );
		foreach ( $mega_menus as $layout_block ) {
			$mega_menus_array[ $layout_block->post_title ] = $layout_block->ID;
		}

		/*
		Add your Visual Composer logic here.
		Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

		More info: http://kb.wpbakery.com/index.php?title=Vc_map
		*/
		vc_map( array(
			"name"        => __( $this->title, $this->textdomain ),
			"description" => __( $this->description, $this->textdomain ),
			"base"        => $this->shortcode_name,
			"class"       => "",
			"controls"    => "full",
			'js_view'     => 'VcColumnView',
			'as_parent'   => array( 'except' => $this->shortcode_name ),
			"icon"        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category"    => __( 'Mega Menu', 'js_composer' ),
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			"params"      => array(
				array(
					'type'        => 'vc_link',
					'heading'     => __( 'URL (Link)', 'js_composer' ),
					'param_name'  => 'link',
					'description' => __( 'Add link.', 'js_composer' ),
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'min_height',
					'heading'     => __( 'Min Height', $this->textdomain ),
					'description' => __( 'Minimal height of the Content Box. Use only if you need specific height. Value in px or %.', $this->textdomain ),
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'use_overlay',
					'heading'    => __( 'Use Overlay', $this->textdomain ),
					'value'      => array(
						'No'  => 'no',
						'Yes' => 'yes'
					),
					'group'      => 'Overlay',
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'overlay_title',
					'heading'    => __( 'Overlay Title', $this->textdomain ),
					'dependency' => array( 'element' => 'use_overlay', 'value' => 'yes' ),
					'group'      => 'Overlay',
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'overlay_subtitle',
					'heading'    => __( 'Overlay Subtitle', $this->textdomain ),
					'dependency' => array( 'element' => 'use_overlay', 'value' => 'yes' ),
					'group'      => 'Overlay',
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'el_class',
					'heading'     => __( 'Extra class name', $this->textdomain ),
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', $this->textdomain ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'admin_label' => true,
					'heading'     => __( 'Mega Menu', $this->textdomain ),
					'param_name'  => 'mega_menu_id',
					'value'       => $mega_menus_array,
					'description' => __( 'Mega Menu to show on hover. If you are using this widget only as container for other widgets. Leave this set to None', $this->textdomain ),
					'group'       => 'Mega Menu',
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'submenu_top',
					'heading'    => __( 'Mega Menu Offset Top', $this->textdomain ),
					'value'      => '0',
					'group'      => 'Mega Menu',
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'mega_menu_width',
					'heading'     => __( 'Override Width', $this->textdomain ),
					'value'       => '',
					'description' => __( 'Value in px or %. If not set it will use default settings for selected Mega Menu.', $this->textdomain ),
					'group'       => 'Mega Menu',
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'mega_menu_position',
					'heading'     => __( 'Override Position', $this->textdomain ),
					'value'       => array(
						'Left'        => 'left',
						'Left Edge'   => 'left_edge',
						'Center'      => 'center',
						'Center Full' => 'center_full',
						'Right'       => 'right',
						'Right Edge'  => 'right_edge',
					),
					'group'       => 'Mega Menu',
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'mega_menu_trigger',
					'heading'     => __( 'Override Trigger', $this->textdomain ),
					'value'       => array(
						'Click' => 'click',
						'Hover' => 'hover',
					),
					'description' => __( 'If not set it will use default settings for selected Mega Menu.', $this->textdomain ),
					'group'       => 'Mega Menu',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Bg Color', $this->textdomain ),
					'param_name' => 'custom_background_color',
					'group'      => __( 'Design Options', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Hover Bg Color', $this->textdomain ),
					'param_name' => 'hover_bg_color',
					'group'      => __( 'Design Options', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_top',
					'heading'    => __( 'Top', $this->textdomain ),
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_left',
					'heading'    => __( 'Left', $this->textdomain ),
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_spread',
					'heading'    => __( 'Spread', $this->textdomain ),
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box Shadow Color', $this->textdomain ),
					'param_name' => 'box_shadow_color',
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_top_hover',
					'heading'    => __( 'Top Hover', $this->textdomain ),
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_left_hover',
					'heading'    => __( 'Left Hover', $this->textdomain ),
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_spread_hover',
					'heading'    => __( 'Spread Hover', $this->textdomain ),
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => __( 'Box Shadow Color Hover', $this->textdomain ),
					'param_name' => 'box_shadow_color_hover',
					'group'      => __( 'Box Shadow', 'js_composer' ),
				),

			)
		) );
	}

	/*
	Shortcode logic how it should be rendered
	*/
	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'mega_menu_id'            => '',
			'mega_menu_width'         => '',
			'mega_menu_position'      => 'left',
			'mega_menu_trigger'       => 'click',
			'submenu_top'             => '0',
			'use_overlay'             => 'no',
			'overlay_title'           => '',
			'overlay_subtitle'        => '',
			'link'                    => '',
			'min_height'              => '',
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

		$class_to_filter = '';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->shortcode_name, $atts );

		$link     = vc_build_link( $link );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'];


		$style = '';


		if ( $min_height ) {
			$style .= 'min-height:' . msm_sanitize_size( $min_height ) . ';';
		}

		/**
		 * Custom BG Color
		 */
		if ( $custom_background_color ) {
			$style .= 'background-color:' . $custom_background_color . ';';
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
		}


		if ( $style ) {
			$style = 'style="' . $style . '"';
		}


		/**
		 * BG Hover
		 */
		$hover = 'onMouseOver="this.style.backgroundColor=\'' . $hover_bg_color . '\'"';
		$hover .= ' onMouseOut="this.style.backgroundColor=\'' . $custom_background_color . '\'"';

		$uid = uniqid( 'mega-menu-content-box-' );

		$mega_menu_output          = '';
		$content_box_wrapper_class = 'msm-content-box';


		if ( $mega_menu_id && ( $mega_menu = get_post( $mega_menu_id ) ) && ! is_wp_error( $mega_menu ) ) {

			// We have a mega menu to display.
			if ( ! empty( $mega_menu->post_content ) ) {

				$wrapper_classes           = apply_filters( Mega_Submenu::FILTER_CSS_CLASSES, Mega_Submenu::$css_classes );
				$content_box_wrapper_class = 'msm-link ' . Mega_Submenu::CSS_CLASS_WRAP;

				if ( $mega_menu_trigger == 'hover' ) {
					$content_box_wrapper_class .= ' msm-hover';
				} else {
					$content_box_wrapper_class .= ' msm-click';
				}


				$mega_menu_style = '';


				$mega_menu_style .= 'top:' . msm_sanitize_size( $submenu_top );


				if ( $mega_menu_style ) {
					$mega_menu_style = 'style="' . $mega_menu_style . '"';
				}

				$mega_menu_output .= "<!-- {$mega_menu->post_title} -->";
				$mega_menu_output .= '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '" ' . $mega_menu_style;

				$mega_menu_item_width = $mega_menu_width ? $mega_menu_width : msm_get_rwmb_meta( 'width', $mega_menu_id );
				if ( $mega_menu_item_width ) {
					$mega_menu_item_width = strstr( $mega_menu_item_width, '%' ) ? $mega_menu_item_width : (int) $mega_menu_item_width;

					$mega_menu_output .= ' data-width="' . $mega_menu_item_width . '"';
				}

				$mega_menu_item_position = $mega_menu_position ? $mega_menu_position : msm_get_rwmb_meta( 'position', $mega_menu_id );
				if ( $mega_menu_item_position ) {
					$mega_menu_output .= ' data-position="' . $mega_menu_item_position . '"';
				}

				$mega_menu_item_margin = msm_get_rwmb_meta( 'margin', $mega_menu_id );
				if ( $mega_menu_item_margin ) {
					$mega_menu_output .= ' data-margin="' . (int) $mega_menu_item_margin . '"';
				}

				$mega_menu_item_offset_left = msm_get_rwmb_meta( 'offset_left', $mega_menu_id );
				if ( $mega_menu_item_offset_left ) {
					$mega_menu_output .= ' data-offset-left="' . (int) $mega_menu_item_offset_left . '"';
				}

				$mega_menu_output .= ">\n";
				$mega_menu_output .= do_shortcode( $mega_menu->post_content );
				$mega_menu_output .= "</div>\n";
			}


		}


		ob_start();

		?>
		<div class="<?php echo $content_box_wrapper_class; ?>">

			<div id="<?php echo $uid; ?>"
			     class="<?php echo esc_attr( $css_class ); ?>" <?php echo $style; ?> <?php echo $hover; ?>>
				<?php if ( $use_overlay == 'yes' ) : ?>
					<div class="overlay">
						<div class="content">
							<?php if ( $overlay_title ) : ?>
								<div class="title">
									<?php echo $overlay_title; ?>
								</div>
							<?php endif; ?>
							<?php if ( $overlay_subtitle ) : ?>
								<div class="subtitle">
									<?php echo $overlay_subtitle; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
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
				<?php if ( $mega_menu_output ) : ?>
					<div class="msm-submenu-container">
						<?php echo $mega_menu_output; ?>
					</div>
					<?php
					echo msm_get_vc_post_custom_css( $mega_menu_id );
					echo msm_get_vc_shortcodes_custom_css( $mega_menu_id );
					?>
				<?php endif; ?>
			</div>
			<?php if ( $box_shadow_hover ) : ?>
				<script>
					(function ($) {
						$('#<?php echo $uid; ?>').hover(function () {
							$(this).css({
								boxShadow: '<?php echo $box_shadow_hover; ?>'
							});
						}, function () {
							$(this).css({
								boxShadow: '<?php echo $box_shadow; ?>'
							});
						});
					})(jQuery);
				</script>
			<?php endif; ?>
		</div>

		<?php
		$content = ob_get_clean();

		return $content;
	}

	/*
	Load plugin css and javascript files which you may need on front end of your site
	*/
	public function loadCssAndJs() {

	}

	/*
	Show notice if your plugin is activated but Visual Composer is not
	*/
	public function showVcVersionNotice() {
		$plugin_data = get_plugin_data( __FILE__ );
		echo '
        <div class="updated">
          <p>' . sprintf( __( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', $this->textdomain ), $plugin_data['Name'] ) . '</p>
        </div>';
	}
}

// Finally initialize code
new MSM_Content_Box();

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_msm_content_box extends WPBakeryShortCodesContainer {
	}
}
