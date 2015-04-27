<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_statistics_table extends WP_List_Table {
	function display(){
	global $wpdb, $blog_id;
?>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col" width="250"><legend><h3><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Statistics', 'spam_master'); ?></h3></legend></th>
			<th id="columnname" class="manage-column column-columnname" scope="col"></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th class="manage-column column-columnname" scope="col" width="250"></th>
			<th class="manage-column column-columnname" scope="col"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
<b>Total Users</b>
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3">
<font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option($blog_id, 'spam_master_user_registrations');
}
else{
echo get_option('spam_master_user_registrations');
}
?></b> Registered</font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
Total Users Blocked
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3"><font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option(1, 'spam_master_block_count');
}
else{
echo get_option('spam_master_block_count');
}
?></b> Spam Users Blocked</font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
<b>Total Comments</b>
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3">
<font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option($blog_id, 'spam_master_comments_total');
}
else{
echo get_option('spam_master_comments_total');
}
?></b> Comments</font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
Total Comments Blocked
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3"><font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option($blog_id, 'spam_master_comments_total_blocked');
}
else{
echo get_option('spam_master_comments_total_blocked');
}
?></b> Spam Comments Blocked</font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
Total Comments Approved
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3">
<font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option($blog_id, 'spam_master_comments_total_approved');
}
else{
echo get_option('spam_master_comments_total_approved');
}
?></b> Comments</font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
Total Comments Pending
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3">
<font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option($blog_id, 'spam_master_comments_total_pending');
}
else{
echo get_option('spam_master_comments_total_pending');
}
?></b> Comments</font>
			</td>
		</tr>
		<tr>
			<td class="column-columnname" style="vertical-align:middle" width="250"></td>
			<td class="column-columnname" style="vertical-align:middle"></td>
		</tr>
		<tr class="alternate">
			<td class="column-columnname" style="vertical-align:middle" width="250">
Total Comments Trashed
			</td>
			<td class="column-columnname" style="vertical-align:middle" bgcolor="#078BB3">
<font color="white"><b><?php
if( is_multisite() ) {
echo get_blog_option($blog_id, 'spam_master_comments_total_trashed');
}
else{
echo get_option('spam_master_comments_total_trashed');
}
?></b> Comments</font>
			</td>
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