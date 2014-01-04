<?php
global $wpdb;
if (isset($_POST['update']))
?>
<div class="wrap">
<div style="width:40px; vertical-align:middle; float:left;"><img src="<?php echo plugins_url('../images/techgasp-minilogo.png', __FILE__); ?>" alt="' . esc_attr__( 'TechGasp Plugins') . '" /><br /></div>
<h2><b>&nbsp;TechGasp - Spam Master</b></h2>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
<legend><h3><?php _e('Protection List', 'spam_master'); ?></h3></legend>
<?php
if (isset($_POST['update'])){
// UPDATE KEYS
if ($blacklist_new_keys = $_POST['blacklist_keys']){
$spam_master_array = explode("\n", $blacklist_new_keys);
sort ($spam_master_array);
$spam_master_string = implode("\n", array_unique($spam_master_array));
if( is_multisite() ) { 
update_site_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
update_site_option('spam_master_full_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
}
else {
update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
update_option('spam_master_full_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
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
<td><b>&nbsp;&nbspAdd to Protection List:</b></td>
<td></td>
<td><b>How to Use:</b></td>
</tr>
<tr>
<td>
<textarea name="blacklist_keys" cols="40" rows="25">
<?php echo strip_tags (get_option('blacklist_keys')); ?>
</textarea>
</td>
<td></td>
<td valign="top">
<div class="description"><b>1. Add to protection list</b></div>
<div class="description">Add any domain, word, email or Ip to the protection list. Press Save List.</div>
<div class="description">Repeat the operation as many times as you want always pressing Save List.</div>
<br>
<div class="description"><b>2. How to add</b></div>
<div class="description">Insert 1 entry per line without special characters.</div>
<div class="description">Example of adding 1 domain: example.com</div>
<div class="description">Example of adding 1 word: replica</div>
<div class="description">Example of adding 1 ip: 192.168.1.100</div>
<div class="description">Example of adding 1 email: replica_watches@love_to_spam.com</div>
<div class="description"><b>Important:</b>Make sure you leave no empty lines at the beginning, middle or end of the list.</div>
<br>
<div class="description"><b>3. Smaller list faster results</b></div>
<div class="description">Let's say you want to block 1 domain called (myspamdomain) with several tld's (com, net, org, etc.), just insert myspamdomain as a word in 1 line. Let's say you want to block an ip range 192.168.1.0 to 192.168.1.254, just insert 192.168.1. in 1 line.</div>
<div class="description">Conclusion, the smaller the word or number, the more it blocks. example: if you insert just the letter a in 1 line... it would block any email, domain or word that contains the letter a.</div>
<br>
<div class="description"><b>4. How to delete</b></div>
<div class="description">Delete 1 or more entries at a time. Press Save List.</div>
<div class="description">Repeat the operation as many times as you want always pressing Save List..</div>
<br>
<div class="description"><b>5. Backup your List</b></div>
<div class="description">If you are planing on adding several personal entries to this list, it's always a good idea to create a txt file with your own private list. Some updates can erase your list! With a backup txt file you can safely re-implement your personal list. You do not need to save the entries provided by the RBL Servers, those auto re-generate in case of deletion.</div>
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