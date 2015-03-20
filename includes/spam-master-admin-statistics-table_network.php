<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_statistics_table_network extends WP_List_Table {
	function display(){
?>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Statistics', 'spam_master'); ?></h3></legend></th>
			<th id="columnname" class="manage-column column-columnname" scope="col"></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th class="manage-column column-columnname" scope="col"></th>
			<th class="manage-column column-columnname" scope="col"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle">
<b>Statistics are available per sub-site.</b>
			</td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
<b>Protected Against</b>
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#<?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_protection_number_color');
}
else{
echo get_option('spam_master_protection_number_color');
}
?>"><font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_protection_total');
}
else{
echo get_option('spam_master_protection_total');
}
?> Threats</b></font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
<b>Spam Learning</b>
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#<?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_learning_color');
}
else{
echo get_option('spam_master_learning_color');
}
?>"><font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_learning_status');
}
else{
echo get_option('spam_master_learning_status');
}
?></b></font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
<b>Primary RBL Server Cluster</b>
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#<?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_full_rbl_color');
}
else{
echo get_option('spam_master_full_rbl_color');
}
?>"><font color="white">Cluster Status: <b><?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_full_rbl_status');
}
else{
echo get_option('spam_master_full_rbl_status');
}
?></b></font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
<b>Secondary RBL Server Cluster</b>
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#<?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_full_rbl_color');
}
else{
echo get_option('spam_master_full_rbl_color');
}
?>"><font color="white">Cluster Status: <b><?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_full_rbl_status');
}
else{
echo get_option('spam_master_full_rbl_status');
}
?></b></font>
			</td>
		</tr>
	</tbody>
</table>
<?php
		}
}