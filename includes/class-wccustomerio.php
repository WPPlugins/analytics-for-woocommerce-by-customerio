<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wccustomerio
 * @subpackage Wccustomerio/includes
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
 * @package    Wccustomerio
 * @subpackage Wccustomerio/includes
 * @author     Multidots <wordpress@multidots.com>
 */
class Wccustomerio {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wccustomerio_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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

		$this->plugin_name = 'wccustomerio';
		$this->version = '1.0.0';

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
	 * - Wccustomerio_Loader. Orchestrates the hooks of the plugin.
	 * - Wccustomerio_i18n. Defines internationalization functionality.
	 * - Wccustomerio_Admin. Defines all hooks for the admin area.
	 * - Wccustomerio_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wccustomerio-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wccustomerio-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wccustomerio-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wccustomerio-public.php';

		$this->loader = new Wccustomerio_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wccustomerio_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wccustomerio_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

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

		$plugin_admin = new Wccustomerio_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('admin_init', $plugin_admin, 'wccustomerio_tracking_settings');
		
		$this->loader->add_action( 'wp_ajax_add_plugin_user_awc', $plugin_admin, 'wp_add_plugin_userfn' );
		$this->loader->add_action( 'wp_ajax_hide_subscribe_awc', $plugin_admin, 'hide_subscribe_awcfn' );
		
		/**Welcome page hook**/
		$this->loader->add_action('admin_init', $plugin_admin, 'welcome_woocustomer_io_screen_do_activation_redirect');
        $this->loader->add_action('admin_menu', $plugin_admin, 'welcome_pages_screen_woocustomer_io');
        $this->loader->add_action('woocommerce_woocustomer_io_other_plugins', $plugin_admin, 'woocommerce_woocustomer_io_other_plugins');
        $this->loader->add_action('woocommerce_woocustomer_io_about', $plugin_admin, 'woocommerce_woocustomer_io_about');
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'adjust_the_wp_menu_woocustomer_io', 999 );
		
		/**Custom pointer hook**/
        $this->loader->add_action('admin_print_footer_scripts', $plugin_admin, 'custom_woocustomer_io_pointers_footer');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wccustomerio_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'include_customer_io_identify_script' );
		//$this->loader->add_action( 'wp', $plugin_public, 'trigget_woocommerce_events_for_customer_io' );
		
		
		//Cart Item quantity zero
		$this->loader->add_action('woocommerce_cart_item_removed', $plugin_public ,'woocustomer_io_item_quantity_zero',10,1);
		
		$this->loader->add_action( 'woocommerce_order_details_after_order_table', $plugin_public, 'woocustomer_io_thank_you_page' );
		
		$this->loader->add_filter('woocommerce_paypal_args',  $plugin_public,'paypal_bn_code_filter_woocustomer_io', 99, 1);

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
	 * @return    Wccustomerio_Loader    Orchestrates the hooks of the plugin.
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

}
