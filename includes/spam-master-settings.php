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
		add_menu_page( 'Spam Master', 'Spam Master', 'manage_options', 'spam-master', 'spam_master_admin', plugins_url( 'spam-master/images/techgasp-minilogo-16.png' ), 71 );
		add_submenu_page( 'spam-master', 'Settings', 'Settings', 'manage_options', 'spam-master-settings', 'spam_master_options' );
		}
		}
		else {
		// Create menu
		function menu_single(){
		if ( is_admin() )
		add_menu_page( 'Spam Master', 'Spam Master', 'manage_options', 'spam-master', 'spam_master_admin', plugins_url( 'spam-master/images/techgasp-minilogo-16.png' ), 71 );
		add_submenu_page( 'spam-master', 'Settings', 'Settings', 'manage_options', 'spam-master-settings', 'spam_master_options' );
		}
		}

		//Load Admin page location function
		function spam_master_admin(){
		require_once( dirname( __FILE__ ) . '/spam-master-admin.php');
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
		function spam_master_block(){
		$spam_master_block = "";
		}
		/////////////////////////////////////
		// REGISTRATION WITHOUT BUDDYPRESS //
		/////////////////////////////////////
		function spam_master($user_login, $user_email, $errors) {
		//Check Wordpress for Multisite
		if( is_multisite() ) { 
		$spam_master_blacklist = get_site_option('spam_master_keys');
		} else {
		$spam_master_blacklist = get_option('spam_master_keys');
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
		$errors->add('invalid_email', '<strong>SPAM MASTER: </strong>'.__( get_option('spam_master_message') ))& set_transient( 'spam_master_invalid_email'.current_time( 'mysql' ), current_time( 'mysql' )." - ".$user_email, 604800 );
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
		$spam_master_blacklist = get_option('spam_master_keys');
		$blacklist_string = $spam_master_blacklist;
		$blacklist_array = explode("\n", $blacklist_string);
		$blacklist_size = sizeof($blacklist_array);
		$data = $_POST['user_email'];
		// Analyse List
		for($i = 0; $i < $blacklist_size; $i++) {
		$blacklist_current = trim($blacklist_array[$i]);
		if(stripos($data, $blacklist_current) !== false) {
		$result['errors']->add('invalid_email', '<strong>SPAM MASTER: </strong>'. __( get_site_option( 'spam_master_message') ))& set_transient( 'spam_master_invalid_email'.current_time( 'mysql' ), current_time( 'mysql' )." - ".$result['user_email'], 604800 );
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
			if ( spam_master_buddypress_spammail( $result['user_email'] ) )
			$result['errors']->add('user_email', '<strong>SPAM MASTER: </strong>'. __( get_option('spam_master_message') ) )& set_transient( 'spam_master_invalid_email'.current_time( 'mysql' ), current_time( 'mysql' )." - ".$result['user_email'], 604800 );
			$count = $result;
			add_option( 'spam_master_block_count', $count );
			$count = mysql_query("UPDATE wp_options SET option_value=option_value + 1 WHERE option_name='spam_master_block_count'");
			return $result;
		}
		function spam_master_buddypress_spammail( $user_email ) {
		// Budypress in Mulitsite
		if( is_multisite() ) { 
			$spam_master_blacklist = get_site_option('spam_master_keys');
			}
			else {
			$spam_master_blacklist = get_option('spam_master_keys');
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
		add_site_option('spam_master_keys','spam@spam.com');
		add_site_option('spam_master_message', 'Your Email, Domain, or Ip is banned from registration.');
		add_site_option( 'spam_master_block_count', 0);
		}
		else {
		add_option('spam_master_message', 'Your Email, Domain, or Ip is banned from registration.');
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
$spam_master_blacklist = get_site_option('spam_master_keys');
$spam_master_blocked_message = get_site_option('spam_master_message');
}
else {
$spam_master_blacklist = get_option('spam_master_keys');
$spam_master_blocked_message = get_option('spam_master_message');
}
?>
<?php
//License Update
$license_code = false;
if (isset($_POST['spam_master_license_code'])){
if  ($license_new_code = $_POST['spam_master_license_code']){
update_option('spam_master_license_old_code', get_option('spam_master_license_code'));
if( is_multisite() ){
update_site_option('spam_master_license_code', $license_new_code);
}
else {
update_option('spam_master_license_code', $license_new_code);
}
}
}
?>
<form method="post" width='1'>
<fieldset class="options">
<legend><h3><?php _e('Your License Status & Protection Level', 'spam_master'); ?></h3></legend>
<br>
<table>
<?php
//Display Selected Protection
$spam_master_free_selected = "Free Protection";
$spam_master_full_selected = "Full Protection";
$key_lic = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvbGljLnR4dA==";
$key_code = wp_remote_get(''.base64_decode($key_lic).'');
$response_key = wp_remote_retrieve_response_code( $key_code );
update_option('spam_master_response_key', $response_key);
$trd_free = "spam_master_trd_free";
add_option('spam_master_trd_free', $trd_free);
$trd_full = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
add_option('spam_master_trd_full', $trd_full);
?>
<tr>
<td width="280" height="25" align="center">Insert Spam Master License: <input id="spam_master_license_code" name="spam_master_license_code" type="text" size="16" maxlength="16" value="<?php echo get_option('spam_master_license_code'); ?>" ></td>
<?php
//HOOK LICENSE
if(get_option('spam_master_license_code') !== get_option('spam_master_license_old_code')){
require_once( dirname( __FILE__ ) . '/spam-master-license.php');
}
else {
}
?>
<td width="20" height="25" align="center"></td>
<td width="120" height="25">
<?php
//Protection List
//$spam_master_protection = "no lic.";
//update_option('spam_master_protection', $spam_master_protection);
if (isset($_POST['spam_master_protection'])){
if ($spam_master_protection = $_POST['spam_master_protection']){
if( is_multisite() ){
update_site_option('spam_master_protection', $spam_master_protection);
update_option('selected', '');
}
else {
update_option('spam_master_protection', $spam_master_protection);
update_option('selected', '');
}
}
}
?>
<select id="spam_master_protection" name="spam_master_protection">
<option>Protection Selector</option>
<option value="<?php echo get_option('spam_master_trd_full'); ?>" <?php if ($spam_master_protection == get_option('spam_master_trd_full')) { echo 'selected="selected"'; }?>>Full Protection</option>
<option value="<?php echo get_option('spam_master_trd_free'); ?>" <?php if ($spam_master_protection == get_option('spam_master_trd_free')) { echo 'selected="selected"'; }?>>Free Protection</option>
</select>
<?php

if (get_option( 'spam_master_protection') == get_option( 'spam_master_trd_free' )){
update_option('spam_master_selected', $spam_master_free_selected);
update_option('spam_master_protection', $trd_free);
	if ( get_option('spam_master_response_key') == 200 ){
		$license_color = "F2AE41";
		update_option('spam_master_license_color', $license_color);
		$license_status = "FREE PROTECTION 4 THREATS";
		update_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spam_master_full_rbl_status', $full_rbl_status);
		$medium_rbl_color = "525051";
		update_option('spam_master_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Disconnected";
		update_option('spam_master_medium_rbl_status', $medium_rbl_status);
		$learning_color = "525051";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_option('spam_master_learning_status', $learning_status);
		$spam_master_free_keys = "hotmail\r\nmsn\r\nlive\r\noutlook";
		update_option('spam_master_keys', $spam_master_free_keys);
		//keep user settings saved in blacklist_keys. Removes duplicates array_unique and empty lines trim
		$blacklist_keys = get_option( 'blacklist_keys' );
		$spam_master_array = array($blacklist_keys, $spam_master_free_keys);
		sort ($spam_master_array);
		$spam_master_string = implode("\n", array_unique($spam_master_array));
		if( is_multisite() ) {
		update_site_option('blacklist_keys', strip_tags($spam_master_string));
		}
		else {
		update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
		}
//		update_option('blacklist_keys', $spam_master_free_keys);
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
		$medium_rbl_color = "525051";
		update_option('spam_master_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Disconnected";
		update_option('spam_master_medium_rbl_status', $medium_rbl_status);
		$learning_color = "525051";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_option('spam_master_learning_status', $learning_status);
		$spam_master_free_keys = "hotmail\r\nmsn\r\nlive\r\noutlook";
		update_option('spam_master_keys', $spam_master_free_keys);
		//keep user settings saved in blacklist_keys. Removes duplicates array_unique and empty lines trim
		$blacklist_keys = get_option( 'blacklist_keys' );
		$spam_master_array = array($blacklist_keys, $spam_master_free_keys);
		sort ($spam_master_array);
		$spam_master_string = implode("\n", array_unique($spam_master_array));
		if( is_multisite() ) {
		update_site_option('blacklist_keys', strip_tags($spam_master_string));
		}
		else {
		update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
		}
//		update_option('blacklist_keys', $spam_master_free_keys);
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spam_master_user_registrations', $spam_master_user_registrations);
		}
}
if (get_option( 'spam_master_protection') == get_option( 'spam_master_trd_full' )){
update_option('spam_master_selected', $spam_master_full_selected);
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
		$medium_rbl_color = "07B357";
		update_option('spam_master_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Optimal Connection";
		update_option('spam_master_medium_rbl_status', $medium_rbl_status);
		$learning_color = "07B357";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "ONLINE";
		update_option('spam_master_learning_status', $learning_status);
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "07B357";
		update_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spam_master_user_registrations', $spam_master_user_registrations);
		$spam_master_full_keys = get_option('spam_master_full_keys');
		update_option('spam_master_keys', $spam_master_full_keys);
		//keep user settings saved in blacklist_keys. Removes duplicates array_unique and empty lines trim
		$blacklist_keys = get_option( 'blacklist_keys' );
		$spam_master_array = array($blacklist_keys, $spam_master_full_keys);
		sort ($spam_master_array);
		$spam_master_string = implode("\n", array_unique($spam_master_array));
		if( is_multisite() ) {
		update_site_option('blacklist_keys', strip_tags($spam_master_string));
		}
		else {
		update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
		}
//		update_option('blacklist_keys', get_option('spam_master_full_keys'));
	}
	else {
		$license_color = "E8052B";
		update_option('spam_master_license_color', $license_color);
		$license_status = "No Valid License, Select Free Protection >>>";
		update_option('spam_master_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spam_master_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spam_master_full_rbl_status', $full_rbl_status);
		$medium_rbl_color = "525051";
		update_option('spam_master_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Disconnected";
		update_option('spam_master_medium_rbl_status', $medium_rbl_status);
		$learning_color = "F2AE41";
		update_option('spam_master_learning_color', $learning_color);
		$learning_status = "No License, OFFLINE";
		update_option('spam_master_learning_status', $learning_status);
		$protection_total = "No License, 0";
		update_option('spam_master_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_option('spam_master_protection_number_color', $protection_number_color);
		$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spam_master_user_registrations', $spam_master_user_registrations);
		$trd_light = false;
		$spam_master_keys = "";
		update_option('spam_master_keys', $spam_master_keys);
		//keep user settings saved in blacklist_keys. Removes duplicates array_unique and empty lines trim
		$blacklist_keys = get_option( 'blacklist_keys' );
		$spam_master_array = array($blacklist_keys, $spam_master_keys);
		sort ($spam_master_array);
		$spam_master_string = implode("\n", array_unique($spam_master_array));
		if( is_multisite() ) {
		update_site_option('blacklist_keys', strip_tags($spam_master_string));
		}
		else {
		update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
		}
//		update_option('blacklist_keys', $spam_master_keys);
	}
}
?>
</td>
<td width="20" height="20">
<td width="140" height="25" align="center">Total Users</td>
<td width="1" height="25" align="center"></td>
<td width="140" height="25" align="center">Total Spam</td>
<td width="1" height="20">
<td width="220" height="25" align="center">Primary RBL Server Cluster</td>
</tr>
<tr>
<td width="280" height="25"align="center" bgcolor="#<?php echo get_option('spam_master_license_color'); ?>"><font color="white"><b><?php echo get_option('spam_master_license_status'); ?></b></font></td>
<td width="20" height="25" align="center"></td>
<td width="120" height="25" align="center" bgcolor="#078BB3"><font color="white"><b><?php echo get_option('spam_master_selected'); ?></b></font></td>
<td width="20" height="25"></td>
<td width="140" height="25" align="center" bgcolor="#078BB3"><font color="white"><b><?php echo get_option('spam_master_user_registrations'); ?></b> Registered</font></td>
<td width="1" height="25"></td>
<td width="140" height="25" align="center" bgcolor="#078BB3"><font color="white"><b><?php echo get_option('spam_master_block_count'); ?></b> Blocks</font></td>
<td width="1" height="25"></td>
<td width="220" height="25" align="center" bgcolor="#<?php echo get_option('spam_master_full_rbl_color'); ?>"><font color="white">Cluster Status: <b><?php echo get_option('spam_master_full_rbl_status'); ?></b></font></td>
</tr>
<tr>
<td width="280" height="25" align="center"><a class="button-secondary" href="http://wordpress.techgasp.com/spam-master/" target="_blank" title="Visit Website">get rbl protection license</a></td>
<td width="20" height="25"></td>
<td width="120" height="25"></td>
<td width="20" height="25"></td>
<td width="140" height="25" align="center">Protected Against</td>
<td width="1" height="25"></td>
<td width="140" height="25" align="center">Spam Learning</td>
<td width="1" height="25"></td>
<td width="220" height="25" align="center">Secondary RBL Server Cluster</td>
</tr>
<tr>
<td width="280" height="25"></td>
<td width="20" height="25"></td>
<td width="120" height="25"></td>
<td width="20" height="25"></td>
<td width="150" height="25" align="center" bgcolor="#<?php echo get_option('spam_master_protection_number_color'); ?>"><font color="white"><b><?php echo get_option('spam_master_protection_total'); ?></b> Threats</font></td>
<td width="1" height="25"></td>
<td width="150" height="25" align="center" bgcolor="#<?php echo get_option('spam_master_learning_color'); ?>"><font color="white"><b><?php echo get_option('spam_master_learning_status'); ?></b></font></td>
<td width="1" height="25"></td>
<td width="220" height="25" align="center" bgcolor="#<?php echo get_option('spam_master_medium_rbl_color'); ?>"><font color="white">Cluster Status: <b><?php echo get_option('spam_master_medium_rbl_status'); ?></b></font></td>
</tr>
</table>
</fieldset>
<tr>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Save & Refresh", 'spam_master'); ?>' id='submitbutton' /></p>
<p><b>Full Protection:</b></p>
<p>Insert your license code and press Save & Refresh. The license code can be found at the top of your purchase email  <b>Order Number : 4996195009</b>, the license code to insert would be <b>4996195009</b>. If you already inserted the license code and are getting "Invalid / Awaiting Authorization", it might take a few hours for your license to be accepted and replicated in all Real Time Scan servers.</p>
<p><b>Free Protection:</b></p>
<p>If you do not have a license code it is very important that you select Free Protection in above Protection Selector. Free protection grants you immunity against 4 threats Hotmail, Live, Msn and Outlook and you can manually add more in Protection List page</p>

<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<fieldset class="options">
<legend><h3><?php _e('Edit Registration Message', 'spam_master'); ?></h3></legend>
<p><?php _e('This message is displayed to spam users who are not allowed to register in your Wordpress. If you edit, remember there\'s not a lot of space so keep it short.', 'spam_master'); ?></p>
<textarea name='spam_master_new_message' cols='80' rows='2'>
<?php echo $spam_master_blocked_message; ?>
</textarea>
</fieldset>
<fieldset class="options">
<textarea name="blacklist_keys" cols="40" rows="15" style="display:none;">
<?php echo strip_tags (get_option('spam_master_keys')); ?>
</textarea>
</fieldset>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Save Settings", 'spam_master'); ?>' id='submitbutton' /></p>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
</form>
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