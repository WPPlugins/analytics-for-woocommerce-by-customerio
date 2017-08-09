<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.multidots.com/
 * @since             1.0.1
 * @package           Wccustomerio
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Customer.io Analytics Integration
 * Plugin URI:        http://www.multidots.com/
 * Description:       Woocommerce customer.io analytics integration helps you to set an event for WooCommerce users. It integrate with customer.io through APIs and allows communication between woocommerce and customer.io system.
 * Version:           1.1.3
 * Author:            Multidots
 * Author URI:        http://www.multidots.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wccustomerio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wccustomerio-activator.php
 */
function activate_wccustomerio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wccustomerio-activator.php';
	Wccustomerio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wccustomerio-deactivator.php
 */
function deactivate_wccustomerio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wccustomerio-deactivator.php';
	Wccustomerio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wccustomerio' );
register_deactivation_hook( __FILE__, 'deactivate_wccustomerio' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wccustomerio.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wccustomerio() {

	$plugin = new Wccustomerio();
	$plugin->run();

}
run_wccustomerio();
