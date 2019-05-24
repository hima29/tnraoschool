<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Menu {

	protected $shortcode_name = 'scp_menu';
	
	function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			"name"        => esc_html__( 'Menu', 'superwise_plugin' ),
			"description" => esc_html__( 'Choose a menu', 'superwise_plugin' ),
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
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Depth', 'superwise_plugin' ),
					'param_name'  => 'depth',
					'value'       => esc_html__( '3', 'superwise_plugin' ),
					'description' => esc_html__( 'Depth of the menu.', 'superwise_plugin' )
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Type', 'superwise_plugin' ),
					'param_name'  => 'menu_type',
					'admin_label' => true,
					'value'       => array(
						'Custom Menu'        => 'menu_custom',
						'Main Menu'          => 'menu_main',
						'Main Menu Fullwidth' => 'menu_main_fullwidth',
						'Top Menu'           => 'menu_top',
						'Mobile Menu'        => 'menu_mobile',
						'Quick Sidebar Menu' => 'menu_quick_sidebar',
					),
					'description' => esc_html__( 'Select menu type.', 'superwise_plugin' )
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Main Menu Orientation', 'superwise_plugin' ),
					'param_name'  => 'menu_orientation',
					'value'       => array(
						'Horizontal' => 'horizontal',
						'Vertical'   => 'vertical',
					),
					'dependency'  => array(
						'element' => 'menu_type',
						'value'   => 'menu_main',
					),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Menu', 'superwise_plugin' ),
					'param_name'  => 'menu',
//					'admin_label' => true,
					'value'       => array_flip( get_registered_nav_menus() ),
					'dependency'  => array(
						'element' => 'menu_type',
						'value'   => 'menu_custom',
					),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Container', 'superwise_plugin' ),
					'param_name'  => 'container',
					'value'       => array(
						'div'   => 'div',
						'nav'   => 'nav',
						'false' => 'false',
					),
					'description' => esc_html__( 'Container element.', 'superwise_plugin' ),
					'dependency'  => array(
						'element' => 'menu_type',
						'value'   => 'menu_custom',
					),
				),
				array(
					'type'       => 'textfield',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Container Class', 'superwise_plugin' ),
					'param_name' => 'container_class',
					'value'      => '',
					'dependency' => array(
						'element' => 'menu_type',
						'value'   => 'menu_custom',
					),
				),
				array(
					'type'       => 'textfield',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Container ID', 'superwise_plugin' ),
					'param_name' => 'container_id',
					'value'      => '',
					'dependency' => array(
						'element' => 'menu_type',
						'value'   => 'menu_custom',
					),
				),
				array(
					'type'       => 'textfield',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Menu Class', 'superwise_plugin' ),
					'param_name' => 'menu_class',
					'value'      => esc_html__( 'sf-menu', 'superwise_plugin' ),
					'dependency' => array(
						'element' => 'menu_type',
						'value'   => 'menu_custom',
					),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Position', 'superwise_plugin' ),
					'param_name'  => 'position',
					'value'       => array(
						'Left'             => 'vc_pull-left',
						'Right'            => 'vc_pull-right',
						'Center'           => 'wh-menu-center',
						'Center Fullwidth' => 'wh-menu-center fullwidth',
					),
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
			'depth'            => 3,
			'menu'             => 'primary_navigation',
			'menu_type'        => 'menu_custom',
			'menu_orientation' => 'horizontal',
			'container'        => 'div',
			'container_class'  => '',
			'container_id'     => '',
			'menu_class'       => 'sf-menu',
			'position'         => 'vc_pull-left',
			'css'              => ''
		), $atts ) );

		if ( $menu_orientation == 'vertical' ) {
			$menu_class = $menu_class . ' wh-menu-vertical';
		}

		$args = array(
			'theme_location' => $menu,
			'menu_class'     => $menu_class,
			'depth'          => $depth,
			'container'      => $container != 'false' ? $container : false,
			'container_id'   => $container_id,
			'fallback_cb'    => false
		);


		if ( $menu_type == 'menu_main' || $menu_type == 'menu_main_fullwidth' ) {
			$args['theme_location'] = 'primary_navigation';
			$args['menu_class']     = 'sf-menu wh-menu-main';
			$args['container']      = 'div';
			$args['container_id']   = 'cbp-menu-main';

			if ($menu_type == 'menu_main') {
				$container_class = 'cbp-container';
			}

		} elseif ( $menu_type == 'menu_top' ) {
			$args['theme_location'] = 'secondary_navigation';
			$args['menu_class']     = 'sf-menu wh-menu-top';
			$args['container']      = 'div';

		} elseif ( $menu_type == 'menu_mobile' ) {

			$args['theme_location'] = 'primary_navigation';

			if ( has_nav_menu( 'mobile_navigation' ) ) {
				$args['theme_location'] = 'mobile_navigation';
			}

			$args['menu_class']     = 'respmenu';
			if ( class_exists( 'Ed_School_Mobile_Menu_Walker' ) ) {
				$args['walker'] = new Ed_School_Mobile_Menu_Walker();
			}
			ob_start();
			include 'templates/menu-mobile.php';
			return ob_get_clean();

		} elseif ( $menu_type == 'menu_quick_sidebar' ) {
			$args['theme_location'] = 'quick_sidebar_navigation';
			$args['menu_class']     = 'sf-menu wh-menu-vertical';
			$args['container']      = 'div';
			$position = '';
		}
		global $post_id;
		if (
			($menu_type == 'menu_custom' || $menu_type == 'menu_main' )
			&& $menu == 'primary_navigation'
		) {
			if ( function_exists( 'rwmb_meta' ) && (int) rwmb_meta( 'superwise_use_custom_menu', array(), $post_id ) ) {
				$custom_menu_location = rwmb_meta( 'superwise_custom_menu_location', array(), $post_id );
				if ( ! empty( $custom_menu_location ) ) {
					$args['theme_location'] = $custom_menu_location;
				}
			}

		}

		$container_class = $container_class . ' ' . $position;
		$container_class .= vc_shortcode_custom_css_class( $css, ' ' );
		$args['container_class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $container_class, $this->shortcode_name, $atts );

		ob_start();
		wp_nav_menu( $args );

		return ob_get_clean();
	}

}
new SCP_Menu();
