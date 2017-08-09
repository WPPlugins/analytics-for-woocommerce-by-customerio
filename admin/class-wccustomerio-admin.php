<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wccustomerio
 * @subpackage Wccustomerio/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wccustomerio
 * @subpackage Wccustomerio/admin
 * @author     Multidots <wordpress@multidots.com>
 */
class Wccustomerio_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wccustomerio-admin.css', array('wp-jquery-ui-dialog'), $this->version, 'all' );
		wp_enqueue_style( 'wp-pointer' );
	
		

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wccustomerio-admin.js', array( 'jquery','jquery-ui-dialog' ), $this->version, false );
		wp_enqueue_script( 'wp-pointer' );

	}
	
	public function custom_woocustomer_io_pointers_footer(){ 
		$admin_pointers = custom_woocustomer_io_pointers_admin_pointers();
	    ?>
		    <script type="text/javascript">
		        /* <![CDATA[ */
		        ( function($) {
		            <?php
		            foreach ( $admin_pointers as $pointer => $array ) {
		               if ( $array['active'] ) {
		                  ?>
		            $( '<?php echo $array['anchor_id']; ?>' ).pointer( {
		                content: '<?php echo $array['content']; ?>',
		                position: {
		                    edge: '<?php echo $array['edge']; ?>',
		                    align: '<?php echo $array['align']; ?>'
		                },
		                close: function() {
		                    $.post( ajaxurl, {
		                        pointer: '<?php echo $pointer; ?>',
		                        action: 'dismiss-wp-pointer'
		                    } );
		                }
		            } ).pointer( 'open' );
		            <?php
		         }
		      }
		      ?>
		        } )(jQuery);
		        /* ]]> */
		    </script>
		<?php
	}
	
	/**
	 * WooCommerce Customer.io tracking settings
	 *
	 * @since    1.0.0
	 */
	public function wccustomerio_tracking_settings(){
		require_once 'partials/wccustomerio-admin-display.php';
	}
	
	public function wp_add_plugin_userfn() { 
		global  $wpdb;
    	$email_id= $_POST['email_id'];
    	$log_url = $_SERVER['HTTP_HOST'];
    	$cur_date = date('Y-m-d');
    	$url = 'http://www.multidots.com/store/wp-content/themes/business-hub-child/API/wp-add-plugin-users.php';
    	$response = wp_remote_post( $url, array('method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => array('user'=>array('user_email'=>$email_id,'plugin_site' => $log_url,'status' => 1,'plugin_id' => '11','activation_date'=>$cur_date)),
    	'cookies' => array()));
		update_option('awc_plugin_notice_shown', 'true');
    }
     
    public function hide_subscribe_awcfn() { 
     	global  $wpdb;
    	$email_id= $_POST['email_id'];
    	update_option('awc_plugin_notice_shown', 'true');
    }
    
    // Function For Welcome page to plugin 
    public function welcome_woocustomer_io_screen_do_activation_redirect (){ 
    	
    	if (!get_transient('_welcome_screen_woocustomer_io_activation_redirect_data')) {
			return;
		}
		
		// Delete the redirect transient
		delete_transient('_welcome_screen_woocustomer_io_activation_redirect_data');

		// if activating from network, or bulk
		if (is_network_admin() || isset($_GET['activate-multi'])) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect(add_query_arg(array('page' => 'woocommerce_customer_io&tab=about'), admin_url('index.php')));
    }
    
    
    public function welcome_pages_screen_woocustomer_io() {
		add_dashboard_page(
		'Woocommerce Customer.io Analytics Integration Dashboard', 'Woocommerce Customer.io Analytics Integration Dashboard', 'read', 'woocommerce_customer_io', array(&$this, 'welcome_screen_content_woocustomer_io')
		);
	}
	
	public function welcome_screen_content_woocustomer_io() {
        ?>
        
         <div class="wrap about-wrap">
            <h1 style="font-size: 2.1em;"><?php printf(__('Welcome to Woocommerce Customer.io Analytics Integration', 'analytics-for-woocommerce-by-customerio')); ?></h1>

            <div class="about-text woocommerce-about-text">
        <?php
        $message = '';
        printf(__('%s Woocommerce Customer.io Analytics Integration helps you to set event for WooCommerce users.', 'analytics-for-woocommerce-by-customerio'), $message, $this->version);
        ?>
                <img class="version_logo_img" src="<?php echo plugin_dir_url(__FILE__) . 'images/woocustomer_io.png'; ?>">
            </div>

        <?php
        $setting_tabs_wc = apply_filters('woocustomer_io_setting_tab', array("about" => "Overview", "other_plugins" => "Checkout our other plugins"));
        $current_tab_wc = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
        $aboutpage = isset($_GET['page'])
        ?>
            <h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs_wc as $name => $label)
            echo '<a  href="' . home_url('wp-admin/index.php?page=woocommerce_customer_io&tab=' . $name) . '" class="nav-tab ' . ( $current_tab_wc == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
            </h2>

                <?php
                foreach ($setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue) {
                	switch ($setting_tabkey_wc) {
                		case $current_tab_wc:
                			do_action('woocommerce_woocustomer_io_' . $current_tab_wc);
                			break;
                	}
                }
                ?>
            <hr />
            <div class="return-to-dashboard">
                <a href="<?php echo home_url('/wp-admin/admin.php?page=wc-settings&tab=customerio_settings'); ?>"><?php _e('Go to Woocommerce Customer.io Analytics Integration Settings', 'analytics-for-woocommerce-by-customerio'); ?></a>
            </div>
        </div>
	<?php }
	
	
	public function woocommerce_woocustomer_io_other_plugins() { 
		global $wpdb;
         $url = 'http://www.multidots.com/store/wp-content/themes/business-hub-child/API/checkout_other_plugin.php';
    	 $response = wp_remote_post( $url, array('method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => array('plugin' => 'advance-flat-rate-shipping-method-for-woocommerce'),
    	'cookies' => array()));
    	
    	$response_new = array();
    	$response_new = json_decode($response['body']);
		$get_other_plugin = maybe_unserialize($response_new);
		
		$paid_arr = array();
		?>

        <div class="plug-containter">
        	<div class="paid_plugin">
        	<h3>Paid Plugins</h3>
	        	<?php foreach ($get_other_plugin as $key=>$val) { 
	        		if ($val['plugindesc'] =='paid') {?>
	        			
	        			
	        		   <div class="contain-section">
	                <div class="contain-img"><img src="<?php echo $val['pluginimage']; ?>"></div>
	                <div class="contain-title"><a target="_blank" href="<?php echo $val['pluginurl'];?>"><?php echo $key;?></a></div>
	            </div>	
	        			
	        			
	        		<?php }else {
	        			
	        			$paid_arry[$key]['plugindesc']= $val['plugindesc'];
	        			$paid_arry[$key]['pluginimage']= $val['pluginimage'];
	        			$paid_arry[$key]['pluginurl']= $val['pluginurl'];
	        			$paid_arry[$key]['pluginname']= $val['pluginname'];
	        		
	        	?>
	        	
	         
	            <?php } }?>
           </div>
           <?php if (isset($paid_arry) && !empty($paid_arry)) {?>
           <div class="free_plugin">
           	<h3>Free Plugins</h3>
                <?php foreach ($paid_arry as $key=>$val) { ?>  	
	            <div class="contain-section">
	                <div class="contain-img"><img src="<?php echo $val['pluginimage']; ?>"></div>
	                <div class="contain-title"><a target="_blank" href="<?php echo $val['pluginurl'];?>"><?php echo $key;?></a></div>
	            </div>
	            <?php } }?>
           </div>
          
        </div>

    <?php
	}
	
	
	/**
     * About tab content of Add social share button about
     *
     */
	public function woocommerce_woocustomer_io_about() {
		//do_action('my_own');
		$current_user = wp_get_current_user();

    	?>
        <div class="changelog">
            </br>
           	<style type="text/css">
				p.woocustomer_io_overview {max-width: 100% !important;margin-left: auto;margin-right: auto;font-size: 15px;line-height: 1.5;}
				.woocustomer_io_ul ul li {margin-left: 3%;list-style: initial;line-height: 23px;}
			</style>  
            <div class="changelog about-integrations">
                <div class="wc-feature feature-section col three-col">
                    <div>
                    
                    <p class="woocustomer_io_overview"><?php _e('Woocommerce customer.io analytics integration helps you to set an event for WooCommerce users. It integrate with customer.io through APIs and allows communication between woocommerce and customer.io system.', 'analytics-for-woocommerce-by-customerio'); ?></p>
                        
                         <p class="woocustomer_io_overview"><strong>Plugin Functionality: </strong></p> 
                        <div class="woocustomer_io_ul">
                        	<ul>
								<li>Easy setup no specialization required to use User-friendly interface.</li>
								<li>Add Site ID and API Key of customer io .</li>
								<li>Set up event for send emails to users who has cart empty on your store.</li>
								<li>Set up event for send emails to users who has placed an order with loggedIn.</li>
								
							</ul>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
        
        <?php	
        	global $wpdb;
        	$current_user =  wp_get_current_user();
		  if (!get_option('awc_plugin_notice_shown')) {
		   			 echo '<div id="awc_dialog" title="Basic dialog"><p>Subscribe for latest plugin update and get notified when we update our plugin and launch new products for free! </p> <p><input type="text" id="txt_user_sub_awc" class="regular-text" name="txt_user_sub_awc" value="'.$current_user->user_email.'"></p></div>';
   			
		?>
             
        <script type="text/javascript">

        jQuery( document ).ready(function() {
		
        	$( "#awc_dialog" ).dialog({ 
				modal: true, title: 'Subscribe Now', zIndex: 10000, autoOpen: true,
				width: '500', resizable: false,
				position: {my: "center", at:"center", of: window },
				dialogClass: 'dialogButtons',
				buttons: {
					Yes: function () {
						// $(obj).removeAttr('onclick');
						// $(obj).parents('.Parent').remove();
						var email_id = $('#txt_user_sub_awc').val();
		
						var data = {
						'action': 'add_plugin_user_awc',
						'email_id': email_id
						};
		
						// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
						jQuery.post(ajaxurl, data, function(response) {
							$('#awc_dialog').html('<h2>You have been successfully subscribed');
							$(".ui-dialog-buttonpane").remove();
						});
		
						
					},
					No: function () {
						var email_id = $('#txt_user_sub_awc').val();
		
						var data = {
						'action': 'hide_subscribe_awc',
						'email_id': email_id
						};
		
						// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
						$.post(ajaxurl, data, function(response) {
							        					 
						});
						
						$(this).dialog("close");
						
					}
				},
				close: function (event, ui) {
					$(this).remove();
				}
			});
        	
        	jQuery("div.dialogButtons .ui-dialog-buttonset button").addClass("button-primary woocommerce-save-button");
        	jQuery("div.dialogButtons .ui-dialog-buttonpane .ui-button").css("width","80px");
        	jQuery("div.dialogButtons .ui-dialog-buttonpane .ui-button").css("margin-right","14px");
        	jQuery("div.dialogButtons .ui-dialog-buttonset button").removeClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only");
        	
        });
        </script>
        <?php
        
        } 
        
        ?>
       
	<?php  }
	
	public function adjust_the_wp_menu_woocustomer_io(){ 
		remove_submenu_page('index.php', 'woocommerce_customer_io');
	}
    
    
}

function custom_woocustomer_io_pointers_admin_pointers(){ 
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
    $version = '1_0'; // replace all periods in 1.0 with an underscore
    $prefix = 'custom_woocustomer_io_pointers' . $version . '_';

    $new_pointer_content = '<h3>' . __( 'Welcome to Woocommerce Customer.io Analytics Integration' ) . '</h3>';
    $new_pointer_content .= '<p>' . __( 'Woocommerce customer.io analytics integration helps you to set an event for WooCommerce users. It integrate with customer.io through APIs and allows communication between woocommerce and customer.io system.' ) . '</p>';

    return array(
        $prefix . 'woocustomer_io_notice_view' => array(
            'content' => $new_pointer_content,
            'anchor_id' => '#adminmenu',
            'edge' => 'left',
            'align' => 'left',
            'active' => ( ! in_array( $prefix . 'woocustomer_io_notice_view', $dismissed ) )
        )
    );
}
