<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wccustomerio
 * @subpackage Wccustomerio/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wccustomerio
 * @subpackage Wccustomerio/includes
 * @author     Multidots <wordpress@multidots.com>
 */
class Wccustomerio_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb,$woocommerce;


		if( !in_array( 'woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins'))) && !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )   ) { 
                    
			wp_die( "<strong>Analytics for WooCommerce by Customer.io</strong> Plugin requires <strong>WooCommerce</strong> <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
		}
		
		set_transient( '_welcome_screen_woocustomer_io_activation_redirect_data', true, 30 );
	}

}
