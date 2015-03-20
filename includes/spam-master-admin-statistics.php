<?php
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		function menu_stat_single(){
		if ( is_admin() )
		add_submenu_page( 'spam-master', 'Statistics', 'Statistics', 'manage_options', 'spam-master-statistics', 'spam_master_statistics' );
		}

//RE-GENERATE COUNTING STATISTICS
function spam_master_statistics_generate(){
global $wpdb, $blog_id;
$blog_prefix = $wpdb->get_blog_prefix();
$table_prefix = $wpdb->base_prefix;
	//IF MULTI-SITE
	if(is_multisite()){
		//IF FREE
		if (get_blog_option($blog_id, 'spam_master_protection') == get_blog_option($blog_id, 'spam_master_trd_free')){
			//IF FREE 200
			if (get_blog_option($blog_id, 'spam_master_response_key') == 200 ){
			$full_rbl_color = "F2AE41";
			update_blog_option($blog_id, 'spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "ONLINE. Not Optimal, Select FULL PROTECTION";
			update_blog_option($blog_id, 'spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "F2AE41";
			update_blog_option($blog_id, 'spam_master_learning_color', $learning_color);
			$learning_status = "License is Valid. Select FULL PROTECTION";
			update_blog_option($blog_id, 'spam_master_learning_status', $learning_status);
			$protection_total_number = $wpdb->get_var("SELECT option_value FROM {$table_prefix}options WHERE option_name='blacklist_keys'");
			$protection_total = 'Select FULL PROTECTION. Protected Against '.str_word_count($protection_total_number);
			update_blog_option($blog_id,'spam_master_protection_total', $protection_total);
			$protection_number_color = "F2AE41";
			update_blog_option($blog_id,'spam_master_protection_number_color', $protection_number_color);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(umeta_id) FROM {$table_prefix}usermeta WHERE meta_key='primary_blog' AND meta_value={$blog_id}");
			update_blog_option($blog_id,'spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments");
			update_blog_option($blog_id,'spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='spam'");
			update_blog_option($blog_id,'spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='1'");
			update_blog_option($blog_id,'spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='0'");
			update_blog_option($blog_id,'spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='trash'");
			update_blog_option($blog_id,'spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
			else{
			$full_rbl_color = "525051";
			update_blog_option($blog_id,'spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "DISCONECTED";
			update_blog_option($blog_id,'spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "525051";
			update_blog_option($blog_id,'spam_master_learning_color', $learning_color);
			$learning_status = "OFFLINE";
			update_blog_option($blog_id,'spam_master_learning_status', $learning_status);
			$protection_total_number = $wpdb->get_var("SELECT option_value FROM {$table_prefix}options WHERE option_name='blacklist_keys'");
			$protection_total = str_word_count($protection_total_number);
			update_blog_option($blog_id,'spam_master_protection_total', $protection_total);
			$protection_number_color = "F2AE41";
			update_blog_option($blog_id,'spam_master_protection_number_color', $protection_number_color);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(umeta_id) FROM {$table_prefix}usermeta WHERE meta_key='primary_blog' AND meta_value={$blog_id}");
			update_blog_option($blog_id,'spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments");
			update_blog_option($blog_id,'spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='spam'");
			update_blog_option($blog_id,'spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='1'");
			update_blog_option($blog_id,'spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='0'");
			update_blog_option($blog_id,'spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='trash'");
			update_blog_option($blog_id,'spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
		}
		//IF FULL
		if (get_blog_option($blog_id, 'spam_master_protection') == get_blog_option($blog_id, 'spam_master_trd_full' )){
			//IF FULL 200
			if (get_blog_option($blog_id,'spam_master_response_key') == 200){
			$full_rbl_color = "07B357";
			update_blog_option($blog_id,'spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "Optimal Connection";
			update_blog_option($blog_id,'spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "07B357";
			update_blog_option($blog_id,'spam_master_learning_color', $learning_color);
			$learning_status = "ONLINE";
			update_blog_option($blog_id,'spam_master_learning_status', $learning_status);
			$protection_total_number = $wpdb->get_var("SELECT option_value FROM {$table_prefix}options WHERE option_name='blacklist_keys'");
			$protection_total = str_word_count($protection_total_number);
			update_blog_option($blog_id,'spam_master_protection_total', $protection_total);
			$protection_number_color = "07B357";
			update_blog_option($blog_id,'spam_master_protection_number_color', $protection_number_color);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(umeta_id) FROM {$table_prefix}usermeta WHERE meta_key='primary_blog' AND meta_value={$blog_id}");
			update_blog_option($blog_id,'spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments");
			update_blog_option($blog_id,'spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='spam'");
			update_blog_option($blog_id,'spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='1'");
			update_blog_option($blog_id,'spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='0'");
			update_blog_option($blog_id,'spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='trash'");
			update_blog_option($blog_id,'spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
			else{
			$full_rbl_color = "525051";
			update_blog_option($blog_id,'spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "Disconnected";
			update_blog_option($blog_id,'spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "F2AE41";
			update_blog_option($blog_id,'spam_master_learning_color', $learning_color);
			$learning_status = "No License, Select FREE PROTECTION in Settings Page";
			update_blog_option($blog_id,'spam_master_learning_status', $learning_status);
			$protection_total = "No License. Select FREE PROTECTION in Settings Page";
			update_blog_option($blog_id,'spam_master_protection_total', $protection_total);
			$protection_number_color = "E8052B";
			update_blog_option($blog_id,'spam_master_protection_number_color', $protection_number_color);
			$protection_total = "Select FREE PROTECTION in Settings Page to enable protection against Hotmail, Msn, Live and Outlook ";
			update_blog_option($blog_id,'spam_master_protection_total', $protection_total);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(umeta_id) FROM {$table_prefix}usermeta WHERE meta_key='primary_blog' AND meta_value={$blog_id}");
			update_blog_option($blog_id,'spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments");
			update_blog_option($blog_id,'spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='spam'");
			update_blog_option($blog_id,'spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='1'");
			update_blog_option($blog_id,'spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='0'");
			update_blog_option($blog_id,'spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM {$blog_prefix}comments WHERE comment_approved='trash'");
			update_blog_option($blog_id,'spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
		}
	}
	//IF SINGLE SITE
	else{
		//IF FREE
		if (get_option( 'spam_master_protection') == get_option( 'spam_master_trd_free' )){
			//IF FREE 200
			if ( get_option('spam_master_response_key') == 200 ){
			$full_rbl_color = "F2AE41";
			update_option('spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "ONLINE. Not Optimal, Select FULL PROTECTION";
			update_option('spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "F2AE41";
			update_option('spam_master_learning_color', $learning_color);
			$learning_status = "License is Valid. Select FULL PROTECTION";
			update_option('spam_master_learning_status', $learning_status);
			$table_prefix = $wpdb->base_prefix;
			$protection_total_number = $wpdb->get_var("SELECT option_value FROM {$table_prefix}options WHERE option_name='blacklist_keys'");
			$protection_total = 'Select FULL PROTECTION. Protected Against '.str_word_count($protection_total_number);
			update_option('spam_master_protection_total', $protection_total);
			$protection_number_color = "F2AE41";
			update_option('spam_master_protection_number_color', $protection_number_color);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
			update_option('spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments");
			update_option('spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='spam'");
			update_option('spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='1'");
			update_option('spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='0'");
			update_option('spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='trash'");
			update_option('spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
			else{
			$full_rbl_color = "525051";
			update_option('spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "DISCONECTED";
			update_option('spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "525051";
			update_option('spam_master_learning_color', $learning_color);
			$learning_status = "OFFLINE";
			update_option('spam_master_learning_status', $learning_status);
			$table_prefix = $wpdb->base_prefix;
			$protection_total_number = $wpdb->get_var("SELECT option_value FROM {$table_prefix}options WHERE option_name='blacklist_keys'");
			$protection_total = str_word_count($protection_total_number);
			update_option('spam_master_protection_total', $protection_total);
			$protection_number_color = "F2AE41";
			update_option('spam_master_protection_number_color', $protection_number_color);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
			update_option('spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments");
			update_option('spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='spam'");
			update_option('spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='1'");
			update_option('spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='0'");
			update_option('spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='trash'");
			update_option('spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
		}
		if (get_option( 'spam_master_protection') == get_option( 'spam_master_trd_full' )){
			//IF FULL 200
			if ( get_option('spam_master_response_key') == 200 ){
			$full_rbl_color = "07B357";
			update_option('spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "Optimal Connection";
			update_option('spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "07B357";
			update_option('spam_master_learning_color', $learning_color);
			$learning_status = "ONLINE";
			update_option('spam_master_learning_status', $learning_status);
			$table_prefix = $wpdb->base_prefix;
			$protection_total_number = $wpdb->get_var("SELECT option_value FROM {$table_prefix}options WHERE option_name='blacklist_keys'");
			$protection_total = str_word_count($protection_total_number);
			update_option('spam_master_protection_total', $protection_total);
			$protection_number_color = "07B357";
			update_option('spam_master_protection_number_color', $protection_number_color);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
			update_option('spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments");
			update_option('spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='spam'");
			update_option('spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='1'");
			update_option('spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='0'");
			update_option('spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='trash'");
			update_option('spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
			else{
			$full_rbl_color = "525051";
			update_option('spam_master_full_rbl_color', $full_rbl_color);
			$full_rbl_status = "Disconnected";
			update_option('spam_master_full_rbl_status', $full_rbl_status);
			$learning_color = "F2AE41";
			update_option('spam_master_learning_color', $learning_color);
			$learning_status = "No License, Select FREE PROTECTION in Settings Page";
			update_option('spam_master_learning_status', $learning_status);
			$protection_total = "No License. Select FREE PROTECTION in Settings Page";
			update_option('spam_master_protection_total', $protection_total);
			$protection_number_color = "E8052B";
			update_option('spam_master_protection_number_color', $protection_number_color);
			$protection_total = "Select FREE PROTECTION in Settings Page to enable protection against Hotmail, Msn, Live and Outlook ";
			update_option('spam_master_protection_total', $protection_total);
			$spam_master_user_registrations = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
			update_option('spam_master_user_registrations', $spam_master_user_registrations);
			$spam_master_comments_total = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments");
			update_option('spam_master_comments_total', $spam_master_comments_total);
			$spam_master_comments_total_blocked = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='spam'");
			update_option('spam_master_comments_total_blocked', $spam_master_comments_total_blocked);
			$spam_master_comments_total_approved = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='1'");
			update_option('spam_master_comments_total_approved', $spam_master_comments_total_approved);
			$spam_master_comments_total_pending = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='0'");
			update_option('spam_master_comments_total_pending', $spam_master_comments_total_pending);
			$spam_master_comments_total_trashed = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved='trash'");
			update_option('spam_master_comments_total_trashed', $spam_master_comments_total_trashed);
			}
		}
	}
}

		///////////////////////
		// WORDPRESS ACTIONS //
		///////////////////////
		if( is_multisite() ) {
		add_action( 'admin_menu', 'menu_stat_single' );
		add_action( 'wp_loaded', 'spam_master_statistics_generate' );
		}
		else {
		add_action( 'admin_menu', 'menu_stat_single' );
		add_action( 'wp_loaded', 'spam_master_statistics_generate' );
		}

function spam_master_statistics(){
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

if(!class_exists('spam_master_statistics_table')){
	require_once( dirname( __FILE__ ) . '/spam-master-admin-statistics-table.php');
}

//Prepare Table of elements
$wp_list_table = new spam_master_statistics_table();
//Table of elements
$wp_list_table->display();

?>
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