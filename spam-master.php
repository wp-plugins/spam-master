<?php
/**
Plugin Name: Spam Master
Plugin URI: http://wordpress.techgasp.com/spam-master/
Version: 4.2
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
define( 'spam_master_VERSION', '4.2' );
$spam_master_version = "4.2"; //for other pages
update_option( 'spam_master_version', $spam_master_version );
// HOOK SETTINGS
require_once( dirname( __FILE__ ) . '/includes/spam-master-settings.php');
// HOOK Re-CAPTACHA & HONEYPOT
//require_once( dirname( __FILE__ ) . '/includes/spam-master-recaptcha.php');
// HOOK THREATS
require_once( dirname( __FILE__ ) . '/includes/spam-master-threats.php');
// HOOK INVITATION
require_once( dirname( __FILE__ ) . '/includes/spam-master-invite.php');
// HOOK REGISTRATIONS
require_once( dirname( __FILE__ ) . '/includes/spam-master-registrations.php');
// HOOK BLOCKS
require_once( dirname( __FILE__ ) . '/includes/spam-master-blocks.php');

//SETTINGS LINK IN PLUGIN MANAGER
add_filter( 'plugin_action_links', 'spam_master_link', 10, 2 );
// Add settings link on plugin page
function spam_master_link($links, $file) {
static $this_plugin;
if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
if ($file = dirname( __FILE__ ) . '/includes/spam-master-admin.php'){
$settings_link = '<a href="' . admin_url( 'admin.php?page=spam-master'). '">' . __( 'Settings' ) . '</a>';
array_unshift($links, $settings_link);
}
return $links;
}
?>