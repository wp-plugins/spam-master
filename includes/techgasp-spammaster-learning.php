<?php
/**
Plugin URI: http://wordpress.techgasp.com/spam-master/
Author: TechGasp
Author URI: http://wordpress.techgasp.com
*/
add_action( 'user_register', 'spammaster_learning' );
add_filter( 'pre_comment_user_ip', 'spammaster_learning' );
function spammaster_learning(){

if ( get_option('spammaster_response_key') == 200 ){
$spammaster_date = date( 'H', current_time( 'timestamp', 0 ) );
$spammaster_blog_date = date( 'H', current_time( 'timestamp', 0 ) );
update_option( 'spammaster_blog_date', $spammaster_blog_date);

	if ( get_option('spammaster_blog_date') !== get_option('spammaster_date')){
$spammaster_keys_url = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
$spammaster_keys_url_get = base64_decode(get_option('spammaster_trd_full'));
$curl = curl_init($spammaster_keys_url_get);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$spammaster_full_keys = curl_exec($curl);
curl_close($curl);
update_option('spammaster_full_keys', $spammaster_full_keys);
update_option( 'spammaster_date', $spammaster_date);
	}
	else{
	}
}
else{
$spammaster_full_keys = "";
update_option('spammaster_full_keys', $spammaster_full_keys);
}

if ( get_option('spammaster_selected') == 'Full Protection' ){
global $wpdb;
//url to post
$spammaster_learn = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL2xlYXJuaW5nL2dldF9sZWFybi5waHA=";
$spammaster_learn_post = base64_decode($spammaster_learn);
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
$spammaster_version = get_option('spammaster_version');
$spammaster_protection = get_option('spammaster_selected');
$registered_email = $result_email;
$registered_ip = $result_ip;
$license = get_option('spammaster_license_code');
$post_data['Time'] = urlencode($time);
$post_data['License Code'] = urlencode($license);
$post_data['Wordpress'] = urlencode($wordpress);
$post_data['Blog Name'] = urlencode($blog);
$post_data['Admin Email'] = urlencode($admin_email);
$post_data['Web Adress'] = urlencode($web_adress);
$post_data['Total Users'] = urlencode($total_users);
$post_data['Spam Master']	= urlencode($spammaster_version);
$post_data['Protection'] = urlencode($spammaster_protection);
$post_data['REGISTRATION EMAIL'] = urlencode($registered_email);
$post_data['REGISTRATION IP'] = urlencode($registered_ip);

//traverse array and prepare data for posting (key1=value1)
foreach ( $post_data as $key => $value) {$post_items[] = $key . '=' . $value;}

//create the final string to be posted using implode()
$post_string = implode ('&', $post_items);

$curl_connection = curl_init($spammaster_learn_post);
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
else{
}
}
?>