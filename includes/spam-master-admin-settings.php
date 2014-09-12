<?php
//HOOK LEARNING
require_once( dirname( __FILE__ ) . '/spam-master-learning.php');
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		if( is_multisite() ) {
		function menu_multi(){
		// Create menu
		add_submenu_page( 'spam-master', 'Settings', 'Settings', 'manage_options', 'spam-master-settings', 'spam_master_options' );
		}
		}
		else {
		// Create menu
		function menu_single(){
		if ( is_admin() )
		add_submenu_page( 'spam-master', 'Settings', 'Settings', 'manage_options', 'spam-master-settings', 'spam_master_options' );
		}
		}

		////////////////////////////////////
		// CHECK IF BUDDYPRESS IS PRESENT //
		////////////////////////////////////
		if (defined('BP_PLUGIN_DIR'))
		define('spam_master_buddypress',1);
		else
		define('spam_master_buddypress',0);
		 //////////////////////////////////
		// GET EXTERNAL SPAM MASTER KEYS //
		///////////////////////////////////
//		function spam_master_block(){
//		$spam_master_block = "";
//		}
		/////////////////////////////////////
		// REGISTRATION WITHOUT BUDDYPRESS //
		/////////////////////////////////////
		function spam_master($user_login, $user_email, $errors) {
		//Check Wordpress for Multisite
		if( is_multisite() ) { 
		$spam_master_blacklist = get_site_option('blacklist_keys');
		} else {
		$spam_master_blacklist = get_option('blacklist_keys');
		}
		$blacklist_string = $spam_master_blacklist;
		$blacklist_array = explode("\n", $blacklist_string);
		$blacklist_size = sizeof($blacklist_array);
		// Analyse List
		for($i = 0; $i < $blacklist_size; $i++)
		{
		$blacklist_current = trim($blacklist_array[$i]);
		if(stripos($user_email, $blacklist_current) !== false)
		{
		$errors->add('invalid_email', '<strong>SPAM MASTER</strong>'.__( get_option('spam_master_message') ))& set_transient( 'spam_master_invalid_email'.current_time( 'mysql' ), "Date: ".current_time( 'mysql' )." - Email: ".$result['user_email'], 604800 );
		$count = $errors;
		add_option( 'spam_master_block_count', $count );
		$count = mysql_query("UPDATE wp_options SET option_value=option_value + 1 WHERE option_name='spam_master_block_count'");
		return;
		}
		}
		}
		// Multisite Block
		if( is_multisite() && ($spam_master_buddypress == 0) ) 
		{
		add_filter('wpmu_validate_user_signup', 'spam_master_email_check', 99);
		}
		function spam_master_email_check($result) {
		$spam_master_blacklist = get_option('blacklist_keys');
		$blacklist_string = $spam_master_blacklist;
		$blacklist_array = explode("\n", $blacklist_string);
		$blacklist_size = sizeof($blacklist_array);
		$data = $_POST['user_email'];
		// Analyse List
		for($i = 0; $i < $blacklist_size; $i++) {
		$blacklist_current = trim($blacklist_array[$i]);
		if(stripos($data, $blacklist_current) !== false) {
		$result['errors']->add('invalid_email', '<strong>SPAM MASTER</strong>'. __( get_site_option( 'spam_master_message') ))& set_site_transient( 'spam_master_invalid_email'.current_time( 'mysql' ), "Date: ".current_time( 'mysql' )." - Email: ".$result['user_email'], 604800 );
		$count = $result;
		add_site_option( 'spam_master_block_count', $count );
		$count = mysql_query("UPDATE wp_options SET option_value=option_value + 1 WHERE option_name='spam_master_block_count'");
			echo '<p class="error">'.get_site_option('spam_master_message').'</p>';
		}
		}
		return $result;
		}
		/////////////////////////////////
		// REGISTRATION WITH BUDDYPRES //
		/////////////////////////////////
		function spam_master_buddypress_init() {
			function spam_master_buddypress_register( $result ) {
			global $wpdb;
			if ( spam_master_buddypress_spammail( $result['user_email'] ) )
			$result['errors']->add('user_email', '<strong>SPAM MASTER</strong>'. __( get_option('spam_master_message') ) )& set_transient( 'spam_master_invalid_email'.current_time( 'mysql' ), "Date: ".current_time( 'mysql' )." - Email: ".$result['user_email'], 604800 );
			$count = $result;
			add_option( 'spam_master_block_count', $count );
			$count = mysql_query("UPDATE wp_options SET option_value=option_value + 1 WHERE option_name='spam_master_block_count'");
			return $result;
		}
		function spam_master_buddypress_spammail( $user_email ) {
		// Budypress in Mulitsite
		if( is_multisite() ) { 
			$spam_master_blacklist = get_site_option('blacklist_keys');
			}
			else {
			$spam_master_blacklist = get_option('blacklist_keys');
			}
			// Retrieve Blocked List
			$blacklist_string = $spam_master_blacklist;
			$blacklist_array = explode("\n", $blacklist_string);
			$blacklist_size = sizeof($blacklist_array);
			// Analyse List
			for($i = 0; $i < $blacklist_size; $i++)
			{
			$blacklist_current = trim($blacklist_array[$i]);
			if(stripos($user_email, $blacklist_current) !== false)
			{
			return true;
			}
			}
		}
		}
		/////////////////////////////////////////////
		// ACTIVATION MESSAGE OPTIONS AND DEFAULTS //
		/////////////////////////////////////////////
		function spam_master_active() {
		if( is_multisite() ) {
		add_site_option('blacklist_keys','spam@spam.com');
//		add_site_option('spam_master_message', ': Email, Domain, or Ip banned from registration.');
		add_site_option( 'spam_master_block_count', 0);
		}
		else {
//		add_option('spam_master_message', ': Email, Domain, or Ip banned from registration.');
		add_option( 'spam_master_block_count', 0);
		}
		}
		///////////////////////
		// WORDPRESS ACTIONS //
		///////////////////////
		if( is_multisite() ) {
		add_action( 'network_admin_menu', 'menu_multi' );
		//add_action( 'user_register', 'spam_master_get_keys' );
		}
		else {
		add_action( 'admin_menu', 'menu_single' );
		add_action( 'bp_include', 'spam_master_buddypress_init' );
		add_filter( 'bp_core_validate_user_signup', 'spam_master_buddypress_register' );
		add_action( 'register_post', 'spam_master', 10, 3 );
		//add_action( 'user_register', 'spam_master' );
		register_activation_hook( __FILE__, 'spam_master_active' );
		//register_uninstall_hook( __FILE__, 'spam_master_uninstall' );
		}
		////////////////////////
		// ADMINISTRATOR PAGE //
		////////////////////////
		function spam_master_options() {
		global $wpdb;
		?>
<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<br>
<?php
if (isset($_POST['update']))
{
// Update Message
if ($spam_master_new_message = $_POST['spam_master_new_message'])
{
if( is_multisite() )
{
update_site_option('spam_master_message', $spam_master_new_message);
}
else {
update_option('spam_master_message', $spam_master_new_message);
}
}
?>
<div id="message" class="updated fade">
<p><strong><?php _e('Settings Saved!', 'spam_master'); ?></strong></p>
</div>
<?php }
if( is_multisite() )
{
$spam_master_blacklist = get_site_option('blacklist_keys');
$spam_master_blocked_message = get_site_option('spam_master_message');
}
else {
$spam_master_blacklist = get_option('blacklist_keys');
$spam_master_blocked_message = get_option('spam_master_message');
}
?>
<?php
//License Update
$license_code = false;
if (isset($_POST['spam_master_license_code'])){
if( is_multisite() ){
update_site_option('spam_master_license_old_code', get_site_option('spam_master_license_code'));
}
else{
update_option('spam_master_license_old_code', get_option('spam_master_license_code'));
}
if  ($license_new_code = $_POST['spam_master_license_code']){
//update_option('spam_master_license_old_code', get_option('spam_master_license_code'));
if( is_multisite() ){
update_site_option('spam_master_license_code', $license_new_code);
}
else {
update_option('spam_master_license_code', $license_new_code);
}
}
}
//LOAD Table Class - Table starts below
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
?>
<form method="post" width='1'>
<fieldset class="options">

<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col" width="250"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Protection Level', 'spam_master'); ?></h3></legend></th>
			<th id="columnname" class="manage-column column-columnname" scope="col" width="250"></th>
			<th id="columnname" class="manage-column column-columnname" scope="col"></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th class="manage-column column-columnname" scope="col" width="250">
<p class="submit" style="margin-top:-10px !important; margin-bottom:-18px !important"><input class='button-primary' type='submit' name='update' value='<?php _e("Save & Refresh", 'spam_master'); ?>' id='submitbutton' /></p>
			</th>
			<th class="manage-column column-columnname" scope="col" width="250"></th>
			<th class="manage-column column-columnname" scope="col"></th>
		</tr>
	</tfoot>

	<tbody>
<?php
//Protection List
if ( $_POST) {
if ( isset($_POST['spam_master_protection_selection']) )
if( is_multisite() ){
update_site_option('spam_master_protection_selection', $_POST['spam_master_protection_selection'] );
}
else{
update_option('spam_master_protection_selection', $_POST['spam_master_protection_selection'] );
}
}

//Display Selected Protection
$key_lic = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvbGljLnR4dA==";
$key_code = wp_remote_get(''.base64_decode($key_lic).'');
$response_key = wp_remote_retrieve_response_code( $key_code );
if( is_multisite() ) {
update_site_option('spam_master_response_key', $response_key);
add_site_option('spam_master_message', ': Email, Domain, or Ip banned from registration.');
$trd_free = "spam_master_trd_free";
add_site_option('spam_master_trd_free', $trd_free);
$trd_full = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
add_site_option('spam_master_trd_full', $trd_full);
}
else{
update_option('spam_master_response_key', $response_key);
add_option('spam_master_message', ': Email, Domain, or Ip banned from registration.');
$trd_free = "spam_master_trd_free";
add_option('spam_master_trd_free', $trd_free);
$trd_full = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
add_option('spam_master_trd_full', $trd_full);
}
?>
<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
Select Protection Level:
			</td>
			<td class="column-columnname" width="250" height="25" style="vertical-align:middle">
<select id="spam_master_protection_selection" name="spam_master_protection_selection">
<option>SELECT PROTECTION</option>
<option value="1" <?php echo get_option('spam_master_protection_selection') == 1 ? 'selected="selected"':''; ?>>Free Protection</option>
<option value="2"<?php echo get_option('spam_master_protection_selection') == 2 ? 'selected="selected"':''; ?>>Full Protection</option>
</select>
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3">
<font color="white"><b><?php
if( is_multisite() ) {
	if(get_site_option('spam_master_protection_selection') == 1 ){
		echo "FREE PROTECTION";
	}
	else {
		echo "FULL PROTECTION";
	}
}
else{
	if(get_option('spam_master_protection_selection') == 1 ){
		echo "FREE PROTECTION";
	}
	else {
		echo "FULL PROTECTION";
	}
}
?></b></font>
			</td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle">
<p><b>FREE PROTECTION</b>, if you do not have a license code it is very important that you select Free Protection in Protection Selector. Free protection grants you immunity against 4 threats Hotmail, Live, Msn and Outlook and you can manually add more in the Protection List page.</p>
<p><b>FULL PROTECTION</b>, insert your license code and press Save & Refresh. The license code can be found at the top of your purchase email <b>Order Number : 4996195009</b>, the license code to insert would be <b>4996195009</b>. If you already inserted the license code and are getting "Invalid / Awaiting Authorization", it might take a few hours for your license to be accepted and replicated in all Real Time Scan servers.</p>
			</td>
		</tr>
<?php
//IF MULTI-SITE
if( is_multisite() ) {
if (get_site_option( 'spam_master_protection_selection') ==  1 ){
update_site_option('spam_master_protection', $trd_free);
add_site_option('spam_master_free_keys', "hotmail\r\nmsn\r\nlive\r\noutlook");
	if ( get_site_option('spam_master_response_key') == 200 ){
		$license_color = "F2AE41";
		update_site_option('spam_master_license_color', $license_color);
		$license_status = "FREE PROTECTION 4 THREATS";
		update_site_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_site_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_site_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "525051";
		update_site_option('spam_master_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_site_option('spam_master_learning_status', $learning_status);
//
//		$blacklist_keys = get_site_option( 'blacklist_keys' );
		update_site_option('spam_master_rbl_keys', get_site_option('spam_master_free_keys'));
		update_site_option('blacklist_keys', get_site_option('spam_master_free_keys'));
//
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_site_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_site_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_site_option('spam_master_user_registrations', $spam_master_user_registrations);
		}
		else{
		$license_color = "F2AE41";
		update_site_option('spam_master_license_color', $license_color);
		$license_status = "FREE PROTECTION 4 THREATS";
		update_site_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_site_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_site_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "525051";
		update_site_option('spam_master_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_site_option('spam_master_learning_status', $learning_status);
//
//		$blacklist_keys = get_site_option( 'blacklist_keys' );
		update_site_option('spam_master_rbl_keys', get_site_option('spam_master_free_keys'));
		update_site_option('blacklist_keys', get_site_option('spam_master_free_keys'));
//
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_site_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_site_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_site_option('spam_master_user_registrations', $spam_master_user_registrations);
		}
}
if (get_site_option( 'spam_master_protection_selection') == 2 ){
update_site_option('spam_master_protection', $trd_full);
	if ( $response_key == 200 ){
		$license_color = "07B357";
		update_site_option('spam_master_license_color', $license_color);
		$license_status = "VALID LICENSE";
		update_site_option('spam_master_license_status', $license_status);
		$full_rbl_color = "07B357";
		update_site_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Optimal Connection";
		update_site_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "07B357";
		update_site_option('spam_master_learning_color', $learning_color);
		$learning_status = "ONLINE";
		update_site_option('spam_master_learning_status', $learning_status);
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_site_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "07B357";
		update_site_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_site_option('spam_master_user_registrations', $spam_master_user_registrations);
//
//		$blacklist_keys = get_site_option( 'blacklist_keys' );
		update_site_option('spam_master_rbl_keys', get_site_option('spam_master_full_keys'));
		update_site_option('blacklist_keys', get_site_option('spam_master_full_keys'));
//
	}
	else {
		$license_color = "E8052B";
		update_site_option('spam_master_license_color', $license_color);
		$license_status = "No Valid License, Select Free Protection";
		update_site_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_site_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_site_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "F2AE41";
		update_site_option('spam_master_learning_color', $learning_color);
		$learning_status = "No License, Select FREE PROTECTION in Settings Page";
		update_site_option('spam_master_learning_status', $learning_status);
		$protection_total = "No License. Select FREE PROTECTION in Settings Page";
		update_site_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_site_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_site_option('spam_master_user_registrations', $spam_master_user_registrations);
		$trd_light = false;
//
//		$blacklist_keys = get_site_option( 'blacklist_keys' );
		update_site_option('spam_master_rbl_keys', get_site_option('spam_master_free_keys'));
		update_site_option('blacklist_keys', get_site_option('spam_master_free_keys'));
//
	}
}
}
//IF SINGLE-SITE
else{
if (get_option( 'spam_master_protection_selection') == 1 ){
update_option('spam_master_protection', $trd_free);
add_option('spam_master_free_keys', "hotmail\r\nmsn\r\nlive\r\noutlook");
	if ( get_option('spam_master_response_key') == 200 ){
		$license_color = "F2AE41";
		update_option('spam_master_license_color', $license_color);
		$license_status = "FREE PROTECTION 4 THREATS. License Valid, select FULL PROTECTION.";
		update_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "525051";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_option('spam_master_learning_status', $learning_status);
//
//		$blacklist_keys = get_option( 'blacklist_keys' );
		update_option('spam_master_rbl_keys', get_option('spam_master_free_keys'));
		update_option('blacklist_keys', get_site_option('spam_master_free_keys'));
//
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spam_master_user_registrations', $spam_master_user_registrations);
		}
		else{
		$license_color = "F2AE41";
		update_option('spam_master_license_color', $license_color);
		$license_status = "FREE PROTECTION 4 THREATS";
		update_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "525051";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_option('spam_master_learning_status', $learning_status);
//
//		$blacklist_keys = get_option( 'blacklist_keys' );
		update_option('spam_master_rbl_keys', get_option('spam_master_free_keys'));
		update_site_option('blacklist_keys', get_site_option('spam_master_free_keys'));
//
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spam_master_user_registrations', $spam_master_user_registrations);
		}
}
if (get_option( 'spam_master_protection_selection') == 2 ){
update_option('spam_master_protection', $trd_full);
	if ( $response_key == 200 ){
		$license_color = "07B357";
		update_option('spam_master_license_color', $license_color);
		$license_status = "VALID LICENSE";
		update_option('spam_master_license_status', $license_status);
		$full_rbl_color = "07B357";
		update_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Optimal Connection";
		update_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "07B357";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "ONLINE";
		update_option('spam_master_learning_status', $learning_status);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spam_master_user_registrations', $spam_master_user_registrations);
//
//		$blacklist_keys = get_option( 'blacklist_keys' );
		update_option('spam_master_rbl_keys', get_option('spam_master_full_keys'));
		update_option('blacklist_keys', get_site_option('spam_master_full_keys'));
//
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "07B357";
		update_option('spam_master_protection_number_color', $protection_number_color);
	}
	else {
		$license_color = "E8052B";
		update_option('spam_master_license_color', $license_color);
		$license_status = "No Valid License, Select Free Protection";
		update_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spam_master_full_rbl_status', $full_rbl_status);
		$learning_color = "F2AE41";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "No License, Learning OFFLINE";
		update_option('spam_master_learning_status', $learning_status);
		$protection_total = "No License, 4 RBL FREE";
		update_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spam_master_user_registrations', $spam_master_user_registrations);
		$trd_light = false;
		echo '<div id="message" class="error"><p><b>WARNING</b>... Full Protection Selected without Valid License Key.</p></div><br><div id="message" class="error"><p><b>1.</b> If you inserted a valid license key, please wait for license activation. The warning will disapear upon license activation.</p></div><br><div id="message" class="error"><p><b>2.</b> If you do not have a licence key, please select Free Protection and click Save & Refresh.</p></div>';
//
//		$blacklist_keys = get_option( 'blacklist_keys' );
		update_option('spam_master_rbl_keys', get_option('spam_master_free_keys'));
		update_option('blacklist_keys', get_site_option('spam_master_free_keys'));
//
	}
}
}
?>
	</tbody>
</table>
<br>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col" width="250"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;License Status', 'spam_master'); ?></h3></legend></th>
			<th id="columnname" class="manage-column column-columnname" scope="col" width="250"></th>
			<th id="columnname" class="manage-column column-columnname" scope="col"></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th class="manage-column column-columnname" scope="col" width="250">
<p class="submit" style="margin-top:-10px !important; margin-bottom:-18px !important"><input class='button-primary' type='submit' name='update' value='<?php _e("Save & Refresh", 'spam_master'); ?>' id='submitbutton' /></p>
			</th>
			<th class="manage-column column-columnname" scope="col" width="250"></th>
			<th class="manage-column column-columnname" scope="col"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td class="column-columnname" width="250" height="25" style="vertical-align:middle">
Insert Spam Master License: 
			</td>
			<td class="column-columnname" style="vertical-align:middle" width="250"><input id="spam_master_license_code" name="spam_master_license_code" type="text" size="16" value="<?php 
if( is_multisite() ) {
echo get_site_option('spam_master_license_code');
}
else{
echo get_option('spam_master_license_code');
}
?>">
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#<?php
if( is_multisite() ) {
echo get_site_option('spam_master_license_color');
}
else{
echo get_option('spam_master_license_color');
}
?>"><font color="white"><b><?php
if( is_multisite() ) {
echo get_site_option('spam_master_license_status');
}
else{
echo get_option('spam_master_license_status');
}
?></b></font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" width="250" height="25" style="vertical-align:middle">
<a class="button-secondary" href="http://wordpress.techgasp.com/spam-master/" target="_blank" title="Visit Website">get rbl protection license</a>
			</td>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>



<?php
//HOOK LICENSE
if( is_multisite() ) {
if(get_site_option('spam_master_license_code') !== get_site_option('spam_master_license_old_code')){
require_once( dirname( __FILE__ ) . '/spam-master-license.php');
}
else {
}
}
else{
if(get_option('spam_master_license_code') !== get_option('spam_master_license_old_code')){
require_once( dirname( __FILE__ ) . '/spam-master-license.php');
}
else {
}
}
?>
	</tbody>
</table>
<br>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Edit Registration Message', 'spam_master'); ?></h3></legend></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th class="manage-column column-columnname" scope="col">
<p class="submit" style="margin-top:-10px !important; margin-bottom:-18px !important"><input class='button-primary' type='submit' name='update' value='<?php _e("Save & Refresh", 'spam_master'); ?>' id='submitbutton' /></p>
			</th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle">
This message is displayed to spam users who are not allowed to register in your Wordpress. There's not a lot of space so, keep it short.
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle">
<textarea name='spam_master_new_message' style="width:100%" rows='2'>
<?php echo $spam_master_blocked_message; ?>
</textarea>
			</td>
		</tr>
	</tbody>
</table>
</fieldset>
<fieldset class="options">
<textarea name="blacklist_keys" cols="40" rows="15" style="display:none;">
<?php
if( is_multisite() ) {
echo strip_tags (get_site_option('blacklist_keys'));
}
else{
echo strip_tags (get_option('blacklist_keys'));
}
?>
</textarea>
</fieldset>
</form>
<br>
<h2>IMPORTANT: Makes no use of Javascript or Ajax to keep your website fast and conflicts free</h2>

<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<br>
<p>
<a class="button-secondary" href="http://wordpress.techgasp.com" target="_blank" title="Visit Website">More TechGasp Plugins</a>
<a class="button-secondary" href="http://wordpress.techgasp.com/support/" target="_blank" title="Facebook Page">TechGasp Support</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master/" target="_blank" title="Visit Website">Spam Master Info</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master-documentation/" target="_blank" title="Visit Website">Spam Master Documentation</a>
<a class="button-primary" href="http://wordpress.org/plugins/spam-master/" target="_blank" title="Visit Website">RATE US *****</a>
</p>
</div>
<?php
}