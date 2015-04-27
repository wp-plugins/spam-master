<?php
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		function menu_thr_single(){
		if ( is_admin() )
		add_submenu_page( 'spam-master', 'Protection List', 'Protection List', 'manage_options', 'spam-master-threats', 'spam_master_threats' );
		}
		///////////////////////
		// WORDPRESS ACTIONS //
		///////////////////////
		if( is_multisite() ) {
		add_action( 'network_admin_menu', 'menu_thr_single' );
		}
		else {
		add_action( 'admin_menu', 'menu_thr_single' );
		}

function spam_master_threats(){
require_once( dirname( __FILE__ ) . '/spam-master-admin-threats-table.php');
}