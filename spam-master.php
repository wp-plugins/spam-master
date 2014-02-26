<?php
/**
Plugin Name: Spam Master
Plugin URI: http://wordpress.techgasp.com/spam-master/
Version: 4.3.1
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
if(!class_exists('spam_master')) :
///////DEFINE VERSION///////
define('SPAM_MASTER_ID', 'spam-master');
///////DEFINE VERSION///////
define( 'spam_master_VERSION', '4.3.1' );
global $spam_master_version, $spam_master_name;
$spam_master_version = "4.3.1"; //for other pages
$spam_master_name = "Spam Master"; //pretty name
if( is_multisite() ) {
update_site_option( 'spam_master_installed_version', $spam_master_version );
update_site_option( 'spam_master_name', $spam_master_name );
}
else{
update_option( 'spam_master_installed_version', $spam_master_version );
update_option( 'spam_master_name', $spam_master_name );
}

// HOOK ADMIN
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin.php');
// HOOK SETTINGS
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin-settings.php');
// HOOK THREATS
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin-threats.php');
// HOOK REGISTRATIONS
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin-registrations.php');
// HOOK BLOCKS
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin-blocks.php');
// HOOK STATISTICS
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin-statistics.php');
// HOOK UPDATER
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin-updater.php');
// HOOK Re-CAPTACHA & HONEYPOT
//require_once( dirname( __FILE__ ) . '/includes/spam-master-recaptcha.php');
// HOOK INVITATION
require_once( dirname( __FILE__ ) . '/includes/spam-master-admin-invite.php');

class spam_master{
//REGISTER PLUGIN
public static function spam_master_register(){
register_setting(SPAM_MASTER_ID, 'tsm_quote');
}
public static function content_with_quote($content){
$quote = '<p>' . get_option('tsm_quote') . '</p>';
	return $content . $quote;
}
//SETTINGS LINK IN PLUGIN MANAGER
public static function spam_master_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/spam-master.php' ) ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=spam-master' ) . '">'.__( 'Settings' ).'</a>';
	}

	return $links;
}

public static function spam_master_updater_version_check(){
global $spam_master_version;
//CHECK NEW VERSION
$spam_master_slug = basename(dirname(__FILE__));
$current = get_site_transient( 'update_plugins' );
$spam_plugin_slug = $spam_master_slug.'/'.$spam_master_slug.'.php';
@$r = $current->response[ $spam_plugin_slug ];
if (empty($r)){
$r = false;
$spam_plugin_slug = false;
if( is_multisite() ) {
update_site_option( 'spam_master_newest_version', $spam_master_version );
}
else{
update_option( 'spam_master_newest_version', $spam_master_version );
}
}
if (!empty($r)){
$spam_plugin_slug = $spam_master_slug.'/'.$spam_master_slug.'.php';
@$r = $current->response[ $spam_plugin_slug ];
if( is_multisite() ) {
update_site_option( 'spam_master_newest_version', $r->new_version );
}
else{
update_option( 'spam_master_newest_version', $r->new_version );
}
}
}
		// Advanced Updater

//END CLASS
}
if ( is_admin() ){
	add_action('admin_init', array('spam_master', 'spam_master_register'));
	add_action('init', array('spam_master', 'spam_master_updater_version_check'));
}
add_filter('the_content', array('spam_master', 'content_with_quote'));
add_filter( 'plugin_action_links', array('spam_master', 'spam_master_links'), 10, 2 );
endif;
?>