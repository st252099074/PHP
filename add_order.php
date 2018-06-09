<?php # add_order.php

$page_title = 'Add Order';

include ('mysqli_connect.php');

// Check if the form has been submitted.

if(isset($_GET['id']))
{
	$customer_id = $_GET['id'];
	
	
	
}



if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.

	// Check for a customer.
	if (empty($_POST['Customer_ID'])) {
		$errors[] = 'You forgot to enter the customer of the order.';
	} else {
		$customer_id = $_POST['Customer_ID'];
		
$query = "SELECT * FROM customers WHERE Customer_ID=$customer_id ORDER BY Last_N ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
	{
$customer_name=$row['First_N'].' '.$row['Last_N'];
}
	}

	// Check for a  ID.
	if (empty($_POST['Store_ID'])) {
		$errors[] = 'You forgot to enter the store of the order.';
	} else {
		$store_id = $_POST['Store_ID'];

// Build the query
$query = "SELECT * FROM store WHERE Store_ID=$store_id ORDER BY Store_Name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
$store_name=$row['Store_Name'];
}
	}
	

	// Check for the date of order.
	if (empty($_POST['Order_Date'])) {
		$errors[] = 'You forgot to enter the date of order.';
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
	
	
	
	if (empty($errors)) { // If everything's okay.
	
		// Add the order to the database.
		
		// Make the query.
		$query = "INSERT INTO prescription_orders (Customer_ID, Order_Date, Store_ID) VALUES ('$customer_id', '$order_date', '$store_id')";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
		if ($result) { // If it ran OK.
		
			// Print a message.
			echo '<h1 id="mainhead">Success!</h1>
		<p>You have added:</p>';

		   echo "<table>
		<tr><td>Customer_ID:</td><td>{$customer_id}</td></tr>
		<tr><td>Order_Date:</td><td>{$order_date}</td></tr>
		<tr><td>Store_ID:</td><td>{$store_id}</td></tr>
	
		</table>";

		$order_id = mysqli_insert_id($dbc); // Retrieve the id number of the newly added record
		echo'<a href="add_order_item.php?id=' . $order_id . '"><strong>Please continue to Add a item to this order</strong></a>';
		exit();
			
		} else { // If it did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The item could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
			exit();
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.


} // End of the main Submit conditional.
?>







<?php 
 if (isset($_POST['Customer_ID'])) $this_customer_id=$_POST['Customer_ID']; elseif (isset($GET['id'])) {$this_customer_id = $_GET['id'];}
 if (isset($_POST['Store_ID'])) $this_store_id=$_POST['Store_ID'];
?>
<h2>Add an Order</h2>
<form action="add_order.php" method="post">

	<p>Store: <select name="Store_ID">
<?php 
// Build the query
$query = "SELECT * FROM store ORDER BY Store_Name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['Store_ID'] == $this_store_id) {
echo '<option value="'.$row['Store_ID'].'" selected="selected">'.$row['Store_Name'].'</option>';
}
 else {
echo'<option value="'.$row['Store_ID'].'">'.$row['Store_Name'].'</option>';}

}
?>
</select>&nbsp;&nbsp;&nbsp;<a href="add_store.php">Add a new Store</a>
</p>
	<p>Customer: <select name="Customer_ID">
<?php 
// Build the query
$query = "SELECT * FROM customers ORDER BY Last_N ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['Customer_ID'] == $this_customer_id) {
echo '<option value="'.$row['Customer_ID'].'" selected="selected">'.$row['First_N'].' '.$row['Last_N'].'</option>';
}
 else {
echo'<option value="'.$row['Customer_ID'].'">'.$row['First_N'].' '.$row['Last_N'].'</option>';}

}
?>
</select>&nbsp;&nbsp;&nbsp;<a href="add_customer.php">Add a new Customer</a>
</p>
<p>Date of Order: <input type="text" name="Order_Date" size="10" maxlength="10" value="<?php if (isset($_POST['Order_Date'])) echo $_POST['Order_Date']; ?>" />&nbsp;<i>YYYY-MM-DD</i></p>
	<p><input type="submit" name="submit" value="Add Order" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<a href="index.php">Go back to Main Page</a>
<?php
mysqli_close($dbc); // Close the database connection.
?>