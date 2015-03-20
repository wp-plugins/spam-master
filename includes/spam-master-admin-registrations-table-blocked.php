<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class spam_master_registrations_table_blocked extends WP_List_Table {
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
//	function extra_tablenav( $which ) {
//		if ( $which == "top" ){
			//The code that goes before the table is here
//			echo '<h3>Latest Registrations</h3>';
//		}
//		if ( $which == "bottom" ){
			//The code that goes after the table is there
//			echo '<br><br><p><b>User Status:</b> 0= User Registered & Account Active. 1= User Registered, Account Active & Account Disabled by Administrator, Marked as Spam. 2= User Registered & Account Not Active</p><br>';
//		}
//	}

	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'col_blocks'	=>__('Date & Registration Email. This list contains up to a week of data. Older data is automatically purged to keep your <b>database "slim"</b> and your website with <b>fast page load times</b>.'),
		);
	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
//		function get_sortable_columns() {
//		return $sortable = array(
//			'col_blocks'	=>	array('option_value',false)
//		);
//	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		/* -- Preparing your query -- */
		if (is_multisite()){
			$blog_prefix = $wpdb->get_blog_prefix();
			$table_prefix = $wpdb->base_prefix;
			$query = "SELECT meta_value FROM {$table_prefix}sitemeta WHERE meta_key LIKE '_site_transient_spam_master_invalid_email%'";
		}
		else{
			$table_prefix = $wpdb->base_prefix;
			$query = "SELECT option_value FROM {$table_prefix}options WHERE option_name LIKE '_transient_spam_master_invalid_email%'";
		}

		/* -- Ordering parameters -- */
	    //Parameters that are going to be used to order the result
		if(is_multisite()){
		$orderby = !empty($_GET["orderby"]) ? esc_sql($_GET["orderby"]) : 'meta_value';
		}
		else{
		$orderby = !empty($_GET["orderby"]) ? esc_sql($_GET["orderby"]) : 'option_value';
		}
		$order = !empty($_GET["order"]) ? esc_sql($_GET["order"]) : 'DESC';
		if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

		/* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 10;
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? esc_sql($_GET["paged"]) : '';
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
		
		/* � Register the Columns � */
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
			if(is_multisite()){
			echo '<tr '.$row_class.' id="record_'.$rec->meta_value.'">';
			}
			else{
			echo '<tr '.$row_class.' id="record_'.$rec->option_value.'">';
			}
			foreach ( $columns as $column_name => $column_display_name ) {

				//Style attributes for each col
				$class = "class='$column_name column-$column_name'";
				$style = '';
				if ( in_array( $column_name, $hidden ) )
				$style = ' style="display:none;"';
				$attributes = '$class$style';

				//Display the cell
				if(is_multisite()){
				$value = "meta_value";
				}
				else{
				$value = "option_value";
				}
				switch ( $column_name ) {
					case "col_blocks":	echo '<td '.$attributes.'>'.stripslashes($rec->$value).'</td>';break;
				}
			}

			//Close the line
			echo'</tr>';
		}}
	}
}