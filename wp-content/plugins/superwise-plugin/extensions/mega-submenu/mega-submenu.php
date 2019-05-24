<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeforest.net/user/aislin/portfolio
 * @since             1.0.0
 * @package           Mega_Submenu
 *
 * @wordpress-plugin
 * Plugin Name:       Mammoth Mega Submenu
 * Plugin URI:        https://themeforest.net/user/aislin/portfolio
 * Description:       Mega Submenu addon for Visual Composer. Simple and easy to use. It works with your existing menu but with ability to use Visual Composer widgets and grid system to build mega submenus.
 * Version:           1.2.5
 * Author:            aislin
 * Author URI:        https://themeforest.net/user/aislin/portfolio
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mega-submenu
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MSM_PLUGIN_VERSION', '1.2.5' );
define( 'MSM_OPTION_NAME', 'mega_submenu_options' );
define( 'MSM_PLUGIN_SLUG', 'mega-submenu' );
define( 'MSM_PREFIX', 'msm_' );
define( 'MSM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MSM_PLUGIN_PATH', dirname( __FILE__ ) . '/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mega-submenu-activator.php
 */
function activate_mega_submenu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mega-submenu-activator.php';
	Mega_Submenu_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mega-submenu-deactivator.php
 */
function deactivate_mega_submenu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mega-submenu-deactivator.php';
	Mega_Submenu_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mega_submenu' );
register_deactivation_hook( __FILE__, 'deactivate_mega_submenu' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mega-submenu.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mega_submenu() {

	$plugin = new Mega_Submenu();
	$plugin->run();

}
run_mega_submenu();
