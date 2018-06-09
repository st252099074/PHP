<?php 


$page_title = 'Edit_Orders';

// Check for a valid   ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_moives.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	exit();
}

include ('mysqli_connect.php');

// Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for a title.
	if (empty($_POST['Customer_ID'])) {
		$errors[] = 'You forgot to enter the customer.';
	} else {
		$customer_id = $_POST['Customer_ID'];
	}
	
	
	if (empty($_POST['Store_ID'])) {
		$errors[] = 'You forgot to enter the store.';
	} else {
		$store_id = $_POST['Store_ID'];
	}
	
	// Check for a genre ID.
	if (empty($_POST['Order_Date'])) {
		$errors[] = 'You forgot to enter date.';
	} else {
		
		
		
			
	function validateDate($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
	$order_date = $_POST['Order_Date'];
	
	if (validateDate($order_date, 'Y-m-d') == true){
		$order_date = $_POST['Order_Date'];
		
	} else {
		$errors[] = 'Date of order is invalid.';
	}
	}
	

	
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "UPDATE prescription_orders SET Customer_ID='$customer_id', Store_ID ='$store_id',  Order_Date= '$order_date'  WHERE Order_ID = $id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Edit a Order</h1>
				<p>The order record has been edited.</p><p><br /><br /></p>';	
							
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The order could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				exit();
			}
				
	} // End of if (empty($errors)) IF.
	
	else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		} // End of foreach
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	}  // End of report errors else()

} // End of submit conditional.



// Always show the form.

// Retrieve the  s's information.
$query = "SELECT Order_Date, customers.Customer_ID, First_N , Last_N, store.Store_ID, store.Store_Name FROM prescription_orders, store, customers 
WHERE prescription_orders.Customer_ID = customers.Customer_ID AND prescription_orders.Store_ID = store.Store_ID AND Order_ID = $id ";		
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) { // Valid   ID, show the form.

	// Get the  's information.
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	$this_store_id=$row[1];
	$this_customer_id=$row[4];
	// Create the form.


echo '<h2>Edit a Order</h2>

<form action="edit_orders.php" method="post">

<p>Order_Date: <input type="text" name="Order_Date" size="15" maxlength="15" value="' . $row[0] . '" /></p>';
echo '<p>Store: <select name="Store_ID">';

$query = "SELECT Store_ID, Store_Name FROM store";
$result = @mysqli_query ($dbc, $query);

while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
	if ($row['Store_ID'] == $this_store_id) 
	{
		echo '<option value="'.$row['Store_ID'].'" selected="selected">' . 	$row['Store_Name'] . '</option>';
	}
	else 
	{
		echo '<option value="'.$row['Store_ID'].'">'. $row['Store_Name'] . '</option>';
	}   
}
echo '</select> </p>';

echo '<p>Customer: <select name="Customer_ID">';
// Build the query for genre drop-down
$query = "SELECT Customer_ID, First_N, Last_N FROM customers";
$result = @mysqli_query ($dbc, $query);

while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
	if ($row['Customer_ID'] == $this_customer_id) 
	{
		echo '<option value="'.$row['Customer_ID'].'" 	selected="selected">'.$row['First_N'] . ' ' . $row['Last_N'].'</option>';
	}
	else 
	{
		echo '<option 	value="'.$row['Customer_ID'].'">'.$row['First_N'] . ' ' . $row['Last_N'].'</option>';
	}
}
echo '</select> </p>';


$query = "SELECT Order_Date FROM prescription_orders WHERE Order_ID = $id ";		
$result = @mysqli_query ($dbc, $query); // Run the query.
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
echo '

<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
</form>

<p><a href="add_order_item.php?id='.$id.'">Add a new item to this order</a>&nbsp;&nbsp;&nbsp; <a href="view_orders.php">Get back to orders</a></p>
   <a href="index.php?">Get back to Main Page</a></p>

';

} else { // Not a valid   ID.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Order ID.</p><p><br /><br /></p>';
}

mysqli_close($dbc); // Close the database connection.
		
?>
