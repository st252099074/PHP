<?php 

$page_title = 'Delete a Order Item';

// Check for a valid ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { 
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
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
	   $query = "SELECT ID, Item_Name, oi.Order_ID, Quantity FROM order_items oi
JOIN prescription_orders po
ON po.Order_ID = oi.Order_ID
JOIN items 
ON items.Item_ID = oi.Item_ID
where ID = $id;";		
	   $result = @mysqli_query ($dbc, $query); // Run the query.
	
	   if (mysqli_num_rows($result) == 1) { 

		// Get the information.
		$row = mysqli_fetch_array ($result, MYSQL_NUM);

		
	$item_name_del=$row[1];
	$order_id_del=$row[2];
	$quantity_del=$row[3];
		
		
		
		
		$query = "DELETE FROM order_items WHERE ID=$id";		
		$result_del = @mysqli_query ($dbc, $query); // Run the query.
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.


		
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Create the result page.
		echo '<h1 id="mainhead">Delete a order item</h1>
		<p>The item <b>'.$item_name_del.'</b> from the order <b>'.$order_id_del.'</b> with quantity <b>'.$quantity_del.'</b> has been deleted.</p><p><br /><br />
		
		<a href="view_order_items.php?id=' .$order_id_del. '">Go back to the order</a>&nbsp;&nbsp;&nbsp;<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_orders.php">Go back to Orders</a></p>';	
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}


	}
		

		
	 else { // If the query did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The order could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
		}
	
	} else { 
		echo '<h1 id="mainhead">Delete a Item</h1>';

	$query = "SELECT ID, Item_Name, oi.Order_ID, Quantity FROM order_items oi
JOIN prescription_orders po
ON po.Order_ID = oi.Order_ID
JOIN items 
ON items.Item_ID = oi.Item_ID
where ID = $id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) {

		
		$row = mysqli_fetch_array ($result, MYSQL_NUM);

		$item_name_del=$row[1];
	    $order_id_del=$row[2];
	    $quantity_del=$row[3];
		
		// Create the result page.
  echo'
		<p>The item <b>'.$item_name_del.'</b> from the order <b>'.$order_id_del.'</b> with quantity <b>'.$quantity_del.'</b> has NOT been deleted.</p><p><br /><br />
		<a href="view_order_items.php?id=' .$row[2]. '">Go back to the order</a>&nbsp;&nbsp;&nbsp;<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_orders.php">Go back to Orders</a></p>';	
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}


}
} else { // Show the form.

	
	$query = "SELECT ID, Item_Name, oi.Order_ID, Quantity FROM order_items oi
JOIN prescription_orders po
ON po.Order_ID = oi.Order_ID
JOIN items 
ON items.Item_ID = oi.Item_ID
where ID = $id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) { 

	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Create the form.
		echo '<h2>Delete a order item</h2>
	<form action="delete_order_item.php" method="post">
	<h3>Order_ID: ' . $row[2] . '</h3>
	<h3>Item Nmae: ' . $row[1] . '</h3>
	<h3>Quantity: ' . $row[3] . '</h3>
	<p>Are you sure you want to delete this order item?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>';
	  echo '<a href="view_order_items.php?id=' .$row[2]. '">Go back to the order</a>&nbsp;&nbsp;&nbsp;<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_orders.php">Go back to Orders</a></p>';
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}

} // End of the main Submit conditional.

mysqli_close($dbc); // Close the database connection.

?>