<?php
	/*
		Plugin Name: Paid subscription for Buddypress
		Plugin URI: http://abstraktion.co.uk
		Description: Adds paid subscriptions to Buddypress
		Author: Chris Garrett (of Abstraktion fame)
		Version: 0.1
		Author URI: http://abstraktion.co.uk
		
		What lies ahead is an epic adventure into the mystical and psychologically challenging, dare I say terrifying, world of Wordpress plugin development... Not for the faint of hearted, bring a bucket...
	*/
	
	require('paypal.php');
	
	class paid_membership{
		// Price options (Full and Associate as per clients spec)
		private $prices = array();
		
		// Available currencies - append new currencies here
		private $currencies = array(
			'GBP' => '&#xa3;',
			'USD' => '&#x24;',
			'EUR' => '&#x20ac;'
		);
		
		public function __construct(){			
			// Validate when signup for is submitted
			add_action('bp_signup_validate', array(&$this, 'validate_credentials'));
			
			// Load fields into register page
			add_action('bp_custom_profile_edit_fields', array(&$this, 'load_fields'));
			
			// If validation successful, store user meta
			add_filter('bp_core_activate_account', array(&$this, 'update_usermeta'));
			
			// Is using sandbox?
			if(get_option('paypal_sandbox') == 'yes'){
				$sandbox = TRUE;
			}else{
				$sandbox = FALSE;
			}
			
			// Load client side assets - limit to paid membership options page to avoid conflict
			if($_GET['page'] == 'paid_membership_options'){
				add_action('init', array(&$this, 'load_assets'));
			}
			
			if($_POST['save'] == 'account_types' && !empty($_POST['account_type'])){
				// Has the "account type" form been submitted?
				add_action('plugins_loaded', array(&$this, 'save_account_types'));
			}elseif($_POST['save'] == 'paypal'){
				// Has the "account type" form been submitted?
				add_action('plugins_loaded', array(&$this, 'save_paypal_options'));
			}
			
			// Retrieve prices stored in DB
			add_action('plugins_loaded', array(&$this, 'get_prices'));
			
			// Instantiate paypal wrapper
			$this->paypal = new paypal(get_option('paypal_user'), get_option('paypal_pass'), get_option('paypal_sig'), $sandbox);
			
			// Wordpress admin menu
			add_action('admin_menu', array(&$this, 'admin_menu'));
			
			// Create options on activation
			register_activation_hook(__FILE__, array(&$this, 'initiate'));
		}
		
		public function initiate(){
			// Add options to DB when plugin is activated
			add_option('paypal_sandbox');
			add_option('paypal_sig');
			add_option('paypal_user');
			add_option('paypal_pass');
			add_option('paypal_currency');
			add_option('paypal_recurring');
			add_option('membership_currency', 'GBP');
			add_option('membership_recurring', 'Month');
			add_option('membership_account_types');
		}
		
		public function save_account_types(){
			if(current_user_can('manage_options')){
				foreach($_POST['account_type'] as $ac_type){
					if(!empty($ac_type['label']) && is_numeric($ac_type['price'])){
						$save[$ac_type['label']] = $ac_type['price'];
					}
				}
				
				// Store JSON hash of new accounts in options table
				update_option('membership_account_types', json_encode($save));
			}
		}
		
		public function save_paypal_options(){
			foreach($_POST['paypal'] as $key => $val){
				update_option($key, $val);
			}
		}
		
		public function get_prices(){
			// And get prices hash from options table
			$this->prices = (array) json_decode(get_option('membership_account_types'));
		}
		
		public function load_assets(){
			// Load essential admin assets
			wp_enqueue_script('jquery');
			wp_enqueue_script('paid_membership_js', get_bloginfo('home').'/wp-content/plugins/Buddypress-paid-membership/lib.js');
			
			wp_enqueue_style('paid_membership_css', get_bloginfo('home').'/wp-content/plugins/Buddypress-paid-membership/style.css');
		}
		
		public function admin_menu(){
			// Add new options page for Membership configuration, access to admins
			add_options_page('Paid Membership', 'Paid Membership', 'manage_options', 'paid_membership_options', array(&$this, 'options'));
		}
		
		public function options(){
			// Load fields into registration page
			require('admin_views/options.php');
		}
		
		public function load_fields(){ require('fields.php'); }
		
		public function validate_credentials(){
			// What fields are required?
			$this->required(array('card_name_first', 'card_name_second', 'address1', 'address2', 'city', 'county', 'postcode'), $_POST);
			
			// Check valid integers
			$this->check_number('card_number', 16, $_POST);
			$this->check_number('security', 3, $_POST);
			
			// Get price to deliver Paypal API, based on account type selection
			$this->set_price($_POST['field_account_type']);
			
			// Process the payment
			$this->process_payment($_POST);
		}
		
		private function required($fields, $data){
			global $bp;
			
			// What fields are required?
			foreach($fields as $field){
				// Is this field empty?
				if(empty($data[$field]) || $data[$field] == ''){
					// Append Buddypress error
					$bp->signup->errors[$field] = 'This field is required.';
					
					// Throw class error
					$this->card_error = TRUE;
				}
			}
		}
		
		private function set_price($option){
			// Get price from price array and set it as class property
			$this->price = $this->prices[$option];
		}
		
		private function check_number($key, $length = 1, $data){
			global $bp;
			
			// Strip out whitespace
			$number = str_replace(' ', '', $data[$key]);
			
			// Make sure it's an integer, isn't empty and is greater than the length specified
			if(empty($number) || !is_numeric($number) || strlen($number) != $length){
				// Append to buddtpress errors array
				$bp->signup->errors[$key] = 'Please enter a valid number.';
				
				// And set error in class	
				$this->card_error = TRUE;
			}
		}
		
		private function process_payment($data){
			global $bp;
			
			// Check buddypress hasn't stored any errors
			if(empty($bp->signup->errors) && $this->price > 0){
				// Instantiate Paypal object (can be any object you like, I'm using an open source Paypal NVP API wrapper)				
				// Set values
				$this->paypal->addvalue('METHOD', 'CreateRecurringPaymentsProfile');
				$this->paypal->addvalue('PAYMENTACTION', 'Sale');
				$this->paypal->addvalue('DESC', 'Subscription to '.get_bloginfo('name'));
				$this->paypal->addvalue('PROFILESTARTDATE', date('Y-m-d\T00:00:00\Z', strtotime(date('m/d/Y'))));
				
				if(get_option('membership_recurring') == 'Year'){
					$this->paypal->addvalue('BILLINGPERIOD', 'Year');
				}else{
					$this->paypal->addvalue('BILLINGPERIOD', 'Month');
				}
				
				if(get_option('membership_recurring') == 'Year'){
					$this->paypal->addvalue('BILLINGFREQUENCY', 1);
				}else{
					$this->paypal->addvalue('BILLINGFREQUENCY', 12);
				}
				$this->paypal->addvalue('IPADDRESS', '92.238.193.140');
				$this->paypal->addvalue('CREDITCARDTYPE', $data['cc_type']);
				$this->paypal->addvalue('ACCT', str_replace(' ', '', $data['card_number']));
				$this->paypal->addvalue('EXPDATE', $data['ccExpMonth'].$data['ccExpYear']);
				$this->paypal->addvalue('CVV2', $data['security']);
				$this->paypal->addvalue('FIRSTNAME', $data['card_name_first']);
				$this->paypal->addvalue('LASTNAME', $data['card_name_second']);
				$this->paypal->addvalue('STREET', $data['address1']);
				$this->paypal->addvalue('STREET2', $data['address2']);
				$this->paypal->addvalue('CITY', $data['city']);
				$this->paypal->addvalue('STATE', $data['county']);
				$this->paypal->addvalue('COUNTRYCODE', 'GB');
				$this->paypal->addvalue('ZIP', $data['postcode']);
				$this->paypal->addvalue('EMAIL', $data['email']);
				$this->paypal->addvalue('AMT', $this->price);
				$this->paypal->addvalue('CURRENCYCODE', get_option('membership_currency'));
				
				// And call			
				$result = $this->paypal->call_paypal();
				
				print_r($result);
				
				// Check for Paypal API response. If error, append Buddypress error.		
				if($result['ACK'] != 'Success'){ $bp->signup->errors['general'] = 'There was an error processing your payment. Please check your card details and try again.'; }
			}
		}
		
		public function update_usermeta($signup){
			// Update user meta to show account type
			update_usermeta($signup['user_id'], 'account_type', $signup['meta']['field_account_type']);
			
			return $signup;
		}
		
		public function account_type($id){			
			// Get user's account type
			return get_usermeta($id, 'account_type', TRUE);
		}
	}
	
	$paid_membership = new paid_membership();
?>