<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wccustomerio
 * @subpackage Wccustomerio/admin/partials
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( !class_exists( 'WC_Settings_Wccustomerio' ) ) {
	
	class WC_Settings_Wccustomerio {
		
		const SETTINGS_NAMESPACE = 'customerio_settings';
		/**
		 * Get the setting fields
		 *
		 * @since  1.0.0
		 * @access private
		 *
		 * @return array $setting_fields
		 */
		private function get_fields() { 
			$setting_fields = array(
				'section_title' => array(
					'name' => __( 'Customer.io Settings', 'wccustomerio' ),
					'type' => 'title',
					'desc' => '',
					'id'   => self::SETTINGS_NAMESPACE . '_title'
				),
				
				'check_load_customerio_code' =>	array(
					'title'   => __( 'Enable Customer.io', 'wccustomerio' ),
					'desc'    => __( 'Enable Customer.io for WooCommerce', 'wccustomerio' ),
					'id'      => self::SETTINGS_NAMESPACE . '_load_customerio_code',
					'default' => 'no',
					'type'    => 'checkbox'
				),
				
				'customerio_site_id' => array(
					'name'    => __( 'Customer.io Site ID', 'wccustomerio' ),
					'type'    => 'text',
					'desc'    => __( 'Enter Customer.io Site ID from your Customer.io account', 'wccustomerio' ),
					'id'      => self::SETTINGS_NAMESPACE . '_customerio_site_id',
					'default' => '',
				),
				
				'customerio_api_key' => array(
					'name'    => __( 'Customer.io API Key', 'wccustomerio' ),
					'type'    => 'text',
					'desc'    => __( 'Enter Customer.io API Key from your Customer.io account', 'wccustomerio' ),
					'id'      => self::SETTINGS_NAMESPACE . '_customerio_api_key',
					'default' => '',
				),

				'section_end'   => array(
					'type' => 'sectionend',
					'id'   => self::SETTINGS_NAMESPACE . '_section_end'
				)
			);
			return apply_filters( 'wc_settings_tab_' . self::SETTINGS_NAMESPACE, $setting_fields );
		}
		/**
		 * Get an option set in our settings tab
		 *
		 * @param $key
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @return String
		 */
		public function get_option( $key ) {
			$fields = $this->get_fields();
			return apply_filters( 'wc_option_' . $key, get_option( 'wc_settings_' . self::SETTINGS_NAMESPACE . '_' . $key, ( ( isset( $fields[$key] ) && isset( $fields[$key]['default'] ) ) ? $fields[$key]['default'] : '' ) ) );
		}
		/**
		 * Setup the WooCommerce settings
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function setup() {
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab_wccustomerio' ), 70 );
			add_action( 'woocommerce_settings_tabs_' . self::SETTINGS_NAMESPACE, array( $this, 'tab_content_wccustomerio' ) );
			add_action( 'woocommerce_update_options_' . self::SETTINGS_NAMESPACE, array( $this, 'update_settings_wccustomerio' ) );
		}
		/**
		 * Add a settings tab to the settings page
		 *
		 * @param array $settings_tabs
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @return array
		 */
		public function add_settings_tab_wccustomerio( $settings_tabs ) {
			$settings_tabs[self::SETTINGS_NAMESPACE] = __( 'Customer.io Integration', 'wccustomerio' );
			return $settings_tabs;
		}
		/**
		 * Output the tab content
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function tab_content_wccustomerio() { 
				global $wpdb;  
				$current_user = wp_get_current_user();
				 if (!get_option('awc_plugin_notice_shown')) {
		   			 echo '<div id="awc_dialog" title="Basic dialog"><p>Subscribe for latest plugin update and get notified when we update our plugin and launch new products for free! </p> <p><input type="text" id="txt_user_sub_awc" class="regular-text" name="txt_user_sub_awc" value="'.$current_user->user_email.'"></p></div>';
				 }
			woocommerce_admin_fields( $this->get_fields() );
		}
		/**
		 * Update the settings
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function update_settings_wccustomerio() {
			woocommerce_update_options( $this->get_fields() );
		}
	}
}

// Only load in admin
if ( is_admin() ) {
    // Initiate the settings class
    $settings = new WC_Settings_Wccustomerio();
     
    // Setup the hooks and filters
    $settings->setup();
}