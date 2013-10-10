<?php
/**
Plugin Name: Spam Master
Plugin URI: http://wordpress.techgasp.com/spam-master/
Version: 2.4.5
Author: TechGasp
Author URI: http://wordpress.techgasp.com
Text Domain: spam-master
Description: Spam Master is the Ultimate Spam Protection plugin that blocks new user registrations and post comments with Real Time anti-spam lists.
License: GPL2 or later
*/
/* Copyright 2013 TechGasp (email : info@techgasp.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

///////DEFINE VERSION///////
define( 'SPAMMASTER_VERSION', '2.4.5' );
$spammaster_version = "2.4.5"; //for other pages
update_option( 'spammaster_version', $spammaster_version );

// HOOK INVITATION
require_once('includes/techgasp-spammaster-invite.php');

//HOOK LEARNING
include_once('includes/techgasp-spammaster-learning.php');


		/** function/method
		* Usage: return absolute file path
		* Arg(1): string
		* Return: string
		*/
		function file_path($file)
		{
			return ABSPATH.'wp-content/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).$file;
		}
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		if( is_multisite() ) {
		function menu()
		{
		// Create menu tab
		add_submenu_page('settings.php', __('Spam Master', 'spammaster'), __('Spam Master', 'spammaster'), 'manage_networks', 'spammaster', 'spammaster_options');
		}
		}
		else {
		function options_page()
		{
		if ( is_admin() )
		add_submenu_page('options-general.php', __('Spam Master', 'spammaster'), __('Spam Master', 'spammaster'), 'moderate_comments', 'spammaster', 'spammaster_options');
		add_filter( 'plugin_action_links', 'spammaster_link', 10, 2 );
		}
		}
		// Add settings link on plugin page
		function spammaster_link($links, $file) {
		static $this_plugin;
		$option_name = "spammaster";
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
		if ($file == $this_plugin){
		$spammaster_name = "spammaster";
		add_option('spammaster', $spammaster_name);
		$settings_link = '<a href="' . admin_url( 'options-general.php?page='.get_option('spammaster')). '">' . __( 'Settings' ) . '</a>';
		array_unshift($links, $settings_link);
		}
		return $links;
		}
		////////////////////////////////////
		// CHECK IF BUDDYPRESS IS PRESENT //
		////////////////////////////////////
		if (defined('BP_PLUGIN_DIR'))
		define('spammaster_buddypress',1);
		else
		define('spammaster_buddypress',0);
		 //////////////////////////////////
		// GET EXTERNAL SPAM MASTER KEYS //
		///////////////////////////////////
		function spammaster_block(){
		$spammaster_block = "";
		}
		/////////////////////////////////////
		// REGISTRATION WITHOUT BUDDYPRESS //
		/////////////////////////////////////
		function spammaster($user_login, $user_email, $errors) {
		//Check Wordpress for Multisite
		if( is_multisite() ) { 
		$spammaster_blacklist = get_site_option('spammaster_keys');
		} else {
		$spammaster_blacklist = get_option('spammaster_keys');
		}
		$blacklist_string = $spammaster_blacklist;
		$blacklist_array = explode("\n", $blacklist_string);
		$blacklist_size = sizeof($blacklist_array);
		// Analyse List
		for($i = 0; $i < $blacklist_size; $i++)
		{
		$blacklist_current = trim($blacklist_array[$i]);
		if(stripos($user_email, $blacklist_current) !== false)
		{
		$errors->add('invalid_email', '<strong>SPAM MASTER: </strong>'.__( get_option('spammaster_message') ));
		$count = $errors;
		$count = mysql_query("UPDATE wp_options SET option_value=option_value + 1 WHERE option_name='spammaster_block_count'");
		return;
		}
		}
		}
		// Multisite Block
		if( is_multisite() && ($spammaster_buddypress == 0) ) 
		{
		add_filter('wpmu_validate_user_signup', 'spammaster_email_check', 99);
		}
		function spammaster_email_check($result) {
		$spammaster_blacklist = get_option('spammaster_keys');
		$blacklist_string = $spammaster_blacklist;
		$blacklist_array = explode("\n", $blacklist_string);
		$blacklist_size = sizeof($blacklist_array);
		$data = $_POST['user_email'];
		// Analyse List
		for($i = 0; $i < $blacklist_size; $i++) {
		$blacklist_current = trim($blacklist_array[$i]);
		if(stripos($data, $blacklist_current) !== false) {
		$result['errors']->add('invalid_email', '<strong>SPAM MASTER: </strong>'. __( get_site_option( 'spammaster_message') ));
		$count = $result;
		$count = mysql_query("UPDATE wp_options SET option_value=option_value + 1 WHERE option_name='spammaster_block_count'");
			echo '<p class="error">'.get_site_option('spammaster_message').'</p>';
		}
		}
		return $result;
		}
		/////////////////////////////////
		// REGISTRATION WITH BUDDYPRES //
		/////////////////////////////////
		function spammaster_buddypress_init() {
			function spammaster_buddypress_register( $result ) {
			if ( spammaster_buddypress_spammail( $result['user_email'] ) )
			$result['errors']->add('user_email', '<strong>SPAM MASTER: </strong>'. __( get_option('spammaster_message') ) );
			$count = $result;
			$count = mysql_query("UPDATE wp_options SET option_value=option_value + 1 WHERE option_name='spammaster_block_count'");
			return $result;
		}
		function spammaster_buddypress_spammail( $user_email ) {
		// Budypress in Mulitsite
		if( is_multisite() ) { 
			$spammaster_blacklist = get_site_option('spammaster_keys');
			}
			else {
			$spammaster_blacklist = get_option('spammaster_keys');
			}
			// Retrieve Blocked List
			$blacklist_string = $spammaster_blacklist;
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
		function spammaster_active() {
		if( is_multisite() ) {
		add_site_option('spammaster_keys','spam@spam.com');
		add_site_option('spammaster_message', 'Your Email, Domain, or Ip is banned from registration.');
		add_site_option( 'spammaster_block_count', 0);
		}
		else {
		add_option('spammaster_message', 'Your Email, Domain, or Ip is banned from registration.');
		add_option( 'spammaster_block_count', 0);
		}
		}
		///////////////////////
		// WORDPRESS ACTIONS //
		///////////////////////
		if( is_multisite() ) {
		add_action( 'network_admin_menu', 'menu' );
		//add_action( 'user_register', 'spammaster_get_keys' );
		}
		else {
		add_action( 'admin_menu', 'options_page' );
		add_action( 'bp_include', 'spammaster_buddypress_init' );
		add_filter( 'bp_core_validate_user_signup', 'spammaster_buddypress_register' );
		add_action( 'register_post', 'spammaster', 10, 3 );
		//add_action( 'user_register', 'spammaster' );
		register_activation_hook( __FILE__, 'spammaster_active' );
		//register_uninstall_hook( __FILE__, 'spammaster_uninstall' );
		}
		////////////////////////
		// ADMINISTRATOR PAGE //
		////////////////////////
		function spammaster_options() {
		global $wpdb;
		?>
<div class="wrap">
<div class="icon32" style="width:40px; vertical-align:middle;"><img src="../wp-content/plugins/spam-master/images/techgasp-minilogo.png" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><?php _e("TechGasp", 'spammaster'); ?></h2>
<table>
<tr>
<td width="387">
<img src="../wp-content/plugins/spam-master/images/techgasp-spammaster-logo.png" alt="Spam Master" align="left" width="387px" height="171px" style="padding:5px;"/>
</td>
<td width="0,5"></td>
<td width="670">
<p>Major player in the Content Management System world! Wordpress, Joomla and Jomsocial Partner with more than 100 high quality, error free Extensions. We provide website customizations and development, SEO Optimization, Facebook Apps, etc. We have fast & furious specialized Hosting for WordPress and Joomla. Our promise, the use of minimal error free code with fast loading times. Check our website for more high quality extensions. Stay up to date by "like" and "follow" our facebook and twitter page for fresh news, releases and upgrades and updates.</p>
<p>
<a class="button-primary" href="http://wordpress.techgasp.com" target="_blank" title="Visit Website">Wordpress Website</a>
<a class="button-primary" href="http://www.techgasp.com" target="_blank" title="Visit Website">Joomla Website</a>
<a class="button-primary" href="http://hosting.techgasp.com" target="_blank" title="Visit Website">Hosting Website</a>
<a class="button-secondary" href="https://www.facebook.com/TechGasp" target="_blank" title="Facebook Page">Facebook Page</a>
<a class="button-secondary" href="https://twitter.com/TechGasp" target="_blank" title="Follow Twitter">Twitter Page</a>
<a class="button-secondary" href="https://plus.google.com/118126459543511361864" target="_blank" title="Follow Google">Google Page</a>
</p>
<h3>Stay up-to-date with new extension releases, extension updates, & upgrades:</h3>
<p>
<span style="float:left; vertical-align:baseline;">
<fb:like href="https://www.facebook.com/TechGasp" send="true" layout="button_count" width="90" show_faces="false"></fb:like>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=281766848505812";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
&nbsp;
<a href="https://twitter.com/TechGasp" class="twitter-follow-button" data-show-count="true">Follow @TechGasp</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</span>
</p>
</td>
</tr>
</table>
<hr>
<div id="icon-tools" class="icon32" style="width:40px; vertical-align:middle;"></br></div>
<h2>Spam Master</h2><br>
<?php
if (isset($_POST['update']))
{
// Update Message
if ($spammaster_new_message = $_POST['spammaster_new_message'])
{
if( is_multisite() )
{
update_site_option('spammaster_message', $spammaster_new_message);
}
else {
update_option('spammaster_message', $spammaster_new_message);
}
}
?>
<div id="message" class="updated fade">
<p><strong><?php _e('Settings Saved!', 'spammaster'); ?></strong></p>
</div>
<?php }
if( is_multisite() )
{
$spammaster_blacklist = get_site_option('spammaster_keys');
$spammaster_blocked_message = get_site_option('spammaster_message');
}
else {
$spammaster_blacklist = get_option('spammaster_keys');
$spammaster_blocked_message = get_option('spammaster_message');
}
?>
<?php
//License Update
$license_code = false;
if (isset($_POST['spammaster_license_code'])){
if  ($license_new_code = $_POST['spammaster_license_code']){
update_option('spammaster_license_old_code', get_option('spammaster_license_code'));
if( is_multisite() ){
update_site_option('spammaster_license_code', $license_new_code);
}
else {
update_option('spammaster_license_code', $license_new_code);
}
}
}
?>
<form method="post" width='1'>
<fieldset class="options">
<legend><h3><?php _e('Your License Status & Protection Level:', 'spammaster'); ?></h3></legend>
<table>
<?php
//Display Selected Protection
$spammaster_free_selected = "Free Protection";
$spammaster_full_selected = "Full Protection";
$key_lic = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvbGljLnR4dA==";
$key_code = wp_remote_get(''.base64_decode($key_lic).'');
$response_key = wp_remote_retrieve_response_code( $key_code );
update_option('spammaster_response_key', $response_key);
$trd_free = "spammaster_trd_free";
add_option('spammaster_trd_free', $trd_free);
$trd_full = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
add_option('spammaster_trd_full', $trd_full);
?>
<tr>
<td width="280" height="25" align="center">Insert Spam Master License: <input id="spammaster_license_code" name="spammaster_license_code" type="text" size="16" maxlength="16" value="<?php echo get_option('spammaster_license_code'); ?>" ></td>
<?php
//HOOK LICENSE
if(get_option('spammaster_license_code') !== get_option('spammaster_license_old_code')){
include('includes/techgasp-spammaster-license.php');
}
else {
}
?>
<td width="20" height="25" align="center"></td>
<td width="120" height="25">
<?php
//Protection List
//$spammaster_protection = "no lic.";
//update_option('spammaster_protection', $spammaster_protection);
if (isset($_POST['spammaster_protection'])){
if ($spammaster_protection = $_POST['spammaster_protection']){
if( is_multisite() ){
update_site_option('spammaster_protection', $spammaster_protection);
update_option('selected', '');
}
else {
update_option('spammaster_protection', $spammaster_protection);
update_option('selected', '');
}
}
}
?>
<select id="spammaster_protection" name="spammaster_protection">
<option>Protection Selector</option>
<option value="<?php echo get_option('spammaster_trd_full'); ?>" <?php if ($spammaster_protection == get_option('spammaster_trd_full')) { echo 'selected="selected"'; }?>>Full Protection</option>
<option value="<?php echo get_option('spammaster_trd_free'); ?>" <?php if ($spammaster_protection == get_option('spammaster_trd_free')) { echo 'selected="selected"'; }?>>Free Protection</option>
</select>
<?php

if (get_option( 'spammaster_protection') == get_option( 'spammaster_trd_free' )){
update_option('spammaster_selected', $spammaster_free_selected);
update_option('spammaster_protection', $trd_free);
	if ( get_option('spammaster_response_key') == 200 ){
		$license_color = "07B357";
		update_option('spammaster_license_color', $license_color);
		$license_status = "protected from 4 threats, read documentation";
		update_option('spammaster_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spammaster_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spammaster_full_rbl_status', $full_rbl_status);
		$medium_rbl_color = "525051";
		update_option('spammaster_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Disconnected";
		update_option('spammaster_medium_rbl_status', $medium_rbl_status);
		$learning_color = "525051";
		update_option('spammaster_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_option('spammaster_learning_status', $learning_status);
		$spammaster_free_keys = "hotmail\r\nmsn\r\nlive\r\noutlook";
		update_option('spammaster_keys', $spammaster_free_keys);
		update_option('blacklist_keys', $spammaster_free_keys);
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='spammaster_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_option('spammaster_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_option('spammaster_protection_number_color', $protection_number_color);
		$spammaster_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spammaster_user_registrations', $spammaster_user_registrations);
	}
	else{
		$license_color = "525051";
		update_option('spammaster_license_color', $license_color);
		$license_status = "protected from 4 threats, read documentation";
		update_option('spammaster_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spammaster_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spammaster_full_rbl_status', $full_rbl_status);
		$medium_rbl_color = "525051";
		update_option('spammaster_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Disconnected";
		update_option('spammaster_medium_rbl_status', $medium_rbl_status);
		$learning_color = "525051";
		update_option('spammaster_learning_color', $learning_color);
		$learning_status = "OFFLINE";
		update_option('spammaster_learning_status', $learning_status);
		$spammaster_free_keys = "hotmail\r\nmsn\r\nlive\r\noutlook";
		update_option('spammaster_keys', $spammaster_free_keys);
		update_option('blacklist_keys', $spammaster_free_keys);
		$protection_total_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='spammaster_keys'");
		$protection_total = str_word_count($protection_total_number);
		update_option('spammaster_protection_total', $protection_total);
		$protection_number_color = "F2AE41";
		update_option('spammaster_protection_number_color', $protection_number_color);
		$spammaster_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spammaster_user_registrations', $spammaster_user_registrations);
		}
}
if (get_option( 'spammaster_protection') == get_option( 'spammaster_trd_full' )){
update_option('spammaster_selected', $spammaster_full_selected);
update_option('spammaster_protection', $trd_full);
	if ( $response_key == 200 ){
		$license_color = "07B357";
		update_option('spammaster_license_color', $license_color);
		$license_status = "VALID LICENSE";
		update_option('spammaster_license_status', $license_status);
		$full_rbl_color = "07B357";
		update_option('spammaster_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Optimal Connection";
		update_option('spammaster_full_rbl_status', $full_rbl_status);
		$medium_rbl_color = "07B357";
		update_option('spammaster_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Optimal Connection";
		update_option('spammaster_medium_rbl_status', $medium_rbl_status);
		$learning_color = "07B357";
		update_option('spammaster_learning_color', $learning_color);
		$learning_status = "ONLINE";
		update_option('spammaster_learning_status', $learning_status);
		$protection_entries = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='spammaster_full_keys'");
		$protection_total = str_word_count($protection_entries);
		update_option('spammaster_protection_total', $protection_total);
		$protection_active_number = $wpdb->get_var("SELECT option_value FROM wp_options WHERE option_name='blacklist_keys'");
		$protection_active = str_word_count($protection_active_number);
		update_option('spammaster_protection_active', $protection_active);
		$protection_number_color = "07B357";
		update_option('spammaster_protection_number_color', $protection_number_color);
		$spammaster_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spammaster_user_registrations', $spammaster_user_registrations);
		update_option('spammaster_keys', get_option('spammaster_full_keys'));
		update_option('blacklist_keys', get_option('spammaster_full_keys'));
	}
	else {
		$license_color = "525051";
		update_option('spammaster_license_color', $license_color);
		$license_status = "No Valid License, Select Free Protection >>>";
		update_option('spammaster_license_status', $license_status);
		$full_rbl_color = "525051";
		update_option('spammaster_full_rbl_color', $full_rbl_color);
		$full_rbl_status = "Disconnected";
		update_option('spammaster_full_rbl_status', $full_rbl_status);
		$medium_rbl_color = "525051";
		update_option('spammaster_medium_rbl_color', $medium_rbl_color);
		$medium_rbl_status = "Disconnected";
		update_option('spammaster_medium_rbl_status', $medium_rbl_status);
		$learning_color = "F2AE41";
		update_option('spammaster_learning_color', $learning_color);
		$learning_status = "No License, OFFLINE";
		update_option('spammaster_learning_status', $learning_status);
		$protection_total = "No License, 0";
		update_option('spammaster_protection_total', $protection_total);
		$protection_active = "No License, 0";
		update_option('spammaster_protection_active', $protection_active);
		$protection_number_color = "F2AE41";
		update_option('spammaster_protection_number_color', $protection_number_color);
		$spammaster_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
		update_option('spammaster_user_registrations', $spammaster_user_registrations);
		$trd_light = false;
		$spammaster_keys = "";
		update_option('spammaster_keys', $spammaster_keys);
		update_option('blacklist_keys', $spammaster_keys);
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
<td width="280" height="25"align="center" bgcolor="#<?php echo get_option('spammaster_license_color'); ?>"><font color="white"><b><?php echo get_option('spammaster_license_status'); ?></b></font></td>
<td width="20" height="25" align="center"></td>
<td width="120" height="25" align="center" bgcolor="#078BB3"><font color="white"><b><?php echo get_option('spammaster_selected'); ?></b></font></td>
<td width="20" height="25"></td>
<td width="140" height="25" align="center" bgcolor="#078BB3"><font color="white"><b><?php echo get_option('spammaster_user_registrations'); ?></b> Registered</font></td>
<td width="1" height="25"></td>
<td width="140" height="25" align="center" bgcolor="#078BB3"><font color="white"><b><?php echo get_option('spammaster_block_count'); ?></b> Blocks</font></td>
<td width="1" height="25"></td>
<td width="220" height="25" align="center" bgcolor="#<?php echo get_option('spammaster_full_rbl_color'); ?>"><font color="white">Cluster Status: <b><?php echo get_option('spammaster_full_rbl_status'); ?></b></font></td>
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
<td width="150" height="25" align="center" bgcolor="#<?php echo get_option('spammaster_protection_number_color'); ?>"><font color="white"><b><?php echo get_option('spammaster_protection_total'); ?></b> Threats</font></td>
<td width="1" height="25"></td>
<td width="150" height="25" align="center" bgcolor="#<?php echo get_option('spammaster_learning_color'); ?>"><font color="white"><b><?php echo get_option('spammaster_learning_status'); ?></b></font></td>
<td width="1" height="25"></td>
<td width="220" height="25" align="center" bgcolor="#<?php echo get_option('spammaster_medium_rbl_color'); ?>"><font color="white">Cluster Status: <b><?php echo get_option('spammaster_medium_rbl_status'); ?></b></font></td>
</tr>
</table>
</fieldset>
<tr>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Save & Refresh", 'spammaster'); ?>' id='submitbutton' /></p>
<p>Insert your license code and press refresh. The license code can be found at the top of your purchase email  <b>Order Number : 4996195009</b>, the license code to insert would be <b>4996195009</b>. If you already inserted the license code and are getting "Invalid / Awaiting Authorization", it might take a few hours for your license to be accepted and replicated in all Real Time Scan servers.</p>
<hr>
<fieldset class="options">
<legend><h3><?php _e('User Management:', 'spammaster'); ?></h3></legend>
<table>
<tr>
<td align="center" bgcolor="#EDECE8">User ID</td>
<td align="center" bgcolor="#EDECE8">Registration</td>
<td align="center" bgcolor="#EDECE8">User Status</td>
<td align="center" bgcolor="#EDECE8">Name</td>
<td align="center" bgcolor="#EDECE8">Username</td>
<td align="center" bgcolor="#EDECE8">Email</td>
</tr>
<ul>
<?php
$spammaster_args = array(
	'orderby'		=> 'ID',
	'order'			=> 'DESC',
	'number'		=> '5',
	'count_total'	=> false,
	'fields'		=> 'all',
);
$spammaster_list_users = get_users( $spammaster_args );
foreach ($spammaster_list_users as $user) {
?> 
</ul>
<tr>
<td align="center"><?php echo $user->ID; ?></td>
<td align="center"><?php echo $user->user_registered; ?></td>
<td align="center"><?php echo $user->user_status; ?></td>
<td align="center"><?php echo $user->display_name; ?></td>
<td align="center"><?php echo $user->user_nicename; ?></td>
<td align="center"><?php echo $user->user_email; ?></td>
</tr>
<?php
}
?>
</table>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Refresh User List", 'spammaster'); ?>' id='submitbutton' /></p>
<hr>
<fieldset class="options">
<legend><h3><?php _e('Edit Registration Message:', 'spammaster'); ?></h3></legend>
<p><?php _e('This message is displayed to spam users who are not allowed to register in your Wordpress. If you edit, remember there\'s not a lot of space so keep it short.', 'spammaster'); ?></p>
<textarea name='spammaster_new_message' cols='80' rows='2'>
<?php echo $spammaster_blocked_message; ?>
</textarea>
</fieldset>
<fieldset class="options">
<textarea name="blacklist_keys" cols="40" rows="15" style="display:none;">
<?php echo strip_tags (get_option('spammaster_keys')); ?>
</textarea>
</fieldset>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Save Settings", 'spammaster'); ?>' id='submitbutton' /></p>
<hr>
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