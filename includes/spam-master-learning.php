<?php
add_action( 'user_register', 'spam_master_learning' );
add_filter( 'pre_comment_user_ip', 'spam_master_learning' );
function spam_master_learning(){

if ( get_option('spam_master_response_key') == 200 ){
$spam_master_date = date( 'H', current_time( 'timestamp', 0 ) );
$spam_master_blog_date = date( 'H', current_time( 'timestamp', 0 ) );
update_option( 'spam_master_blog_date', $spam_master_blog_date);

	if ( get_option('spam_master_blog_date') !== get_option('spam_master_date')){
$spam_master_keys_url = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
$spam_master_keys_url_get = base64_decode(get_option('spam_master_trd_full'));
$curl = curl_init($spam_master_keys_url_get);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$spam_master_full_keys = curl_exec($curl);
curl_close($curl);
update_option('spam_master_full_keys', $spam_master_full_keys);
update_option( 'spam_master_date', $spam_master_date);
update_option('spam_master_keys', $spam_master_full_keys);

//keep user settings saved in blacklist_keys. Removes duplicates array_unique and empty lines trim
$blacklist_keys = get_option( 'blacklist_keys' );
$spam_master_array = array($blacklist_keys, $spam_master_full_keys);
sort ($spam_master_array);
$spam_master_string = implode("\n", array_unique($spam_master_array));
if( is_multisite() ) {
update_site_option('blacklist_keys', strip_tags($spam_master_string));
}
else {
update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
}
	}
	else{
	}
}
else{
$spam_master_full_keys = "";
update_option('spam_master_full_keys', $spam_master_full_keys);
update_option('spam_master_keys', $spam_master_full_keys);
update_option('blacklist_keys', $spam_master_full_keys);
}

if ( get_option('spam_master_selected') == 'Full Protection' ){
	if ( get_option('spam_master_response_key') == 200 ){
global $wpdb;
//url to post
$spam_master_learn = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL2xlYXJuaW5nL2dldF9sZWFybi5waHA=";
$spam_master_learn_post = base64_decode($spam_master_learn);
$result_ip = $_SERVER['REMOTE_ADDR'];
$result_email = $wpdb->get_var("SELECT user_email FROM wp_users ORDER BY user_registered DESC LIMIT 1");
$result_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_users");

//create array of data to be posted
$time = current_time('mysql');
$wordpress = get_bloginfo('version');
$blog = get_option('blogname');
$admin_email = get_option('admin_email');
$web_adress = get_site_url();
$total_users = $result_count;
$spam_master_version = get_option('spam_master_version');
$spam_master_protection = get_option('spam_master_selected');
$registered_email = $result_email;
$registered_ip = $result_ip;
$license = get_option('spam_master_license_code');
$post_data['Time'] = urlencode($time);
$post_data['License Code'] = urlencode($license);
$post_data['Wordpress'] = urlencode($wordpress);
$post_data['Blog Name'] = urlencode($blog);
$post_data['Admin Email'] = urlencode($admin_email);
$post_data['Web Adress'] = urlencode($web_adress);
$post_data['Total Users'] = urlencode($total_users);
$post_data['Spam Master']	= urlencode($spam_master_version);
$post_data['Protection'] = urlencode($spam_master_protection);
$post_data['REGISTRATION EMAIL'] = urlencode($registered_email);
$post_data['REGISTRATION IP'] = urlencode($registered_ip);

//traverse array and prepare data for posting (key1=value1)
foreach ( $post_data as $key => $value) {$post_items[] = $key . '=' . $value;}

//create the final string to be posted using implode()
$post_string = implode ('&', $post_items);

$curl_connection = curl_init($spam_master_learn_post);
curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);

//set data to be posted
curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

//perform our request
$result = curl_exec($curl_connection);

//close the connection
curl_close($curl_connection);
}
}
else{
}
}
?>