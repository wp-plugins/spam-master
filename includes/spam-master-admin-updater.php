<?php
function menu_single_spam_admin_updater(){
		if ( is_admin() )
		add_submenu_page( 'spam-master', 'Updater', 'Updater', 'manage_options', 'spam-master-admin-updater', 'spam_master_admin_updater' );
}

function spam_master_admin_updater(){
?>
<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;Updater</b></h2>

<div id="icon-tools" class="icon32" style="width:40px; vertical-align:middle;"></br></div>
<?php
if(!class_exists('spam_master_admin_updater_version_table')){
	require_once( dirname( __FILE__ ) . '/spam-master-admin-updater-version-table.php');
}
//Prepare Table of elements
$wp_list_table = new spam_master_admin_updater_version_table();
//Table of elements
$wp_list_table->display();
?>
</br>
<h2>IMPORTANT: Makes no use of Javascript or Ajax to keep your website fast and conflicts free</h2>

<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>

<br>

<p>
<a class="button-secondary" href="http://wordpress.techgasp.com" target="_blank" title="Visit Website">More TechGasp Plugins</a>
<a class="button-secondary" href="http://wordpress.techgasp.com/support/" target="_blank" title="TechGasp Support">TechGasp Support</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master/" target="_blank" title="Visit Website"><?php echo get_option('spam_master_name'); ?> Info</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master-documentation/" target="_blank" title="Visit Website"><?php echo get_option('spam_master_name'); ?> Documentation</a>
<a class="button-primary" href="http://wordpress.org/plugins/spam-master/" target="_blank" title="Visit Website">RATE US *****</a>
</p>

<?php
}
if( is_multisite() ) {
add_action( 'network_admin_menu', 'menu_single_spam_admin_updater' );
}
else {
add_action( 'admin_menu', 'menu_single_spam_admin_updater' );
}
?>