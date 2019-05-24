<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/aislin/portfolio
 * @since      1.0.0
 *
 * @package    Mega_Submenu
 * @subpackage Mega_Submenu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mega_Submenu
 * @subpackage Mega_Submenu/public
 * @author     aislin <aislin.themes@gmail.com>
 */
class Mega_Submenu_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Flagging when we are rendering Mega Menus.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      bool $rendering Rendering flag.
	 */
	protected $rendering = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-woocommerce', plugin_dir_url( __FILE__ ) . 'css/woocommerce.css', array(), $this->version, 'all' );
		if ( apply_filters( Mega_Submenu::FILTER_LOAD_COMPILED_STYLE, true ) ) {
			msm_add_compiled_style();
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/msm-main.min.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Get filtered menu location
	 *
	 * @since    1.0.0
	 */
	public function get_menu_location() {
		return msm_get_menu_location_primary();
	}

	/**
	 * Get filtered custom theme mobile menu location
	 *
	 * @since    1.0.0
	 */
	public function get_menu_location_theme_mobile() {
		return msm_get_menu_location_theme_mobile();
	}

	/**
	 * Filtered WC Templates in Mega Menus
	 *
	 * @since    1.0.0
	 */
	public function filter_wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {

		if ( ! $this->isRendering() ) {
			return $located;
		}

		$file = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/woocommerce/' . $template_name;

		if ( file_exists( $file ) ) {
			$located = $file;
		}

		return $located;
	}


	/**
	 * Filtered WC Template Parts in Mega Menus
	 *
	 * @since    1.0.0
	 */
	public function filter_wc_get_template_part( $template, $slug, $name ) {

		if ( ! $this->isRendering() ) {
			return $template;
		}


		$template = plugin_dir_path( dirname( __FILE__ ) ) . "public/partials/woocommerce/{$slug}-{$name}.php";
		if ( file_exists( $template ) ) {
			return $template;
		}

		return $template;
	}

	/**
	 * Flag mobile menu start.
	 *
	 * @since    1.1.4
	 */
	public function filter_pre_nav_menu( $content, $args ) {

		$mobile_menu_location = $this->get_menu_location_theme_mobile();
		if ( isset( $args->theme_location ) && $args->theme_location == $mobile_menu_location ) {
			msm_mobile_menu_render_start();
		}

		return $content;
	}

	/**
	 * Flag mobile menu end.
	 *
	 * @since    1.1.4
	 */
	public function filter_wp_nav_menu( $nav_menu, $args ) {

		$mobile_menu_location = $this->get_menu_location_theme_mobile();
		if ( isset( $args->theme_location ) && $args->theme_location == $mobile_menu_location ) {
			msm_mobile_menu_render_end();
		}

		return $nav_menu;
	}

	/**
	 * Add custom CSS class to menu item that has mega menu assigned.
	 *
	 * $args     set to null because some themes use this filter and do not provide default number of arguments
	 * @since    1.0.0
	 */
	public function filter_nav_menu_item_css_class( $classes, $item, $args = null ) {


		if ( $item->menu_item_parent == '0' ) {
			$classes[] = Mega_Submenu::CSS_CLASS_ITEM_TOP_LEVEL;
		}

		$msm_menu_item_class = Mega_Submenu::CSS_CLASS_ITEM . ' menu-item-has-children';

		$mega_menu_id = get_post_meta( $item->ID, Mega_Submenu::META_ID, true );
		if ( ! empty( $mega_menu_id ) && ( $mega_menu = get_post( $mega_menu_id ) ) && ! is_wp_error( $mega_menu ) ) {
			// We have a mega menu to display.
			$classes[] = $msm_menu_item_class;

			if ( msm_in_mobile_menu() ) {
				$classes[] = 'msm-mobile';
			} else {
				$trigger = msm_get_rwmb_meta( 'trigger', $mega_menu_id );
				if ( $trigger == 'click' ) {
					$classes[] = 'msm-click';
				} else {
					$classes[] = 'msm-hover';
				}

			}

			$classes = apply_filters( 'msm_filter_menu_item_css_class', $classes, $args ? $args->theme_location : null );
		}

		return $classes;
	}


	/**
	 * Add Visual Composer CSS to head
	 *
	 * @since    1.0.0
	 */
	public function add_vc_css() {

		if ( class_exists( 'MSM_VC' ) ) {
			MSM_VC::print_vc_css( $this->get_menu_location() );
			MSM_VC::print_vc_css( $this->get_menu_location_theme_mobile() );
		}

	}

	public function display_mega_menu_contents( $output, $item, $depth, $args ) {
		$item = (array) $item;
		$args = (array) $args;


		if ( empty( $args['hide_mega_menu'] ) && empty( $item['has_children'] ) ) {
			$mega_menu_id = get_post_meta( $item['ID'], Mega_Submenu::META_ID, true );
			if ( ! empty( $mega_menu_id ) && ( $mega_menu = get_post( $mega_menu_id ) ) && ! is_wp_error( $mega_menu ) ) {

				$this->beginRender();

				// We have a mega menu to display.
				$wrapper_classes = apply_filters( Mega_Submenu::FILTER_CSS_CLASSES, Mega_Submenu::$css_classes, $item, $depth, $args );
				ob_start();

				/**
				 * @since  1.2.6
				 */
				if ( class_exists( 'MSM_VC' ) ) {
					echo do_shortcode( $mega_menu->post_content );
				} elseif ( function_exists('msm_elementor_print_menu') ) {
					echo msm_elementor_print_menu( $mega_menu_id );
				} else {
					the_content();
				}
				$contents = ob_get_clean();
				wp_reset_postdata();
				if ( ! empty( $contents ) ) {

					$theme_location = isset( $args['theme_location'] ) ? $args['theme_location'] : null;

					$is_mobile_navigation_location = $theme_location && $theme_location == Mega_Submenu::NAVIGATION_MOBILE;

					/**
					 * @since  1.1.0
					 */
					if ( $is_mobile_navigation_location ) {
						$output .= '<div class="respmenu-submenu-toggle cbp-respmenu-more"><img src="' . MSM_PLUGIN_URL . 'public/img/angle-arrow-down.png"></div>';
						$output .= '<div class="sub-menu">';
					} else {
						$output .= apply_filters( 'msm_filter_submenu_before', '', $theme_location );
					}


					$output .= "<!-- {$mega_menu->post_title} -->";
					$output .= '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '"';

					$output .= ' data-depth="' . $depth . '"';

					$mega_menu_item_width = msm_get_rwmb_meta( 'width', $mega_menu_id );
					if ( $mega_menu_item_width ) {
						$mega_menu_item_width = strstr( $mega_menu_item_width, '%' ) ? $mega_menu_item_width : (int) $mega_menu_item_width;

						$output .= ' data-width="' . $mega_menu_item_width . '"';
					}

					$mega_menu_item_position = msm_get_rwmb_meta( 'position', $mega_menu_id );
					if ( ! $mega_menu_item_position ) {
						$mega_menu_item_position = 'center';
					}
					$output .= ' data-position="' . $mega_menu_item_position . '"';

					$mega_menu_item_margin = msm_get_rwmb_meta( 'margin', $mega_menu_id );
					if ( $mega_menu_item_margin ) {
						$output .= ' data-margin="' . (int) $mega_menu_item_margin . '"';
					}

					$mega_menu_item_bg_color = msm_get_rwmb_meta( 'bg_color', $mega_menu_id );
					if ( $mega_menu_item_bg_color ) {
						$output .= ' data-bg-color="' . $mega_menu_item_bg_color . '"';
					}

					$output .= ">\n";
					$output .= $contents;
					$output .= "</div>\n";

					/**
					 * @since  1.1.0
					 */
					if ( $is_mobile_navigation_location ) {
						$output .= "</div>\n";
					} else {
						$output .= apply_filters( 'msm_filter_submenu_after', '', $theme_location );
					}

				}

			}
		}

		$this->endRender();

		return $output;
	}

	public function mobile_navigation() {

		if ( ! $this->use_mobile_navigation() ) {
			return;
		}
		include_once 'partials/mobile-navigation.php';
	}

	public function mobile_item_wrap_start() {

	}

	public function responsive_menu_scripts() {

		if ( ! $this->use_mobile_navigation() ) {
			return;
		}

		$respmenu_show_start = (int) msm_get_option( 'respmenu-show-start', 767 );

		if ( $respmenu_show_start ) {
			?>
			<style>
				#msm-mobile-menu {
					display: none;
				}

				@media screen and (max-width: <?php echo intval( $respmenu_show_start ); ?>px) {

					.<?php echo Mega_Submenu::CSS_CLASS_PRIMARY_NAVIGATION; ?> {
						display: none;
					}

					#msm-mobile-menu {
						display: block;
					}
				}
			</style>
		<?php
		}
	}

	protected function use_mobile_navigation() {

		$respmenu_use = (bool) msm_get_option( 'respmenu-use', false );

		return apply_filters( Mega_Submenu::FILTER_USE_MOBILE_NAVIGATION, $respmenu_use );
	}

	public function add_global_js_object() {
		$settings = array(
			'data' => array(
				'submenu_items_position_relative'  => (int) msm_get_option( 'submenu-items-position-relative', false ),
				'mobile_menu_trigger_click_bellow' => (int) msm_get_option( 'mobile-menu-trigger-click-bellow', 768 ),
			)
		);

		?>
		<script>
			var msm_mega_submenu = <?php echo json_encode($settings); ?>;
		</script>
	<?php

	}

	public function load_template($template) {
		if ( function_exists('msm_elementor_print_menu') && is_single() && get_post_type() == Mega_Submenu::POST_TYPE ) {
			return  plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/single.php';
		}

		return $template;
	}

	protected function beginRender() {
		$this->setRendering( true );
	}

	protected function endRender() {
		$this->setRendering( false );
	}

	/**
	 * @return boolean
	 */
	public function isRendering() {
		return $this->rendering;
	}

	/**
	 * @param boolean $rendering
	 */
	public function setRendering( $rendering ) {
		$this->rendering = $rendering;
	}


}
