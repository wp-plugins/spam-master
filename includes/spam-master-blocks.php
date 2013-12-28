<?php
//Delete Transients after  week
require_once( dirname( __FILE__ ) . '/spam-master-table-blocks-transients.php');
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		if( is_multisite() ) {
		function menu_blo_multi(){
		// Create menu
		add_submenu_page( 'spam-master', 'Registrations Blocked', 'Registrations Blocked', 'manage_options', 'spam-master-blocks', 'spam_master_blocks' );
		}
		}
		else {
		// Create menu
		function menu_blo_single(){
		if ( is_admin() )
		add_submenu_page( 'spam-master', 'Registrations Blocked', 'Registrations Blocked', 'manage_options', 'spam-master-blocks', 'spam_master_blocks' );
		}
		}

		///////////////////////
		// WORDPRESS ACTIONS //
		///////////////////////
		if( is_multisite() ) {
		add_action( 'network_admin_menu', 'menu_blo_multi' );
		}
		else {
		add_action( 'admin_menu', 'menu_blo_single' );
		}

		function spam_master_blocks(){
?>
<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<br>
<form method="post" width='1'>
<fieldset class="options">
<legend><h3><?php _e('Registrations Blocked', 'spam_master'); ?></h3></legend>
<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists('spam_master_table_blocks')){
	require_once( dirname( __FILE__ ) . '/spam-master-table-blocks.php');
}
//Prepare Table of elements
$wp_list_table = new spam_master_table_blocks();
$wp_list_table->prepare_items();
//Table of elements
$wp_list_table->display();

function spam_master_load_export(){
echo plugins_url( 'spam-master-table-blocks-export.php', __FILE__);
}
function spam_master_clean_transients(){
echo plugins_url( 'spam-master-table-blocks-transients.php', __FILE__);
}
?>
<p>This list contains up to a week of data. Blocked registration data more than a week old is automatically deleted from your database. Reason is simple, keeping your <b>database "slim" and your website with fast page load times</b>.</p>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Refresh List", 'spam_master'); ?>' id='submitbutton' /> <a class="button-primary" href="<?php spam_master_load_export() ?>" target="_blank" title="Export List">Export List</a></p>
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