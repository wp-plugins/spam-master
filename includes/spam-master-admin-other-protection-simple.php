<?php
function menu_rct_single(){
	if ( is_admin() )
	add_submenu_page( 'spam-master', 'Protection Tools', 'Protection Tools', 'manage_options', 'spam-master-recaptcha', 'spam_master_recaptcha' );
}
	///////////////////////
	// WORDPRESS ACTIONS //
	///////////////////////
	if( is_multisite() ) {
		add_action( 'network_admin_menu', 'menu_rct_single' );
	}
	else {
		add_action( 'admin_menu', 'menu_rct_single' );
	}

function spam_master_recaptcha(){
global $wp_nonce, $current_user, $wpdb, $blog_id;
?>
<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<?php
//////////////////
//RECATCHA ADMIN//
//////////////////
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if(!class_exists('spam_master_other_protection_table_header_recaptcha')){
	require_once( dirname( __FILE__ ) . '/spam-master-admin-other-protection-table-header-recaptcha.php');
}
//Prepare Table of elements
$wp_list_table = new spam_master_other_protection_table_header_recaptcha();
//Table of elements
$wp_list_table->display();
?>
</br>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
</br>
<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if(!class_exists('spam_master_other_protection_table_header_honeypot')){
	require_once( dirname( __FILE__ ) . '/spam-master-admin-other-protection-table-header-honeypot.php');
}
//Prepare Table of elements
$wp_list_table = new spam_master_other_protection_table_header_honeypot();
//Table of elements
$wp_list_table->display();
?>
<br>
<div style="width:100%">
<div style="width:50%; float:left;">
<img src="<?php echo plugins_url('../images/techgasp-spam-master-recaptcha.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" />
</div>
<div style="width:50%; float:left;">
<img src="<?php echo plugins_url('../images/techgasp-spam-master-honeypot.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" />
</div>
</div>
<br>
<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if(!class_exists('spam_master_other_protection_table_header_experimental')){
	require_once( dirname( __FILE__ ) . '/spam-master-admin-other-protection-table-header-experimental.php');
}
//Prepare Table of elements
$wp_list_table = new spam_master_other_protection_table_header_experimental();
//Table of elements
$wp_list_table->display();
}
?>