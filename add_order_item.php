<?php # add_order_item.php


$page_title = 'Add Order Item';


include ('mysqli_connect.php');

// Check if the form has been submitted.

if (isset($_GET['id']))
{
	$order_id =$_GET['id'];
}

if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.

	// Check for a ID.
	if (empty($_POST['Order_ID'])) {
		$errors[] = 'You forgot to enter the order.';
	} else {
		$order_id = $_POST['Order_ID'];

// Build the query
$query = "SELECT * FROM prescription_orders WHERE Order_ID =$order_id";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
$title=$row['Order_ID'];
}
	}
	
	// Check for a  ID.
	if (empty($_POST['Item_ID'])) {
		$errors[] = 'You forgot to enter the item.';
	} else {
		$item_id = $_POST['Item_ID'];

// Build the query
$query = "SELECT * FROM items WHERE Item_ID =$item_id";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
$item_name=$row['Item_Name'];
}
	}
	
	
	
	
	   	if (empty($_POST['Quantity'])) {
		$errors[] = 'You forgot to enter Quantity.';
	} else {
		
		if(preg_match('/^[0-9]+$/', $_POST['Quantity']))
		{ $quantity = $_POST['Quantity'];
						
		}	
		else {$errors[] = 'Quantity must be interger';
	}
  	}
	
	
	if (empty($errors)) { // If everything's okay.
	
		// Add the order_item to the database.
		
		
		//check if the record exists.
		$query = "SELECT ITEM_ID FROM order_items where Order_ID = '$order_id' and ITEM_ID = '$item_id'";
		$result = @mysqli_query ($dbc, $query); 
		
		if ($result -> num_rows == '0') {
		
		
		
		// Make the query.
		$query = "INSERT INTO order_items (Order_ID, Item_ID, Quantity) VALUES ('$order_id', '$item_id', '$quantity')";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
		//global $identifier = mysqli_insert_id($dbc); // Retrieve the id number of the newly added record
		$id = mysqli_insert_id($dbc);
		$query2 = "SELECT Order_ID FROM order_items where ID = '$id'";	
		$result2 = @mysqli_query ($dbc, $query2); 
		$row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
		$current_order_id = $row2['Order_ID'];
		
		if ($result) { // If it ran OK.
		
			// Print a message.
			echo '<h1 id="mainhead">Success!</h1>
		<p>You have added the following item:</p>';

		   echo "<table>
		<tr><td>Order_ID:</td><td>{$title}</td></tr>
		<tr><td>Item_Name:</td><td>{$item_name}</td></tr>
		<tr><td>Quantity:</td><td>{$quantity}</td></tr>	
		<tr><td><button><a href='add_order_item.php?id=" .$current_order_id. "'>Add another Item</a></button></td></tr>
		<tr><td><a href='view_order_items.php?id=" .$current_order_id. "'>Get back to the order</a> &nbsp;&nbsp;&nbsp; <a href='view_orders.php'>Go back to order list</a>&nbsp;&nbsp;&nbsp;
		<a href='index.php'>Go back to index</td></tr></a>";
		
	
			exit();
			
		} else { // If it did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The item could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
			exit();
		}}
		
		else {
			
	        echo "<strong>This item already exits in this order, please edit the exiting order for the same item from  <a href='view_order_items.php?id=" .$order_id. "'>HERE</a></strong>";}
	
	
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


<?php if (isset($_POST['Order_ID'])) {$this_order_id=$_POST['Order_ID'];} elseif (isset($_GET['id'])) {$this_order_id=$_GET['id'];}
?>
<?php if (isset($_POST['Item_ID'])) $this_item_id=$_POST['Item_ID'];
?>
<h2>Add item</h2>
<form action="add_order_item.php" method="post">
	
	<p>Order_ID: <select name="Order_ID">
<?php 
// Build the query
$query = "SELECT * FROM prescription_orders ORDER BY Order_ID ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['Order_ID'] == $this_order_id) {
echo '<option value="'.$row['Order_ID'].'" selected="selected">'.$row['Order_ID'].'</option>';
}
 else {
echo'<option value="'.$row['Order_ID'].'">'.$row['Order_ID'].'</option>';}

}
?>
</select>&nbsp;&nbsp;&nbsp;<a href="add_order.php">Add a new Order</a>
</p>
	<p>Item: <select name="Item_ID">
<?php 
// Build the query
$query = "SELECT * FROM items ORDER BY Item_Name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['Item_ID'] == $this_item_id) {
echo '<option value="'.$row['Item_ID'].'" selected="selected">'.$row['Item_Name'].'</option>';
}
 else {
echo'<option value="'.$row['Item_ID'].'">'.$row['Item_Name'].'</option>';}

}
?>
</select>&nbsp;&nbsp;&nbsp;<a href="add_item.php">Add a new Item</a>
</p>
<p>Quantity: <input type="text" name="Quantity" size="10" maxlength="10" value="<?php if (isset($_POST['Quantity'])) echo $_POST['Quantity']; ?>"  /> </p> &nbsp;<i>only integer, ex:30</i></p>
	<p><input type="submit" name="submit" value="Add Item" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<?php mysqli_close($dbc); // Close the database connection. ?>


