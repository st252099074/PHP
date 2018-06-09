<?php # view_items.php

$page_title = 'View_Order_Items';


// Check for a valid ID, through GET.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_order_items.php
	$id = $_GET['id'];
} else { // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	exit();
}



include ('mysqli_connect.php');

// Number of records to show per page:
$display = 10;

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.
	$num_pages = $_GET['np'];
} else { // Need to determine.

 	// Count the number of records
	$query = "SELECT COUNT(*) FROM  order_items WHERE Order_ID =$id";
	$result = @mysqli_query ($dbc, $query);
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	$num_records = $row[0];
	
	// Calculate the number of pages.
	if ($num_records > $display) { // More than 1 page.
		$num_pages = ceil ($num_records/$display);
	} else {
		$num_pages = 1;
	}

} // End of np IF.


// Determine where in the database to start returning results.
if (isset($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Default column links.
$link1 = "{$_SERVER['PHP_SELF']}?id=$id&sort=f_a";
$link2 = "{$_SERVER['PHP_SELF']}?id=$id&sort=l_d";
$link3 = "{$_SERVER['PHP_SELF']}?id=$id&sort=r_a";
$link4 = "{$_SERVER['PHP_SELF']}?id=$id&sort=q_d";

// Determine the sorting order.
if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		case 'f_a':
			$order_by = 'po.Order_ID ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?id=$id&sort=f_d";
			break;
		case 'f_d':
			$order_by = 'po.Order_ID DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?id=$id&sort=f_a";
			break;
		case 'l_a':
			$order_by = 'it.Item_Name ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?id=$id&sort=l_d";
			break;
		case 'l_d':
			$order_by = 'it.Item_Name DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?id=$id&sort=l_a";
			break;
		case 'r_a':
			$order_by = 'it.Unit_Price ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?id=$id&sort=r_d";
			break;
		case 'r_d':
			$order_by = 'it.Unit_Price DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?id=$id&sort=r_a";
			
		case 'q_a':
			$order_by = 'oi.Quantity ASC';
			$link4 = "{$_SERVER['PHP_SELF']}?id=$id&sort=q_d";
			break;
		case 'q_d':
			$order_by = 'oi.Quantity DESC';
			$link4 = "{$_SERVER['PHP_SELF']}?id=$id&sort=q_a";	
			
			
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
} else { // Use the default sorting order.
	$order_by = 'Item_Name ASC';
	$sort = 'l_a';
}

// Make the query.
$query = "SELECT po.Order_ID, it.Item_Name, it.Unit_Price,  oi.Quantity FROM  prescription_orders po
JOIN  order_items oi
ON  oi.Order_ID = po.Order_ID
JOIN  items it
ON it.Item_ID = oi.Item_Id
where po.Order_ID = $id ORDER BY $order_by LIMIT $start, $display";		
$result = @mysqli_query ($dbc, $query); // Run the query.

$row = mysqli_fetch_array($result, MYSQL_ASSOC);

// Page header.
echo '<h1 id="mainhead">items in the selected Order: ' . $row['Order_ID'] . '</h1>';

// Table header.
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="left"><b>Edit</b></td>
	<!--<td align="left"><b>Delete</b></td> -->
	<td align="left"><b><a href="' . $link1 . '">Order_ID </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Item_Name</a></b></td>
	<td align="left"><b><a href="' . $link3 . '">Unit_Price</a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Quantity</a></b></td>
</tr>
';

// Make the query.
$query = "SELECT po.Order_ID, it.Item_Name, it.Unit_Price,  oi.Quantity, oi.ID FROM  prescription_orders po
JOIN  order_items oi
ON  oi.Order_ID = po.Order_ID
JOIN  items it
ON it.Item_ID = oi.Item_Id
where po.Order_ID = $id ORDER BY $order_by LIMIT $start, $display";			
$result = @mysqli_query ($dbc, $query); // Run the query.

// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_order_item.php?id=' . $row['ID'] . '">Edit</a></td>
		<!--<td align="left"><a href="delete_order_item.php?id=' . $row['ID'] . '">Delete</a></td>-->
		<td align="left">' . $row['Order_ID'] . '</td>
		<td align="left">' . $row['Item_Name'] . ' </td>
		<td align="left">' . $row['Unit_Price'] . '</td>
		<td align="left">' . $row['Quantity'] . '</td>
	</tr>
	';
}

echo '</table>';

mysqli_free_result ($result); // Free up the resources.	

mysqli_close($dbc); // Close the database connection.

// Make the links to other pages, if necessary.
if ($num_pages > 1) {
	
	echo '<br /><p>';
	// Determine what page the script is on.	
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a First button and a Previous button.
	if ($current_page != 1) {
		echo '<a href="view_order_items.php?s=0&np=' . $num_pages . '&id=' . $id . '&sort=' . $sort .'">First</a> ';
		echo '<a href="view_order_items.php?s=' . ($start - $display) . '&np=' . $num_pages . '&id=' . $id . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_order_items.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&id=' . $id . '&sort=' . $sort .'">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Last button and a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="view_order_items.php?s=' . ($start + $display) . '&np=' . $num_pages . '&id=' . $id . '&sort=' . $sort .'">Next</a> ';
		echo '<a href="view_order_items.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&id=' . $id . '&sort=' . $sort .'">Last</a>';

	}
	
	echo '</p>';
	
} // End of links section.

echo '<p><a href="add_order_item.php?id='.$id.'">Add a new item to this order.</a>
&nbsp;&nbsp;&nbsp;<a href="view_orders.php">Go back to orders</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to Main page</a></p>';
?>


