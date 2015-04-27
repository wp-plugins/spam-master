<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_other_protection_table_header_experimental extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
?>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Experimental Tools', 'spam_master'); ?></h3></legend></th>
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
<p><b>Experimental development.</b></p>
<p>We are currently testing these tools and so far, we achieved incredible results combining our Full Protection list, re-captcha and honeypots. Literally <b>100% Spam block</b>.</p>
<p>Customers with an RBL License and Full Protection get experimental access to these tools. Free licenses can get access if requested.</p>
<p>Get in touch with us or test them at our website (register page) <a href="http://wordpress.techgasp.com/spam-master/" title="Spam Master" target="_blank">Click Here</a>.</p>
			</td>
		</tr>
	</tbody>
</table>
<?php
		}
}