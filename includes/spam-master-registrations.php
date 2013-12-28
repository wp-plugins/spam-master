<?php
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		if( is_multisite() ) {
		function menu_reg_multi(){
		// Create menu
		add_submenu_page( 'spam-master', 'Registrations', 'Registrations', 'manage_options', 'spam-master-registrations', 'spam_master_registrations' );
		}
		}
		else {
		// Create menu
		function menu_reg_single(){
		if ( is_admin() )
		add_submenu_page( 'spam-master', 'Registrations', 'Registrations', 'manage_options', 'spam-master-registrations', 'spam_master_registrations' );
		}
		}

		///////////////////////
		// WORDPRESS ACTIONS //
		///////////////////////
		if( is_multisite() ) {
		add_action( 'network_admin_menu', 'menu_reg_multi' );
		}
		else {
		add_action( 'admin_menu', 'menu_reg_single' );
		}

		function spam_master_registrations(){
?>
<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<br>
<form method="post" width='1'>
<fieldset class="options">
<legend><h3><?php _e('Registrations', 'spam_master'); ?></h3></legend>
<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists('spam_master_table_registrations')){
	require_once( dirname( __FILE__ ) . '/spam-master-table-registrations.php');
}
//Prepare Table of elements
$wp_list_table = new spam_master_table_registrations();
$wp_list_table->prepare_items();
//Table of elements
$wp_list_table->display();
?>
<p><b>User Status:</b></p>
<p><b>0 User Registered & Account Active.</b></p>
<p><b>1 User Registered, Account Active & Account Disabled by Administrator, Marked as Spam.</b></p>
<p><b>2 User Registered & Account Not Active</b></p>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Refresh Lists", 'spam_master'); ?>' id='submitbutton' /></p>
</fieldset>
</form>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
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