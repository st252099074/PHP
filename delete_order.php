<?php 

$page_title = 'Delete a Order';


if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { 
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
	$id = $_POST['id'];
} else { 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	include ('./includes/footer.html'); 
	exit();
}

include ('mysqli_connect.php'); // Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	if ($_POST['sure'] == 'Yes') { // Delete them.

		// Make the query.
	   $query = "SELECT Order_ID, Order_Date  FROM prescription_orders WHERE Order_ID=$id";		
	   $result = @mysqli_query ($dbc, $query); // Run the query.
	
	   if (mysqli_num_rows($result) == 1) { 

	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);

		$order_date_del=$row[1];
		$order_id_del=$row[0];
		
		$query = "DELETE FROM prescription_orders WHERE Order_ID=$id";		
		$result_del = @mysqli_query ($dbc, $query); // Run the query.
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.


		
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Create the result page.
		echo '<h1 id="mainhead">Delete a Order</h1>
		<p>The Order <b>'.$order_id_del.'</b> from date <b>'.$order_date_del.'</b> has been deleted.</p><p><br /><br /></p>
		<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_orders.php">Go back to Orders</a></p>';	
	} else { // Did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The order could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
	}


	}
		

		
	 else { 
					echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
		} //End of else.
	
	} // End of $_POST['sure'] == 'Yes' if().
	else { 
		echo '<h1 id="mainhead">Delete an Order</h1>';

	$query = "SELECT Order_ID, Order_Date FROM prescription_orders WHERE Order_ID=$id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) { 

		
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
	
  echo'
		<p>The Order_ID <b>'.$row[0].'</b> from date <b>'.$row[1].'</b> has NOT been deleted.</p><p><br /><br />	</p>
		<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_orders.php">Go back to Orders</a></p>';
		
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}


} // End of wasn抰 sure else().

} // End of main submit conditional.

else { // Show the form.

	
	$query = "SELECT Order_ID, Order_Date, customers.Customer_ID, First_N, Last_N, prescription_orders.Store_ID, Store_Name 
	FROM prescription_orders, customers, store WHERE prescription_orders.Customer_ID =customers.Customer_ID AND prescription_orders.Store_ID = store.Store_ID AND prescription_orders.Order_ID =$id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) { 

	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Draw the form.
		echo '<h2>Delete a Order</h2>
	<form action="delete_order.php" method="post">
	<h3>Order_ID: ' . $row[0] . '</h3>
	<h3>Order_Date: ' . $row[1] . '</h3>
	<h3>Customer: ' . $row[3] . ' '. $row[4] .'</h3>
	<h3>Store: ' . $row[6] . '</h3>
	<p>Are you sure you want to delete this order?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>
	<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_orders.php">Go back to Orders</a></p>';
	} //End of valid  ID if().
	else { // Not a valid ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}

} // End of the main Submit conditional.

mysqli_close($dbc); // Close the database connection.

?>