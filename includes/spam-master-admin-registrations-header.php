<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_registrations_header extends WP_List_Table {
	function display() {
?>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Registrations', 'spam_master'); ?></h3></legend></th>
		</tr>
	</thead>

	<tfoot>
		<tr>

		</tr>
	</tfoot>

	<tbody>

	</tbody>
</table>
<?php
		}
}