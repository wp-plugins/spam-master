<?php
//Delete Transients after  week
require_once( dirname( __FILE__ ) . '/spam-master-admin-registrations-transients.php');
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		function menu_reg_net_single(){
		if ( is_admin() )
		add_submenu_page( 'spam-master', 'Registrations', 'Registrations', 'manage_options', 'spam-master-registrations', 'spam_master_registrations_net' );
		}

		///////////////////////
		// WORDPRESS ACTIONS //
		///////////////////////
		if( is_multisite() ) {
		add_action( 'network_admin_menu', 'menu_reg_net_single' );
		}
		else {
		}

		function spam_master_registrations_net(){
?>
<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<br>
<form method="post" width='1'>
<fieldset class="options">
<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists('spam_master_registrations_header')){
	require_once( dirname( __FILE__ ) . '/spam-master-admin-registrations-header-network-admin.php');
}

//Prepare Table of elements
$wp_list_table = new spam_master_registrations_header_network_admin();
//Table of elements
$wp_list_table->display();
?>
</fieldset>
</form>
<br>
<h2>IMPORTANT: Makes no use of Javascript or Ajax to keep your website fast and conflicts free</h2>

<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<br>
<p>
<a class="button-secondary" href="http://wordpress.techgasp.com" target="_blank" title="Visit Website">More TechGasp Plugins</a>
<a class="button-secondary" href="http://wordpress.techgasp.com/support/" target="_blank" title="TechGasp Support">TechGasp Support</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master/" target="_blank" title="Visit Website">Spam Master Info</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master-documentation/" target="_blank" title="Visit Website">Spam Master Documentation</a>
<a class="button-primary" href="http://wordpress.org/plugins/spam-master/" target="_blank" title="Visit Website">RATE US *****</a>
</p>
</div>
<?php
}