<?php # view_items.php

$page_title = 'View_Items';

// Page header.
echo '<h1 id="mainhead">Items currently in the Database:</h1>';

include ('mysqli_connect.php');

// Number of records to show per page:
$display = 15;

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.
	$num_pages = $_GET['np'];
} else { // Need to determine.

 	// Count the number of records
	$query = "SELECT COUNT(*) FROM items ORDER BY Item_Name ASC";
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
$link1 = "{$_SERVER['PHP_SELF']}?sort=f_a";
$link2 = "{$_SERVER['PHP_SELF']}?sort=l_d";
$link3 = "{$_SERVER['PHP_SELF']}?sort=d_a";
$link4 = "{$_SERVER['PHP_SELF']}?sort=p_a";

// Determine the sorting order.
if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		case 'f_a':
			$order_by = 'Item_Name ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=f_d";
			break;
		case 'f_d':
			$order_by = 'Item_Name DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=f_a";
			break;
		case 'l_a':
			$order_by = 'Item_Dec ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=l_d";
			break;
		case 'l_d':
			$order_by = 'Item_Dec DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=l_a";
			break;
		case 'd_a':
			$order_by = 'Unit_Price ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=d_d";
			break;
		case 'd_d':
			$order_by = 'Unit_Price DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=d_a";
			break;
		case 'p_a':
			$order_by = 'Issuer_Name ASC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=p_d";
			break;
		case 'p_d':
			$order_by = 'Issuer_Name DESC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=p_a";
			break;
		default:
			$order_by = 'Item_Name ASC';
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
} else { // Use the default sorting order.
	$order_by = 'Item_Name ASC';
	$sort = 'l_a';
}

// Make the query.
$query = "SELECT Item_ID, Item_Name, Item_Dec, Unit_Price, Issuer_Name FROM items it JOIN prescription_issuers pi
ON it.Issuer_ID = pi.Issuer_ID ORDER BY $order_by LIMIT $start, $display";		
$result = @mysqli_query ($dbc, $query); // Run the query.

// Table header.
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b><a href="' . $link1 . '">Item_Name </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Item_Dec</a></b></td>
	<td align="left"><b><a href="' . $link3 . '">Unit_Price</a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Issuer_Name</a></b></td>
</tr>';


// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_item.php?id=' . $row['Item_ID'] . '">Edit</a></td>
		<td align="left"><a href="delete_item.php?id=' . $row['Item_ID'] . '">Delete</a></td>
		<td align="left">' . $row['Item_Name'] . '</td>
		<td align="left">' . $row['Item_Dec'] . '</td>
		<td align="left">' . $row['Unit_Price'] . '</td>
		<td align="left">' . $row['Issuer_Name'] . '</td>
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
		echo '<a href="view_items.php?s=0&np=' . $num_pages . '&sort=' . $sort .'">First</a> ';
		echo '<a href="view_items.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_items.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Last button and a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="view_items.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Next</a> ';
		echo '<a href="view_items.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Last</a>';

	}
	
	echo '</p>';
	echo '<a href="index.php">Go back to the Main Menu</a>';
} // End of links section.

?>


