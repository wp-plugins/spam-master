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
if($_POST){
if(is_multisite()){
if (isset($_POST['spam_master_honeypot_timetrap'])){
update_blog_option($blog_id, 'spam_master_honeypot_timetrap', $_POST['spam_master_honeypot_timetrap'] );
}
else{
update_blog_option($blog_id, 'spam_master_honeypot_timetrap', 'false' );
}
if (isset($_POST['spam_master_honeypot_timetrap_speed'])){
update_blog_option($blog_id, 'spam_master_honeypot_timetrap_speed', $_POST['spam_master_honeypot_timetrap_speed'] );
}
else{
update_blog_option($blog_id, 'spam_master_honeypot_timetrap_speed', '' );
}
if (isset($_POST['spam_master_recaptcha'])){
update_blog_option($blog_id, 'spam_master_recaptcha', $_POST['spam_master_recaptcha'] );
}
else{
update_blog_option($blog_id, 'spam_master_recaptcha', 'false' );
}
if (isset($_POST['spam_master_recaptcha_public_key'])){
update_blog_option($blog_id, 'spam_master_recaptcha_public_key', $_POST['spam_master_recaptcha_public_key'] );
}
else{
update_blog_option($blog_id, 'spam_master_recaptcha_public_key', '' );
}
if (isset($_POST['spam_master_recaptcha_theme'])){
update_blog_option($blog_id, 'spam_master_recaptcha_theme', $_POST['spam_master_recaptcha_theme'] );
}
else{
update_blog_option($blog_id, 'spam_master_recaptcha_theme', '' );
}
}
else{
if (isset($_POST['spam_master_honeypot_timetrap'])){
update_option('spam_master_honeypot_timetrap', $_POST['spam_master_honeypot_timetrap'] );
}
else{
update_option('spam_master_honeypot_timetrap', 'false' );
}
if (isset($_POST['spam_master_honeypot_timetrap_speed'])){
update_option('spam_master_honeypot_timetrap_speed', $_POST['spam_master_honeypot_timetrap_speed'] );
}
else{
update_option('spam_master_honeypot_timetrap_speed', '' );
}
if (isset($_POST['spam_master_recaptcha'])){
update_option('spam_master_recaptcha', $_POST['spam_master_recaptcha'] );
}
else{
update_option('spam_master_recaptcha', 'false' );
}
if (isset($_POST['spam_master_recaptcha_public_key'])){
update_option('spam_master_recaptcha_public_key', $_POST['spam_master_recaptcha_public_key'] );
}
else{
update_option('spam_master_recaptcha_public_key', '' );
}
if (isset($_POST['spam_master_recaptcha_theme'])){
update_option('spam_master_recaptcha_theme', $_POST['spam_master_recaptcha_theme'] );
}
else{
update_option('spam_master_recaptcha_theme', '' );
}
}
}
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
<form method="post" width='1'>
<fieldset class="options">
<p>
<input name="spam_master_recaptcha" id="spam_master_recaptcha" value="true" type="checkbox" <?php if(is_multisite()){echo get_blog_option($blog_id, 'spam_master_recaptcha') == 'true' ? 'checked="checked"':'';}else{echo get_option('spam_master_recaptcha') == 'true' ? 'checked="checked"':'';} ?> />
<label for="spam_master_recaptcha"><b><?php _e('Activate Re-Captcha', 'spam_master_recaptcha'); ?></b></label>
</p>
<p>
<input id="spam_master_recaptcha_public_key" name="spam_master_recaptcha_public_key" type="text" size="16" value="<?php if(is_multisite()){echo get_blog_option($blog_id, 'spam_master_recaptcha_public_key');}else{echo get_option('spam_master_recaptcha_public_key');} ?>">
<label for="spam_master_recaptcha_public_key"><?php _e('Re-Captcha API Site Key', 'spam_master_recaptcha'); ?></label>
</p>
<p>
<input id="spam_master_recaptcha_theme" name="spam_master_recaptcha_theme" type="text" size="16" value="<?php if(is_multisite()){echo get_blog_option($blog_id, 'spam_master_recaptcha_theme');}else{echo get_option('spam_master_recaptcha_theme');} ?>">
<label for="spam_master_recaptcha_theme"><?php _e(' Color Scheme', 'spam_master_recaptcha'); ?></label>
<div class="description">Color Scheme options are: <b>light</b> or <b>dark</b>.</div>
</p>
<?php
if(is_multisite()){
$spam_master_recaptcha = get_blog_option($blog_id, 'spam_master_recaptcha');
$spam_master_recaptcha_public_key = get_blog_option($blog_id, 'spam_master_recaptcha_public_key');
$spam_master_recaptcha_theme = get_blog_option($blog_id,'spam_master_recaptcha_theme');
}
else{
$spam_master_recaptcha = get_option('spam_master_recaptcha');
$spam_master_recaptcha_public_key = get_option('spam_master_recaptcha_public_key');
$spam_master_recaptcha_theme = get_option('spam_master_recaptcha_theme');
}
if ($spam_master_recaptcha == 'true' ){
	if ($spam_master_recaptcha_public_key !== '' ){
echo '<script src="https://www.google.com/recaptcha/api.js"></script>' .
'<div class="g-recaptcha" data-theme="'.$spam_master_recaptcha_theme.'" data-sitekey="'.$spam_master_recaptcha_public_key.'"></div>';
}
else{
echo '<div id="message" class="error"><p>Warning... Re-Captcha Acivated without Public Key</p></div>';
}
}
?>
<!--HONEYPOT ADMIN-->
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
<p>
<input name="spam_master_honeypot_timetrap" id="spam_master_honeypot_timetrap" value="true" type="checkbox" <?php if(is_multisite()){echo get_blog_option($blog_id, 'spam_master_honeypot_timetrap') == 'true' ? 'checked="checked"':'';}else{echo get_option('spam_master_honeypot_timetrap') == 'true' ? 'checked="checked"':'';} ?> />
<label for="spam_master_honeypot_timetrap"><b><?php _e('Activate Time Trap', 'spam_master_recaptcha'); ?></b></label>
</p>
<p>
<input id="spam_master_honeypot_timetrap_speed" name="spam_master_honeypot_timetrap_speed" type="text" size="16" value="<?php if(is_multisite()){echo get_blog_option($blog_id, 'spam_master_honeypot_timetrap_speed');}else{echo get_option('spam_master_honeypot_timetrap_speed');} ?>">
<label for="spam_master_honeypot_timetrap_speed"><?php _e('Honeypot Trap Speed', 'spam_master_recaptcha'); ?></label>
<div class="description">Time trap checks for how fast the "bots" are trying to submit the registration data. Default is <b>5</b> seconds.</div>
</p>
<p class="submit"><input class='button-primary' type='submit' name='update' value='<?php _e("Save & Preview", 'spam_master'); ?>' id='submitbutton' /></p>
</fieldset>
</form>
<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>
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

//IMPLEMENT RECAPTCHA FRONTEND
if(is_multisite()){
$spam_master_recaptcha = get_blog_option($blog_id, 'spam_master_recaptcha');
$spam_master_recaptcha_public_key = get_blog_option($blog_id, 'spam_master_recaptcha_public_key');
}
else{
$spam_master_recaptcha = get_option('spam_master_recaptcha');
$spam_master_recaptcha_public_key = get_option('spam_master_recaptcha_public_key');
}
if ($spam_master_recaptcha == 'true'){
	if ($spam_master_recaptcha_public_key !== ''){
		//MULTISITE HOOKS
		if(is_multisite()){
		add_action('signup_extra_fields', 'spam_master_recaptcha_register_field' );
		add_action('register_form', 'spam_master_recaptcha_register_field' );
		add_action('wpmu_validate_user_signup', 'spam_master_recaptcha_register_multi_errors', 99);
		}
		//SINGLE SITE HOOKS
		else{
		add_action('login_enqueue_scripts', 'spam_master_recaptcha_css');
		add_action('register_form', 'spam_master_recaptcha_register_field' );
		add_filter( 'registration_errors', 'spam_master_recaptcha_register_single_errors', 10, 3 );
		}
//CSS FOR SINGLE SITE
function spam_master_recaptcha_css(){
	echo '<style type="text/css">';
	echo '#login{width:350px !important;}';
	echo '</style>';
}
//END CSS
//INSERT FIELD
function spam_master_recaptcha_register_field(){
global $wpdb, $blog_id;
?>
<label>Re-CAPTCHA Code</label>
<?php
if(is_multisite()){
	$spam_master_public_key = get_blog_option($blog_id, 'spam_master_recaptcha_public_key');
	$spam_master_recaptcha_theme = get_blog_option($blog_id, 'spam_master_recaptcha_theme');
}
else{
	$spam_master_public_key = get_option('spam_master_recaptcha_public_key');
	$spam_master_recaptcha_theme = get_option('spam_master_recaptcha_theme');
}
echo '<script src="https://www.google.com/recaptcha/api.js"></script>' .
'<div class="g-recaptcha" data-theme="'.$spam_master_recaptcha_theme.'" data-sitekey="'.$spam_master_public_key.'"></div>';
if (is_multisite()){
?>
<p>Press <b>Next</b> after verifying captcha.</p>
<br>
<?php
}
else{
?>
<p>Press <b>Register</b> after verifying captcha.</p>
<br>
<?php
}
}
//END FIELD
//START ERRORS VALIDATION MULTI SITE
function spam_master_recaptcha_register_multi_errors($result){
if(isset($_POST['g-recaptcha-response'])){
$captcha=$_POST['g-recaptcha-response'];
	if(!$captcha){
			$result['errors']->add('invalid_email',__('<strong>SPAM MASTER</strong>: Insert Correct Captcha','spam_master'));
			echo '<p class="error"><strong>SPAM MASTER</strong>: Insert Correct Captcha</p>';
	}
}
return $result;
}
//END ERRORS MULTI VALIDATION
//START ERRORS VALIDATION SINGLE SITE
function spam_master_recaptcha_register_single_errors($errors){
if(isset($_POST['g-recaptcha-response'])){
$captcha=$_POST['g-recaptcha-response'];
	if(!$captcha){
		if(is_multisite()){
			$errors->add('error', __('<strong>SPAM MASTER</strong>: Insert Correct Captcha','spam_master') );
			echo '<p class="error"><strong>SPAM MASTER</strong>: Insert Correct Captcha</p>';
		}
		else{
			$errors->add('error', __('<strong>SPAM MASTER</strong>: Insert Correct Captcha','spam_master') );
		}
	}
}
return $errors;
}
//END ERRORS VALIDATION
	}
}
//////////////
//BUDDYPRESS//
//////////////
if($spam_master_buddypress == 1){
add_action( 'bp_before_registration_submit_buttons', 'spam_master_buddypress_protection' );
add_action( 'bp_signup_validate', 'spam_master_buddypress_protection_validate' );

function spam_master_buddypress_protection(){
global $bp, $errors;
	if(is_multisite()){
		$spam_master_public_key = get_blog_option($blog_id, 'spam_master_recaptcha_public_key');
		$spam_master_recaptcha_theme = get_blog_option($blog_id, 'spam_master_recaptcha_theme');
	}
	else{
		$spam_master_public_key = get_option('spam_master_recaptcha_public_key');
		$spam_master_recaptcha_theme = get_option('spam_master_recaptcha_theme');
	}
	$html = '<div class="register-section" id="profile-details-section">';
	$html .= '<div class="editfield">';
	$html .= '<script src="https://www.google.com/recaptcha/api.js"></script>';
	$html .= '<label>Re-CAPTCHA Code</label>';
	if(isset($_POST['g-recaptcha-response'])){
		$captcha=$_POST['g-recaptcha-response'];
			if(!$captcha){
				$html .= '<div class="error">';
				$html .= spam_master_buddypress_protection_validate($errors);
				$html .= '</div>';
			}
	}
	$html .= '<div class="g-recaptcha" data-theme="'.$spam_master_recaptcha_theme.'" data-sitekey="'.$spam_master_public_key.'"></div>';
	$html .= '<p class="small">Press <b>Complete</b> after verifying captcha</p>';
	$html .= '</div>';
	$html .= '</div>';
	echo $html;
}
function spam_master_buddypress_protection_validate($errors){
global $bp, $errors;
	if(isset($_POST['g-recaptcha-response'])){
		$captcha=$_POST['g-recaptcha-response'];
			if(!$captcha){
				$errors = '<strong>SPAM MASTER</strong>: Insert Correct Captcha';
			}
	}
return $errors;
}

}
else{
}