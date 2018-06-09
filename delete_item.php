<?php # delete_item.php


$page_title = 'Delete Item';


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

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query.
	   $query = "SELECT * FROM items WHERE Item_ID=$id";		
	   $result = @mysqli_query ($dbc, $query); // Run the query.
	
	   if (mysqli_num_rows($result) == 1) { 

		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		$item_name=$row[2];

		$query = "DELETE FROM items WHERE Item_ID=$id";		
		$result_del = @mysqli_query ($dbc, $query); // Run the query.
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.


	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Create the result page.
		echo '<h1 id="mainhead">Delete an Item</h1>
		<p>The record <b>'.$item_name.'</b> has been deleted.</p><p><br /><br /></p>
		<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_items.php">Go back to Items</a></p>';	
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error0.</p><p><br /><br /></p>';
	}


	}
		

		
	 else { // If the query did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The item could not be deleted due to a system error1.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
		}
	
	} else { 
		echo '<h1 id="mainhead">Delete an item</h1>';

	$query = "SELECT * FROM items WHERE Item_ID=$id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) { 

	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
			
		$item_name=$row[2];
		
		// Create the result page.
  echo'
		<p>The record <b>'.$item_name.'</b> has NOT been deleted.</p><p><br /><br /></p>
		<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_items.php">Go back to Items</a></p>';	
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error2.</p><p><br /><br /></p>';
	}


}
} else { // Show the form.

	
	$query = "SELECT *, Issuer_Name FROM items join prescription_issuers on items.Issuer_ID = prescription_issuers.Issuer_ID WHERE Item_ID=$id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) { 

		
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Create the form.
		echo '<h2>Delete an item</h2>
	<form action="delete_item.php" method="post">
	<h3>Item Name: ' . $row[2] . '</h3>
	<h3>Item Dec: ' . $row[1] . '</h3>
	<h3>Unit Price: ' . $row[3] . '</h3>
	<h3>Issure name: ' . $row[4] . '</h3>
	<p>Are you sure you want to delete this Item?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>
	<p><a href="index.php">Go back to the Main Menu</a>&nbsp;&nbsp;&nbsp;<a href="view_items.php">Go back to Items</a></p>';
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}

} // End of the main Submit conditional.

mysqli_close($dbc); // Close the database connection.

?>