<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_other_protection_table_header_honeypot extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
?>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Honeypot', 'spam_master'); ?></h3></legend></th>
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
<p>This is an optional setting. Activating Honeypot adds invisible traps for "bots" or "robots in <b>Wordpress</b> or <b>Buddypress</b> registration page.</p>
<p>"Normal persons" or "humans" will not see these traps, more about <a href="http://en.wikipedia.org/wiki/Honeypot_(computing)" title="honeypot" target="_blank">honeypot</a>.</p>
			</td>
		</tr>
	</tbody>
</table>
<?php
		}
}