<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wccustomerio
 * @subpackage Wccustomerio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wccustomerio
 * @subpackage Wccustomerio/public
 * @author     Multidots <wordpress@multidots.com>
 */
class Wccustomerio_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wccustomerio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wccustomerio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wccustomerio-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wccustomerio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wccustomerio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wccustomerio-public.js', array( 'jquery' ), $this->version, false );

	}
	/**
	 * Include Customer.io Identify Script
	 *
	 */
	public function include_customer_io_identify_script() {
		$current_user    = wp_get_current_user();
		$current_user_id = $current_user->ID;
		
		//Customer.io Site ID
		$customerio_settings_customerio_site_id = get_option('customerio_settings_customerio_site_id');
		
		//Customer.io API Key
		$customerio_settings_customerio_api_key = get_option('customerio_settings_customerio_api_key');
		
		//Check Enable Customer.io API Key
		$customerio_settings_load_customerio_code = get_option('customerio_settings_load_customerio_code');
						
		if ( is_user_logged_in() && $customerio_settings_load_customerio_code == 'yes' ) { 
			?>
			<!-- Customer.io -->
			<script type="text/javascript">
			
				var _cio = _cio || [];
				(function() {
				  var a,b,c;a=function(f){return function(){_cio.push([f].
				  concat(Array.prototype.slice.call(arguments,0)))}};b=["load","identify",
				  "sidentify","track","page"];for(c=0;c<b.length;c++){_cio[b[c]]=a(b[c])};
				  var t = document.createElement('script'),
				      s = document.getElementsByTagName('script')[0];
				  t.async = true;
				  t.id    = 'cio-tracker';
				  t.setAttribute('data-site-id', '<?php echo $customerio_settings_customerio_site_id;?>');     // production 
				  t.src = 'https://assets.customer.io/assets/track.js';
				  s.parentNode.insertBefore(t, s);
				})();
			
			
			  _cio.identify({
			    // Required attributes:
			    id: 'md_<?php echo $current_user->ID; ?>',
			    email: '<?php echo $current_user->user_email; ?>',
			    created_at: <?php echo date("U", strtotime( $current_user->user_registered )); ?>,   	
			
			    // Additional attributes
			    display_name: '<?php echo $current_user->display_name; ?>'
			
			  });
			</script>
	<?php }
	}
	
	
	
	/**
	 * Once Item cart is removed then this event will works
	 *
	 */
	public function woocustomer_io_item_quantity_zero() { 
		global $woocommerce;
                
		$current_user    = wp_get_current_user();
		$current_user_id = $current_user->ID;
		
		//Customer.io Site id
		$customerio_settings_customerio_site_id = get_option('customerio_settings_customerio_site_id');
		if ( !empty( $customerio_settings_customerio_site_id ) ) {
			$customerio_settings_customerio_site_id = get_option('customerio_settings_customerio_site_id');
		} else {
			$customerio_settings_customerio_site_id = '';
		}
		
		//Customer.io API Key
		$customerio_settings_customerio_api_key = get_option('customerio_settings_customerio_api_key');
		if ( !empty( $customerio_settings_customerio_api_key ) ) {
			$customerio_settings_customerio_api_key = get_option('customerio_settings_customerio_api_key');
		} else {
			$customerio_settings_customerio_api_key = '';
		}
		
		//Check Enable Customer.io API Key
		$customerio_settings_load_customerio_code = '';
		$customerio_settings_load_customerio_code = get_option('customerio_settings_load_customerio_code');
		
		
		if ( is_user_logged_in() && $customerio_settings_load_customerio_code == 'yes' ) {
			
		 	$session = curl_init();
			$customer_id = 'md_'.$current_user->ID; // You'll want to set this dynamically to the unique id of the user associated with the event
			$customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
			
			$site_id = $customerio_settings_customerio_site_id;
			$api_key = $customerio_settings_customerio_api_key;
			$data = array('name' => 'Item Removed From Cart');
			
			curl_setopt($session, CURLOPT_URL, $customerio_url);
			curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($session, CURLOPT_VERBOSE, 1);
			curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($session, CURLOPT_USERPWD, $site_id . ':' . $api_key);
			curl_exec($session);
			curl_close($session);
			
		}
		
	}
	
	public function woocustomer_io_thank_you_page(){ 
	
		global $woocommerce;
                
		$current_user    = wp_get_current_user();
		$current_user_id = $current_user->ID;
		
		//Customer.io Site id
		$customerio_settings_customerio_site_id = get_option('customerio_settings_customerio_site_id');
		if ( !empty( $customerio_settings_customerio_site_id ) ) {
			$customerio_settings_customerio_site_id = get_option('customerio_settings_customerio_site_id');
		} else {
			$customerio_settings_customerio_site_id = '';
		}
		
		//Customer.io API Key
		$customerio_settings_customerio_api_key = get_option('customerio_settings_customerio_api_key');
		if ( !empty( $customerio_settings_customerio_api_key ) ) {
			$customerio_settings_customerio_api_key = get_option('customerio_settings_customerio_api_key');
		} else {
			$customerio_settings_customerio_api_key = '';
		}
		
		//Check Enable Customer.io API Key
		$customerio_settings_load_customerio_code = '';
		$customerio_settings_load_customerio_code = get_option('customerio_settings_load_customerio_code');
		
			if ( is_user_logged_in() && !empty( $_GET['key'] ) ){
			
			$session = curl_init();
			$customer_id = 'md_'.$current_user->ID; // You'll want to set this dynamically to the unique id of the user associated with the event
			$customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
			
			$site_id = $customerio_settings_customerio_site_id;
			$api_key = $customerio_settings_customerio_api_key;
			$data = array('name' => 'Order Placed Thank you Event','email' => $current_user->user_email, 'created_at' => date("U", strtotime( $current_user->user_registered )));
			
			curl_setopt($session, CURLOPT_URL, $customerio_url);
			curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($session, CURLOPT_VERBOSE, 1);
			curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($session, CURLOPT_USERPWD, $site_id . ':' . $api_key);
			curl_exec($session);
			curl_close($session);
			
		}
	}
	
	/**
	 * BN code added 
	 */
	function paypal_bn_code_filter_woocustomer_io($paypal_args) {
		$paypal_args['bn'] = 'Multidots_SP';
		return $paypal_args;
	}

}
