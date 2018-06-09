<?php 


$page_title = 'Delete a Customer';


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
	   $query = "SELECT * FROM customers WHERE Customer_ID=$id";		
	   $result = @mysqli_query ($dbc, $query); // Run the query.
	
	   if (mysqli_num_rows($result) == 1) { 

	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
	
		$first_name_del=$row[1];
		$last_name_del=$row[2];
		
		$query = "DELETE FROM customers WHERE Customer_ID =$id";		
		$result_del = @mysqli_query ($dbc, $query); // Run the query.
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.


	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Create the result page.
		echo '<h1 id="mainhead">Delete a Customer</h1>
		<p>The record <b>'.$first_name_del. ' ' .$last_name_del.'</b> has been deleted.</p><p><br /><br /></p>
	   <p><a href="view_customers.php">Go back to customer list</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to the Main Menu</a></p>';	
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error1.</p><p><br /><br /></p>';
	}


	}
		

		
	 else { // If the query did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The customer could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
		}
	
	} else {
		echo '<h1 id="mainhead">Delete a customer</h1>';

	$query = "SELECT * FROM customers WHERE Customer_ID=$id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) { 

	
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
			
		$first_name_del=$row[1];
		$last_name_del=$row[2];
		
		// Create the result page.
  echo'
		<p>The record <b>'.$first_name_del. ' ' .$last_name_del.'</b> has NOT been deleted.</p><p><br /><br /></p>
		<p><a href="view_customers.php">Go back to customer list</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to the Main Menu</a></p>';	
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error2.</p><p><br /><br /></p>';
	}


}
} else { // Show the form.

	
	$query = "SELECT Customer_ID, First_N, Last_N, Date_of_Birth, Email FROM customers WHERE Customer_ID=$id";		
	$result = @mysqli_query ($dbc, $query); // Run the query.
	
	if (mysqli_num_rows($result) == 1) { 

		
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		
		// Create the form.
		echo '<h2>Delete a Customer</h2>
	<form action="delete_customer.php" method="post">
	<h3>First Name: ' . $row[1] . '</h3>
	<h3>Last Name: ' . $row[2] . '</h3>
	<h3>Date of Birth: ' . $row[3] . '</h3>
	<h3>Email: ' . $row[4] . '</h3>
	<p>Are you sure you want to delete this customer?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>';
	  echo '<a href="view_customers.php">Go back to customer list</a>&nbsp;&nbsp;&nbsp;<p><a href="index.php">Go back to the Main Menu</a></p>';
	} else { 
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}

} // End of the main Submit conditional.

mysqli_close($dbc); // Close the database connection.

?>