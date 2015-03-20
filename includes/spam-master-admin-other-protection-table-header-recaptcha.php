<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_other_protection_table_header_recaptcha extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
?>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Re-Captcha', 'spam_master'); ?></h3></legend></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th class="manage-column column-columnname" scope="col"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr>
			<td class="column-columnname">
<p>This is an optional setting.  Activating Re-Captcha adds a captcha code field to the registration page of your <b>Wordpress</b> or <b>Buddypress</b>.</p>
<p>Activating re-captcha will automatically eliminate all "bots" or "robots" fake registrations. <b>Make sure you have no other plugins installed that use captcha's or re-captcha</b></p>
<p>Re-Captcha is freely provided by google and requires a google api key that you can get in seconds. Get your free google <a href="https://www.google.com/recaptcha/intro/index.html" title="re-captcha" target="_blank">re-captcha key</a>.</p>
			</td>
		</tr>
	</tbody>
</table>
<?php
		}
}