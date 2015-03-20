<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<br>
<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if(!class_exists('spam_master_threats_header')){
	require_once( dirname( __FILE__ ) . '/spam-master-admin-threats-header.php');
}
//Prepare Table of elements
$wp_list_table = new spam_master_threats_header();
//Table of elements
$wp_list_table->display_threats();

global $wpdb, $blog_id;
if (is_multisite()){
$spam_master_my_keys = get_blog_option($blog_id, 'spam_master_my_keys');
$spam_master_keys_white = get_blog_option($blog_id, 'spam_master_keys_white');
$spam_master_free_keys = get_blog_option($blog_id, 'spam_master_free_keys');
$spam_master_full_keys = get_blog_option($blog_id, 'spam_master_full_keys');
}
else{
$spam_master_my_keys = get_option('spam_master_my_keys');
$spam_master_keys_white = get_option('spam_master_keys_white');
$spam_master_free_keys = get_option('spam_master_free_keys');
$spam_master_full_keys = get_option('spam_master_full_keys');
}

if (isset($_POST['update'])){
//MY KEYS
	if ($spam_master_my_keys = $_POST['spam_master_my_keys']){
//UPDATE & CLEAN PERSONAL KEYS
$spam_master_array_my_keys = array_merge(explode("\n", $spam_master_my_keys));
$spam_master_array_my_keys = array_map("trim", $spam_master_array_my_keys);
sort ($spam_master_array_my_keys);
$spam_master_string_my_keys = implode("\n", array_unique($spam_master_array_my_keys));
	}

//delete blacklist to be re-set
delete_option('blacklist_keys');


//MULTI-SITE
if(is_multisite()){
	if( get_blog_option($blog_id, 'spam_master_protection') == get_blog_option($blog_id, 'spam_master_trd_free') ){
		//JOIN & CLEAN PERSONAL KEYS + FREE KEYS TO BLACKLIST
		$spam_master_array_join_keys = array_merge(explode("\n", $spam_master_free_keys), explode("\n", $spam_master_my_keys));
		$spam_master_array_join_keys = array_map("trim", $spam_master_array_join_keys);
		sort ($spam_master_array_join_keys);
		$spam_master_string_join_keys = implode("\n", array_unique($spam_master_array_join_keys));
			if(isset($spam_master_string_my_keys)){
				update_blog_option($blog_id, 'spam_master_my_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_my_keys))));
				update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
			else{
			update_blog_option($blog_id, 'spam_master_my_keys', '');
			update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
	}
	if( get_blog_option($blog_id, 'spam_master_protection') == get_blog_option($blog_id, 'spam_master_trd_full') ){
			//JOIN & CLEAN PERSONAL KEYS + FULL KEYS TO BLACKLIST
		$spam_master_array_join_keys = array_merge(explode("\n", $spam_master_full_keys), explode("\n", $spam_master_my_keys));
		$spam_master_array_join_keys = array_map("trim", $spam_master_array_join_keys);
		sort ($spam_master_array_join_keys);
		$spam_master_string_join_keys = implode("\n", array_unique($spam_master_array_join_keys));
			if(isset($spam_master_string_my_keys)){
				update_blog_option($blog_id, 'spam_master_my_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_my_keys))));
				update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
			else{
			update_blog_option($blog_id, 'spam_master_my_keys', '');
			update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
	}
}
//SINGLE-SITE
else {
	if( get_option('spam_master_protection') == get_option('spam_master_trd_free') ){
		//JOIN & CLEAN PERSONAL KEYS + FREE KEYS TO BLACKLIST
		$spam_master_array_join_keys = array_merge(explode("\n", $spam_master_free_keys), explode("\n", $spam_master_my_keys));
		$spam_master_array_join_keys = array_map("trim", $spam_master_array_join_keys);
		sort ($spam_master_array_join_keys);
		$spam_master_string_join_keys = implode("\n", array_unique($spam_master_array_join_keys));
			if(isset($spam_master_string_my_keys)){
				update_option('spam_master_my_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_my_keys))));
				update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
			else{
				update_option('spam_master_my_keys', '');
				update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
	}
	if( get_option('spam_master_protection') == get_option('spam_master_trd_full') ){
		//JOIN & CLEAN PERSONAL KEYS + FULL KEYS TO BLACKLIST
		$spam_master_array_join_keys = array_merge(explode("\n", $spam_master_full_keys), explode("\n", $spam_master_my_keys));
		$spam_master_array_join_keys = array_map("trim", $spam_master_array_join_keys);
		sort ($spam_master_array_join_keys);
		$spam_master_string_join_keys = implode("\n", array_unique($spam_master_array_join_keys));
			if(isset($spam_master_string_my_keys)){
				update_option('spam_master_my_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_my_keys))));
				update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
			else{
			update_option('spam_master_my_keys', '');
			update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
	}
}

//WHITE KEYS
	if ($spam_master_keys_white = $_POST['spam_master_keys_white']){
//UPDATE & CLEAN WHITE KEYS
$spam_master_array_keys_white = array_merge(explode("\n", $spam_master_keys_white));
$spam_master_array_keys_white = array_map("trim", $spam_master_array_keys_white);
sort ($spam_master_array_keys_white);
$spam_master_string_keys_white = implode("\n", array_unique($spam_master_array_keys_white));
	}

//MULTI-SITE
if( is_multisite() ) {
	if( get_blog_option($blog_id, 'spam_master_protection') == get_blog_option($blog_id, 'spam_master_trd_free') ){
		//JOIN & CLEAN PERSONAL KEYS + FREE KEYS TO BLACKLIST
		$spam_master_array_join_keys_free = explode("\n", $spam_master_string_join_keys);
		$spam_master_array_join_keys_white_clean = explode("\n", $spam_master_keys_white);
		//compare full to white and delet duplicates in full
		@$spam_master_array_join_keys_clean = array_diff($spam_master_array_join_keys_free, $spam_master_array_keys_white);
		@$spam_master_array_join_keys_clean = array_map("trim", $spam_master_array_join_keys_clean);
		@sort ($spam_master_array_join_keys_clean);
		@$spam_master_string_join_keys_clean = implode("\n", array_unique($spam_master_array_join_keys_clean));
			if(isset($spam_master_array_keys_white)){
				update_blog_option($blog_id, 'spam_master_keys_white', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_keys_white))));
				update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
			else{
			update_blog_option($blog_id, 'spam_master_keys_white', '');
			update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
	}
	if( get_blog_option($blog_id, 'spam_master_protection') == get_blog_option($blog_id, 'spam_master_trd_full') ){
		//JOIN & CLEAN PERSONAL KEYS + FULL KEYS TO BLACKLIST
		$spam_master_array_join_keys_full = explode("\n", $spam_master_string_join_keys);
		$spam_master_array_join_keys_white_clean = explode("\n", $spam_master_keys_white);
		//compare full to white and delet duplicates in full
		@$spam_master_array_join_keys_clean = array_diff($spam_master_array_join_keys_full, $spam_master_array_keys_white);
		@$spam_master_array_join_keys_clean = array_map("trim", $spam_master_array_join_keys_clean);
		@sort ($spam_master_array_join_keys_clean);
		@$spam_master_string_join_keys_clean = implode("\n", array_unique($spam_master_array_join_keys_clean));
			if(isset($spam_master_array_keys_white)){
				update_blog_option($blog_id, 'spam_master_keys_white', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_keys_white))));
				update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
			else{
			update_blog_option($blog_id, 'spam_master_keys_white', '');
			update_blog_option($blog_id, 'blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
	}
}
//SINGLE-SITE
else {
	if( get_option('spam_master_protection') == get_option('spam_master_trd_free') ){
		//JOIN & CLEAN PERSONAL KEYS + FREE KEYS TO BLACKLIST
		$spam_master_array_join_keys_free = explode("\n", $spam_master_string_join_keys);
		$spam_master_array_join_keys_white_clean = explode("\n", $spam_master_keys_white);
		//compare full to white and delet duplicates in full
		@$spam_master_array_join_keys_clean = array_diff($spam_master_array_join_keys_free, $spam_master_array_keys_white);
		@$spam_master_array_join_keys_clean = array_map("trim", $spam_master_array_join_keys_clean);
		@sort ($spam_master_array_join_keys_clean);
		@$spam_master_string_join_keys_clean = implode("\n", array_unique($spam_master_array_join_keys_clean));
			if(isset($spam_master_array_keys_white)){
				update_option('spam_master_keys_white', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_keys_white))));
				update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
			else{
			update_option('spam_master_keys_white', '');
			update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
	}
	if( get_option('spam_master_protection') == get_option('spam_master_trd_full') ){
		//JOIN & CLEAN PERSONAL KEYS + FULL KEYS TO BLACKLIST
		$spam_master_array_join_keys_full = explode("\n", $spam_master_string_join_keys);
		$spam_master_array_join_keys_white_clean = explode("\n", $spam_master_keys_white);
		//compare full to white and delet duplicates in full
		@$spam_master_array_join_keys_clean = array_diff($spam_master_array_join_keys_full, $spam_master_array_keys_white);
		@$spam_master_array_join_keys_clean = array_map("trim", $spam_master_array_join_keys_clean);
		@sort ($spam_master_array_join_keys_clean);
		@$spam_master_string_join_keys_clean = implode("\n", array_unique($spam_master_array_join_keys_clean));
			if(isset($spam_master_array_keys_white)){
				update_option('spam_master_keys_white', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_keys_white))));
				update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
			else{
			update_option('spam_master_keys_white', '');
			update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys_clean))));
			}
	}
}
?>
<div id="message" class="updated fade">
<p><strong><?php _e('Settings Saved!', 'spam_master'); ?></strong></p>
</div>
<?php
}
?>
<form method="post" width='1'>
<fieldset class="options">
<table>
<tr>
<td><b>&nbsp;RBL Protection List:</b></td>
<td></td>
<td><b>&nbsp;My Personal Protection List:</b></td>
<td></td>
<td><b>&nbsp;My WhiteList:</b></td>
<td></td>
<td><b>How to Use:</b></td>
</tr>
<tr>
<td>
<textarea name="spam_master_keys" cols="35" rows="25">
<?php echo strip_tags(get_option('spam_master_rbl_keys')); ?>
</textarea>
</td>
<td></td>
<td>
<textarea name="spam_master_my_keys" cols="35" rows="25">
<?php echo strip_tags (get_option('spam_master_my_keys')); ?>
</textarea>
</td>
<td></td>
<td>
<textarea name="spam_master_keys_white" cols="35" rows="25">
<?php echo strip_tags(get_option('spam_master_keys_white')); ?>
</textarea>
</td>
<td></td>
<td valign="top">
<div class="description"><b>RBL Protection List:</b></div>
<div class="description">This column automatically displays  protection keys provided by Spam Master, domains, words, emails or Ip's. These keys are set by the protection level selected in Settings Page, Free or Full Protection. <b>Column Not Editable.</b></div>
<br>
<div class="description"><b>My Personal Protection List:</b></div>
<div class="description">These are your personal keys, they get combined with the RBL Protection List keys. Add or delete as many domains, words, emails or Ip's as you want. Insert 1 per line. <b>Column is Editable.</b></div>
<br>
<div class="description"><b>My WhiteList:</b></div>
<div class="description">Used to override domains, words, emails or Ip's automatically set by the RBL Protection List. The overriding domain, word, email or Ip needs to be written in exactly the same way as it shows in the RBL Protection List. Insert 1 per line. <b>Column is Editable.</b></div>
<br>
<div class="description">Remember to always press <b>Save List</b> to apply your changes.</div>
<br>
<div class="description">More about these settings, <a href="http://wordpress.techgasp.com/spam-master-documentation/" target="_blank">Documentation</a>.</div>
</td>
</tr>
</table>
</fieldset>
<p class="submit" style="margin:0px; padding:0px; height:30px;"><input class='button-primary' type='submit' name='update' value='<?php _e("Save List, Refresh & Sort", 'spam_master'); ?>' id='submitbutton' /></p>
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