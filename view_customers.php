<?php # view_customers.php

$page_title = 'View_Customers';

// Page header.
echo '<h1 id="mainhead">Customers in the Database:</h1>';

include ('mysqli_connect.php');

// Number of records to show per page:
$display = 20;

// Determine how many pages there are. 
if (isset($_GET['np'])) { // Already been determined.
	$num_pages = $_GET['np'];
} else { // Need to determine.

 	// Count the number of records
	$query = "SELECT COUNT(*) FROM customers ORDER BY Last_N ASC";
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

$link5 = "{$_SERVER['PHP_SELF']}?sort=g_a";
$link6 = "{$_SERVER['PHP_SELF']}?sort=s_a";
$link7 = "{$_SERVER['PHP_SELF']}?sort=c_a";
$link8 = "{$_SERVER['PHP_SELF']}?sort=z_a";
$link9 = "{$_SERVER['PHP_SELF']}?sort=st_a";
$link10 = "{$_SERVER['PHP_SELF']}?sort=pp_a";



// Determine the sorting order.
if (isset($_GET['sort'])) {

	// Use existing sorting order.
	switch ($_GET['sort']) {
		case 'f_a':
			$order_by = 'First_N ASC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=f_d";
			break;
		case 'f_d':
			$order_by = 'First_N DESC';
			$link1 = "{$_SERVER['PHP_SELF']}?sort=f_a";
			break;
		case 'l_a':
			$order_by = 'Last_N ASC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=l_d";
			break;
		case 'l_d':
			$order_by = 'Last_N DESC';
			$link2 = "{$_SERVER['PHP_SELF']}?sort=l_a";
			break;
		case 'd_a':
			$order_by = 'Date_of_Birth ASC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=d_d";
			break;
		case 'd_d':
			$order_by = 'Date_of_Birth DESC';
			$link3 = "{$_SERVER['PHP_SELF']}?sort=d_a";
			break;
		case 'p_a':
			$order_by = 'Email ASC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=p_d";
			break;
		case 'p_d':
			$order_by = 'Email DESC';
			$link4 = "{$_SERVER['PHP_SELF']}?sort=p_a";
			break;
		
		
		
		
		case 'g_a':
			$order_by = 'Gender ASC';
			$link5 = "{$_SERVER['PHP_SELF']}?sort=g_d";
			break;
		case 'g_d':
			$order_by = 'Gender DESC';
			$link5 = "{$_SERVER['PHP_SELF']}?sort=g_a";
			break;
		
		case 's_a':
			$order_by = 'Street_Address ASC';
			$link6 = "{$_SERVER['PHP_SELF']}?sort=s_d";
			break;
		case 's_d':
			$order_by = 'Street_Address DESC';
			$link6 = "{$_SERVER['PHP_SELF']}?sort=s_a";
			break;
			
		
			case 'c_a':
			$order_by = 'City ASC';
			$link7 = "{$_SERVER['PHP_SELF']}?sort=c_d";
			break;
		case 'c_d':
			$order_by = 'City DESC';
			$link7 = "{$_SERVER['PHP_SELF']}?sort=c_a";
			break;
		
		
		
		case 'z_a':
			$order_by = 'Zip_Code ASC';
			$link78 = "{$_SERVER['PHP_SELF']}?sort=z_d";
			break;
		case 'z_d':
			$order_by = 'Zip_Code DESC';
			$link8 = "{$_SERVER['PHP_SELF']}?sort=z_a";
			break;
		
		
		
		
		case 'st_a':
			$order_by = 'State_Name ASC';
			$link9 = "{$_SERVER['PHP_SELF']}?sort=st_d";
			break;
		case 'st_d':
			$order_by = 'State_Name DESC';
			$link9 = "{$_SERVER['PHP_SELF']}?sort=st_a";
			break;
		
			case 'pp_a':
			$order_by = 'Primary_Phone ASC';
			$link10 = "{$_SERVER['PHP_SELF']}?sort=pp_d";
			break;
		case 'pp_d':
			$order_by = 'Primary_Phone DESC';
			$link10 = "{$_SERVER['PHP_SELF']}?sort=pp_a";
			break;
		
		
		
		
		
		
		default:
			$order_by = 'Last_N ASC';
			break;
	}
	
	// $sort will be appended to the pagination links.
	$sort = $_GET['sort'];
	
} else { // Use the default sorting order.
	$order_by = 'Last_N ASC';
	$sort = 'l_a';
}

// Make the query.
$query = "SELECT Customer_ID, First_N, Last_N, Date_of_Birth, Email, Gender, 
Street_Address, City , Zip_Code, State_Name, Primary_Phone  
FROM customers JOIN state on state.State_ID = customers.State_ID  ORDER BY $order_by LIMIT $start, $display";		
$result = @mysqli_query ($dbc, $query); // Run the query.

// Table header.
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b><a href="' . $link1 . '">First Name </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Last Name</a></b></td>
	<td align="left"><b><a href="' . $link3 . '">Date of Birth</a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Email</a></b></td>
	
	
	<td align="left"><b><a href="' . $link5 . '">Gender</a></b></td>
	<td align="left"><b><a href="' . $link6 . '">Street_Address</a></b></td>
	<td align="left"><b><a href="' . $link7 . '">City</a></b></td>
	<td align="left"><b><a href="' . $link8 . '">Zip_Code</a></b></td>
	<td align="left"><b><a href="' . $link9 . '">State_Name</a></b></td>
		<td align="left"><b><a href="' . $link10 . '">Primary_Phone</a></b></td>
	
	
	
	
	
	
	
</tr>';


// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_customer.php?id=' . $row['Customer_ID'] . '">Edit</a></td>
		<td align="left"><a href="delete_customer.php?id=' . $row['Customer_ID'] . '">Delete</a></td>
		<td align="left">' . $row['First_N'] . '</td>
		<td align="left">' . $row['Last_N'] . '</td>
		<td align="left">' . $row['Date_of_Birth'] . '</td>
		<td align="left">' . $row['Email'] . '</td>
		
			<td align="left">' . $row['Gender'] . '</td>
				<td align="left">' . $row['Street_Address'] . '</td>
					<td align="left">' . $row['City'] . '</td>
						<td align="left">' . $row['Zip_Code'] . '</td>
							<td align="left">' . $row['State_Name'] . '</td>
								<td align="left">' . $row['Primary_Phone'] . '</td>
							
		
		
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
		echo '<a href="view_customers.php?s=0&np=' . $num_pages . '&sort=' . $sort .'">First</a> ';
		echo '<a href="view_customers.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
	}
	
	// Make all the numbered pages.
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_customers.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	// If it's not the last page, make a Last button and a Next button.
	if ($current_page != $num_pages) {
		echo '<a href="view_customers.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Next</a> ';
		echo '<a href="view_customers.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Last</a>';

	}
	
	echo '</p>';
	echo '<a href="index.php">Go back to the Main Menu</a>';
} // End of links section.

?>


