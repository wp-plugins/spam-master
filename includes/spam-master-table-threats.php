<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<legend><h3><?php _e('Protection List', 'spam_master'); ?></h3></legend>
<?php
global $wpdb;
//$blacklist_keys = get_option( 'blacklist_keys' );
$spam_master_my_keys = get_option( 'spam_master_my_keys' );
$spam_master_free_keys = get_option( 'spam_master_free_keys' );
$spam_master_full_keys = get_option( 'spam_master_full_keys' );

if (isset($_POST['update'])){
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
if( is_multisite() ) {
	if( get_site_option('spam_master_protection') == get_site_option('spam_master_trd_free') ){
		//JOIN & CLEAN PERSONAL KEYS + FREE KEYS TO BLACKLIST
		$spam_master_array_join_keys = array_merge(explode("\n", $spam_master_free_keys), explode("\n", $spam_master_my_keys));
		$spam_master_array_join_keys = array_map("trim", $spam_master_array_join_keys);
		sort ($spam_master_array_join_keys);
		$spam_master_string_join_keys = implode("\n", array_unique($spam_master_array_join_keys));
			if(isset($spam_master_string_my_keys)){
				update_site_option('spam_master_my_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_my_keys))));
				update_site_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
			else{
			update_site_option('spam_master_my_keys', '');
			update_site_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
	}
	if( get_site_option('spam_master_protection') == get_site_option('spam_master_trd_full') ){
			//JOIN & CLEAN PERSONAL KEYS + FULL KEYS TO BLACKLIST
		$spam_master_array_join_keys = array_merge(explode("\n", $spam_master_full_keys), explode("\n", $spam_master_my_keys));
		$spam_master_array_join_keys = array_map("trim", $spam_master_array_join_keys);
		sort ($spam_master_array_join_keys);
		$spam_master_string_join_keys = implode("\n", array_unique($spam_master_array_join_keys));
			if(isset($spam_master_string_my_keys)){
				update_site_option('spam_master_my_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_my_keys))));
				update_site_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
			}
			else{
			update_site_option('spam_master_my_keys', '');
			update_site_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string_join_keys))));
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
<td><b>How to Use:</b></td>
</tr>
<tr>
<td>
<textarea name="spam_master_keys" cols="40" rows="25">
<?php echo strip_tags(get_option('spam_master_rbl_keys')); ?>
</textarea>
</td>
<td></td>
<td>
<textarea name="spam_master_my_keys" cols="40" rows="25">
<?php echo strip_tags (get_option('spam_master_my_keys')); ?>
</textarea>
</td>
<td></td>
<td valign="top">
<div class="description"><b>1. Add to protection list</b></div>
<div class="description">Add 1 or several domains, words, emails or Ip/'s to the <b>My Protection List</b> text area. Press Save List.</div>
<div class="description">Repeat the operation as many times as you want always pressing Save List.</div>
<br>
<div class="description"><b>2. Delete from protection list</b></div>
<div class="description">Delete 1 or more entries at a time from the <b>My Protection List</b> text area. Press Save List.</div>
<div class="description">Repeat the operation as many times as you want always pressing Save List.</div>
<br>
<div class="description"><b>3. What to add</b></div>
<div class="description">Insert 1 entry per line without special characters.</div>
<div class="description">Example of adding 1 domain: example.com</div>
<div class="description">Example of adding 1 word: replica</div>
<div class="description">Example of adding 1 ip: 192.168.1.100</div>
<div class="description">Example of adding 1 email: replica_watches@love_to_spam.com</div>
<br>
<div class="description"><b>4. Better, faster results</b></div>
<div class="description">Let's say you want to block 1 domain called (myspamdomain) with several tld's (com, net, org, etc.), just insert myspamdomain as a word in 1 line. Let's say you want to block an ip range 192.168.1.0 to 192.168.1.254, just insert 192.168.1. in 1 line.</div>
<div class="description">Conclusion, the smaller the word or number, the more it blocks. example: if you insert just the letter <b>a</b> in 1 line... it will block any email, domain or word that contains the letter <b>a</b>.</div>
</td>
</tr>
</table>
</fieldset>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Save List, Refresh & Sort", 'spam_master'); ?>' id='submitbutton' /></p>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
</form>
<p>
<a class="button-secondary" href="http://wordpress.techgasp.com" target="_blank" title="Visit Website">More TechGasp Plugins</a>
<a class="button-secondary" href="http://wordpress.techgasp.com/support/" target="_blank" title="Facebook Page">TechGasp Support</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master/" target="_blank" title="Visit Website">Spam Master Info</a>
<a class="button-primary" href="http://wordpress.techgasp.com/spam-master-documentation/" target="_blank" title="Visit Website">Spam Master Documentation</a>
<a class="button-primary" href="http://wordpress.org/plugins/spam-master/" target="_blank" title="Visit Website">RATE US *****</a>
</p>
</div>