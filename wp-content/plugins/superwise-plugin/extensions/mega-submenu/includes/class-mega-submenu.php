<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeforest.net/user/aislin/portfolio
 * @since      1.0.0
 *
 * @package    Mega_Submenu
 * @subpackage Mega_Submenu/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mega_Submenu
 * @subpackage Mega_Submenu/includes
 * @author     aislin <aislin.themes@gmail.com>
 */
class Mega_Submenu {

	/** =================================
	 * Constants
	 * ===================================*/

	/**
	 * @since   1.0.0
	 */
	const POST_TYPE = 'msm_mega_menu';
	const META_ID = '_msm_mega_menu_id';

	/**
	 * CSS Classes
	 *
	 * @since   1.0.0
	 */
	const CSS_CLASS_WRAP = 'msm-wrap';
	const CSS_CLASS_PRIMARY_NAVIGATION = 'msm-primary-navigation';
	const CSS_CLASS_ITEM = 'msm-menu-item';
	const CSS_CLASS_ITEM_TOP_LEVEL = 'msm-top-level-item';

	/**
	 * Navigation keys
	 *
	 * @since   1.0.0
	 */
	const NAVIGATION_PRIMARY = 'primary_navigation';
	const NAVIGATION_MOBILE = 'msm_mobile_navigation';

	/**
	 * Filters
	 *
	 * @since   1.0.0
	 */
	const FILTER_CSS_CLASSES = 'msm_filter_css_classes';
	const FILTER_USE_REDUX = 'msm_filter_use_redux';
	const FILTER_USE_STYLE_MENU = 'msm_filter_use_style_menu';
	const FILTER_MENU_LOCATION = 'msm_filter_menu_location';
	const FILTER_MENU_LOCATION_THEME_MOBILE = 'msm_filter_menu_location_theme_mobile';
	const FILTER_USE_MOBILE_NAVIGATION = 'msm_filter_use_mobile_navigation';
	const FILTER_LOAD_COMPILED_STYLE = 'msm_filter_load_compiled_style';

	// -> End Constants


	/** =================================
	 * Globals
	 * ===================================*/

	/**
	 * @since   1.0.0
	 * @var     array $css_classes CSS classes used for mega submenu
	 */
	public static $css_classes = array( 'msm-submenu' );

	/**
	 * @since   1.1.1
	 * @var     bool $in_mobile_nav Set to true when rendering mobile navigation
	 */
	public static $in_mobile_nav;

	// -> End Globals

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mega_Submenu_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'mega-submenu';
		$this->version     = MSM_PLUGIN_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mega_Submenu_Loader. Orchestrates the hooks of the plugin.
	 * - Mega_Submenu_i18n. Defines internationalization functionality.
	 * - Mega_Submenu_Admin. Defines all hooks for the admin area.
	 * - Mega_Submenu_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Plugin Activation
		 * If there are issues with theme loading tgm class-tgm-plugin-activation.php can be deleted
		 */

		$this->require_if_exists('includes/class-tgm-plugin-activation.php', true);

		if ( is_admin() ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/activate-plugins.php';
		}

		/**
		 * Helpers
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers.php';

		$this->require_if_exists('includes/vc/helpers.php');
		$this->require_if_exists('includes/vc/class-vc.php');
		$this->require_if_exists('includes/elementor/helpers.php');

		/**
		 * Metaboxes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/metaboxes.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mega-submenu-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mega-submenu-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-menu-wrapper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mobile-menu-walker.php';

		/**
		 * VC Templates
		 */
		$this->require_if_exists('includes/vc-templates.php');

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mega-submenu-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mega-submenu-public.php';


		/**
		 * VC Integrations
		 */
		$this->require_if_exists('includes/vc/theme-integration/theme-integration.php');


		$this->loader = new Mega_Submenu_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mega_Submenu_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mega_Submenu_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mega_Submenu_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// $this->loader->add_action( 'admin_init', $plugin_admin, 'vc_editor_set_post_types' );
		$this->loader->add_action( 'init', $plugin_admin, 'load_redux_panel', 9 );
		$this->loader->add_action( 'init', $plugin_admin, 'add_extensions', 11 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_mega_menu_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_mobile_navigation' );
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'register_vc_addons', 11 );
		$this->loader->add_action( 'wp_update_nav_menu_item', $plugin_admin, 'save_mega_menu_setting', 10, 2 );
		$this->loader->add_action( 'wp_ajax_msm_get_custom_fields', $plugin_admin, 'ajax_get_custom_fields', 10, 2 );

		// $this->loader->add_filter( 'wp_edit_nav_menu_walker', $plugin_admin, 'edit_nav_menu_walker' );
		$this->loader->add_filter( 'cfct-build-enabled-post-types', $plugin_admin, 'add_carrington_build_support' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mega_Submenu_Public( $this->get_plugin_name(), $this->get_version() );
		$menu_wrapper  = new MSM_Menu_Wrapper();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_vc_css' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'responsive_menu_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_global_js_object' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'mobile_navigation' );
		$this->loader->add_action( 'nav_menu_css_class', $plugin_public, 'filter_nav_menu_item_css_class', 10, 3 );
		$this->loader->add_action( 'pre_wp_nav_menu', $plugin_public, 'filter_pre_nav_menu', 10, 2 );
		$this->loader->add_action( 'wp_nav_menu', $plugin_public, 'filter_wp_nav_menu', 10, 2 );

		$this->loader->add_filter( 'walker_nav_menu_start_el', $plugin_public, 'display_mega_menu_contents', 999, 4 );
		$this->loader->add_filter( 'wc_get_template', $plugin_public, 'filter_wc_get_template', 10, 5 );
		$this->loader->add_filter( 'wc_get_template_part', $plugin_public, 'filter_wc_get_template_part', 10, 3 );
		$this->loader->add_filter( 'template_include', $plugin_public, 'load_template' );

		$this->loader->add_filter( 'init', $menu_wrapper, 'init' );
		$this->loader->add_filter( 'msm_filter_submenu_before', $menu_wrapper, 'filter_submenu_before', 10, 2 );
		$this->loader->add_filter( 'msm_filter_submenu_after', $menu_wrapper, 'filter_submenu_after', 10, 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mega_Submenu_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	protected function require_if_exists( $relative_path, $admin_only = false ) {
		$file = plugin_dir_path( dirname( __FILE__ ) ) . $relative_path;

		if ( $admin_only && ! is_admin() ) {
			return;
		}

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}

}
