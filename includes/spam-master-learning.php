<?php
add_action( 'user_register', 'spam_master_learning' );
add_filter( 'pre_comment_user_ip', 'spam_master_learning' );
function spam_master_learning(){
global $wpdb;
//IF MULTI-SITE
if( is_multisite() ) {
//if 200 and set date
if ( get_site_option('spam_master_response_key') == 200 ){
//if full protection is selected
	if( get_site_option('spam_master_protection') == get_site_option('spam_master_trd_full') ){
$spam_master_date = date( 'H', current_time( 'timestamp', 0 ) );
$spam_master_blog_date = date( 'H', current_time( 'timestamp', 0 ) );
update_site_option( 'spam_master_blog_date', $spam_master_blog_date);

//if date ok
		if ( get_site_option('spam_master_blog_date') !== get_site_option('spam_master_date')){
$spam_master_keys_url = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
$spam_master_keys_url_get = base64_decode(get_site_option('spam_master_trd_full'));
$curl = curl_init($spam_master_keys_url_get);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$spam_master_full_keys = curl_exec($curl);
curl_close($curl);
update_site_option('spam_master_full_keys', $spam_master_full_keys);
update_site_option('spam_master_rbl_keys', $spam_master_full_keys);
update_site_option( 'spam_master_date', $spam_master_date);

//set and get my keys if any
$spam_master_my_keys = get_site_option( 'spam_master_my_keys' );

//set and get new full rbl
$spam_master_rbl_keys = get_site_option( 'spam_master_rbl_keys' );

//Join my keys with full rbl keys in new blacklist. Removes duplicates array_unique and empty lines trim
$spam_master_array = array_merge(explode("\n", $spam_master_my_keys), explode("\n", $spam_master_rbl_keys));
$spam_master_array = array_map("trim", $spam_master_array);
sort ($spam_master_array);
$spam_master_string = implode("\n", array_unique($spam_master_array));
update_site_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
//clean keys
		}
	//if date NOT ok
		else{
		}
	}
	else{
	}
}
//if NOT 200
else{
//re-set full and rbl to free
$spam_master_full_keys = update_site_option('spam_master_full_keys', "hotmail\r\nmsn\r\nlive\r\noutlook");
$spam_master_rbl_keys = update_site_option('spam_master_rbl_keys', "hotmail\r\nmsn\r\nlive\r\noutlook");
//set and get my keys if any and new free rbl
$spam_master_my_keys = get_site_option( 'spam_master_my_keys' );
$spam_master_rbl_keys = get_site_option( 'spam_master_rbl_keys' );

//Join my keys with free rbl keys in new blacklist. Removes duplicates array_unique and empty lines trim
$spam_master_array = array_merge(explode("\n", $spam_master_my_keys), explode("\n", $spam_master_rbl_keys));
$spam_master_array = array_map("trim", $spam_master_array);
sort ($spam_master_array);
$spam_master_string = implode("\n", array_unique($spam_master_array));
update_site_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
}
}
//IF SINGLE SITE
else{
//if 200 and set date
if ( get_option('spam_master_response_key') == 200 ){
//if full protection is selected
	if( get_option('spam_master_protection') == get_option('spam_master_trd_full') ){
$spam_master_date = date( 'H', current_time( 'timestamp', 0 ) );
$spam_master_blog_date = date( 'H', current_time( 'timestamp', 0 ) );
update_option( 'spam_master_blog_date', $spam_master_blog_date);

//if date ok
		if ( get_option('spam_master_blog_date') !== get_option('spam_master_date')){
$spam_master_keys_url = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL3NwYW1tYXN0ZXIvc3BhbW1hc3Rlcl9mdWxsLnR4dA==";
$spam_master_keys_url_get = base64_decode(get_option('spam_master_trd_full'));
$curl = curl_init($spam_master_keys_url_get);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$spam_master_full_keys = curl_exec($curl);
curl_close($curl);
update_option('spam_master_full_keys', $spam_master_full_keys);
update_option('spam_master_rbl_keys', $spam_master_full_keys);
update_option( 'spam_master_date', $spam_master_date);

//set and get my keys if any
$spam_master_my_keys = get_option( 'spam_master_my_keys' );

//set and get new full rbl
$spam_master_rbl_keys = get_option( 'spam_master_rbl_keys' );

//Join my keys with full rbl keys in new blacklist. Removes duplicates array_unique and empty lines trim
$spam_master_array = array_merge(explode("\n", $spam_master_my_keys), explode("\n", $spam_master_rbl_keys));
$spam_master_array = array_map("trim", $spam_master_array);
sort ($spam_master_array);
$spam_master_string = implode("\n", array_unique($spam_master_array));
update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
//clean keys
		}
//if date NOT ok
		else{
		}
//if not full protection
	}
	else{
	}
}
//if NOT 200
else{
//re-set full and rbl to free
$spam_master_full_keys = update_option('spam_master_full_keys', "hotmail\r\nmsn\r\nlive\r\noutlook");
$spam_master_rbl_keys = update_option('spam_master_rbl_keys', "hotmail\r\nmsn\r\nlive\r\noutlook");
//set and get my keys if any and new free rbl
$spam_master_my_keys = get_option( 'spam_master_my_keys' );
$spam_master_rbl_keys = get_option( 'spam_master_rbl_keys' );

//Join my keys with free rbl keys in new blacklist. Removes duplicates array_unique and empty lines trim
$spam_master_array = array_merge(explode("\n", $spam_master_my_keys), explode("\n", $spam_master_rbl_keys));
$spam_master_array = array_map("trim", $spam_master_array);
sort ($spam_master_array);
$spam_master_string = implode("\n", array_unique($spam_master_array));
update_option('blacklist_keys', strip_tags(preg_replace('/\n+/', "\n", trim($spam_master_string))));
}
}
/////////////////
//LEARNING DATA//
/////////////////
//IF MULTI-SITE
if( is_multisite() ) {
if ( get_site_option('spam_master_selected') == 'FULL PROTECTION' ){
	if ( get_site_option('spam_master_response_key') == 200 ){
global $wpdb;
//url to post
$spam_master_learn = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL2xlYXJuaW5nL2dldF9sZWFybi5waHA=";
$spam_master_learn_post = base64_decode($spam_master_learn);
$result_ip = $_SERVER['REMOTE_ADDR'];
$result_email = $wpdb->get_var("SELECT user_email FROM wp_users ORDER BY user_registered DESC LIMIT 1");
$result_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_users");
$result_comment_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_comments");
$result_comment_email = $wpdb->get_var("SELECT comment_author_email FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
$result_comment_website = $wpdb->get_var("SELECT comment_author_url FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
$result_comment_content = $wpdb->get_var("SELECT comment_content FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
$result_comment_status = $wpdb->get_var("SELECT comment_approved FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
//create array of data to be posted
$time = current_time('mysql');
$wordpress = get_bloginfo('version');
$blog = get_site_option('blogname');
$admin_email = get_site_option('admin_email');
$web_adress = get_site_url();
$total_users = $result_count;
$spam_master_version = get_site_option('spam_master_installed_version');
$spam_master_protection = get_site_option('spam_master_selected');
$registered_email = $result_email;
$registered_ip = $result_ip;
$comment_total = $result_comment_count;
$comment_email = $result_comment_email;
$comment_website = $result_comment_website;
$comment_content = $result_comment_content;
$comment_status = $result_comment_status;
$license = get_site_option('spam_master_license_code');
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
$post_data['TOTAL COMMENTS'] = urlencode($comment_total);
$post_data['COMMENT EMAIL'] = urlencode($comment_email);
$post_data['COMMENT WEBSITE'] = urlencode($comment_website);
$post_data['COMMENT CONTENT'] = urlencode($comment_content);
$post_data['COMMENT STATUS'] = urlencode($comment_status);

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
//IF SINGLE-SITE
else{
if ( get_option('spam_master_selected') == 'FULL PROTECTION' ){
	if ( get_option('spam_master_response_key') == 200 ){
global $wpdb;
//url to post
$spam_master_learn = "aHR0cDovL3NwYW1tYXN0ZXIudGVjaGdhc3AuY29tL2xlYXJuaW5nL2dldF9sZWFybi5waHA=";
$spam_master_learn_post = base64_decode($spam_master_learn);
$result_ip = $_SERVER['REMOTE_ADDR'];
$result_email = $wpdb->get_var("SELECT user_email FROM wp_users ORDER BY user_registered DESC LIMIT 1");
$result_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_users");
$result_comment_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_comments");
$result_comment_email = $wpdb->get_var("SELECT comment_author_email FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
$result_comment_website = $wpdb->get_var("SELECT comment_author_url FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
$result_comment_content = $wpdb->get_var("SELECT comment_content FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
$result_comment_status = $wpdb->get_var("SELECT comment_approved FROM wp_comments ORDER BY comment_ID DESC LIMIT 1");
//create array of data to be posted
$time = current_time('mysql');
$wordpress = get_bloginfo('version');
$blog = get_option('blogname');
$admin_email = get_option('admin_email');
$web_adress = get_site_url();
$total_users = $result_count;
$spam_master_version = get_option('spam_master_installed_version');
$spam_master_protection = get_option('spam_master_selected');
$registered_email = $result_email;
$registered_ip = $result_ip;
$license = get_option('spam_master_license_code');
$comment_total = $result_comment_count;
$comment_email = $result_comment_email;
$comment_website = $result_comment_website;
$comment_content = $result_comment_content;
$comment_status = $result_comment_status;
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
$post_data['TOTAL COMMENTS'] = urlencode($comment_total);
$post_data['COMMENT EMAIL'] = urlencode($comment_email);
$post_data['COMMENT WEBSITE'] = urlencode($comment_website);
$post_data['COMMENT CONTENT'] = urlencode($comment_content);
$post_data['COMMENT STATUS'] = urlencode($comment_status);

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

//end learning function
}
?>