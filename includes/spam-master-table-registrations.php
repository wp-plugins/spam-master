<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_table_registrations extends WP_List_Table {
	/**
	 * Constructor, we override the parent to pass our own arguments
	 * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	 */
	 function __construct() {
		 parent::__construct( array(
		'singular'=> 'wp_list_text_link', //Singular label
		'plural' => 'wp_list_text_links', //plural label, also this well be one of the table css class
		'ajax'	=> false //We won't support Ajax for this table
		) );
	 }

	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ){
			//The code that goes before the table is here
//			echo '<h3>Latest Registrations</h3>';
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
//			echo '<br><br><p><b>User Status:</b> 0= User Registered & Account Active. 1= User Registered, Account Active & Account Disabled by Administrator, Marked as Spam. 2= User Registered & Account Not Active</p><br>';
		}
	}

	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'col_user_registered'	=>__('Registration Date'),
			'col_user_id'			=>__('User ID'),
			'col_user_status'		=>__('User Status'),
			'col_user_name'			=>__('Name'),
			'col_user_email'		=>__('User Email')
		);
	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
		function get_sortable_columns() {
		return $sortable = array(
			'col_user_registered'	=>	array('user_registered',false),
			'col_user_id'			=>	array('ID',false),
			'col_user_status'		=>	array('user_status',false)
		);
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		/* -- Preparing your query -- */
	    $query = "SELECT * FROM $wpdb->users";

		/* -- Ordering parameters -- */
	    //Parameters that are going to be used to order the result
	    $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'user_registered';
	    $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
	    if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

		/* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 10;
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        //Page Number
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account
		if(!empty($paged) && !empty($perpage)){
			$offset=($paged-1)*$perpage;
	    	$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
		}

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page" => $perpage,
		) );
		//The pagination links are automatically built according to those parameters
		
		/* — Register the Columns — */
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		/* -- Fetch the items -- */
		$this->items = $wpdb->get_results($query);
	}

	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display_rows() {
		global $wpdb;
		//Get the records registered in the prepare_items method
		$records = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns, $hidden ) = $this->get_column_info();

		//Loop for each record
		if(!empty($records)){foreach($records as $rec){

			//Open the line
			static $row_class = '';
			$row_class = ( $row_class == '' ? ' class="alternate"' : '' );
			echo '<tr '.$row_class.' id="record_'.$rec->ID.'">';
			foreach ( $columns as $column_name => $column_display_name ) {

				//Style attributes for each col
				$class = "class='$column_name column-$column_name'";
				$style = '';
				if ( in_array( $column_name, $hidden ) )
				$style = ' style="display:none;"';
				$attributes = '$class$style';

				//edit link
//				$editlink_mark  = $wpdb->query('UPDATE wp_users SET user_status = 1 WHERE ID = '.(int)$rec->ID);
//				$editlink_unmark  = $wpdb->query('UPDATE wp_users SET user_status = 0 WHERE ID = '.(int)$rec->ID);
//				$editlink_unmark  = '/wp-admin/user-edit.php?user_id='.(int)$rec->ID;

				//Display the cell
				switch ( $column_name ) {
					case "col_user_registered":	echo '<td '.$attributes.'>'.stripslashes($rec->user_registered).'</td>';break;
					case "col_user_status": echo '<td '.$attributes.'>'.stripslashes($rec->user_status).'</td>'; break;
					case "col_user_id": echo '<td '.$attributes.'>'.stripslashes($rec->ID).'</td>'; break;
					case "col_user_name": echo '<td '.$attributes.'>'.$rec->display_name.'</td>'; break;
					case "col_user_email": echo '<td '.$attributes.'>'.$rec->user_email.'</td>'; break;
				}
			}

			//Close the line
			echo'</tr>';
		}}
	}
}